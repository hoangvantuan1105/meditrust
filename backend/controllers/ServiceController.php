<?php

class ServiceController
{
    private $clinic;
    public function __construct()
    {
        require_once __DIR__ . '/../models/db.php';
        $this->clinic = new modelClinic();
        $this->clinic->ketNoiDB();
    }

    public function qlydichvu()
    {
        $dichvu = $this->clinic->getAll_dich_vu();

        require_once __DIR__ . "/../views/header.php";
        require_once __DIR__ . "/../views/sidebar.php";
        require_once __DIR__ . "/../views/topbar.php";
        require_once __DIR__ . "/../views/quanlydichvu/quanlydichvu.php";
        require_once __DIR__ . "/../views/fileJS.php";
    }

    public function xemchitietdichvu($id)
    {
        $chitetdichvudichvu = $this->clinic->getDichVuById($id);
        $vatTuSuDung        = $this->clinic->getVatTuByDichVu($id);
        $danhSachBuoc       = $this->clinic->getBuocByDichVuId($id);

        require_once __DIR__ . "/../views/header.php";
        require_once __DIR__ . "/../views/sidebar.php";
        require_once __DIR__ . "/../views/topbar.php";
        require_once __DIR__ . "/../views/quanlydichvu/xemchitietdichvu.php";
        require_once __DIR__ . "/../views/fileJS.php";
    }

    public function addBuocDichVu()
    {
        $dich_vu_id = $_POST['dich_vu_id'] ?? null;
        $thu_tu     = (int) ($_POST['thu_tu']   ?? 1);
        $ten_buoc   = trim($_POST['ten_buoc']   ?? '');
        $mo_ta      = trim($_POST['mo_ta']      ?? '');

        if ($dich_vu_id && $ten_buoc !== '') {
            $this->clinic->addBuocDichVu($dich_vu_id, $thu_tu, $ten_buoc, $mo_ta);
        }
        header("Location: admin.php?admin=xemchitietdichvu&idAdmin={$dich_vu_id}&tab=buoc");
        exit;
    }

    public function editBuocDichVu()
    {
        $id         = $_POST['buoc_id']    ?? null;
        $dich_vu_id = $_POST['dich_vu_id'] ?? null;
        $thu_tu     = (int) ($_POST['thu_tu']   ?? 1);
        $ten_buoc   = trim($_POST['ten_buoc']   ?? '');
        $mo_ta      = trim($_POST['mo_ta']      ?? '');

        if ($id && $ten_buoc !== '') {
            $this->clinic->updateBuocDichVu($id, $thu_tu, $ten_buoc, $mo_ta);
        }
        header("Location: admin.php?admin=xemchitietdichvu&idAdmin={$dich_vu_id}&tab=buoc");
        exit;
    }

    public function deleteBuocDichVu()
    {
        $id         = $_GET['id']         ?? null;
        $dich_vu_id = $_GET['dich_vu_id'] ?? null;
        if ($id) {
            $this->clinic->deleteBuocDichVu($id);
        }
        header("Location: admin.php?admin=xemchitietdichvu&idAdmin={$dich_vu_id}&tab=buoc");
        exit;
    }

    public function addDich_vu()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $ten_dich_vu = $_POST['ten_dich_vu'] ?? '';
            $danhmuc = $_POST['danhmuc'] ?? null;
            $id_loai = $_POST['id_loai'] ?? null;
            $gia = $_POST['gia'] ?? 0;
            $mo_ta = $_POST['mo_ta'] ?? '';

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

                $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['image']['name']);
                $targetFile = $uploadDir . $fileName;

                if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    die("❌ Upload ảnh thất bại");
                }

                $image = $fileName;
            }

            $id_dich_vu = $this->clinic->insertDichVu($ten_dich_vu, $danhmuc, $gia, $mo_ta, $image, $id_loai);

            if (!$id_dich_vu) {
                die("❌ Không thêm được dịch vụ");
            }

            $vatTuPost = $_POST['vat_tu'] ?? [];

            foreach ($vatTuPost as $id_vat_tu => $so_luong) {
                if ((int) $so_luong > 0) {
                    $this->clinic->addVatTuToDichVu($id_dich_vu, $id_vat_tu, (int) $so_luong);
                }
            }

            $tongVatTu = $this->clinic->getTongTienVatTuByDichVu($id_dich_vu);
            $this->clinic->updateGiaDichVu($id_dich_vu, $gia + $tongVatTu);

            header("Location: admin.php?admin=qlydichvu");
            exit;
        }

        $vatTuList = $this->clinic->getAllVatTu();
        $dichvu = ['tieu_hao' => null];

        require_once __DIR__ . "/../views/header.php";
        require_once __DIR__ . "/../views/sidebar.php";
        require_once __DIR__ . "/../views/topbar.php";
        require_once __DIR__ . "/../views/quanlydichvu/themdichvu.php";
        require_once __DIR__ . "/../views/fileJS.php";
    }

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

    public function suaDichVu($id)
    {
        $dichvu = $this->clinic->getDichVuById($id);
        $vatTuList = $this->clinic->getAllVatTu();
        $vatTuDaChon = $this->clinic->getVatTuByDichVu($id);

        $tongTienVatTu = 0;
        foreach ($vatTuDaChon as $vt) {
            $tongTienVatTu += $vt['gia_nhap'] * $vt['so_luong'];
        }

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

            $image = $this->clinic->getHinhAnhDichVuById($id);

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

                $image = $fileName;
            }

            $this->clinic->updateDichVu($id, $ten_dich_vu, $danhmuc, $gia, $mo_ta, $image, $id_loai);

            $vatTuPost = $_POST['vat_tu'] ?? [];
            $this->clinic->setVatTuForDichVu($id, $vatTuPost);

            $tongVatTu = $this->clinic->getTongTienVatTuByDichVu($id);
            $this->clinic->updateGiaDichVu($id, $gia + $tongVatTu);

            header("Location: admin.php?admin=qlydichvu");
            exit;
        }
    }

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
}
