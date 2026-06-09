<?php
require_once __DIR__ . '/../../core/Gemini.php';

class SuppliesController
{
    private $clinic;
    public function __construct()
    {
        require_once __DIR__ . '/../models/db.php';
        $this->clinic = new modelClinic();
        $this->clinic->ketNoiDB();
    }

    public function materials()
    {
        $this->clinic->autoUpdateMaterialStatus();
        $allMaterials = $this->clinic->getAllMaterials();
        $warningMaterials = $this->clinic->getWarningMaterials();
        $alerts = $this->clinic->getMaterialAlerts();

        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once __DIR__ . '/../views/supplies/materialsManagement.php';
        require_once "backend/views/fileJS.php";
    }

    public function addFormMaterials()
    {
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once __DIR__ . '/../views/supplies/addFormMaterials.php';
        require_once "backend/views/fileJS.php";
    }

    public function addMaterials()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $data = [
                $_POST['ten_vat_tu'],
                $_POST['don_vi'],
                $_POST['so_luong'],
                $_POST['hang_san_xuat'],
                $_POST['danh_muc'],
                $_POST['trang_thai'],
                $_POST['gia_nhap'],
                $_POST['han_su_dung'],
                $_POST['trang_thai_han'],
            ];

            $this->clinic->insertMaterials($data);

            header("Location: admin.php?admin=materials");
            exit;
        }

        require_once __DIR__ . '/../views/addFormMaterials.php';
    }

    public function editMaterials()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['id'];

            $ten_vat_tu = $_POST['ten_vat_tu'];
            $don_vi = $_POST['don_vi'];
            $so_luong = (int) $_POST['so_luong'];
            $hang_san_xuat = $_POST['hang_san_xuat'];
            $danh_muc = $_POST['danh_muc'];
            $trang_thai = $_POST['trang_thai'];
            $trang_thai_han = $_POST['trang_thai_han'];
            $gia_nhap = (float) $_POST['gia_nhap'];
            $han_su_dung = $_POST['han_su_dung'];

            $this->clinic->updateMaterials(
                $ten_vat_tu,
                $don_vi,
                $so_luong,
                $hang_san_xuat,
                $danh_muc,
                $trang_thai,
                $trang_thai_han,
                $gia_nhap,
                $han_su_dung,
                $id
            );

            header("Location: admin.php?admin=materials");
            exit;
        }
    }

    public function deleteMaterials()
    {
        $id = $_GET['id'];

        $count = $this->clinic->countXuatVatTu($id);

        if ($count > 0 && !isset($_GET['force'])) {
            $_SESSION['confirm_delete'] = "Vật tư này đã được xuất. Bạn có chắc muốn xóa cả lịch sử xuất không?";
            header("Location: admin.php?admin=materials&confirm=1&id=" . $id);
            exit;
        }

        if (isset($_GET['force'])) {
            $this->clinic->deleteMaterialsForce($id);
        } else {
            $this->clinic->deleteMaterials($id);
        }

        header("Location: admin.php?admin=materials");
    }

    public function updateFormMaterials()
    {
        $idMeterials = $_GET['id'];
        $item = $this->clinic->findMaterials($idMeterials);

        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once __DIR__ . '/../views/supplies/updateFormMaterials.php';
        require_once "backend/views/fileJS.php";
    }

    public function searchMaterials()
    {
        if (isset($_GET['q'])) {
            $keyword = trim($_GET['q']);
            $result = $this->clinic->searchMaterials($keyword);
            echo json_encode($result);
        }
    }

    public function exportMaterial()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        $materials = $this->clinic->getAllMaterials();
        $doctors = $this->clinic->getAllDoctors();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $ids = $_POST['vat_tu_id'];
            $qtys = $_POST['so_luong'];
            $ly_do = $_POST['ly_do'];
            $bac_si_id = $_POST['bac_si_id'];
            $ngay_xuat = date('Y-m-d');

            foreach ($ids as $k => $id) {
                $qty = (int) $qtys[$k];
                if (!$id || !$qty)
                    continue;

                $vatTu = $this->clinic->findMaterials($id);

                if (!$vatTu) {
                    $_SESSION['error'] = "Vật tư không tồn tại";
                    break;
                }

                if ($vatTu['so_luong'] < $qty) {
                    $_SESSION['error'] = "Không đủ tồn: " . $vatTu['ten_vat_tu'];
                    break;
                }

                $thanh_tien = $qty * $vatTu['gia_nhap'];

                $this->clinic->insertExport([
                    $id,
                    $qty,
                    $ly_do,
                    $ngay_xuat,
                    $bac_si_id,
                    $thanh_tien
                ]);

                $this->clinic->updateQuantity($id, $qty);
            }

            if (!isset($_SESSION['error'])) {
                $_SESSION['success'] = "Xuất vật tư thành công";
            }

            header("Location: admin.php?admin=exportMaterial");
            exit;
        }

        $logs = $this->clinic->getExportLogs();

        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once __DIR__ . '/../views/supplies/exportMaterialForm.php';
        require_once "backend/views/fileJS.php";
    }

    public function historyExportMaterial()
    {
        $doctors = $this->clinic->getAllDoctors();
        $bac_si_id = $_GET['bac_si_id'] ?? null;

        $historyMaterials = $this->clinic->historyExportMaterials($bac_si_id);

        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once __DIR__ . '/../views/supplies/historyExportMaterials.php';
        require_once "backend/views/fileJS.php";
    }

    public function exportExcel()
    {
        $data = $this->clinic->historyExportMaterials();

        header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
        header("Content-Disposition: attachment; filename=lich_su_xuat_vat_tu.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo "\xEF\xBB\xBF";

        $tong = 0;

        echo '
    <meta charset="UTF-8">
    <table border="1">
        <thead>
            <tr style="font-weight:bold;text-align:center">
                <th>Vật tư</th>
                <th>Số lượng</th>
                <th>Giá nhập</th>
                <th>Thành tiền</th>
                <th>Bác sĩ</th>
                <th>Ngày</th>
            </tr>
        </thead>
        <tbody>
    ';

        foreach ($data as $row) {
            $tong += $row['thanh_tien'];

            echo '
            <tr>
                <td>' . $row['ten_vat_tu'] . '</td>
                <td align="center">' . $row['so_luong'] . '</td>
                <td>' . number_format($row['gia_nhap']) . '</td>
                <td>' . number_format($row['thanh_tien']) . '</td>
                <td>' . $row['ten_bac_si'] . '</td>
                <td>' . $row['ngay_xuat'] . '</td>
            </tr>
        ';
        }

        echo '
        <tr style="font-weight:bold">
            <td colspan="3">TỔNG</td>
            <td>' . number_format($tong) . '</td>
            <td colspan="2"></td>
        </tr>
        </tbody>
    </table>
    ';
        exit;
    }

    public function aiChatMaterials()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        header("Content-Type: application/json");

        $data = json_decode(file_get_contents("php://input"), true);
        $msg = trim($data['message'] ?? '');

        if (!$msg) {
            echo json_encode(["reply" => "Bạn chưa nhập nội dung"]);
            exit;
        }

        if (isset($_SESSION['pending_material'])) {
            if (mb_strtolower($msg) === 'xác nhận') {
                $p = $_SESSION['pending_material'];

                $ok = $this->clinic->increaseMaterial($p['name'], $p['qty']);

                unset($_SESSION['pending_material']);

                if ($ok) {
                    echo json_encode([
                        "reply" => "✅ Đã nhập thêm {$p['qty']} {$p['name']} vào kho."
                    ]);
                } else {
                    echo json_encode([
                        "reply" => "❌ Không tìm thấy vật tư: {$p['name']}"
                    ]);
                }
                exit;
            }

            if (mb_strtolower($msg) === 'hủy') {
                unset($_SESSION['pending_material']);
                echo json_encode(["reply" => "⛔ Đã hủy thao tác nhập kho."]);
                exit;
            }
        }

        $materials = $this->clinic->getAllMaterialsForAI();

        $context = "Danh sách vật tư:\n";
        foreach ($materials as $m) {
            $context .= "- {$m['ten_vat_tu']} | SL: {$m['so_luong']} | HSD: {$m['han_su_dung']}\n";
        }

        if (preg_match('/nhập thêm (\d+) (.+)/ui', $msg, $m)) {
            $qty = (int) $m[1];
            $name = trim($m[2]);

            $_SESSION['pending_material'] = [
                'qty' => $qty,
                'name' => $name
            ];

            echo json_encode([
                "reply" => "⚠ Bạn có chắc muốn nhập thêm $qty \"$name\"?\n👉 Gõ **XÁC NHẬN** để tiếp tục hoặc **HỦY**."
            ]);
            exit;
        }

        $url = "https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key=" . GEMINI_KEY;

        $payload = [
            "contents" => [
                [
                    "role" => "user",
                    "parts" => [
                        ["text" => "Bạn là AI quản lý kho nha khoa MediTrust.\n$context\n\nCâu hỏi: $msg"]
                    ]
                ]
            ]
        ];

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 60
        ]);

        $result = curl_exec($ch);

        if ($result === false) {
            echo json_encode(["reply" => "Curl error: " . curl_error($ch)]);
            exit;
        }

        curl_close($ch);

        $res = json_decode($result, true);

        if (isset($res['error'])) {
            echo json_encode(["reply" => "Gemini error: " . $res['error']['message']]);
            exit;
        }

        if (!isset($res['candidates'][0]['content']['parts'][0]['text'])) {
            echo json_encode(["reply" => "Response lỗi: " . $result]);
            exit;
        }

        echo json_encode([
            "reply" => trim($res['candidates'][0]['content']['parts'][0]['text'])
        ]);
        exit;
    }
}
