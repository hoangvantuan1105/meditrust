<?php

class DashboardController
{
    private $clinic;
    public function __construct()
    {
        require_once __DIR__ . '/../models/db.php';
        $this->clinic = new modelClinic();
        $this->clinic->ketNoiDB();
    }

    public function index()
    {
        $isAdmin = $_SESSION['role'] === 'admin';
        $bacSiId = $isAdmin ? null : ($_SESSION['bac_si_id'] ?? null);

        $tongBenhNhan = $this->clinic->countAllBenhNhan();

        $tongLichKham = $isAdmin
            ? $this->clinic->countAllLichKham()
            : $this->clinic->countLichKhamByBacSi($bacSiId);

        $tongBacSi = $this->clinic->countAllBacSi();

        $tongDoanhThu  = $this->clinic->tongDoanhThu();
        $doanhThuHomNay  = $this->clinic->doanhThuHomNay();
        $doanhThuThangNay = $this->clinic->doanhThuThang();

        $lichKhamHomNay = $this->clinic->lichKhamHomNay($bacSiId);

        $revenueData = $this->clinic->doanhThu12Thang();

        $topServices   = $this->clinic->topDichVu();
        $serviceLabels = [];
        $serviceData   = [];
        foreach ($topServices as $service) {
            $serviceLabels[] = $service['ten_dich_vu'];
            $serviceData[]   = $service['total'];
        }

        $lichHomNayChiTiet  = $this->clinic->lichKhamHomNayChiTiet($bacSiId);
        $trangThaiHomNay    = $this->clinic->thongKeTrangThaiHomNay($bacSiId);

        $vatTuCanhBao = $this->clinic->getMaterialAlerts();

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

        $user_id      = $_SESSION['admin']['id'];
        $soTinChuaDoc = $this->clinic->demTinChuaDoc($user_id);
        $tinGanNhat   = $this->clinic->layTinGanNhat($user_id);

        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/dashBoard.php";
        require_once "backend/views/fileJS.php";
    }

    public function dashBoard()
    {
        $tongBenhNhan = $this->clinic->countAllBenhNhan();

        if ($_SESSION['role'] === 'admin') {
            $tongLichKham = $this->clinic->countAllLichKham();
        } else {
            $tongLichKham = $this->clinic->countLichKhamByBacSi($_SESSION['bac_si_id']);
        }

        $tongBacSi = $this->clinic->countAllBacSi();

        $revenueData = $this->clinic->doanhThu12Thang();
        $topServices = $this->clinic->topDichVu();

        $serviceLabels = [];
        $serviceData = [];

        foreach ($topServices as $service) {
            $serviceLabels[] = $service['ten_dich_vu'];
            $serviceData[] = $service['total'];
        }

        $tongDoanhThu = $this->clinic->tongDoanhThu();

        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once 'backend/views/dashBoard.php';
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
            $soLuotKham = $this->clinic->countLichKhamThang($month, $year, null);
            $benhNhan = $this->clinic->countAllPatient(null);
        } else {
            $soLuotKham = $this->clinic->countLichKhamThang($month, $year, $bacSiId);
            $benhNhan = $this->clinic->countAllPatient($bacSiId);
        }

        require "backend/views/header.php";
        require "backend/views/sidebar.php";
        require "backend/views/topbar.php";
        require "backend/views/profile.php";
        require "backend/views/fileJS.php";
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

        $allowed = ['jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            die("Chỉ cho phép JPG, JPEG, PNG");
        }

        $newName = "avatar_" . $admin_id . "_" . time() . "." . $ext;

        $uploadDir = realpath(__DIR__ . "/../uploads/avatar/");

        if (!$uploadDir) {
            die("Không tìm thấy thư mục uploads/avatar");
        }

        $uploadPath = $uploadDir . DIRECTORY_SEPARATOR . $newName;

        $oldAvatar = $this->clinic->getAdminById($admin_id)['avatar'];

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {

            if (!empty($oldAvatar)) {
                $oldPath = $uploadDir . DIRECTORY_SEPARATOR . $oldAvatar;
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $this->clinic->updateAdminAvatar($admin_id, $newName);

            $_SESSION['admin']['avatar'] = $newName;

            header("Location: admin.php?admin=profile");
            exit;
        }

        die("Upload thất bại");
    }
}
