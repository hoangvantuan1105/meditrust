<?php

class MedicalRecordController
{
    private $clinic;
    public function __construct()
    {
        require_once __DIR__ . '/../models/db.php';
        $this->clinic = new modelClinic();
        $this->clinic->ketNoiDB();
    }

    public function luuKetQuaKham()
    {
        $lich_kham_id = $_POST['lich_kham_id'];
        $chan_doan = $_POST['ket_luan'];
        $danh_sach_dv = isset($_POST['dich_vu']) ? $_POST['dich_vu'] : [];
        $don_thuoc = isset($_POST['thuoc']) ? $_POST['thuoc'] : [];

        if (!empty($don_thuoc)) {
            foreach ($don_thuoc as $item) {
                $thuoc_id = $item['thuoc_id'];
                $so_luong_ke = (int) $item['so_luong'];

                $infoThuoc = $this->clinic->getThuocById($thuoc_id);
                $ten_thuoc = $infoThuoc['ten_thuoc'];
                $so_luong_ton = (int) $infoThuoc['so_luong'];

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

        $this->clinic->saveResultEx($lich_kham_id, $danh_sach_dv, $chan_doan, $don_thuoc);

        header("Location: admin.php?admin=listLichKham&status=success");
    }

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
