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
        $lich_kham_id    = $_POST['lich_kham_id'];
        $chan_doan       = $_POST['ket_luan'];
        $huong_dieu_tri  = trim($_POST['huong_dieu_tri'] ?? '');
        $danh_sach_dv    = $_POST['dich_vu'] ?? [];
        $don_thuoc       = $_POST['thuoc'] ?? [];
        $can_tai_kham    = !empty($_POST['can_tai_kham']);
        $ngay_tai_kham   = $can_tai_kham ? ($_POST['ngay_tai_kham'] ?? '') : '';
        $ghi_chu_tai_kham = trim($_POST['ghi_chu_tai_kham'] ?? '');

        if (!empty($don_thuoc)) {
            foreach ($don_thuoc as $item) {
                $thuoc_id    = $item['thuoc_id'];
                $so_luong_ke = (int) $item['so_luong'];

                $infoThuoc   = $this->clinic->getThuocById($thuoc_id);
                $ten_thuoc   = $infoThuoc['ten_thuoc'];
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

        $this->clinic->saveResultEx($lich_kham_id, $danh_sach_dv, $chan_doan, $don_thuoc, $huong_dieu_tri);

        if ($can_tai_kham && $ngay_tai_kham) {
            $buoc_tiep_theo_id = !empty($_POST['buoc_tiep_theo_id']) ? (int) $_POST['buoc_tiep_theo_id'] : null;
            $this->clinic->taoLichTaiKham($lich_kham_id, $ngay_tai_kham, $ghi_chu_tai_kham, $buoc_tiep_theo_id);
        }

        header("Location: admin.php?admin=listLichKham&status=success");
    }

    public function listTaiKham()
    {
        $trang_thai = $_GET['trang_thai'] ?? null;
        $tu_ngay    = $_GET['tu_ngay']    ?? null;
        $den_ngay   = $_GET['den_ngay']   ?? null;

        $listTaiKham = $this->clinic->getDanhSachTaiKham($trang_thai, $tu_ngay, $den_ngay);

        // Thống kê nhanh luôn tính từ toàn bộ dữ liệu, không phụ thuộc filter
        $allTaiKham   = $this->clinic->getDanhSachTaiKham();
        $tongTatCa    = count($allTaiKham);
        $choKham      = count(array_filter($allTaiKham, fn($r) => $r['trang_thai'] === 'cho_kham'));
        $homNay       = count(array_filter($allTaiKham, fn($r) => $r['ngay_kham'] === date('Y-m-d')));
        $sapDen       = count(array_filter($allTaiKham, fn($r) => $r['con_lai'] >= 0 && $r['con_lai'] <= 7 && $r['trang_thai'] === 'cho_kham'));

        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/quanlylichhen/danhsachtaikham.php";
        require_once "backend/views/fileJS.php";
    }

    public function capNhatTrangThaiTaiKham()
    {
        $id        = $_POST['id']        ?? null;
        $trang_thai = $_POST['trang_thai'] ?? null;

        if (!$id || !$trang_thai) {
            header("Location: admin.php?admin=listTaiKham");
            exit;
        }

        $this->clinic->capNhatTrangThaiTaiKham($id, $trang_thai);
        header("Location: admin.php?admin=listTaiKham");
        exit;
    }

    public function guiNhacNhoTaiKham()
    {
        $id = $_POST['id'] ?? null;
        if (!$id) {
            header("Location: admin.php?admin=listTaiKham&msg_type=error&msg=" . urlencode('Thiếu ID'));
            exit;
        }

        require_once "frontend/helpers/MailHelper.php";
        $row = $this->clinic->getTaiKhamById($id);

        if (!$row || empty($row['email'])) {
            header("Location: admin.php?admin=listTaiKham&msg_type=error&msg=" . urlencode('Bệnh nhân không có email trong hồ sơ'));
            exit;
        }

        $ok = MailHelper::sendTaiKhamReminder(
            $row['email'],
            $row['ten_benh_nhan'],
            $row['ngay_kham'],
            $row['ten_bac_si'] ?? '---',
            $row['ten_dich_vu'] ?? '---',
            $row['ghi_chu'] ?? ''
        );

        $msg  = $ok ? 'Đã gửi nhắc nhở thành công đến ' . $row['email'] : 'Gửi email thất bại, vui lòng thử lại';
        $type = $ok ? 'success' : 'error';
        header("Location: admin.php?admin=listTaiKham&msg_type={$type}&msg=" . urlencode($msg));
        exit;
    }

    public function guiNhacNhoTatCa()
    {
        require_once "frontend/helpers/MailHelper.php";

        $list  = $this->clinic->getDanhSachTaiKham('cho_kham');
        $sent  = 0;
        $skip  = 0;

        foreach ($list as $row) {
            $conLai = (int) $row['con_lai'];
            if ($conLai < 0 || $conLai > 7 || empty($row['email'])) {
                $skip++;
                continue;
            }
            $ok = MailHelper::sendTaiKhamReminder(
                $row['email'],
                $row['ten_benh_nhan'],
                $row['ngay_kham'],
                $row['ten_bac_si'] ?? '---',
                $row['ten_dich_vu'] ?? '---',
                $row['ghi_chu'] ?? ''
            );
            $ok ? $sent++ : $skip++;
        }

        $msg = "Đã gửi {$sent} email nhắc nhở. Bỏ qua {$skip} (không có email hoặc ngoài 7 ngày).";
        header("Location: admin.php?admin=listTaiKham&msg_type=success&msg=" . urlencode($msg));
        exit;
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
