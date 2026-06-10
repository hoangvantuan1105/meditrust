<?php

class FrontendAuthController
{
    private $clinic;

    public function __construct()
    {
        require_once __DIR__ . '/../model/frontend-db.php';
        $this->clinic = new frontendDB();
        $this->clinic->ketNoiDB();
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $tai_khoan = trim($_POST['account'] ?? '');
            $mat_khau = $_POST['mat_khau'] ?? '';

            if (empty($tai_khoan) || empty($mat_khau)) {
                $_SESSION['auth_error'] = "Vui lòng nhập đầy đủ thông tin.";
                header("Location: index.php?page=login");
                exit;
            }

            $account = $this->clinic->findByPhoneOrEmail($tai_khoan);

            if (!$account) {
                $_SESSION['auth_error'] = "Tài khoản không tồn tại.";
                header("Location: index.php?page=login");
                exit;
            }

            if ((int) $account['trang_thai'] === 0) {
                $_SESSION['auth_error'] = "Tài khoản chưa kích hoạt.";
                header("Location: index.php?page=first_login");
                exit;
            }

            if (!password_verify($mat_khau, $account['mat_khau'])) {
                $_SESSION['auth_error'] = "Sai mật khẩu.";
                header("Location: index.php?page=login");
                exit;
            }

            session_regenerate_id(true);

            $_SESSION['patient'] = [
                'id' => $account['id'],
                'email' => $account['email'],
                'so_dien_thoai' => $account['so_dien_thoai']
            ];

            header("Location: index.php?page=profile");
            exit;
        }

        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function register()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        $mode = $_GET['mode'] ?? 'view';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ho_ten = trim($_POST['ho_ten'] ?? '');
            $so_dien_thoai = trim($_POST['so_dien_thoai'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $gioi_tinh = trim($_POST['gioi_tinh'] ?? '');
            $ngay_sinh = trim($_POST['ngay_sinh'] ?? '');
            $dia_chi = trim($_POST['dia_chi'] ?? '');
            $mat_khau = $_POST['mat_khau'] ?? '';
            $mat_khau_nhap_lai = $_POST['mat_khau_nhap_lai'] ?? '';

            if ($ho_ten === '' || $so_dien_thoai === '' || $mat_khau === '' || $mat_khau_nhap_lai === '') {
                $_SESSION['auth_error'] = "Vui lòng nhập đầy đủ thông tin bắt buộc.";
                header("Location: index.php?page=register");
                exit;
            }

            if (!preg_match('/^0\\d{9}$/', $so_dien_thoai)) {
                $_SESSION['auth_error'] = "Số điện thoại phải đúng 10 số.";
                header("Location: index.php?page=register");
                exit;
            }

            if (strlen($mat_khau) < 6) {
                $_SESSION['auth_error'] = "Mật khẩu phải từ 6 ký tự.";
                header("Location: index.php?page=register");
                exit;
            }

            if ($mat_khau !== $mat_khau_nhap_lai) {
                $_SESSION['auth_error'] = "Xác nhận mật khẩu không khớp.";
                header("Location: index.php?page=register");
                exit;
            }

            if ($this->clinic->checkPhoneExistsInAccount($so_dien_thoai)) {
                $_SESSION['auth_error'] = "Số điện thoại đã có tài khoản.";
                header("Location: index.php?page=register");
                exit;
            }

            $hoSo = $this->clinic->findHoSoByPhone($so_dien_thoai);

            if ($hoSo && $this->clinic->checkHoSoHasAccount($hoSo['id'])) {
                $_SESSION['auth_error'] = "Hồ sơ này đã có tài khoản.";
                header("Location: index.php?page=register");
                exit;
            }

            if (!$hoSo) {
                $newId = $this->clinic->generateHoSoBenhNhanId();
                $insertOk = $this->clinic->insertHoSoBenhNhan([
                    $newId,
                    $ho_ten,
                    $so_dien_thoai,
                    $email,
                    $gioi_tinh,
                    $ngay_sinh,
                    $dia_chi,
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    1
                ]);

                if (!$insertOk) {
                    $_SESSION['auth_error'] = "Không thể tạo hồ sơ bệnh nhân.";
                    header("Location: index.php?page=register");
                    exit;
                }

                $hoSo = ['id' => $newId];
            }

            $hash = password_hash($mat_khau, PASSWORD_BCRYPT);
            $this->clinic->insertPatientAccount([
                $hoSo['id'],
                $so_dien_thoai,
                $hash,
                1
            ]);

            $_SESSION['auth_success'] = "Đăng ký thành công. Vui lòng đăng nhập.";
            header("Location: index.php?page=login");
            exit;
        }

        require_once __DIR__ . '/../views/auth/register.php';
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        unset($_SESSION['patient']);
        header("Location: index.php");
        exit;
    }

    public function profile()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        if (empty($_SESSION['patient'])) {
            header("Location: index.php?page=login");
            exit;
        }

        $userId = $_SESSION['patient']['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $ho_ten = trim($_POST['ho_ten'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $gioi_tinh = trim($_POST['gioi_tinh'] ?? '');
            $ngay_sinh = trim($_POST['ngay_sinh'] ?? '');
            $dia_chi = trim($_POST['dia_chi'] ?? '');
            $tien_su_benh = trim($_POST['tien_su_benh'] ?? '');
            $cmnd_cccd = trim($_POST['cmnd_cccd'] ?? '');
            $bao_hiem_y_te = trim($_POST['bao_hiem_y_te'] ?? '');
            $nguoi_lien_he_khan_cap = trim($_POST['nguoi_lien_he_khan_cap'] ?? '');
            $quan_he = trim($_POST['quan_he'] ?? '');
            $sdt_nguoi_lien_he = trim($_POST['sdt_nguoi_lien_he'] ?? '');

            if ($ho_ten === '') {
                $_SESSION['profile_error'] = "Họ và tên không được để trống.";
                header("Location: index.php?page=profile&mode=edit");
                exit;
            }

            if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['profile_error'] = "Email không hợp lệ.";
                header("Location: index.php?page=profile&mode=edit");
                exit;
            }

            $ok = $this->clinic->updateHoSoBenhNhanByUserId($userId, [
                $ho_ten,
                $gioi_tinh,
                $ngay_sinh,
                $dia_chi,
                $tien_su_benh,
                $cmnd_cccd,
                $bao_hiem_y_te,
                $nguoi_lien_he_khan_cap,
                $quan_he,
                $sdt_nguoi_lien_he,
                $email
            ]);

            if ($ok) {
                $_SESSION['patient']['email'] = $email;
                $_SESSION['profile_success'] = "Cập nhật hồ sơ thành công.";
            } else {
                $_SESSION['profile_error'] = "Không thể cập nhật hồ sơ.";
                header("Location: index.php?page=profile&mode=edit");
                exit;
            }

            header("Location: index.php?page=profile");
            exit;
        }

        $userId = $_SESSION['patient']['id'];
        $hoSo = $this->clinic->getHoSoFullByUserId($userId);
        $lichSuKham = $this->clinic->getLichSuKhamByHoSoId($hoSo['id']);

        if ($hoSo) {
            $_SESSION['patient']['ho_ten'] = $hoSo['ho_ten'] ?? '';
        }
        require_once __DIR__ . '/../views/auth/profile.php';
    }

    public function changePassword()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['patient'])) {
            header("Location: index.php?page=login");
            exit;
        }

        $accountId = $_SESSION['patient']['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $mat_khau_cu = $_POST['mat_khau_cu'] ?? '';
            $mat_khau_moi = $_POST['mat_khau_moi'] ?? '';
            $xac_nhan = $_POST['xac_nhan_mat_khau'] ?? '';

            if ($mat_khau_moi === '' || $xac_nhan === '') {
                $_SESSION['auth_error'] = "Vui lòng nhập đầy đủ thông tin.";
                header("Location: index.php?page=change_password");
                exit;
            }

            if ($mat_khau_moi !== $xac_nhan) {
                $_SESSION['auth_error'] = "Mật khẩu xác nhận không khớp.";
                header("Location: index.php?page=change_password");
                exit;
            }

            $account = $this->clinic->findPatientAccountById($accountId);

            if (!$account) {
                $_SESSION['auth_error'] = "Không tìm thấy tài khoản.";
                header("Location: index.php?page=change_password");
                exit;
            }

            if (!password_verify($mat_khau_cu, $account['mat_khau'])) {
                $_SESSION['auth_error'] = "Mật khẩu cũ không đúng.";
                header("Location: index.php?page=change_password");
                exit;
            }

            $hash = password_hash($mat_khau_moi, PASSWORD_BCRYPT);

            $this->clinic->updatePasswordByEmail($accountId, $hash);

            $_SESSION['auth_success'] = "Đổi mật khẩu thành công.";
            header("Location: index.php?page=profile");
            exit;
        }

        require_once __DIR__ . '/../views/auth/change_password.php';
    }

    public function first_login()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['step'] = $_SESSION['step'] ?? 1;

        require_once __DIR__ . '/../views/auth/change_password.php';
    }

    public function sendLoginOTP()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $email = $_POST['email'] ?? '';

        $user = $this->clinic->findByEmail($email);

        if (!$user) {
            $_SESSION['auth_error'] = "Email không tồn tại";
            header("Location: index.php?page=first_login");
            exit;
        }

        if ($user['trang_thai'] == 1) {
            $_SESSION['auth_error'] = "Tài khoản này đã kích hoạt rồi.";
            header("Location: index.php?page=login");
            exit;
        }

        $otp = rand(100000, 999999);

        $this->clinic->saveOTP($email, $otp);

        require_once 'frontend/helpers/MailHelper.php';

        if (MailHelper::sendOTP($email, $otp)) {

            $_SESSION['otp_email'] = $email;
            $_SESSION['step'] = 2;

            header("Location: index.php?page=first_login");
            exit;
        } else {

            $_SESSION['auth_error'] = "Không gửi được email OTP";
            header("Location: index.php?page=first_login");
            exit;
        }
    }

    public function verifyLoginOTP()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $otp_input = $_POST['otp'] ?? '';
        $email = $_SESSION['otp_email'] ?? '';

        if (!$email) {
            $_SESSION['auth_error'] = "Phiên làm việc không hợp lệ.";
            header("Location: index.php?page=first_login");
            exit;
        }

        $result = $this->clinic->verifyOTP($email, $otp_input);

        if ($result === "expired") {
            $_SESSION['auth_error'] = "OTP đã hết hạn.";
            $_SESSION['step'] = 1;
            header("Location: index.php?page=first_login");
            exit;
        }

        if ($result === false) {
            $_SESSION['auth_error'] = "OTP không đúng.";
            $_SESSION['step'] = 2;
            header("Location: index.php?page=first_login");
            exit;
        }

        $this->clinic->clearOTP($email);

        $_SESSION['step'] = 3;

        header("Location: index.php?page=first_login");
        exit;
    }

    public function update_first_password()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $email = $_SESSION['otp_email'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if ($new_password === '' || $confirm_password === '') {
            $_SESSION['auth_error'] = "Vui lòng nhập đầy đủ thông tin.";
            $_SESSION['step'] = 3;
            header("Location: index.php?page=first_login");
            exit;
        }

        if ($new_password !== $confirm_password) {
            $_SESSION['auth_error'] = "Mật khẩu xác nhận không khớp.";
            $_SESSION['step'] = 3;
            header("Location: index.php?page=first_login");
            exit;
        }

        $hash = password_hash($new_password, PASSWORD_BCRYPT);

        $this->clinic->updatePasswordByEmail($email, $hash);

        $this->clinic->activateAccount($email);

        $this->clinic->clearOTP($email);

        unset($_SESSION['otp_email']);
        unset($_SESSION['step']);

        $_SESSION['auth_success'] = "Kích hoạt tài khoản thành công. Vui lòng đăng nhập.";

        header("Location: index.php?page=login");
        exit;
    }
}
