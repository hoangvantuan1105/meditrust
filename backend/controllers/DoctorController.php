<?php

class DoctorController
{
    private $clinic;
    public function __construct()
    {
        require_once __DIR__ . '/../models/db.php';
        $this->clinic = new modelClinic();
        $this->clinic->ketNoiDB();
    }

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

            $ten_bac_si = trim($_POST['ten_bac_si'] ?? '');
            $sdt = trim($_POST['sdt'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $chuyen_mon = trim($_POST['chuyen_mon'] ?? '');
            $gioi_tinh = trim($_POST['gioi_tinh'] ?? '');
            $ca_lam = trim($_POST['ca_lam'] ?? '');
            $trang_thai = trim($_POST['trang_thai'] ?? '');

            $_SESSION['errors'] = [];
            $_SESSION['old'] = $_POST;

            if ($ten_bac_si === '') {
                $_SESSION['errors']['ten_bac_si'] = 'Vui lòng nhập họ tên bác sĩ';
            }
            if ($sdt === '') {
                $_SESSION['errors']['sdt'] = 'Vui lòng nhập số điện thoại';
            }

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

            if (!empty($sdt) && $this->clinic->kiemTraTrungSDTBacSi($sdt) > 0) {
                $_SESSION['errors']['sdt'] = 'Số điện thoại đã tồn tại';
            }

            if ($email !== '') {
                $emailRegex = '/^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.(com|vn|net|org|edu)$/';
                if (!preg_match($emailRegex, $email)) {
                    $_SESSION['errors']['email'] = 'Vui lòng nhập đúng định dạng email (vd: ten@gmail.com)';
                } else {
                    if ($this->clinic->kiemTraTrungEmailBacSi($email) > 0) {
                        $_SESSION['errors']['email'] = 'Email đã tồn tại';
                    }
                }
            }

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

            if (!empty($_SESSION['errors'])) {
                header('Location: admin.php?admin=formThemBacSi');
                exit;
            }

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

            unset($_SESSION['old']);

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

        $old = $this->clinic->getBacSiByID($id);
        if (!$old) {
            $_SESSION['error'] = 'Không tìm thấy bác sĩ.';
            header('Location: admin.php?admin=qlybacsi');
            exit;
        }

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

        $_SESSION['errors'] = [];
        $_SESSION['old'] = $_POST;

        if ($data['ten_bac_si'] === '') {
            $_SESSION['errors']['ten_bac_si'] = 'Vui lòng nhập họ tên bác sĩ';
        }
        if ($data['sdt'] === '') {
            $_SESSION['errors']['sdt'] = 'Vui lòng nhập số điện thoại';
        }

        if (!empty($data['sdt'])) {
            $exists = $this->clinic->kiemTraTrungSDTBacSi($data['sdt'], $id);
            if ($exists) {
                $_SESSION['errors']['sdt'] = 'Số điện thoại đã tồn tại';
            }
        }

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

        if (!empty($_SESSION['errors'])) {
            header('Location: admin.php?admin=formSuaBacSi&idAdmin=' . $id);
            exit;
        }

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
        $id = isset($_GET['idAdmin']) ? (int) $_GET['idAdmin'] : 0;

        if ($id <= 0) {
            $_SESSION['error'] = 'ID không hợp lệ.';
            header('Location: admin.php?admin=qlybacsi');
            exit;
        }

        $bacSi = $this->clinic->getBacSiByID($id);
        if (!$bacSi) {
            $_SESSION['error'] = 'Không tìm thấy bác sĩ.';
            header('Location: admin.php?admin=qlybacsi');
            exit;
        }
        if ($bacSi && !empty($bacSi['photo_url']) && file_exists($bacSi['photo_url'])) {
            unlink($bacSi['photo_url']);
        }

        try {
            $ok = $this->clinic->deleteBacSi($id);

            if ($ok) {
                $_SESSION['success'] = 'Xóa bác sĩ thành công.';
            } else {
                $_SESSION['error'] = 'Xóa thất bại. Vui lòng thử lại.';
            }
        } catch (Exception $e) {
            error_log('xoaBacSi error: ' . $e->getMessage());
            $_SESSION['error'] = 'Đã xảy ra lỗi khi xóa bác sĩ.';
        }

        header('Location: admin.php?admin=qlybacsi');
        exit;
    }

    public function toggleBacSi()
    {
        $id = isset($_GET['idAdmin']) ? (int) $_GET['idAdmin'] : 0;
        $action = isset($_GET['action']) ? trim($_GET['action']) : '';

        if ($id <= 0 || !in_array($action, ['khoa', 'mo'], true)) {
            $_SESSION['error'] = 'Tham số không hợp lệ.';
            header('Location: admin.php?admin=qlybacsi');
            exit;
        }

        $bacSi = $this->clinic->layBacSiById($id);
        if (!$bacSi) {
            $_SESSION['error'] = 'Không tìm thấy bác sĩ.';
            header('Location: admin.php?admin=qlybacsi');
            exit;
        }

        if ($action === 'khoa') {
            $hasFuture = $this->clinic->kiemTraLichHenTuongLaiTheoBacSi($id);
            if ($hasFuture) {
                $_SESSION['error'] = 'Không thể khóa: bác sĩ còn lịch hẹn tương lai.';
                header('Location: admin.php?admin=qlybacsi');
                exit;
            }
            $newStatus = 'nghi';
        } else {
            $newStatus = 'dang_lam';
        }

        $ok = $this->clinic->updateTrangThaiBacSi($id, $newStatus);

        if ($ok) {
            $_SESSION['success'] = ($action === 'khoa') ? 'Khóa bác sĩ thành công.' : 'Mở lại bác sĩ thành công.';
        } else {
            $_SESSION['error'] = 'Thao tác thất bại. Vui lòng thử lại.';
        }

        header('Location: admin.php?admin=qlybacsi');
        exit;
    }
}
