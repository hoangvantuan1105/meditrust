<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class OrderController
{
    private $clinic;
    public function __construct()
    {
        require_once __DIR__ . '/../models/db.php';
        $this->clinic = new modelClinic();
        $this->clinic->ketNoiDB();
    }

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

            $tong = 0;

            foreach ($dich_vu_ids as $id) {
                $tong += $this->clinic->getGiaDichVu($id);
            }

            foreach ($thuoc_ids as $index => $id) {
                $gia = $this->clinic->getGiaThuoc($id);
                $sl = $so_luong[$index];

                $tong += $gia * $sl;
            }

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

            $dich_vu_ids = array_count_values($dich_vu_ids);

            foreach ($dich_vu_ids as $id => $so_luong) {
                $gia = $this->clinic->getGiaDichVu($id);

                $this->clinic->insertChiTietHoaDon([
                    $hoa_don_id,
                    'dich_vu',
                    $id,
                    $so_luong,
                    $gia,
                    $gia * $so_luong
                ]);
            }

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

    public function exportInvoice()
    {
        $id = $_GET['id'] ?? null;
        if (!$id)
            die("Thiếu ID");

        $hoa_don = $this->clinic->getHoaDonById($id);
        $chi_tiet = $this->clinic->getChiTietHoaDon($id);

        require 'libs/export_invoice.php';
    }
}
