<?php
require_once __DIR__ . '/../../core/Gemini.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class backendController
{
    private $clinic;
    public function __construct()
    {
        require_once __DIR__ . '/../models/db.php';
        $this->clinic = new modelClinic();
        $this->clinic->ketNoiDB();
    }
    // Tuấn thần đèn
    // =================================================================================================================================================
    public function index()
    {
        $isAdmin = $_SESSION['role'] === 'admin';
        $bacSiId = $isAdmin ? null : ($_SESSION['bac_si_id'] ?? null);

        // Tổng bệnh nhân
        $tongBenhNhan = $this->clinic->countAllBenhNhan();

        // Tổng lịch khám
        $tongLichKham = $isAdmin
            ? $this->clinic->countAllLichKham()
            : $this->clinic->countLichKhamByBacSi($bacSiId);

        // Tổng bác sĩ
        $tongBacSi = $this->clinic->countAllBacSi();

        // Doanh thu
        $tongDoanhThu  = $this->clinic->tongDoanhThu();
        $doanhThuHomNay  = $this->clinic->doanhThuHomNay();
        $doanhThuThangNay = $this->clinic->doanhThuThang();

        // Lịch khám hôm nay
        $lichKhamHomNay = $this->clinic->lichKhamHomNay($bacSiId);

        // Biểu đồ doanh thu 12 tháng
        $revenueData = $this->clinic->doanhThu12Thang();

        // Top dịch vụ
        $topServices   = $this->clinic->topDichVu();
        $serviceLabels = [];
        $serviceData   = [];
        foreach ($topServices as $service) {
            $serviceLabels[] = $service['ten_dich_vu'];
            $serviceData[]   = $service['total'];
        }

        // Lịch hôm nay chi tiết + trạng thái
        $lichHomNayChiTiet  = $this->clinic->lichKhamHomNayChiTiet($bacSiId);
        $trangThaiHomNay    = $this->clinic->thongKeTrangThaiHomNay($bacSiId);

        // Cảnh báo vật tư / thuốc
        $vatTuCanhBao = $this->clinic->getMaterialAlerts();

        // Bệnh nhân theo 7 ngày gần nhất
        $raw7Ngay  = $this->clinic->benhNhanTheo7Ngay($bacSiId);
        $ngayMap   = [];
        $ngayLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $ngayMap[$date] = 0;
            $ngayLabels[]   = date('d/m', strtotime($date));
        }
        foreach ($raw7Ngay as $row) {
            if (isset($ngayMap[$row['ngay']])) {
                $ngayMap[$row['ngay']] = (int) $row['so_luong'];
            }
        }
        $ngayData = array_values($ngayMap);

        // Tin nhắn
        $user_id      = $_SESSION['admin']['id'];
        $soTinChuaDoc = $this->clinic->demTinChuaDoc($user_id);
        $tinGanNhat   = $this->clinic->layTinGanNhat($user_id);

        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/dashBoard.php";
        require_once "backend/views/fileJS.php";
    }
    public function tables()
    {
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/tables.php";
        require_once "backend/views/fileJS.php";
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





    // ===========================================================================================================================================================


    public function loginSystem()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        if ($_SERVER['REQUEST_METHOD'] == "POST") {

            $sdt = trim($_POST['sdt']);
            $pass = $_POST['password'];

            $admin = $this->clinic->findByPhone($sdt);

            if (!$admin) {
                $_SESSION['error'] = "SĐT không tồn tại";
                header("Location: admin.php?admin=loginSystem");
                exit;
            }

            // ===== UNLOCK NẾU HẾT THỜI GIAN KHÓA =====
            if ($admin['thoi_gian_tam_khoa'] && strtotime($admin['thoi_gian_tam_khoa']) <= time()) {
                $this->clinic->resetFail($admin['id']);
                $admin['sai_mk'] = 0;
                $admin['thoi_gian_tam_khoa'] = null;
            }

            // ===== CHECK KHÓA =====
            if ($admin['thoi_gian_tam_khoa'] && strtotime($admin['thoi_gian_tam_khoa']) > time()) {
                $_SESSION['error'] = "Tài khoản bị khóa đến: " . $admin['thoi_gian_tam_khoa'];
                header("Location: admin.php?admin=loginSystem");
                exit;
            }

            // ===== CHECK PASSWORD =====
            // ===== CHECK PASSWORD (PLAIN + HASH) =====
            $isPasswordOk = false;

            // Nếu là hash
            if (password_verify($pass, $admin['password'])) {
                $isPasswordOk = true;
            }
            // Nếu là plain text
            elseif ($pass === $admin['password']) {
                $isPasswordOk = true;

                // 👉 Upgrade sang hash
                $newHash = password_hash($pass, PASSWORD_BCRYPT);
                $this->clinic->updatePassword($admin['id'], $newHash);
            }

            if ($isPasswordOk) {

                session_regenerate_id(true);

                $this->clinic->resetFail($admin['id']);
                $this->clinic->logLogin($admin['id'], "SUCCESS");

                $_SESSION['admin'] = $admin;
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_name'] = $admin['ten_nguoi_su_dung'] ?? '';
                $_SESSION['admin_ip'] = $_SERVER['REMOTE_ADDR'];
                $_SESSION['login_time'] = time();
                $_SESSION['role'] = $admin['role'];
                $_SESSION['bac_si_id'] = $admin['bac_si_id'] ?? null;

                switch ($admin['role']) {
                    case 'admin':
                        header("Location: admin.php");
                        break;
                    case 'bac_si':
                        header("Location: admin.php?admin=listLichKham");
                        break;
                    case 'le_tan':
                        header("Location: admin.php?admin=receptionPanel");
                        break;
                    default:
                        header("Location: admin.php");
                }

                exit;
            }

            // ===== SAI MK =====
            $this->clinic->increaseFail($admin['id']);
            $this->clinic->logLogin($admin['id'], "FAILED");

            if ($admin['sai_mk'] + 1 >= 5) {
                $this->clinic->lockAccount($admin['id']);
                $_SESSION['error'] = "Sai quá 5 lần, khóa 10 phút!";
            } else {
                $_SESSION['error'] = "Sai mật khẩu!";
            }

            header("Location: admin.php?admin=loginSystem");
            exit;
        }

        require 'backend/views/auth/login.php';
    }

    public function addAccount()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: admin.php?admin=formRoleAccess");
            exit;
        }

        $sdt = $_POST['sdt'];
        $ten = $_POST['ten_nguoi_su_dung'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = $_POST['role'];
        $bac_si_id = $_POST['bac_si_id'] ?? null;

        if ($role == 'admin') {
            $_SESSION['error'] = "Không thể tạo quyền admin!";
            header("Location: admin.php?admin=formRoleAccess");
            exit;
        }

        if ($role == 'bac_si' && empty($bac_si_id)) {
            $_SESSION['error'] = "Vui lòng chọn bác sĩ!";
            header("Location: admin.php?admin=formRoleAccess");
            exit;
        }

        $this->clinic->insertAccount([$sdt, $password, $ten, $role, $bac_si_id]);

        $_SESSION['success'] = "Tạo tài khoản thành công!";
        header("Location: admin.php?admin=loginSystem");
        exit;
    }

    public function formRoleAccess()
    {
        $listBacSi = $this->clinic->getAllBacSi();
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once 'backend/views/auth/CreateRoleForm.php';
        require_once "backend/views/fileJS.php";
    }

    public function changePassword()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        if (!isset($_SESSION['admin'])) {
            header("Location: admin.php?admin=loginSystem");
            exit;
        }

        $admin = $_SESSION['admin'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $old = $_POST['old_password'] ?? '';
            $new = $_POST['new_password'] ?? '';
            $cf = $_POST['confirm_password'] ?? '';

            // ===== CHECK OLD PASSWORD (HASH + THƯỜNG) =====
            $dbPass = $admin['password'];

            $ok = false;
            if (password_verify($old, $dbPass)) {
                $ok = true;
            } elseif ($old === $dbPass) {
                $ok = true;
            }

            if (!$ok) {
                $_SESSION['error'] = "Mật khẩu hiện tại không đúng";
                header("Location: admin.php?admin=profile");
                exit;
            }

            if (strlen($new) < 6) {
                $_SESSION['error'] = "Mật khẩu mới phải ≥ 6 ký tự";
                header("Location: admin.php?admin=profile");
                exit;
            }

            if ($new !== $cf) {
                $_SESSION['error'] = "Xác nhận mật khẩu không khớp";
                header("Location: admin.php?admin=profile");
                exit;
            }

            // ===== HASH PASSWORD MỚI =====
            $hash = password_hash($new, PASSWORD_BCRYPT);

            $this->clinic->updatePassword($admin['id'], $hash);
            $this->clinic->logActivity($admin['id'], 'CHANGE_PASSWORD');

            $_SESSION['success'] = "Đổi mật khẩu thành công, vui lòng đăng nhập lại!";
            session_unset();
            session_destroy();

            header("Location: admin.php?admin=loginSystem&msg=changed");
            exit;
        }
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        if (isset($_SESSION['admin_id'])) {
            $this->clinic->logLogin($_SESSION['admin_id'], "LOGOUT");
        }

        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();

        header("Location: admin.php?admin=loginSystem");
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

        // ================= CONFIRM STEP =================
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
        // =================================================


        $materials = $this->clinic->getAllMaterialsForAI();

        $context = "Danh sách vật tư:\n";
        foreach ($materials as $m) {
            $context .= "- {$m['ten_vat_tu']} | SL: {$m['so_luong']} | HSD: {$m['han_su_dung']}\n";
        }

        // ================= PARSE COMMAND =================
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
        // =================================================


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


    // ===========================================================================================================================================================


    public function createInvoice()
    {
        $benh_nhan_id = $_POST['benh_nhan_id'] ?? null;
        $bac_si_id = $_POST['bac_si_id'] ?? null;
        $lich_kham_id = $_POST['lich_kham_id'] ?? null;
        $phuong_thuc = $_POST['phuong_thuc_tt'] ?? 'Tien mat';

        $dich_vu_ids = $_POST['dich_vu_id'] ?? [];
        $thuoc_ids = $_POST['thuoc_id'] ?? [];
        $so_luong = $_POST['so_luong'];
        $voucherCode = $_POST['voucher'] ?? null;

        if (!$benh_nhan_id || !$bac_si_id || empty($dich_vu_ids)) {
            die("Thiếu dữ liệu");
        }

        if ($lich_kham_id && $this->clinic->checkHoaDonByLich($lich_kham_id)) {
            die("Lịch này đã có hóa đơn");
        }

        try {

            // ===== TÍNH TỔNG =====
            $tong = 0;

            foreach ($dich_vu_ids as $id) {
                $tong += $this->clinic->getGiaDichVuTheoLichKham($lich_kham_id, $id);
            }

            foreach ($thuoc_ids as $index => $id) {
                $gia = $this->clinic->getGiaThuoc($id);
                $sl = $so_luong[$index];

                $tong += $gia * $sl;
            }

            // ===== VOUCHER =====
            $giam_gia = 0;

            if ($voucherCode) {
                $voucher = $this->clinic->getVoucher($voucherCode);
                if ($voucher) {
                    if ($voucher['loai'] == 'phan_tram') {
                        $giam_gia = $tong * $voucher['gia_tri'] / 100;
                    } else {
                        $giam_gia = $voucher['gia_tri'];
                    }

                    $this->clinic->truLuotVoucher($voucher['id']);
                }
            }

            $thanh_tien = max(0, $tong - $giam_gia);

            // ===== INSERT HÓA ĐƠN =====
            $hoa_don_id = $this->clinic->insertHoaDon([
                $benh_nhan_id,
                $bac_si_id,
                $lich_kham_id,
                $tong,
                $giam_gia,
                $thanh_tien,
                $phuong_thuc,
                0
            ]);

            // ===== CHI TIẾT DỊCH VỤ =====
            $dich_vu_ids = array_count_values($dich_vu_ids);

            foreach ($dich_vu_ids as $id => $so_luong) {
                $gia = $this->clinic->getGiaDichVuTheoLichKham($lich_kham_id, $id);

                $this->clinic->insertChiTietHoaDon([
                    $hoa_don_id,
                    'dich_vu',
                    $id,
                    $so_luong,
                    $gia,
                    $gia * $so_luong
                ]);
            }


            // ===== CHI TIẾT THUỐC =====
            foreach ($thuoc_ids as $index => $id) {

                $gia = $this->clinic->getGiaThuoc($id);
                $sl = $so_luong[$index];

                $this->clinic->insertChiTietHoaDon([
                    $hoa_don_id,
                    'thuoc',
                    $id,
                    $sl,
                    $gia,
                    $gia * $sl
                ]);
            }


            $_SESSION['success'] = "Tạo hóa đơn thành công!";
            header("Location: admin.php?admin=getAllOrder");
            exit;
        } catch (Exception $e) {
            die("Lỗi: " . $e->getMessage());
        }
    }


    public function checkVoucher()
    {
        header("Content-Type: application/json");

        $code = $_POST['code'] ?? '';
        $tong = (int) ($_POST['tong'] ?? 0);

        if (!$code) {
            echo json_encode(['status' => false, 'msg' => 'Vui lòng nhập mã']);
            return;
        }

        if ($tong <= 0) {
            echo json_encode(['status' => false, 'msg' => 'Chưa có dịch vụ để áp dụng']);
            return;
        }

        $voucher = $this->clinic->getVoucher($code);

        if (!$voucher) {
            echo json_encode(['status' => false, 'msg' => 'Mã không hợp lệ']);
            return;
        }

        if ($voucher['loai'] == 'phan_tram') {
            $giam = $tong * $voucher['gia_tri'] / 100;
        } else {
            $giam = $voucher['gia_tri'];
        }

        // 👉 KHÔNG ÉP GIẢM = TỔNG NỮA
        $thanh_tien = $tong - $giam;
        if ($thanh_tien < 0)
            $thanh_tien = 0;

        echo json_encode([
            'status' => true,
            'giam' => (int) $giam,
            'thanh_tien' => (int) $thanh_tien
        ]);
    }




    public function formCreateOrder()
    {
        $lich_kham_id = $_GET['lich_kham_id'] ?? null;

        if (!$lich_kham_id) {
            die('Thiếu lịch khám');
        }


        $lich = $this->clinic->getLichKhamById($lich_kham_id);
        $thuocs = $this->clinic->getThuocTheoLich($lich_kham_id);
        $services = $this->clinic->getDichVuTheoLichKham($lich_kham_id);

        $dichvu = $this->clinic->getDichVuTheoLichKham($lich_kham_id);
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once 'backend/views/order/createOrder.php';
        require_once "backend/views/fileJS.php";
    }

    // ==========================================================================================================================================================
    public function getAllOrder()
    {
        $allOrder = $this->clinic->getAllHoaDon();
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/order/listOrder.php";
        require_once "backend/views/fileJS.php";
    }

    public function updateHoaDonStatus()
    {
        $id = $_POST['id'];
        $status = $_POST['status'];

        $this->clinic->updateHoaDonStatus($id, $status);

        echo json_encode(['status' => true]);
    }

    // Tuấn thần đèn

    // ========================================================================================================================================================
    public function saveOrder()
    {
        $lich_kham_id = $_POST['lich_kham_id'];
        $benh_nhan_id = $_POST['benh_nhan_id'];
        $bac_si_id = $_POST['bac_si_id'];
        $giam_gia = $_POST['giam_gia'];
        $phuong_thuc = $_POST['phuong_thuc_tt'];

        $lich = $this->clinic->getLichKhamById($lich_kham_id);

        $tong_tien = $lich['gia'];
        $thanh_tien = $tong_tien - $giam_gia;

        $data = [
            $benh_nhan_id,
            $bac_si_id,
            $lich_kham_id,
            $tong_tien,
            $giam_gia,
            $thanh_tien,
            $phuong_thuc
        ];

        $this->clinic->insertHoaDon($data);
        $this->clinic->updateTrangThaiLich($lich_kham_id);

        header("Location: admin.php?admin=listOrder");
    }

    // Tuấn thần đèn ngày 5/02/2026
    public function listDiscount()
    {
        $allDiscount = $this->clinic->getAllDiscount();
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/order/voucher/listDiscount.php";
        require_once "backend/views/fileJS.php";
    }
    public function createDiscount()
    {
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require __DIR__ . '/../views/order/voucher/createVoucher.php';
        require_once "backend/views/fileJS.php";
    }

    public function storeDiscount()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $data = [
            $_POST['code'] ?? '',
            $_POST['loai'] ?? '',
            $_POST['gia_tri'] ?? 0,
            $_POST['so_luot'] ?? 0,
            $_POST['ngay_bat_dau'] ?? null,
            $_POST['ngay_ket_thuc'] ?? null,
            $_POST['trang_thai'] ?? 1
        ];

        $this->clinic->insertVoucher($data);

        header("Location: admin.php?admin=listDiscount");
        exit;
    }


    public function editDiscount()
    {
        $item = $this->clinic->findVoucher($_GET['id']);
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once __DIR__ . '/../views/order/voucher/editVoucher.php';
        require_once "backend/views/fileJS.php";
    }

    public function updateDiscount()
    {
        $this->clinic->updateVoucher([
            $_POST['code'],
            $_POST['loai'],
            $_POST['gia_tri'],
            $_POST['so_luot'],
            $_POST['ngay_bat_dau'],
            $_POST['ngay_ket_thuc'],
            $_POST['trang_thai'],
            $_POST['id']
        ]);
        header("Location: admin.php?admin=listDiscount");
    }

    public function deleteDiscount()
    {
        $this->clinic->deleteVoucher($_GET['id']);
        header("Location: admin.php?admin=listDiscount");
    }



    // Tuấn idol ngày 02/10/2026
    // ==========================================================================================================================================================================
    public function accessDenied()
    {
        require_once 'backend/views/accessDenied.php';
    }

    public function dashBoard()
    {
        // Tổng bệnh nhân
        $tongBenhNhan = $this->clinic->countAllBenhNhan();
        // Tổng lịch khám && lịch khám theo bác sĩ
        if ($_SESSION['role'] === 'admin') {
            $tongLichKham = $this->clinic->countAllLichKham();
        } else {
            $tongLichKham = $this->clinic->countLichKhamByBacSi($_SESSION['bac_si_id']);
        }
        // Tổng bác sĩ
        $tongBacSi = $this->clinic->countAllBacSi();

        // doanh thu
        // $tongDoanhThu = $this->clinic->doanhThuDichVu();
        // $listDoanhThuDichVu = $this->clinic->doanhThuTheoTungDichVu();
        $revenueData = $this->clinic->doanhThu12Thang();
        $topServices = $this->clinic->topDichVu();


        $serviceLabels = [];
        $serviceData = [];

        foreach ($topServices as $service) {
            $serviceLabels[] = $service['ten_dich_vu'];
            $serviceData[] = $service['total'];
        }
        // tổng doanh thu
        $tongDoanhThu = $this->clinic->tongDoanhThu();

        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once 'backend/views/dashBoard.php';
        require_once "backend/views/fileJS.php";
    }


    // tuấn idol ngày 15/02/2026
    // ============================================================================================================================================================================
    public function adminLogs()
    {
        $logs = $this->clinic->getAdminLogs();

        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/activityLog.php";
        require_once "backend/views/fileJS.php";
    }

    // tuấn idol ngày 18/02/2026
    public function listKetQuaKham()
    {
        $this->clinic = new modelClinic();
        $this->clinic->ketNoiDB();

        $list = $this->clinic->getDanhSachKetQuaKham();

        foreach ($list as &$item) {

            $lich_kham_id = $item['lich_kham_id'];

            $thuoc = $this->clinic->getThuocTheoLich($lich_kham_id);

            $item['thuoc'] = is_array($thuoc) ? $thuoc : [];
        }

        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/order/listExaminationResults.php";
        require_once "backend/views/fileJS.php";
    }
    // tuấn idol 19/02/2026
    public function exportInvoice()
    {
        $id = $_GET['id'] ?? null;
        if (!$id)
            die("Thiếu ID");

        $hoa_don = $this->clinic->getHoaDonById($id);
        $chi_tiet = $this->clinic->getChiTietHoaDon($id);

        require 'libs/export_invoice.php';
    }


    public function updateAvatar()
    {
        if (!isset($_FILES['avatar'])) {
            die("Không có file");
        }

        $admin_id = $_SESSION['admin']['id'];
        $file = $_FILES['avatar'];

        if ($file['error'] !== 0) {
            die("Lỗi upload");
        }

        // Kiểm tra định dạng
        $allowed = ['jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            die("Chỉ cho phép JPG, JPEG, PNG");
        }

        // Tạo tên mới
        $newName = "avatar_" . $admin_id . "_" . time() . "." . $ext;

        // 👉 Đường dẫn tuyệt đối
        $uploadDir = realpath(__DIR__ . "/../uploads/avatar/");

        if (!$uploadDir) {
            die("Không tìm thấy thư mục uploads/avatar");
        }

        $uploadPath = $uploadDir . DIRECTORY_SEPARATOR . $newName;

        // Lấy avatar cũ
        $oldAvatar = $this->clinic->getAdminById($admin_id)['avatar'];

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {

            // Xóa ảnh cũ nếu có
            if (!empty($oldAvatar)) {
                $oldPath = $uploadDir . DIRECTORY_SEPARATOR . $oldAvatar;
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // Update DB
            $this->clinic->updateAdminAvatar($admin_id, $newName);

            $_SESSION['admin']['avatar'] = $newName;

            header("Location: admin.php?admin=profile");
            exit;
        }

        die("Upload thất bại");
    }
    public function tatCaTin()
    {

        $user_id = $_SESSION['admin']['id'];


        $danhSachTin = $this->clinic->layTatCaTin($user_id);


        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        include "backend/views/allMessages.php";
        require_once "backend/views/fileJS.php";
    }
    // tuấn idol ngày 20/02/2026
    public function chiTietTin()
    {
        $id = $_GET['id'] ?? 0;
        $user_id = $_SESSION['admin']['id'];

        if (!$id) {
            header("Location: admin.php?admin=tatCaTin");
            exit;
        }

        $tin = $this->clinic->layChiTietTin($id);

        if (!$tin) {
            header("Location: admin.php?admin=tatCaTin");
            exit;
        }

        // đánh dấu đã đọc
        $this->clinic->danhDauDaDoc($id, $user_id);
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        include "backend/views/chiTietTin.php";
        require_once "backend/views/fileJS.php";
    }

    public function hienFormTraLoi()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            die("Không tìm thấy tin nhắn");
        }

        $tin = $this->clinic->layChiTietTin($id);

        if (!$tin) {
            die("Tin không tồn tại");
        }


        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/guiTraLoiTinNhan.php";
        require_once "backend/views/fileJS.php";
    }

    public function xuLyGuiTraLoi()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            exit("Sai phương thức");
        }

        require_once __DIR__ . '/../../libs/vendor/autoload.php';


        // 🔥 LẤY DỮ LIỆU POST
        $id = $_POST['id'] ?? null;
        $noi_dung = $_POST['noi_dung'] ?? null;

        if (!$id || !$noi_dung) {
            exit("Thiếu dữ liệu");
        }

        $tin = $this->clinic->layChiTietTin($id);

        if (!$tin)
            exit("Tin không tồn tại");

        $mail = new PHPMailer(true);


        try {

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'tenmienfree26@gmail.com';
            $mail->Password = 'dtnzvyakgmauszqs';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('tenmienfree26@gmail.com', 'MediTrust Admin');
            $mail->addAddress($tin['email_nguoi_gui']);
            $mail->SMTPDebug = 0;
            $mail->Debugoutput = 'html';
            // Upload file
            $fileName = null;

            if (!empty($_FILES['file']['name'])) {

                $uploadDir = "uploads/";

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileName = time() . "_" . $_FILES['file']['name'];

                move_uploaded_file(
                    $_FILES['file']['tmp_name'],
                    $uploadDir . $fileName
                );

                $mail->addAttachment($uploadDir . $fileName);
            }

            $noiDungHTML = "
<div style='font-family:Segoe UI,Arial,sans-serif;background:#f4f6fb;padding:30px'>
    
    <div style='max-width:600px;margin:auto;background:#ffffff;
                border-radius:12px;overflow:hidden;
                box-shadow:0 4px 20px rgba(0,0,0,0.08)'>

        <!-- HEADER -->
        <div style='background:#4e73df;color:#fff;padding:20px 24px'>
            <h2 style='margin:0;font-size:20px'>📩 Phản hồi từ MediTrust</h2>
        </div>

        <!-- CONTENT -->
        <div style='padding:24px;color:#333;line-height:1.6;font-size:15px'>
            <p>Xin chào,</p>

            <div style='background:#f8f9fc;
                        border-left:4px solid #4e73df;
                        padding:16px;
                        border-radius:6px;
                        margin:16px 0'>
                " . nl2br(htmlspecialchars($noi_dung)) . "
            </div>

            <p style='margin-top:24px'>
                Nếu bạn cần hỗ trợ thêm, vui lòng phản hồi lại email này.
            </p>

            <p style='margin-top:30px'>
                Trân trọng,<br>
                <b>Đội ngũ MediTrust</b>
            </p>
        </div>

        <!-- FOOTER -->
        <div style='background:#f1f3f9;
                    padding:14px 24px;
font-size:12px;
                    color:#888;
                    text-align:center'>
            © " . date('Y') . " MediTrust. All rights reserved.
        </div>

    </div>
</div>
";

            $mail->isHTML(true);
            $mail->Subject = "Phản hồi: " . $tin['tieu_de'];
            $mail->Body = $noiDungHTML;
            $mail->AltBody = strip_tags($noi_dung);
            $mail->send();

            // Lưu DB
            $this->clinic->luuPhanHoi($id, $noi_dung, $fileName);

            header("Location: admin.php?admin=tatCaTin");
            exit;
        } catch (Exception $e) {
            echo "Lỗi gửi mail: {$mail->ErrorInfo}";
        }
    }


    //  ======================================================================================================================================================================
    // update materials



    public function listMedicine()
    {
        $listMedicine = $this->clinic->modelListMedicine();
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/supplies/listMedicine.php";
        require_once "backend/views/fileJS.php";
    }
    public function formAddMedicine()
    {
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/supplies/addMedicine.php";
        require_once "backend/views/fileJS.php";
    }
    public function addMedicine()
    {
        $medicineName = $_POST['medicineName'];
        $classMedicine = $_POST['classMedicine'];
        $dosageForm = $_POST['dosageForm'];
        $drugContent = $_POST['drugContent'];
        $unit = $_POST['unit'];
        $quantity = $_POST['quantity'];
        $expirationDate = $_POST['expirationDate'];
        $price = $_POST['price'];
        $manufacturer = $_POST['manufacturer'];
        $countryProduction = $_POST['countryProduction'];
        $description = $_POST['description'];

        $result = $this->clinic->addMedicine($medicineName, $classMedicine, $dosageForm, $drugContent, $unit, $quantity, $expirationDate, $price, $manufacturer, $countryProduction, $description);

        if ($result) {
            header("location: admin.php?admin=listMedicine&status=addSuccess");
        } else {
            header("location: admin.php?admin=listMedicine&status=addError");
        }
    }

    public function formEditMedicine($idAdmin)
    {
        $medicineByID = $this->clinic->getMedicineByID($idAdmin);
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/supplies/editMedicine.php";
        require_once "backend/views/fileJS.php";
    }

    public function editMedicine($idAdmin)
    {
        $medicineName = $_POST['medicineName'];
        $classMedicine = $_POST['classMedicine'];
        $dosageForm = $_POST['dosageForm'];
        $drugContent = $_POST['drugContent'];
        $unit = $_POST['unit'];
        $quantity = $_POST['quantity'];
        $expirationDate = $_POST['expirationDate'];
        $price = $_POST['price'];
        $manufacturer = $_POST['manufacturer'];
        $countryProduction = $_POST['countryProduction'];
        $description = $_POST['description'];

        $result = $this->clinic->editMedicine($medicineName, $classMedicine, $dosageForm, $drugContent, $unit, $quantity, $expirationDate, $price, $manufacturer, $countryProduction, $description, $idAdmin);

        if ($result) {
            header("location: admin.php?admin=listMedicine&status=updateSuccess");
        } else {
            header("location: admin.php?admin=listMedicine&status=updateError");
        }
    }

    public function deleteMedicine($idAdmin)
    {
        $result = $this->clinic->deleteMedicine($idAdmin);
        if ($result) {
            header("location: admin.php?admin=listMedicine&status=deleteSuccess");
        } else {
            header("location: admin.php?admin=listMedicine&status=errorDelete");
        }
    }

    public function detailMedicine($idAdmin)
    {
        $detailMedicineByID = $this->clinic->getMedicineByID($idAdmin);
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/supplies/deltailMedicine.php";
        require_once "backend/views/fileJS.php";
    }

    public function listDispenseMedicine()
    {
        $historyDispenseMedicine = $this->clinic->getAllDispenseMedicine();
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/supplies/listDispenseMedicine.php";
        require_once "backend/views/fileJS.php";
    }

    public function formDispenseMedicine()
    {
        $listMedicine = $this->clinic->modelListMedicine();
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/supplies/formDispenseMedicine.php";
        require_once "backend/views/fileJS.php";
    }

    public function dispenseMedicine()
    {
        $idMedicine = $_POST['idMedicine'];
        $quantityDispense = $_POST['quantityDispense'];
        $reasonDispense = $_POST['reasonDispense'];
        $dateDispense = $_POST['dateDispense'];

        $currentMedicine = $this->clinic->getMedicineByID($idMedicine);

        $stockAvailable = (int) $currentMedicine['so_luong'];

        if ($quantityDispense <= 0) {
            header("location: admin.php?admin=formDispenseMedicine&status=too_little");
        } else if ($stockAvailable < $quantityDispense) {
            header("location: admin.php?admin=formDispenseMedicine&status=out_of_stock&available=$stockAvailable");
        } else {
            $this->clinic->dispenseMedicine($idMedicine, $quantityDispense, $reasonDispense, $dateDispense);
            header("location: admin.php?admin=listDispenseMedicine&status=success");
        }
    }

    public function formPrescription()
    {
        $medicines = $this->clinic->modelListMedicine();
        $doctor = $this->clinic->getAllDoctor();
        $namePatient = $this->clinic->getAllPatient();
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/supplies/formPrescription.php";
        require_once "backend/views/fileJS.php";
    }

    public function listPrescription()
    {
        $prescription = $this->clinic->modelListPrescription();
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/supplies/listPrescription.php";
        require_once "backend/views/fileJS.php";
    }

    public function prescription()
    {
        $patient = $_POST['patient_id'];
        $doctor = $_POST['doctor_id'];
        $diagnose = $_POST['diagnose'];
        $dosage = $_POST['dosage'];
        $idMedicine = $_POST['idMedicine'];
        $quantityMedicine = $_POST['quantityMedicine'];

        $result = $this->clinic->prescription($patient, $doctor, $diagnose, $idMedicine, $quantityMedicine, $dosage);

        if ($result === false) {
            header("location: admin.php?admin=formPrescription&status=error");
        } else {
            header("location: admin.php?admin=listPrescription&status=success");
        }
    }

    public function detailPrescription($idAdmin)
    {
        $detailPres = $this->clinic->getPrescriptionByID($idAdmin);
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/supplies/detailPrescription.php";
        require_once "backend/views/fileJS.php";
    }



    // đây là của dũng
    // Hiển thị danh sách bệnh nhân
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
        $mode = $_GET['mode'] ?? 'normal'; // normal | tiep_nhan
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
    // Hiển thị form thêm bệnh nhân mới

    public function add()
    {
        $errors = [];

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

            /* ================= VALIDATE ================= */

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

            /* ================= CHECK TRÙNG ================= */

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

            /* ================= NẾU CÓ LỖI ================= */

            if (!empty($errors)) {
                $item = $_POST; // giữ lại dữ liệu cũ
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

                    // ================== TẠO TÀI KHOẢN TỰ ĐỘNG ==================
                    $account = $this->clinic->findAccountByEmailOrPhone($email, $so_dien_thoai);

                    if (!$account) {

                        $this->clinic->insertUser([
                            $newId,          // ho_so_benh_nhan_id
                            $email,
                            $so_dien_thoai,
                            null,            // mật khẩu NULL
                            0,               // trạng thái chưa kích hoạt
                            null,            // otp
                            null             // otp_expired
                        ]);
                    } else {
                        $_SESSION['success'] =
                            "Thêm hồ sơ thành công (bệnh nhân đã có tài khoản trước đó).";
                    }

                    // ===========================================================

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

    // Hiển thị form sửa bệnh nhân
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

    // Xóa bệnh nhân
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

    // Chi tiết bệnh nhân
    public function detail()
    {
        $id = $_GET['id'] ?? '';
        $item = $this->clinic->getById($id);
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/patientManagement/chi-tiet-benh-nhan.php";
        require_once "backend/views/fileJS.php";
    }
    public function profile()
    {
        if (empty($_SESSION['admin'])) {
            header("Location: admin.php?admin=loginSystem");
            exit;
        }

        $role = $_SESSION['role'] ?? '';
        $admin = $_SESSION['admin'];

        $admin_id = $admin['id'];
        $bacSiId = $admin['bac_si_id'] ?? null;

        $lastLogin = $this->clinic->getLastLogin($admin_id);
        $loginLogs = $this->clinic->getLastLoginLogs($admin_id);

        $month = date('m');
        $year = date('Y');

        if ($role === 'admin') {
            // admin thấy tất cả
            $soLuotKham = $this->clinic->countLichKhamThang($month, $year, null);
            $benhNhan = $this->clinic->countAllPatient(null);
        } else {
            // bác sĩ chỉ thấy của mình
            $soLuotKham = $this->clinic->countLichKhamThang($month, $year, $bacSiId);
            $benhNhan = $this->clinic->countAllPatient($bacSiId);
        }

        require "backend/views/header.php";
        require "backend/views/sidebar.php";
        require "backend/views/topbar.php";
        require "backend/views/profile.php";
        require "backend/views/fileJS.php";
    }


    // end dũng

    //==========Của Quang đây nè==============================
    // ================= QUẢN LÝ DỊCH VỤ =================
    public function qlydichvu()
    {
        $dichvu = $this->clinic->getAll_dich_vu();

        require_once __DIR__ . "/../views/header.php";
        require_once __DIR__ . "/../views/sidebar.php";
        require_once __DIR__ . "/../views/topbar.php";
        require_once __DIR__ . "/../views/quanlydichvu/quanlydichvu.php";
        require_once __DIR__ . "/../views/fileJS.php";
    }

    // ================= XEM CHI TIẾT DỊCH VỤ =================
    public function xemchitietdichvu($id)
    {
        $chitetdichvudichvu = $this->clinic->getDichVuById($id);
        $vatTuSuDung = $this->clinic->getVatTuByDichVu($id);

        require_once __DIR__ . "/../views/header.php";
        require_once __DIR__ . "/../views/sidebar.php";
        require_once __DIR__ . "/../views/topbar.php";
        require_once __DIR__ . "/../views/quanlydichvu/xemchitietdichvu.php";
        require_once __DIR__ . "/../views/fileJS.php";
    }

    // ================= THÊM DỊCH VỤ + VẬT TƯ =================
    public function addDich_vu()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $ten_dich_vu = $_POST['ten_dich_vu'] ?? '';
            $danhmuc = $_POST['danhmuc'] ?? null;
            $id_loai = $_POST['id_loai'] ?? null;
            $gia = $_POST['gia'] ?? 0;
            $mo_ta = $_POST['mo_ta'] ?? '';

            // Upload ảnh
            // Upload ảnh
            $image = null;

            if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

                $allowed = ['jpg', 'jpeg', 'png', 'webp'];
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

                if (!in_array($ext, $allowed)) {
                    die("❌ Chỉ cho phép upload ảnh jpg, jpeg, png, webp");
                }

                $uploadDir = __DIR__ . '/../uploads/services/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                // Đổi tên file tránh trùng
                $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['image']['name']);
                $targetFile = $uploadDir . $fileName;

                if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    die("❌ Upload ảnh thất bại");
                }

                $image = $fileName;
            }

            // Thêm dịch vụ → lấy ID
            $id_dich_vu = $this->clinic->insertDichVu($ten_dich_vu, $danhmuc, $gia, $mo_ta, $image, $id_loai);

            if (!$id_dich_vu) {
                die("❌ Không thêm được dịch vụ");
            }

            // Gán vật tư cho dịch vụ (không lệch mảng)
            $vatTuPost = $_POST['vat_tu'] ?? [];

            foreach ($vatTuPost as $id_vat_tu => $so_luong) {
                if ((int) $so_luong > 0) {
                    $this->clinic->addVatTuToDichVu($id_dich_vu, $id_vat_tu, (int) $so_luong);
                }
            }

            // CẬP NHẬT GIÁ DỊCH VỤ = GIÁ CÔNG + TỔNG TIỀN VẬT TƯ
            $tongVatTu = $this->clinic->getTongTienVatTuByDichVu($id_dich_vu);
            $this->clinic->updateGiaDichVu($id_dich_vu, $gia + $tongVatTu);

            header("Location: admin.php?admin=qlydichvu");
            exit;
        }

        // GET form
        $vatTuList = $this->clinic->getAllVatTu();
        $dichvu = ['tieu_hao' => null];


        require_once __DIR__ . "/../views/header.php";
        require_once __DIR__ . "/../views/sidebar.php";
        require_once __DIR__ . "/../views/topbar.php";
        require_once __DIR__ . "/../views/quanlydichvu/themdichvu.php";
        require_once __DIR__ . "/../views/fileJS.php";
    }

    // ================= LIÊN KẾT / CẬP NHẬT VẬT TƯ DỊCH VỤ =================
    public function lienketVatTuDichVu($id)
    {
        $dichvu = $this->clinic->getDichVuById($id);
        $vatTu = $this->clinic->getAllVatTu();
        $vatTuDaChon = $this->clinic->getVatTuByDichVu($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $this->clinic->deleteAllVatTuByDichVu($id);

            if (!empty($_POST['vat_tu_id'])) {
                foreach ($_POST['vat_tu_id'] as $id_vat_tu) {
                    $so_luong = isset($_POST['so_luong'][$id_vat_tu])
                        ? (int) $_POST['so_luong'][$id_vat_tu]
                        : 0;

                    if ($so_luong > 0) {
                        $this->clinic->addVatTuToDichVu($id, $id_vat_tu, $so_luong);
                    }
                }
            }

            header("Location: admin.php?admin=xemchitietdichvu&id=" . $id);
            exit;
        }

        require_once __DIR__ . "/../views/header.php";
        require_once __DIR__ . "/../views/sidebar.php";
        require_once __DIR__ . "/../views/topbar.php";
        require_once __DIR__ . "/../views/quanlydichvu/xemchitietdichvu.php";
        require_once __DIR__ . "/../views/fileJS.php";
    }
    // public function showFormBenhNhanh(){
    //     require_once __DIR__ . "/../views/header.php";
    //     require_once __DIR__ . "/../views/sidebar.php";
    //     require_once __DIR__ . "/../views/topbar.php";
    //     require_once __DIR__ . "/../views/form-benh-nhan.php";
    //     require_once __DIR__ . "/../views/fileJS.php";
    // }
    //=================sửa dịch vụ=================
    public function suaDichVu($id)
    {
        $dichvu = $this->clinic->getDichVuById($id);
        $vatTuList = $this->clinic->getAllVatTu();
        $vatTuDaChon = $this->clinic->getVatTuByDichVu($id);

        // Tính tổng tiền vật tư hiện tại để trừ ra khi hiển thị form sửa
        $tongTienVatTu = 0;
        foreach ($vatTuDaChon as $vt) {
            $tongTienVatTu += $vt['gia_nhap'] * $vt['so_luong'];
        }

        // Map vật tư đã chọn: id_vat_tu => so_luong
        $mapVatTu = [];
        foreach ($vatTuDaChon as $vt) {
            $mapVatTu[$vt['id']] = $vt['so_luong'];
        }

        require_once __DIR__ . "/../views/header.php";
        require_once __DIR__ . "/../views/sidebar.php";
        require_once __DIR__ . "/../views/topbar.php";
        require_once __DIR__ . "/../views/quanlydichvu/suadichvu.php";
        require_once __DIR__ . "/../views/fileJS.php";
    }
    // ================= CẬP NHẬT DỊCH VỤ =================
    // ================= CẬP NHẬT DỊCH VỤ (CÓ UPLOAD ẢNH) =================
    public function capNhatDichVu($id = 0)
    {

        if ($id <= 0) {
            die(" Thiếu ID dịch vụ");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $ten_dich_vu = trim($_POST['ten_dich_vu'] ?? '');
            $danhmuc = $_POST['danhmuc'] ?? '';
            $id_loai = $_POST['id_loai'] ?? null;
            $gia = (int) ($_POST['gia'] ?? 0);
            $mo_ta = $_POST['mo_ta'] ?? '';

            if ($ten_dich_vu === '' || $danhmuc === '' || $gia < 0) {
                die(" Dữ liệu không hợp lệ");
            }

            // Mặc định giữ ảnh cũ
            $image = $this->clinic->getHinhAnhDichVuById($id); // viết hàm này trong model

            // Nếu có upload ảnh mới
            if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

                $allowed = ['jpg', 'jpeg', 'png', 'webp'];
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

                if (!in_array($ext, $allowed)) {
                    die("❌ Chỉ cho phép upload ảnh jpg, jpeg, png, webp");
                }

                $uploadDir = __DIR__ . '/../uploads/services/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['image']['name']);
                $targetFile = $uploadDir . $fileName;

                if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    die("❌ Upload ảnh thất bại");
                }

                $image = $fileName; // cập nhật ảnh mới
            }

            // Update dịch vụ (có ảnh)
            $this->clinic->updateDichVu($id, $ten_dich_vu, $danhmuc, $gia, $mo_ta, $image, $id_loai);

            // Update vật tư
            $vatTuPost = $_POST['vat_tu'] ?? [];
            $this->clinic->setVatTuForDichVu($id, $vatTuPost);

            // CẬP NHẬT LẠI GIÁ = GIÁ CÔNG (NHẬP TỪ FORM) + TỔNG TIỀN VẬT TƯ MỚI
            $tongVatTu = $this->clinic->getTongTienVatTuByDichVu($id);
            $this->clinic->updateGiaDichVu($id, $gia + $tongVatTu);

            header("Location: admin.php?admin=qlydichvu");
            exit;
        }
    }
    //===================sửa trạng thái dịch vụ===================
    public function toggleTrangThaiDichVu($id)
    {
        if (empty($id)) {
            header("Location: admin.php?admin=qlydichvu");
            exit;
        }

        $dichvu = $this->clinic->getDichVuById($id);

        if (!$dichvu) {
            header("Location: admin.php?admin=qlydichvu");
            exit;
        }

        $trangthai_moi = ($dichvu['trang_thai'] === 'Hoạt động')
            ? 'Ngưng'
            : 'Hoạt động';

        $this->clinic->updateTrangThaiDichVu($id, $trangthai_moi);
        header("Location: admin.php?admin=qlydichvu");
        exit;
    }
    public function vattuthem()
    {
        $vatTuList = $this->clinic->getAllVatTu();
        require_once __DIR__ . "/../views/header.php";
        require_once __DIR__ . "/../views/sidebar.php";
        require_once __DIR__ . "/../views/topbar.php";
        require_once __DIR__ . "/../views/quanlydichvu/vattuthem.php";
        require_once __DIR__ . "/../views/fileJS.php";
    }

    public function vattusua($id)
    {
        $dichvu = $this->clinic->getDichVuById($id);
        $vatTuList = $this->clinic->getAllVatTu();
        $vatTuDaChonRaw = $this->clinic->getVatTuByDichVu($id);

        // Chuyển đổi mảng để view dễ kiểm tra (key là id vật tư)
        $vatTuDaChon = [];
        foreach ($vatTuDaChonRaw as $vt) {
            $vatTuDaChon[$vt['id']] = $vt;
        }

        require_once __DIR__ . "/../views/header.php";
        require_once __DIR__ . "/../views/sidebar.php";
        require_once __DIR__ . "/../views/topbar.php";
        require_once __DIR__ . "/../views/quanlydichvu/vattusua.php";
        require_once __DIR__ . "/../views/fileJS.php";
    }

    //========Hết của Quang rồi nhé.==========================

    // ===========================================================================================/
    // <!-- Coder Vi Đức Được    FB/duocXdangiu    Zalo/0389232813    Github/coderDuoc --> 

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
        // Form Thêm Tài Khoản Bệnh Nhân
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

            // Lưu lại form nếu lỗi
            $_SESSION['old'] = $_POST;

            // 3️⃣ Check hồ sơ đã có tài khoản
            if ($this->clinic->checkHoSoHasAccount($ho_so_id)) {
                $_SESSION['error'] = "Hồ sơ này đã được tạo tài khoản!";
                header("Location: admin.php?admin=formAddPatientAccounts");
                exit;
            }

            // 1️⃣ Check SĐT 10 số
            if (!preg_match('/^0\d{9}$/', $so_dien_thoai)) {
                $_SESSION['error'] = "Số điện thoại phải đúng 10 số!";
                header("Location: admin.php?admin=formAddPatientAccounts");
                exit;
            }

            // 2️⃣ Check trùng SĐT
            if ($this->clinic->checkPhoneExistsInAccount($so_dien_thoai)) {
                $_SESSION['error'] = "Số điện thoại đã có tài khoản!";
                header("Location: admin.php?admin=formAddPatientAccounts");
                exit;
            }

            // 4️⃣ Hash mật khẩu
            $mat_khau = password_hash($mat_khau_raw, PASSWORD_DEFAULT);

            // 5️⃣ Insert
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
    // Edit tài khoản bệnh nhân

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

            // 1️⃣ Check SĐT 10 số
            if (!preg_match('/^0\d{9}$/', $so_dien_thoai)) {
                $_SESSION['error'] = "Số điện thoại phải đúng 10 số!";
                header("Location: admin.php?admin=formEditPatientAccount&id=" . $id);
                exit;
            }

            // 2️⃣ Check trùng SĐT (trừ chính nó)
            if ($this->clinic->checkPhoneExistsExceptId($so_dien_thoai, $id)) {
                $_SESSION['error'] = "Số điện thoại đã được sử dụng!";
                header("Location: admin.php?admin=formEditPatientAccount&id=" . $id);
                exit;
            }

            // 3️⃣ Update SĐT
            $this->clinic->updatePatientAccountPhone($id, $so_dien_thoai);

            unset($_SESSION['old']);
            $_SESSION['success'] = "Cập nhật thành công!";
            header("Location: admin.php?admin=patient-accounts");
            exit;
        }
    }

    // 02/02/2026
    public function listTaiKhoanLeTan()
    {
        $listLeTan = $this->clinic->layThongTinLeTan();
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/userManagement/quanlyletan.php";
        require_once "backend/views/fileJS.php";
    }
    public function formThemTaiKhoanLeTan()
    {
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/userManagement/formaddletan.php";
        require_once "backend/views/fileJS.php";
    }
    public function themTaiKhoanLeTan()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $ten_le_tan = $_POST['ten_le_tan'];
            $sdt = $_POST['sdt'];
            $email = $_POST['email'];
            $gioi_tinh = $_POST['gioi_tinh'];
            $ca_lam = $_POST['ca_lam'];
            $trang_thai = $_POST['trang_thai'];

            $_SESSION['errors'] = [];
            $_SESSION['old'] = $_POST;

            // ✅ CHECK SDT
            if ($this->clinic->checkTrungSDT($sdt) > 0) {
                $_SESSION['errors']['sdt'] = 'Số điện thoại đã tồn tại';
            }

            // ✅ CHECK EMAIL
            $emailRegex = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9-]+\.(com|vn|net|org|edu)$/';
            if (!preg_match($emailRegex, $email)) {
                $_SESSION['errors']['email'] = 'Vui lòng nhập đúng định dạng email (vd: ten@gmail.com)';
                $_SESSION['old'] = $_POST;

                header('Location: admin.php?admin=formThemTaiKhoanLeTan');
                exit;
            }

            // ❌ CÓ LỖI → QUAY VỀ FORM
            if (!empty($_SESSION['errors'])) {
                header('Location: admin.php?admin=formThemTaiKhoanLeTan');
                exit;
            }

            // ✅ THÊM MỚI
            $this->clinic->themTaiKhoanLeTan(
                $ten_le_tan,
                $sdt,
                $email,
                $gioi_tinh,
                $ca_lam,
                $trang_thai
            );

            unset($_SESSION['old']);

            header('Location: admin.php?admin=listTaiKhoanLeTan');
            exit;
        }
    }
    // ===== FORM SỬA =====
    public function formSuaTaiKhoanLeTan()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: admin.php?admin=listTaiKhoanLeTan");
            exit;
        }

        $leTan = $this->clinic->layLeTanTheoID($id);
        require_once 'backend/views/header.php';
        require_once 'backend/views/sidebar.php';
        require_once 'backend/views/topbar.php';
        require_once 'backend/views/userManagement/editletan.php';
        require_once 'backend/views/fileJS.php';
    }

    // ===== XỬ LÝ SỬA =====
    public function suaTaiKhoanLeTan()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['id'];

            // LẤY DỮ LIỆU
            $ten_le_tan = trim($_POST['ten_le_tan']);
            $sdt = trim($_POST['sdt']);
            $email = trim($_POST['email']);
            $gioi_tinh = $_POST['gioi_tinh'];
            $ca_lam = $_POST['ca_lam'];
            $trang_thai = $_POST['trang_thai'];

            $errors = [];

            /* ================= CHECK ĐỊNH DẠNG EMAIL ================= */
            $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com|vn|net|org|edu)$/';

            if (!preg_match($pattern, $email)) {
                $errors['email'] = 'Vui lòng nhập đúng định dạng email (vd: ten@gmail.com)';
            }

            /* ================= CHECK TRÙNG SDT (TRỪ CHÍNH NÓ) ================= */
            if ($this->clinic->kiemTraTrungSDT($sdt, $id)) {
                $errors['sdt'] = 'Số điện thoại đã tồn tại';
            }

            /* ================= CHECK TRÙNG EMAIL (TRỪ CHÍNH NÓ) ================= */
            if ($this->clinic->kiemTraTrungEmail($email, $id)) {
                $errors['email'] = 'Email đã tồn tại';
            }

            /* ================= CÓ LỖI → QUAY LẠI FORM ================= */
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $_SESSION['old'] = $_POST;
                header("Location: admin.php?admin=formSuaTaiKhoanLeTan&id=$id");
                exit;
            }

            /* ================= UPDATE ================= */
            $data = [
                'ten_le_tan' => $ten_le_tan,
                'sdt' => $sdt,
                'email' => $email,
                'gioi_tinh' => $gioi_tinh,
                'ca_lam' => $ca_lam,
                'trang_thai' => $trang_thai
            ];

            $this->clinic->suaTaiKhoanLeTan($id, $data);

            header("Location: admin.php?admin=listTaiKhoanLeTan");
            exit;
        }
    }
    public function listYcLichHen()
    {

        $listYcLichHen = $this->clinic->layThongTinYeuCauLichHen();
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/quanlylichhen/quanlyyeucaulichhen.php";
        require_once "backend/views/fileJS.php";
    }
    public function formSuaYeuCauDatLich()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: admin.php?admin=listYcLichHen");
            exit;
        }

        $yeuCau = $this->clinic->layYeuCauDatLichById($id);

        // LẤY BÁC SĨ ĐANG LÀM + BÁC SĨ ĐÃ GÁN (dù nghỉ)
        $dsBacSi = $this->clinic->layBacSiDangLamKemBacSiCu($yeuCau['bac_si_id']);

        $dsDichVu = $this->clinic->layTatCaDichVu();

        require_once 'backend/views/header.php';
        require_once 'backend/views/sidebar.php';
        require_once 'backend/views/topbar.php';
        require_once 'backend/views/quanlylichhen/suayeucaulichhen.php';
        require_once 'backend/views/fileJS.php';
    }
    public function suaYeuCauDatLich()
    {
        $id = $_POST['id'];

        // Lấy dữ liệu cũ
        $old = $this->clinic->layYeuCauDatLichById($id);
        $gioBatDau = $_POST['gio_bat_dau'];
        $gioKetThuc = date('H:i', strtotime($gioBatDau . ' +1 hour'));

        // Dữ liệu mới từ form
        $data = [
            'ho_ten' => $_POST['ho_ten'],
            'email' => $_POST['email'],
            'so_dien_thoai' => $_POST['so_dien_thoai'],
            'dich_vu_id' => $_POST['dich_vu_id'],
            'bac_si_id' => $_POST['bac_si_id'],
            'ngay_mong_muon' => $_POST['ngay_mong_muon'],
            'gio_bat_dau' => $gioBatDau,
            'gio_ket_thuc' => $gioKetThuc,
            'mo_ta_trieu_chung' => $_POST['mo_ta_trieu_chung'],
            'trang_thai' => $_POST['trang_thai']
        ];

        // Cập nhật bảng yêu cầu
        $this->clinic->updateLH($id, $data);

        // ✅ Nếu chuyển sang ĐÃ XÁC NHẬN
        if ($old['trang_thai'] !== 'da_xac_nhan' && $data['trang_thai'] === 'da_xac_nhan') {

            // Check trùng lịch bác sĩ
            $trung = $this->clinic->checkTrungLichBacSi(
                $data['bac_si_id'],
                $data['ngay_mong_muon'],
            );

            if ($trung) {
                $_SESSION['error'] = "Bác sĩ đã có lịch vào thời điểm này!";
                header("Location: admin.php?admin=formSuaYeuCauDatLich&id=" . $id);
                exit;
            }

            // Tìm hồ sơ bệnh nhân theo SĐT (nếu đã từng khám)
            $hs = $this->clinic->findHoSoByPhone($data['so_dien_thoai']);
            $hoSoBenhNhanId = $hs ? $hs['id'] : null;

            // Lấy yêu cầu mới nhất
            $yeuCauMoi = $this->clinic->layYeuCauDatLichById($id);

            // 👉 Insert sang bảng lịch hẹn với trạng thái = cho_kham
            $this->clinic->insertLichHenFromYeuCau($yeuCauMoi, $hoSoBenhNhanId);
        }

        $_SESSION['success'] = "Cập nhật yêu cầu thành công!";
        header("Location: admin.php?admin=listYcLichHen");
    }

    public function listLichHen()
    {
        $listLichHen = $this->clinic->layThongTinLichHen();
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/quanlylichhen/quanlylichhen.php";
        require_once "backend/views/fileJS.php";
    }
    public function themlichhenmoi()
    {
        $dsBacSi = $this->clinic->layBacSiDangLamKemBacSiCu(null);
        $dsDichVu = $this->clinic->layTatCaDichVu();
        $listHSBN = $this->clinic->getAllIDHoSoBenhNhan();

        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/quanlylichhen/themlichhen.php";
        require_once "backend/views/fileJS.php";
    }
    public function ganHoSo()
    {
        $hoSoId = $_GET['ho_so_id'] ?? null;
        $lichHenId = $_GET['lich_hen_id'] ?? null;

        if (!$hoSoId || !$lichHenId) {
            header("Location: admin.php?admin=listLichHen");
            exit;
        }

        $this->clinic->ganHoSoVaoLichHen($hoSoId, $lichHenId);
        $lichHen = $this->clinic->getLichHenById($lichHenId);

        // 3. Insert sang bảng lịch khám
        $this->clinic->insertLichKham([
            $lichHenId,
            $hoSoId,
            $lichHen['bac_si_id'],
            $lichHen['dich_vu_id'],
            $lichHen['ngay_hen'],          // ngay_kham
            $lichHen['gio_bat_dau'],       // gio_bat_dau
            $lichHen['gio_ket_thuc'],      // gio_ket_thuc
            null,                          // ghi_chu
            'cho_kham'
        ]);

        header("Location: admin.php?admin=listLichHen");
        exit;
    }

    public function listLichKham()
    {
        if (empty($_SESSION['admin'])) {
            die("Chưa đăng nhập");
        }

        $role = $_SESSION['role'];

        if ($role === 'admin') {

            // Admin xem tất cả
            $listLichKham = $this->clinic->getAllLichKham();
        } elseif ($role === 'bac_si') {

            if (empty($_SESSION['bac_si_id'])) {
                die("Không xác định bác sĩ");
            }

            $bacSiId = $_SESSION['bac_si_id'];
            $listLichKham = $this->clinic->getLichKhamByBacSi($bacSiId);
        } else {
            die("Không có quyền truy cập");
        }

        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/quanlylichhen/quanlylichkham.php";
        require_once "backend/views/fileJS.php";
    }


    public function tiepNhanKham()
    {
        $lichKhamId = $_GET['id'] ?? null;
        if (!$lichKhamId) {
            header("Location: admin.php?admin=listLichKham");
            exit;
        }

        // Cập nhật trạng thái lịch khám
        $this->clinic->capNhatTrangThaiLichKham($lichKhamId, 'dang_kham');

        // Lấy lich_hen_id từ lịch khám để update lịch hẹn
        $lichKham = $this->clinic->getLichKhamById2($lichKhamId);
        if (!empty($lichKham['lich_hen_id'])) {
            $this->clinic->capNhatTrangThaiLichHen($lichKham['lich_hen_id'], 'dang_kham');
        }

        header("Location: admin.php?admin=listLichKham");
        exit;
    }
    public function formKham()
    {
        $lichKhamId = $_GET['id'] ?? null;
        $ho_so_id = $_GET['ho_so_id'] ?? null;
        if (!$lichKhamId) {
            header("Location: admin.php?admin=listLichKham");
            exit;
        }

        $data = $this->clinic->getLichKhamChiTietById($lichKhamId);

        if (!$data) {
            header("Location: admin.php?admin=listLichKham&msg=Không tìm thấy lịch khám");
            exit;
        }

        $listDV = $this->clinic->getAllDichVu();
        $listThuoc = $this->clinic->getAllThuocConHang();
        $medicalHistory = $this->clinic->getMedicalHistoryByHoSoId($lichKhamId);

        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/quanlylichhen/khambenh.php";
        require_once "backend/views/fileJS.php";
    }
    // Tạo lịch khám bệnh
    public function getAvailableTime()
    {
        header('Content-Type: application/json');

        $bac_si_id = $_POST['bac_si_id'] ?? null;
        $ngay = $_POST['ngay'] ?? null;

        if (!$bac_si_id || !$ngay) {
            echo json_encode([]);
            exit;
        }

        // Lấy lịch đã đặt (PHẢI trả về gio_bat_dau + gio_ket_thuc)
        $bookedList = $this->clinic->layGioDaDat($bac_si_id, $ngay);

        $allTime = [
            "07:00:00",
            "08:00:00",
            "09:00:00",
            "10:00:00",
            "11:00:00",
            "13:00:00",
            "14:00:00",
            "15:00:00",
            "16:00:00",
            "17:00:00"
        ];

        foreach ($bookedList as $lich) {

            $start = strtotime($lich['gio_bat_dau']);
            $end = strtotime($lich['gio_ket_thuc']);

            foreach ($allTime as $key => $slot) {

                $slotTime = strtotime($slot);

                if ($slotTime >= $start && $slotTime < $end) {
                    unset($allTime[$key]);
                }
            }
        }

        echo json_encode(array_values($allTime));
        exit;
    }
    // Add lịch khám trực tiếp
    public function themlichhentructiep()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [
                'yeu_cau_dat_lich_id' => null,
                'ho_ten' => $_POST['ho_ten'],
                'so_dien_thoai' => $_POST['so_dien_thoai'],
                'bac_si_id' => $_POST['bac_si_id'],
                'dich_vu_id' => $_POST['dich_vu_id'],
                'ngay_hen' => $_POST['ngay_hen'],
                'gio_bat_dau' => $_POST['exam_time'],
                'loai_dat' => 'truc_tiep',
                'trang_thai' => 'cho_kham',
                'ghi_chu' => ''
            ];

            $result = $this->clinic->insertLichHenTrucTiep($data);

            if ($result) {
                header("Location: admin.php?admin=listLichHen");
                exit;
            } else {
                echo "Thêm lịch hẹn thất bại!";
            }
        }
    }
    // End <!-- Coder Vi Đức Được    FB/duocXdangiu    Zalo/0389232813    Github/coderDuoc --> /

    //========================================Vinh=================================================
    public function qlybacsi()
    {
        $listBacSi = $this->clinic->getAllBacSi();
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/quanlybacsi/danh_sach_bac_si.php";
        require_once "backend/views/fileJS.php";
    }
    public function formThemBacSi()
    {
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/quanlybacsi/add_bac_si.php";
        require_once "backend/views/fileJS.php";
    }
    public function addBacSi()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Lấy dữ liệu từ form
            $ten_bac_si = trim($_POST['ten_bac_si'] ?? '');
            $sdt = trim($_POST['sdt'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $chuyen_mon = trim($_POST['chuyen_mon'] ?? '');
            $gioi_tinh = trim($_POST['gioi_tinh'] ?? '');
            $ca_lam = trim($_POST['ca_lam'] ?? '');
            $trang_thai = trim($_POST['trang_thai'] ?? '');

            // Khởi tạo session lưu lỗi và dữ liệu cũ
            $_SESSION['errors'] = [];
            $_SESSION['old'] = $_POST;

            // ✅ Kiểm tra bắt buộc
            if ($ten_bac_si === '') {
                $_SESSION['errors']['ten_bac_si'] = 'Vui lòng nhập họ tên bác sĩ';
            }
            if ($sdt === '') {
                $_SESSION['errors']['sdt'] = 'Vui lòng nhập số điện thoại';
            }
            // Xử lý upload ảnh
            $photo_url = null;
            if (!empty($_FILES['photo']['name'])) {
                $targetDir = "uploads/doctors/";
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $fileName = time() . "_" . basename($_FILES['photo']['name']);
                $targetFile = $targetDir . $fileName;

                if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
                    $photo_url = $targetFile;
                } else {
                    $_SESSION['errors']['photo'] = 'Upload ảnh thất bại';
                }
            }


            // ✅ CHECK SDT trùng (sử dụng hàm model tương tự lễ tân)
            // Điều chỉnh tên hàm nếu model của bạn khác
            if (!empty($sdt) && $this->clinic->kiemTraTrungSDTBacSi($sdt) > 0) {
                $_SESSION['errors']['sdt'] = 'Số điện thoại đã tồn tại';
            }

            // ✅ CHECK EMAIL (nếu có nhập)
            if ($email !== '') {
                $emailRegex = '/^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.(com|vn|net|org|edu)$/';
                if (!preg_match($emailRegex, $email)) {
                    $_SESSION['errors']['email'] = 'Vui lòng nhập đúng định dạng email (vd: ten@gmail.com)';
                } else {
                    // Nếu muốn kiểm tra trùng email (tuỳ DB)
                    if ($this->clinic->kiemTraTrungEmailBacSi($email) > 0) {
                        $_SESSION['errors']['email'] = 'Email đã tồn tại';
                    }
                }
            }

            // ✅ Kiểm tra các giá trị enum/option (tùy chọn)
            $validGioiTinh = ['nam', 'nu', 'khac', ''];
            $validCaLam = ['sang', 'chieu', 'full', ''];
            $validTrangThai = ['dang_lam', 'nghi', ''];

            if (!in_array($gioi_tinh, $validGioiTinh, true)) {
                $_SESSION['errors']['gioi_tinh'] = 'Giới tính không hợp lệ';
            }
            if (!in_array($ca_lam, $validCaLam, true)) {
                $_SESSION['errors']['ca_lam'] = 'Ca làm không hợp lệ';
            }
            if (!in_array($trang_thai, $validTrangThai, true)) {
                $_SESSION['errors']['trang_thai'] = 'Trạng thái không hợp lệ';
            }


            // ❌ Nếu có lỗi → quay về form thêm
            if (!empty($_SESSION['errors'])) {
                header('Location: admin.php?admin=formThemBacSi');
                exit;
            }

            // ✅ THÊM MỚI bác sĩ (gọi model)
            // Điều chỉnh tên hàm và thứ tự tham số theo model của bạn
            $this->clinic->themBacSi(
                $ten_bac_si,
                $sdt,
                $email,
                $chuyen_mon,
                $gioi_tinh,
                $ca_lam,
                $trang_thai,
                $photo_url,
            );

            // Xóa dữ liệu cũ trong session sau khi thêm thành công
            unset($_SESSION['old']);

            // Chuyển hướng về danh sách bác sĩ
            header('Location: admin.php?admin=qlybacsi');
            exit;
        }
    }
    public function formSuaBacSi()
    {
        $id = $_GET['idAdmin'] ?? null;
        if (!$id) {
            header("Location: admin.php?admin=qlybacsi");
            exit;
        }
        $bacSi = $this->clinic->getBacSiByID($id);
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/quanlybacsi/edit_bac_si.php";
        require_once "backend/views/fileJS.php";
    }
    public function suaBacSi()
    {
        // Bảo đảm là POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: admin.php?admin=qlybacsi');
            exit;
        }

        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['error'] = 'ID bác sĩ không hợp lệ.';
            header('Location: admin.php?admin=qlybacsi');
            exit;
        }

        // Lấy dữ liệu cũ
        $old = $this->clinic->getBacSiByID($id);
        if (!$old) {
            $_SESSION['error'] = 'Không tìm thấy bác sĩ.';
            header('Location: admin.php?admin=qlybacsi');
            exit;
        }

        // Dữ liệu mới từ form (trim để an toàn)
        $data = [
            'ten_bac_si' => trim($_POST['ten_bac_si'] ?? ''),
            'sdt' => trim($_POST['sdt'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'chuyen_mon' => trim($_POST['chuyen_mon'] ?? ''),
            'gioi_tinh' => trim($_POST['gioi_tinh'] ?? ''),
            'ca_lam' => trim($_POST['ca_lam'] ?? ''),
            'trang_thai' => trim($_POST['trang_thai'] ?? ''),
            'photo_url' => $old['photo_url'] ?? null
        ];
        // Nếu có upload ảnh mới
        if (!empty($_FILES['photo']['name'])) {
            $targetDir = "uploads/doctors/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $fileName = time() . "_" . basename($_FILES['photo']['name']);
            $targetFile = $targetDir . $fileName;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
                $data['photo_url'] = $targetFile;
            } else {
                $_SESSION['errors']['photo'] = 'Upload ảnh thất bại';
            }
        }

        // Khởi tạo session lưu lỗi và old
        $_SESSION['errors'] = [];
        $_SESSION['old'] = $_POST;

        // Validate cơ bản
        if ($data['ten_bac_si'] === '') {
            $_SESSION['errors']['ten_bac_si'] = 'Vui lòng nhập họ tên bác sĩ';
        }
        if ($data['sdt'] === '') {
            $_SESSION['errors']['sdt'] = 'Vui lòng nhập số điện thoại';
        }

        // Kiểm tra trùng SĐT (loại trừ chính bản ghi đang sửa)
        if (!empty($data['sdt'])) {
            $exists = $this->clinic->kiemTraTrungSDTBacSi($data['sdt'], $id);
            if ($exists) {
                $_SESSION['errors']['sdt'] = 'Số điện thoại đã tồn tại';
            }
        }

        // Kiểm tra email (nếu có nhập)
        if ($data['email'] !== '') {
            $emailRegex = '/^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.(com|vn|net|org|edu)$/';
            if (!preg_match($emailRegex, $data['email'])) {
                $_SESSION['errors']['email'] = 'Vui lòng nhập đúng định dạng email (vd: ten@gmail.com)';
            } else {
                $existsEmail = $this->clinic->kiemTraTrungEmailBacSi($data['email'], $id);
                if ($existsEmail) {
                    $_SESSION['errors']['email'] = 'Email đã tồn tại';
                }
            }
        }

        // Nếu có lỗi → quay về form sửa
        if (!empty($_SESSION['errors'])) {
            header('Location: admin.php?admin=formSuaBacSi&idAdmin=' . $id);
            exit;
        }

        // Cập nhật vào DB (model updateBacSi cần được implement)
        $ok = $this->clinic->updateBacSi($id, $data);

        if ($ok) {
            unset($_SESSION['old']);
            $_SESSION['success'] = 'Cập nhật bác sĩ thành công!';
        } else {
            $_SESSION['error'] = 'Cập nhật thất bại. Vui lòng thử lại.';
        }

        header('Location: admin.php?admin=qlybacsi');
        exit;
    }
    public function xoaBacSi()
    {
        // Lấy id từ query string
        $id = isset($_GET['idAdmin']) ? (int) $_GET['idAdmin'] : 0;

        // Kiểm tra id hợp lệ
        if ($id <= 0) {
            $_SESSION['error'] = 'ID không hợp lệ.';
            header('Location: admin.php?admin=qlybacsi');
            exit;
        }

        // Lấy bản ghi hiện tại (nếu cần kiểm tra trước khi xóa)
        $bacSi = $this->clinic->getBacSiByID($id);
        if (!$bacSi) {
            $_SESSION['error'] = 'Không tìm thấy bác sĩ.';
            header('Location: admin.php?admin=qlybacsi');
            exit;
        }
        if ($bacSi && !empty($bacSi['photo_url']) && file_exists($bacSi['photo_url'])) {
            unlink($bacSi['photo_url']); // xóa file ảnh
        }

        // Thực hiện xóa (gọi model)
        try {
            // Gọi hàm model để xóa; đổi tên hàm nếu model của bạn khác
            $ok = $this->clinic->deleteBacSi($id);

            if ($ok) {
                $_SESSION['success'] = 'Xóa bác sĩ thành công.';
            } else {
                $_SESSION['error'] = 'Xóa thất bại. Vui lòng thử lại.';
            }
        } catch (Exception $e) {
            // Log lỗi để debug, không hiển thị chi tiết cho user
            error_log('xoaBacSi error: ' . $e->getMessage());
            $_SESSION['error'] = 'Đã xảy ra lỗi khi xóa bác sĩ.';
        }

        header('Location: admin.php?admin=qlybacsi');
        exit;
    }
    public function toggleBacSi()
    {
        // Lấy tham số từ URL
        $id = isset($_GET['idAdmin']) ? (int) $_GET['idAdmin'] : 0;
        $action = isset($_GET['action']) ? trim($_GET['action']) : '';

        if ($id <= 0 || !in_array($action, ['khoa', 'mo'], true)) {
            $_SESSION['error'] = 'Tham số không hợp lệ.';
            header('Location: admin.php?admin=qlybacsi');
            exit;
        }

        // Lấy bác sĩ
        $bacSi = $this->clinic->layBacSiById($id);
        if (!$bacSi) {
            $_SESSION['error'] = 'Không tìm thấy bác sĩ.';
            header('Location: admin.php?admin=qlybacsi');
            exit;
        }

        // Quyết định trạng thái mới
        if ($action === 'khoa') {
            // Trước khi khóa, kiểm tra có lịch hẹn tương lai không
            $hasFuture = $this->clinic->kiemTraLichHenTuongLaiTheoBacSi($id);
            if ($hasFuture) {
                $_SESSION['error'] = 'Không thể khóa: bác sĩ còn lịch hẹn tương lai.';
                header('Location: admin.php?admin=qlybacsi');
                exit;
            }
            $newStatus = 'nghi'; // hoặc 'khong_hoat_dong' tuỳ chuẩn của bạn
        } else { // action === 'mo'
            $newStatus = 'dang_lam';
        }

        // Cập nhật trạng thái (gọi model)
        $ok = $this->clinic->updateTrangThaiBacSi($id, $newStatus);

        if ($ok) {
            $_SESSION['success'] = ($action === 'khoa') ? 'Khóa bác sĩ thành công.' : 'Mở lại bác sĩ thành công.';
        } else {
            $_SESSION['error'] = 'Thao tác thất bại. Vui lòng thử lại.';
        }

        header('Location: admin.php?admin=qlybacsi');
        exit;
    }
    //========================================Hết Vinh=============================================


    // Mạnh đẹp zai - lưu kết quả khám 

    public function luuKetQuaKham()
    {
        $lich_kham_id = $_POST['lich_kham_id'];
        $chan_doan = $_POST['ket_luan'];
        $danh_sach_dv = isset($_POST['dich_vu']) ? $_POST['dich_vu'] : [];
        $don_thuoc = isset($_POST['thuoc']) ? $_POST['thuoc'] : [];

        // 1. Kiểm tra số lượng thuốc trong kho trước khi lưu
        if (!empty($don_thuoc)) {
            foreach ($don_thuoc as $item) {
                $thuoc_id = $item['thuoc_id'];
                $so_luong_ke = (int) $item['so_luong'];

                // Gọi hàm từ model để lấy thông tin thuốc hiện tại trong DB
                // Giả sử ông có hàm getThuocById hoặc tương tự
                $infoThuoc = $this->clinic->getThuocById($thuoc_id);
                $ten_thuoc = $infoThuoc['ten_thuoc'];
                $so_luong_ton = (int) $infoThuoc['so_luong']; // Giả sử tên cột là so_luong_ton

                // Check: Nếu số kê đơn > số tồn kho thì báo lỗi
                if ($so_luong_ke > $so_luong_ton) {
                    echo "<script>
                    alert('Lỗi: Thuốc \"$ten_thuoc\" không đủ số lượng! (Trong kho: $so_luong_ton, kê đơn: $so_luong_ke)');
                    window.history.back();
                </script>";
                    exit;
                }
            }
        }

        if (empty($chan_doan) || empty($danh_sach_dv) || empty($don_thuoc)) {
            header("Location: admin.php?admin=formKham&id=" . $lich_kham_id . "&status=error");
            exit;
        }

        // 2. Nếu check hết mảng thuốc mà OK thì mới tiến hành lưu
        // Trong hàm saveResultEx này, ông nhớ xử lý thêm việc trừ số lượng tồn kho trong DB nhé
        $this->clinic->saveResultEx($lich_kham_id, $danh_sach_dv, $chan_doan, $don_thuoc);

        header("Location: admin.php?admin=listLichKham&status=success");
    }
    // Mạnh đẹp zai - lịch sử khám
    public function listLichSuKham()
    {
        $listLichSuKham = $this->clinic->getLichSuKham();
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/quanlylichhen/lichsukham.php";
        require_once "backend/views/fileJS.php";
    }

    public function chiTietLichSuKham()
    {
        $lichKhamId = $_GET['id'] ?? null;
        if (!$lichKhamId) {
            header("Location: admin.php?admin=listLichSuKham");
            exit;
        }

        $data = $this->clinic->getChiTietLichSuKhamById($lichKhamId);

        if (!$data) {
            header("Location: admin.php?admin=listLichSuKham&msg=Không tìm thấy lịch sử khám");
            exit;
        }

        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/quanlylichhen/chitietlichsukham.php";
        require_once "backend/views/fileJS.php";
    }
    // hết Mạnh đẹp zai
    // Mạnh đẹp zai -  tìm kiếm ajax lịch sử khám
    public function searchLichSuKham()
    {
        if (isset($_POST['keyword'])) {
            $keyword = $_POST['keyword'];
            $data = $this->clinic->searchLichSuKham($keyword);

            foreach ($data as $lk) {
                echo "<tr>
                    <td>{$lk['id']}</td>
                    <td>{$lk['ho_so_benh_nhan_id']}</td>
                    <td>{$lk['chan_doan']}</td>
                    <td>{$lk['huong_dieu_tri']}</td>
                    <td>" . (!empty($lk['ghi_chu']) ? $lk['ghi_chu'] : 'Không có ghi chú') . "</td>
                    <td>" . date('d/m/Y H:i', strtotime($lk['ngay_kham'])) . "</td>
                    <td class='text-center'>
                        <a href='admin.php?admin=chiTietLichSuKham&id={$lk['id']}' 
                           class='btn btn-sm btn-info'>Chi tiết</a>
                    </td>
                  </tr>";
            }

            if (empty($data)) {
                echo "<tr><td colspan='7' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
            }
        }
    }
}
