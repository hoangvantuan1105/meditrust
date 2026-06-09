<?php

class AppointmentController
{
    private $clinic;
    public function __construct()
    {
        require_once __DIR__ . '/../models/db.php';
        $this->clinic = new modelClinic();
        $this->clinic->ketNoiDB();
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
        $id = $_POST['id'] ?? null;
        if (!$id) {
            header("Location: admin.php?admin=listYcLichHen");
            exit;
        }

        $hoTen        = trim($_POST['ho_ten'] ?? '');
        $soDienThoai  = trim($_POST['so_dien_thoai'] ?? '');
        $bacSiId      = $_POST['bac_si_id'] ?? '';
        $dichVuId     = $_POST['dich_vu_id'] ?? '';
        $ngay         = $_POST['ngay_mong_muon'] ?? '';
        $gioBatDau    = $_POST['gio_bat_dau'] ?? '';
        $trangThai    = $_POST['trang_thai'] ?? 'cho_xu_ly';

        // ── Validate ──
        if ($hoTen === '') {
            $_SESSION['error'] = "Họ tên không được để trống.";
            header("Location: admin.php?admin=formSuaYeuCauDatLich&id=$id"); exit;
        }
        if (!preg_match('/^0\d{9}$/', $soDienThoai)) {
            $_SESSION['error'] = "Số điện thoại phải đúng 10 số và bắt đầu bằng 0.";
            header("Location: admin.php?admin=formSuaYeuCauDatLich&id=$id"); exit;
        }
        if ($ngay === '') {
            $_SESSION['error'] = "Ngày khám không được để trống.";
            header("Location: admin.php?admin=formSuaYeuCauDatLich&id=$id"); exit;
        }
        if ($trangThai === 'da_xac_nhan') {
            if (strtotime($ngay) < strtotime(date('Y-m-d'))) {
                $_SESSION['error'] = "Không thể xác nhận lịch có ngày đã qua.";
                header("Location: admin.php?admin=formSuaYeuCauDatLich&id=$id"); exit;
            }
            if (empty($bacSiId)) {
                $_SESSION['error'] = "Phải chọn bác sĩ trước khi xác nhận lịch.";
                header("Location: admin.php?admin=formSuaYeuCauDatLich&id=$id"); exit;
            }
            if (empty($dichVuId)) {
                $_SESSION['error'] = "Phải chọn dịch vụ trước khi xác nhận lịch.";
                header("Location: admin.php?admin=formSuaYeuCauDatLich&id=$id"); exit;
            }
            if (empty($gioBatDau)) {
                $_SESSION['error'] = "Phải chọn giờ khám trước khi xác nhận lịch.";
                header("Location: admin.php?admin=formSuaYeuCauDatLich&id=$id"); exit;
            }
        }

        $old        = $this->clinic->layYeuCauDatLichById($id);
        $gioKetThuc = date('H:i:s', strtotime($gioBatDau . ' +1 hour'));

        $data = [
            'ho_ten'            => $hoTen,
            'email'             => $_POST['email'] ?? null,
            'so_dien_thoai'     => $soDienThoai,
            'dich_vu_id'        => $dichVuId ?: null,
            'bac_si_id'         => $bacSiId  ?: null,
            'ngay_mong_muon'    => $ngay,
            'gio_bat_dau'       => $gioBatDau,
            'gio_ket_thuc'      => $gioKetThuc,
            'mo_ta_trieu_chung' => $_POST['mo_ta_trieu_chung'] ?? '',
            'trang_thai'        => $trangThai,
        ];

        // ── Kiểm tra trùng lịch TRƯỚC khi lưu (chỉ khi xác nhận) ──
        if ($trangThai === 'da_xac_nhan' && $old['trang_thai'] !== 'da_xac_nhan') {
            $trung = $this->clinic->checkTrungLichBacSi(
                $data['bac_si_id'],
                $data['ngay_mong_muon'],
                $data['gio_bat_dau']
            );
            if ($trung) {
                $_SESSION['error'] = "Bác sĩ đã có lịch vào " . date('H:i', strtotime($gioBatDau)) . " ngày $ngay. Vui lòng chọn giờ khác.";
                header("Location: admin.php?admin=formSuaYeuCauDatLich&id=$id"); exit;
            }
        }

        $this->clinic->updateLH($id, $data);

        // Tạo lịch hẹn khi vừa xác nhận
        if ($old['trang_thai'] !== 'da_xac_nhan' && $trangThai === 'da_xac_nhan') {
            $hs             = $this->clinic->findHoSoByPhone($data['so_dien_thoai']);
            $hoSoBenhNhanId = $hs ? $hs['id'] : null;
            $yeuCauMoi      = $this->clinic->layYeuCauDatLichById($id);
            $this->clinic->insertLichHenFromYeuCau($yeuCauMoi, $hoSoBenhNhanId);
        }

        $_SESSION['success'] = "Cập nhật yêu cầu thành công!";
        header("Location: admin.php?admin=listYcLichHen");
        exit;
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

        $this->clinic->insertLichKham([
            $lichHenId,
            $hoSoId,
            $lichHen['bac_si_id'],
            $lichHen['dich_vu_id'],
            $lichHen['ngay_hen'],
            $lichHen['gio_bat_dau'],
            $lichHen['gio_ket_thuc'],
            null,
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

    public function tiepNhanBenhNhan()
    {
        $lichHenId = $_GET['id'] ?? null;
        if (!$lichHenId) {
            header("Location: admin.php?admin=listLichHen");
            exit;
        }

        // Tìm lịch khám tương ứng với lịch hẹn này
        $lichKham = $this->clinic->getLichKhamByLichHenId($lichHenId);

        if ($lichKham) {
            header("Location: admin.php?admin=tiepNhanKham&id=" . $lichKham['id']);
        } else {
            // Chưa có lịch khám → cần gán hồ sơ trước
            $_SESSION['error'] = "Lịch hẹn này chưa được gán hồ sơ bệnh nhân.";
            header("Location: admin.php?admin=listLichHen");
        }
        exit;
    }

    public function tiepNhanKham()
    {
        $lichKhamId = $_GET['id'] ?? null;
        if (!$lichKhamId) {
            header("Location: admin.php?admin=listLichKham");
            exit;
        }

        $this->clinic->capNhatTrangThaiLichKham($lichKhamId, 'dang_kham');

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

        $listDV         = $this->clinic->getAllDichVu();
        $listThuoc      = $this->clinic->getAllThuocConHang();
        $medicalHistory = $this->clinic->getMedicalHistoryByHoSoId($lichKhamId);

        // Lấy danh sách bước của dịch vụ (nếu có) để hiện trong form
        $dichVuId   = $data['dich_vu_id'] ?? null;
        $danhSachBuoc  = $dichVuId ? $this->clinic->getBuocByDichVuId($dichVuId) : [];
        $buocTiepTheo  = (!empty($data['buoc_id']) && $dichVuId)
            ? $this->clinic->getBuocTiepTheo($data['buoc_id'], $dichVuId)
            : null;

        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/quanlylichhen/khambenh.php";
        require_once "backend/views/fileJS.php";
    }

    public function getAvailableTime()
    {
        header('Content-Type: application/json');

        $bac_si_id = $_POST['bac_si_id'] ?? null;
        $ngay = $_POST['ngay'] ?? null;

        if (!$bac_si_id || !$ngay) {
            echo json_encode([]);
            exit;
        }

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

    public function themlichhentructiep()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: admin.php?admin=themlichhenmoi");
            exit;
        }

        $hoTen       = trim($_POST['ho_ten'] ?? '');
        $soDienThoai = trim($_POST['so_dien_thoai'] ?? '');
        $bacSiId     = $_POST['bac_si_id'] ?? '';
        $dichVuId    = $_POST['dich_vu_id'] ?? '';
        $ngayHen     = $_POST['ngay_hen'] ?? '';
        $examTime    = $_POST['exam_time'] ?? '';

        // ── Validate ──
        if ($hoTen === '') {
            $_SESSION['error'] = "Họ tên không được để trống.";
            header("Location: admin.php?admin=themlichhenmoi"); exit;
        }
        if (!preg_match('/^0\d{9}$/', $soDienThoai)) {
            $_SESSION['error'] = "Số điện thoại phải đúng 10 số và bắt đầu bằng 0.";
            header("Location: admin.php?admin=themlichhenmoi"); exit;
        }
        if (empty($bacSiId)) {
            $_SESSION['error'] = "Vui lòng chọn bác sĩ.";
            header("Location: admin.php?admin=themlichhenmoi"); exit;
        }
        if (empty($dichVuId)) {
            $_SESSION['error'] = "Vui lòng chọn dịch vụ.";
            header("Location: admin.php?admin=themlichhenmoi"); exit;
        }
        if ($ngayHen === '' || strtotime($ngayHen) < strtotime(date('Y-m-d'))) {
            $_SESSION['error'] = "Ngày hẹn không hợp lệ hoặc đã qua.";
            header("Location: admin.php?admin=themlichhenmoi"); exit;
        }
        if (empty($examTime)) {
            $_SESSION['error'] = "Vui lòng chọn giờ khám.";
            header("Location: admin.php?admin=themlichhenmoi"); exit;
        }

        // ── Kiểm tra trùng lịch bác sĩ ──
        $trung = $this->clinic->checkTrungLichBacSi($bacSiId, $ngayHen, $examTime);
        if ($trung) {
            $_SESSION['error'] = "Bác sĩ đã có lịch lúc " . date('H:i', strtotime($examTime)) . " ngày $ngayHen. Vui lòng chọn giờ khác.";
            header("Location: admin.php?admin=themlichhenmoi"); exit;
        }

        $data = [
            'yeu_cau_dat_lich_id' => null,
            'ho_ten'              => $hoTen,
            'so_dien_thoai'       => $soDienThoai,
            'bac_si_id'           => $bacSiId,
            'dich_vu_id'          => $dichVuId,
            'ngay_hen'            => $ngayHen,
            'gio_bat_dau'         => $examTime,
            'loai_dat'            => 'truc_tiep',
            'trang_thai'          => 'cho_kham',
            'ghi_chu'             => '',
        ];

        if ($this->clinic->insertLichHenTrucTiep($data)) {
            $_SESSION['success'] = "Tạo lịch hẹn trực tiếp thành công!";
            header("Location: admin.php?admin=listLichHen");
        } else {
            $_SESSION['error'] = "Tạo lịch thất bại, vui lòng thử lại.";
            header("Location: admin.php?admin=themlichhenmoi");
        }
        exit;
    }
}
