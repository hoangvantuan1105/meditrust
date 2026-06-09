<?php

class PatientController
{
    private $clinic;
    public function __construct()
    {
        require_once __DIR__ . '/../models/db.php';
        $this->clinic = new modelClinic();
        $this->clinic->ketNoiDB();
    }

    public function dsbenhnhan()
    {
        $data = $this->clinic->getAll();
        $title = "Quản lý bệnh nhân";
        $content = "backend/views/patientManagement/danh-sach-benh-nhan.php";

        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/patientManagement/danh-sach-benh-nhan.php";
        require_once "backend/views/fileJS.php";
    }

    public function form()
    {
        $mode = $_GET['mode'] ?? 'normal';
        $lich_hen_id = $_GET['lich_hen_id'] ?? null;
        $lichHen = null;

        if ($lich_hen_id) {
            $lichHen = $this->clinic->getLichHenById($lich_hen_id);
        }
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/patientManagement/form-benh-nhan.php";
        require_once "backend/views/fileJS.php";
    }

    public function add()
    {
        $errors    = [];
        $from      = $_GET['from']       ?? $_POST['from']       ?? '';
        $lichHenId = $_GET['lich_hen_id'] ?? $_POST['lich_hen_id'] ?? null;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $newId = $this->clinic->generateHoSoBenhNhanId();

            $ho_ten = trim($_POST['ho_ten'] ?? '');
            $so_dien_thoai = trim($_POST['so_dien_thoai'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $gioi_tinh = $_POST['gioi_tinh'] ?? '';
            $ngay_sinh = $_POST['ngay_sinh'] ?? '';
            $dia_chi = $_POST['dia_chi'] ?? '';
            $tien_su_benh = $_POST['tien_su_benh'] ?? '';
            $cmnd_cccd = trim($_POST['cmnd_cccd'] ?? '');
            $bao_hiem_y_te = trim($_POST['bao_hiem_y_te'] ?? '');
            $nguoi_lien_he_khan_cap = $_POST['nguoi_lien_he_khan_cap'] ?? '';
            $quan_he = $_POST['quan_he'] ?? '';
            $sdt_nguoi_lien_he = $_POST['sdt_nguoi_lien_he'] ?? '';

            if ($ho_ten == '') {
                $errors['ho_ten'] = "Họ tên không được để trống";
            }

            if (!preg_match('/^0\d{9}$/', $so_dien_thoai)) {
                $errors['so_dien_thoai'] = "Số điện thoại phải 10 số và bắt đầu bằng 0";
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Email không hợp lệ";
            }

            if ($cmnd_cccd != '' && !preg_match('/^\d{12}$/', $cmnd_cccd)) {
                $errors['cmnd_cccd'] = "CCCD phải đủ 12 số";
            }

            if ($bao_hiem_y_te != '' && !preg_match('/^\d+$/', $bao_hiem_y_te)) {
                $errors['bao_hiem_y_te'] = "Bảo hiểm y tế chỉ được chứa số";
            }

            if (empty($errors)) {

                $duplicates = $this->clinic->checkDuplicate(
                    $so_dien_thoai,
                    $email,
                    $cmnd_cccd,
                    $bao_hiem_y_te
                );

                foreach ($duplicates as $row) {

                    if ($row['so_dien_thoai'] == $so_dien_thoai) {
                        $errors['so_dien_thoai'] = "Số điện thoại đã tồn tại";
                    }

                    if ($row['email'] == $email) {
                        $errors['email'] = "Email đã tồn tại";
                    }

                    if ($row['cmnd_cccd'] == $cmnd_cccd && $cmnd_cccd != '') {
                        $errors['cmnd_cccd'] = "CCCD đã tồn tại";
                    }

                    if ($row['bao_hiem_y_te'] == $bao_hiem_y_te && $bao_hiem_y_te != '') {
                        $errors['bao_hiem_y_te'] = "Bảo hiểm y tế đã tồn tại";
                    }
                }
            }

            if (!empty($errors)) {
                $item = $_POST;
            } else {

                $trang_thai = isset($_POST['trang_thai']) ? (int)$_POST['trang_thai'] : 1;

                if ($this->clinic->insert([
                    $newId,
                    $ho_ten,
                    $so_dien_thoai,
                    $email,
                    $gioi_tinh,
                    $ngay_sinh,
                    $dia_chi,
                    $tien_su_benh,
                    $cmnd_cccd,
                    $bao_hiem_y_te,
                    $nguoi_lien_he_khan_cap,
                    $quan_he,
                    $sdt_nguoi_lien_he,
                    $trang_thai
                ])) {

                    $account = $this->clinic->findAccountByEmailOrPhone($email, $so_dien_thoai);

                    if (!$account) {

                        $this->clinic->insertUser([
                            $newId,
                            $email,
                            $so_dien_thoai,
                            null,
                            0,
                            null,
                            null
                        ]);
                    } else {
                        $_SESSION['success'] =
                            "Thêm hồ sơ thành công (bệnh nhân đã có tài khoản trước đó).";
                    }

                    if ($from === 'tiep_nhan' && $lichHenId) {
                        $this->clinic->ganHoSoVaoLichHen($newId, $lichHenId);
                        $this->clinic->taoLichKhamTuLichHen($lichHenId);
                        header("Location: admin.php?admin=listLichHen");
                        exit;
                    }

                    header("Location: admin.php?admin=dsbenhnhan");
                    exit;
                } else {
                    $msg = "Thêm thất bại";
                }
            }
        }
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/patientManagement/form-benh-nhan.php";
        require_once "backend/views/fileJS.php";
    }

    public function edit()
    {
        $id = $_GET['id'] ?? '';
        $item = $this->clinic->getById($id);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ho_ten = $_POST['ho_ten'] ?? '';
            $so_dien_thoai = $_POST['so_dien_thoai'] ?? '';
            $email = $_POST['email'] ?? '';
            $gioi_tinh = $_POST['gioi_tinh'] ?? '';
            $ngay_sinh = $_POST['ngay_sinh'] ?? '';
            $dia_chi = $_POST['dia_chi'] ?? '';
            $tien_su_benh = $_POST['tien_su_benh'] ?? '';
            $cmnd_cccd = $_POST['cmnd_cccd'] ?? '';
            $bao_hiem_y_te = $_POST['bao_hiem_y_te'] ?? '';
            $nguoi_lien_he_khan_cap = $_POST['nguoi_lien_he_khan_cap'] ?? '';
            $quan_he = $_POST['quan_he'] ?? '';
            $sdt_nguoi_lien_he = $_POST['sdt_nguoi_lien_he'] ?? '';
            $trang_thai = $_POST['trang_thai'] ?? '';

            if ($this->clinic->update([$ho_ten, $so_dien_thoai, $email, $gioi_tinh, $ngay_sinh, $dia_chi, $tien_su_benh, $cmnd_cccd, $bao_hiem_y_te, $nguoi_lien_he_khan_cap, $quan_he, $sdt_nguoi_lien_he, $trang_thai, $id])) {
                header("Location: admin.php?admin=dsbenhnhan&msg=Cập nhật thành công");
                exit;
            } else {
                $msg = "Cập nhật thất bại";
            }
        }
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/patientManagement/form-benh-nhan.php";
        require_once "backend/views/fileJS.php";
    }

    public function delete()
    {
        $id = $_GET['id'] ?? '';
        if ($this->clinic->delete($id)) {
            header("Location: admin.php?admin=dsbenhnhan&msg=Xóa thành công");
            exit;
        } else {
            header("Location: admin.php?admin=dsbenhnhan&msg=Xóa thất bại");
            exit;
        }
    }

    public function detail()
    {
        $id = $_GET['id'] ?? '';
        $item = $this->clinic->getById($id);
        $lichSuKham = $this->clinic->getLichSuKhamByHoSoId($id);
        $donThuocList = $this->clinic->getPatientPrescriptionsForAI($id);
        $xrayList     = $this->clinic->getXrayByPatient($id);
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/patientManagement/chi-tiet-benh-nhan.php";
        require_once "backend/views/fileJS.php";
    }

    public function aiSummarizePatient()
    {
        ob_start();

        $hoSoId = $_GET['id'] ?? '';
        if (!$hoSoId) {
            ob_clean();
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error' => 'Thiếu ID bệnh nhân']);
            exit;
        }

        $patient = $this->clinic->getById($hoSoId);
        if (!$patient) {
            ob_clean();
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error' => 'Không tìm thấy bệnh nhân']);
            exit;
        }

        $lichSuKham     = $this->clinic->getLichSuKhamByHoSoId($hoSoId);
        $donThuocList   = $this->clinic->getPatientPrescriptionsForAI($hoSoId);

        $tuoi = '';
        if (!empty($patient['ngay_sinh'])) {
            $tuoi = (int) date('Y') - (int) substr($patient['ngay_sinh'], 0, 4) . ' tuổi';
        }

        $context  = "=== HỒ SƠ BỆNH NHÂN ===\n";
        $context .= "Họ tên: {$patient['ho_ten']}\n";
        $context .= "Giới tính: {$patient['gioi_tinh']}\n";
        $context .= "Tuổi: $tuoi\n";
        $context .= "Tiền sử bệnh: " . ($patient['tien_su_benh'] ?: 'Không ghi nhận') . "\n\n";

        if (!empty($lichSuKham)) {
            $context .= "=== LỊCH SỬ KHÁM (" . count($lichSuKham) . " lần) ===\n";
            foreach ($lichSuKham as $i => $ls) {
                $context .= ($i + 1) . ". Ngày: {$ls['ngay_kham']} | BS: " . ($ls['ten_bac_si'] ?: 'Không rõ') . "\n";
                $context .= "   Dịch vụ: " . ($ls['danh_sach_dich_vu'] ?: 'Không có') . "\n";
                $context .= "   Chẩn đoán: " . ($ls['chan_doan'] ?: 'Không có') . "\n";
                $context .= "   Hướng điều trị: " . ($ls['huong_dieu_tri'] ?: 'Không có') . "\n";
                if (!empty($ls['ghi_chu'])) {
                    $context .= "   Ghi chú: {$ls['ghi_chu']}\n";
                }
            }
        } else {
            $context .= "=== LỊCH SỬ KHÁM: Chưa có lần khám nào ===\n";
        }

        if (!empty($donThuocList)) {
            $context .= "\n=== ĐƠN THUỐC ĐÃ KÊ ===\n";
            foreach ($donThuocList as $p) {
                $context .= "- Ngày: {$p['ngay_ke_don']} | Thuốc: {$p['thuoc_list']}\n";
            }
        }

        $prompt = "Bạn là trợ lý AI hỗ trợ bác sĩ nha khoa tại phòng khám MediTrust.
Dưới đây là toàn bộ hồ sơ bệnh nhân:

$context

Hãy tạo BẢN TÓM TẮT HỒ SƠ ngắn gọn, chuyên nghiệp bằng tiếng Việt theo cấu trúc:

**1. TỔNG QUAN BỆNH NHÂN**
Thông tin cơ bản và tiền sử bệnh đáng chú ý.

**2. QUÁ TRÌNH ĐIỀU TRỊ**
Tóm tắt các lần khám, xu hướng bệnh, dịch vụ đã thực hiện.

**3. VẤN ĐỀ HIỆN TẠI**
Dựa trên các chẩn đoán và hướng điều trị gần nhất.

**4. LƯU Ý CHO BÁC SĨ**
Điểm cần theo dõi, dị ứng thuốc (nếu có), gợi ý tái khám.";

        $url = "https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key=" . GEMINI_KEY;

        $payload = [
            "contents" => [[
                "role" => "user",
                "parts" => [["text" => $prompt]]
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

        $raw     = curl_exec($ch);
        $curlErr = curl_error($ch);
        curl_close($ch);

        ob_clean();
        header('Content-Type: application/json; charset=utf-8');

        if ($raw === false) {
            echo json_encode(['error' => 'curl: ' . $curlErr]);
            exit;
        }

        $decoded = json_decode($raw, true);

        if (isset($decoded['error'])) {
            echo json_encode(['error' => 'Gemini: ' . ($decoded['error']['message'] ?? 'Lỗi không xác định')]);
            exit;
        }

        $summary = $decoded['candidates'][0]['content']['parts'][0]['text'] ?? null;

        if (!$summary) {
            echo json_encode(['error' => 'Không có nội dung trả về. Raw: ' . substr($raw, 0, 300)]);
            exit;
        }

        echo json_encode(['summary' => $summary]);
        exit;
    }

    public function patientAccounts()
    {
        $listTaiKhoan = $this->clinic->getAllPatientAccounts();
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/patientManagement/patient-accounts.php";
        require_once "backend/views/fileJS.php";
    }

    public function formAddPatientAccounts()
    {
        $hoSoList = $this->clinic->getAllIDHoSoBenhNhan();
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/patientManagement/addPatient-accounts.php";
        require_once "backend/views/fileJS.php";
    }

    public function addPatientAccount()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $ho_so_id = $_POST['ho_so_benh_nhan_id'];
            $so_dien_thoai = trim($_POST['so_dien_thoai']);
            $mat_khau_raw = $_POST['mat_khau'];
            $trang_thai = 1;

            $_SESSION['old'] = $_POST;

            if ($this->clinic->checkHoSoHasAccount($ho_so_id)) {
                $_SESSION['error'] = "Hồ sơ này đã được tạo tài khoản!";
                header("Location: admin.php?admin=formAddPatientAccounts");
                exit;
            }

            if (!preg_match('/^0\d{9}$/', $so_dien_thoai)) {
                $_SESSION['error'] = "Số điện thoại phải đúng 10 số!";
                header("Location: admin.php?admin=formAddPatientAccounts");
                exit;
            }

            if ($this->clinic->checkPhoneExistsInAccount($so_dien_thoai)) {
                $_SESSION['error'] = "Số điện thoại đã có tài khoản!";
                header("Location: admin.php?admin=formAddPatientAccounts");
                exit;
            }

            $mat_khau = password_hash($mat_khau_raw, PASSWORD_DEFAULT);

            $this->clinic->insertPatientAccount([
                $ho_so_id,
                $so_dien_thoai,
                $mat_khau,
                $trang_thai
            ]);

            unset($_SESSION['old']);
            $_SESSION['success'] = "Thêm tài khoản thành công!";
            header("Location: admin.php?admin=patient-accounts");
            exit;
        }
    }

    public function formEditPatientAccount()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: admin.php?admin=patient-accounts");
            exit;
        }

        $account = $this->clinic->getPatientAccountById($id);
        if (!$account) {
            header("Location: admin.php?admin=patient-accounts");
            exit;
        }

        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/patientManagement/editPatient-accounts.php";
        require_once "backend/views/fileJS.php";
    }

    public function updatePatientAccount()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['id'];
            $so_dien_thoai = trim($_POST['so_dien_thoai']);

            $_SESSION['old'] = $_POST;

            if (!preg_match('/^0\d{9}$/', $so_dien_thoai)) {
                $_SESSION['error'] = "Số điện thoại phải đúng 10 số!";
                header("Location: admin.php?admin=formEditPatientAccount&id=" . $id);
                exit;
            }

            if ($this->clinic->checkPhoneExistsExceptId($so_dien_thoai, $id)) {
                $_SESSION['error'] = "Số điện thoại đã được sử dụng!";
                header("Location: admin.php?admin=formEditPatientAccount&id=" . $id);
                exit;
            }

            $this->clinic->updatePatientAccountPhone($id, $so_dien_thoai);

            unset($_SESSION['old']);
            $_SESSION['success'] = "Cập nhật thành công!";
            header("Location: admin.php?admin=patient-accounts");
            exit;
        }
    }
}
