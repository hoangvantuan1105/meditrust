<?php

class UserManagementController
{
    private $clinic;
    public function __construct()
    {
        require_once __DIR__ . '/../models/db.php';
        $this->clinic = new modelClinic();
        $this->clinic->ketNoiDB();
    }

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

            if ($this->clinic->checkTrungSDT($sdt) > 0) {
                $_SESSION['errors']['sdt'] = 'Số điện thoại đã tồn tại';
            }

            $emailRegex = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9-]+\.(com|vn|net|org|edu)$/';
            if (!preg_match($emailRegex, $email)) {
                $_SESSION['errors']['email'] = 'Vui lòng nhập đúng định dạng email (vd: ten@gmail.com)';
                $_SESSION['old'] = $_POST;

                header('Location: admin.php?admin=formThemTaiKhoanLeTan');
                exit;
            }

            if (!empty($_SESSION['errors'])) {
                header('Location: admin.php?admin=formThemTaiKhoanLeTan');
                exit;
            }

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

    public function suaTaiKhoanLeTan()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['id'];

            $ten_le_tan = trim($_POST['ten_le_tan']);
            $sdt = trim($_POST['sdt']);
            $email = trim($_POST['email']);
            $gioi_tinh = $_POST['gioi_tinh'];
            $ca_lam = $_POST['ca_lam'];
            $trang_thai = $_POST['trang_thai'];

            $errors = [];

            $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com|vn|net|org|edu)$/';

            if (!preg_match($pattern, $email)) {
                $errors['email'] = 'Vui lòng nhập đúng định dạng email (vd: ten@gmail.com)';
            }

            if ($this->clinic->kiemTraTrungSDT($sdt, $id)) {
                $errors['sdt'] = 'Số điện thoại đã tồn tại';
            }

            if ($this->clinic->kiemTraTrungEmail($email, $id)) {
                $errors['email'] = 'Email đã tồn tại';
            }

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $_SESSION['old'] = $_POST;
                header("Location: admin.php?admin=formSuaTaiKhoanLeTan&id=$id");
                exit;
            }

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
}
