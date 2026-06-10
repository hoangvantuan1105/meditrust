<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AuthController
{
    private $clinic;
    public function __construct()
    {
        require_once __DIR__ . '/../models/db.php';
        $this->clinic = new modelClinic();
        $this->clinic->ketNoiDB();
    }

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

            if ($admin['thoi_gian_tam_khoa'] && strtotime($admin['thoi_gian_tam_khoa']) <= time()) {
                $this->clinic->resetFail($admin['id']);
                $admin['sai_mk'] = 0;
                $admin['thoi_gian_tam_khoa'] = null;
            }

            if ($admin['thoi_gian_tam_khoa'] && strtotime($admin['thoi_gian_tam_khoa']) > time()) {
                $_SESSION['error'] = "Tài khoản bị khóa đến: " . $admin['thoi_gian_tam_khoa'];
                header("Location: admin.php?admin=loginSystem");
                exit;
            }

            $isPasswordOk = false;

            if (password_verify($pass, $admin['password'])) {
                $isPasswordOk = true;
            } elseif ($pass === $admin['password']) {
                $isPasswordOk = true;
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

    public function accessDenied()
    {
        require_once 'backend/views/accessDenied.php';
    }

    public function adminLogs()
    {
        $logs = $this->clinic->getAdminLogs();

        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/activityLog.php";
        require_once "backend/views/fileJS.php";
    }
}
