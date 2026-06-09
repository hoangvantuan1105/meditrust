<?php

class XrayController
{
    private $clinic;

    public function __construct()
    {
        require_once __DIR__ . '/../models/db.php';
        $this->clinic = new modelClinic();
        $this->clinic->ketNoiDB();
    }

    private function requireLogin()
    {
        if (empty($_SESSION['admin'])) {
            header("Location: admin.php?admin=loginSystem");
            exit;
        }
    }

    // Danh sách tất cả X-quang
    public function listXray()
    {
        $this->requireLogin();
        $role     = $_SESSION['role'] ?? '';
        $bacSiId  = ($role === 'bac_si') ? ($_SESSION['bac_si_id'] ?? null) : null;
        $listXray = $this->clinic->getAllXray($bacSiId);

        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/xray/listXray.php";
        require_once "backend/views/fileJS.php";
    }

    // Form upload X-quang (gắn với lịch khám)
    public function formUploadXray()
    {
        $this->requireLogin();
        $lich_kham_id = $_GET['lich_kham_id'] ?? null;

        if (!$lich_kham_id) {
            header("Location: admin.php?admin=listLichKham");
            exit;
        }

        $lichKham   = $this->clinic->getLichKhamById($lich_kham_id);
        $listBacSi  = $this->clinic->getAllBacSi();
        $listXray   = $this->clinic->getXrayByLichKham($lich_kham_id);

        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/xray/uploadXray.php";
        require_once "backend/views/fileJS.php";
    }

    // Lưu X-quang + phân tích AI
    public function luuXray()
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: admin.php?admin=listLichKham");
            exit;
        }

        $lich_kham_id = $_POST['lich_kham_id']       ?? null;
        $ho_so_id     = $_POST['ho_so_benh_nhan_id'] ?? null;
        $loai_xray    = $_POST['loai_xray']           ?? '';
        $vi_tri       = trim($_POST['vi_tri']         ?? '');
        $mo_ta        = trim($_POST['mo_ta']          ?? '');
        $bac_si_id    = $_POST['bac_si_id']           ?? ($_SESSION['bac_si_id'] ?? null);

        if (empty($_FILES['xray_file']['name'])) {
            $_SESSION['error'] = "Vui lòng chọn file ảnh X-quang";
            header("Location: admin.php?admin=formUploadXray&lich_kham_id=$lich_kham_id");
            exit;
        }

        $file    = $_FILES['xray_file'];
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $_SESSION['error'] = "Chỉ cho phép ảnh JPG, PNG, WEBP";
            header("Location: admin.php?admin=formUploadXray&lich_kham_id=$lich_kham_id");
            exit;
        }

        $uploadDir = realpath(__DIR__ . '/../../backend/uploads/xray');
        if (!$uploadDir) {
            $uploadDir = __DIR__ . '/../../backend/uploads/xray';
            mkdir($uploadDir, 0777, true);
            $uploadDir = realpath($uploadDir);
        }

        $fileName   = 'xray_' . ($ho_so_id ?? 'unknown') . '_' . time() . '.' . $ext;
        $uploadPath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            $_SESSION['error'] = "Upload thất bại, kiểm tra quyền thư mục";
            header("Location: admin.php?admin=formUploadXray&lich_kham_id=$lich_kham_id");
            exit;
        }

        // Lưu DB trước (không chờ AI)
        $this->clinic->insertXray([
            'ho_so_benh_nhan_id' => $ho_so_id,
            'lich_kham_id'       => $lich_kham_id,
            'bac_si_id'          => $bac_si_id,
            'loai_xray'          => $loai_xray,
            'vi_tri'             => $vi_tri,
            'file_path'          => $fileName,
            'mo_ta'              => $mo_ta,
            'ai_phan_tich'       => null,
            'ngay_chup'          => date('Y-m-d'),
        ]);

        // Lấy ID vừa insert để redirect thẳng vào viewXray
        $newId = $this->clinic->lastXrayId();

        $_SESSION['success'] = "Upload X-quang thành công! Nhấn 'Phân Tích AI' để nhận kết quả.";
        header("Location: admin.php?admin=viewXray&idAdmin=$newId");
        exit;
    }

    // Phân tích ảnh X-quang bằng Gemini Vision
    private function analyzeWithGemini($filePath, $ext, $loai, $vi_tri)
    {
        $mimeMap  = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp'];
        $mimeType = $mimeMap[$ext] ?? 'image/jpeg';

        $loaiLabel = [
            'toan_ham'  => 'toàn hàm (panoramic)',
            'rang_cu'   => 'răng cụ thể (periapical)',
            'cat_loc'   => 'cắn lọc (bitewing)',
            'cat_ngang' => 'cắt ngang',
            'cbct_3d'   => 'CBCT 3D',
        ][$loai] ?? $loai;

        $viTriNote = $vi_tri ? " tại vị trí $vi_tri" : "";

        // Lấy danh sách dịch vụ từ DB để inject vào prompt
        $danhSachDichVu = $this->clinic->layTatCaDichVu();
        $dsDV = implode("\n", array_map(
            fn($dv) => "- " . $dv['ten_dich_vu'],
            $danhSachDichVu
        ));

        $prompt = <<<PROMPT
Bạn là trợ lý AI của phòng khám nha khoa MediTrust. Nhiệm vụ của bạn là đọc ảnh X-quang nha khoa và đưa ra thông tin hỗ trợ bác sĩ.

Đây là ảnh X-quang $loaiLabel$viTriNote.

Hãy trả lời ĐÚNG theo cấu trúc sau, KHÔNG thêm nội dung ngoài cấu trúc:

**TÌNH TRẠNG RĂNG MIỆNG:**
(Mô tả ngắn gọn tình trạng phát hiện được: sâu răng, viêm tủy, tiêu xương, cao răng, thiếu răng, v.v. Nếu không phát hiện bất thường rõ ràng, ghi "Không phát hiện bất thường rõ ràng".)

**GỢI Ý DỊCH VỤ:**
(Chỉ chọn từ danh sách dịch vụ bên dưới, mỗi dòng một dịch vụ theo định dạng "- Tên dịch vụ: lý do ngắn gọn". Chỉ gợi ý dịch vụ phù hợp với tình trạng, tối đa 4 dịch vụ.)

DANH SÁCH DỊCH VỤ PHÒNG KHÁM:
$dsDV

Lưu ý:
- Trả lời bằng tiếng Việt.
- Không chẩn đoán thay bác sĩ, chỉ hỗ trợ nhận diện dấu hiệu.
- Chỉ gợi ý dịch vụ có trong danh sách trên, KHÔNG tự thêm dịch vụ khác.
- Không đề cập đến giá tiền.
PROMPT;

        $imageData = base64_encode(file_get_contents($filePath));

        $url = "https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key=" . GEMINI_KEY;

        $payload = [
            "contents" => [[
                "parts" => [
                    ["text" => $prompt],
                    ["inline_data" => ["mime_type" => $mimeType, "data" => $imageData]],
                ]
            ]]
        ];

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => ["Content-Type: application/json"],
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT        => 60,
        ]);

        $result = curl_exec($ch);
        curl_close($ch);

        if (!$result) return null;

        $res = json_decode($result, true);
        return $res['candidates'][0]['content']['parts'][0]['text'] ?? null;
    }

    // Chi tiết X-quang (read-only, có in ấn)
    public function detailXray($id)
    {
        $this->requireLogin();
        $xray = $this->clinic->getXrayById($id);

        if (!$xray) {
            header("Location: admin.php?admin=listXray");
            exit;
        }

        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/xray/detailXray.php";
        require_once "backend/views/fileJS.php";
    }


    // Xem chi tiết 1 X-quang
    public function viewXray($id)
    {
        $this->requireLogin();
        $xray = $this->clinic->getXrayById($id);

        if (!$xray) {
            header("Location: admin.php?admin=listXray");
            exit;
        }

        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/xray/viewXray.php";
        require_once "backend/views/fileJS.php";
    }

    // X-quang theo bệnh nhân
    public function xrayBenhNhan($id)
    {
        $this->requireLogin();
        $listXray = $this->clinic->getXrayByPatient($id);
        $benhNhan = $this->clinic->getById($id);

        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/xray/xrayBenhNhan.php";
        require_once "backend/views/fileJS.php";
    }

    // Lưu kết quả đọc phim của bác sĩ
    public function luuKetQuaXray()
    {
        $this->requireLogin();
        $id      = $_POST['id']              ?? null;
        $ket_qua = trim($_POST['ket_qua_bac_si'] ?? '');

        if (!$id) {
            header("Location: admin.php?admin=listXray");
            exit;
        }

        $this->clinic->updateXrayKetQua($id, $ket_qua);
        $_SESSION['success'] = "Lưu kết quả đọc phim thành công";
        header("Location: admin.php?admin=viewXray&idAdmin=$id");
        exit;
    }

    // Xóa X-quang
    public function xoaXray($id)
    {
        $this->requireLogin();
        $xray = $this->clinic->getXrayById($id);

        if ($xray) {
            $filePath = realpath(__DIR__ . '/../../backend/uploads/xray') . DIRECTORY_SEPARATOR . $xray['file_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $this->clinic->deleteXray($id);
        }

        $_SESSION['success'] = "Đã xóa X-quang";
        header("Location: admin.php?admin=listXray");
        exit;
    }

    // API: phân tích lại ảnh (dùng trong viewXray.php)
    public function reAnalyzeXray($id)
    {
        $this->requireLogin();
        set_time_limit(120);
        header("Content-Type: application/json");

        $xray = $this->clinic->getXrayById($id);
        if (!$xray) {
            echo json_encode(['error' => 'Không tìm thấy X-quang']);
            exit;
        }

        $filePath = realpath(__DIR__ . '/../../backend/uploads/xray') . DIRECTORY_SEPARATOR . $xray['file_path'];
        if (!file_exists($filePath)) {
            echo json_encode(['error' => 'File ảnh không tồn tại']);
            exit;
        }

        $ext    = strtolower(pathinfo($xray['file_path'], PATHINFO_EXTENSION));
        $result = $this->analyzeWithGemini($filePath, $ext, $xray['loai_xray'], $xray['vi_tri']);

        if ($result) {
            $this->clinic->updateXrayAi($id, $result);
            echo json_encode(['result' => $result]);
        } else {
            echo json_encode(['error' => 'Gemini không trả về kết quả']);
        }
        exit;
    }

    // API: lấy X-quang theo lịch khám (dùng trong khambenh.php)
    public function getXrayByLichKham()
    {
        header("Content-Type: application/json");
        $lich_kham_id = $_GET['lich_kham_id'] ?? null;
        if (!$lich_kham_id) {
            echo json_encode([]);
            exit;
        }
        echo json_encode($this->clinic->getXrayByLichKham($lich_kham_id));
    }
}
