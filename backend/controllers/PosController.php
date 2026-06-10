<?php

class PosController
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
        $title = "Bán hàng tại quầy (POS)";
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/pos/index.php";
        require_once "backend/views/fileJS.php";
    }

    public function apiGetItems()
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $products = array_map(function ($m) {
                return [
                    'id' => (int) $m['id'],
                    'name' => $m['ten_vat_tu'],
                    'price' => (float) $m['gia_nhap'],
                    'type' => 'product',
                    'stock' => (int) $m['so_luong'],
                    'unit' => $m['don_vi'] ?? '',
                ];
            }, $this->clinic->getProductsForPos(false));

            $medicines = array_map(function ($m) {
                $name = trim($m['ten_thuoc'] . (!empty($m['ham_luong']) ? ' - ' . $m['ham_luong'] : ''));

                return [
                    'id' => (int) $m['id'],
                    'name' => $name,
                    'price' => (float) $m['gia_nhap'],
                    'type' => 'medicine',
                    'stock' => (int) $m['so_luong'],
                    'unit' => $m['don_vi_tinh'] ?? '',
                ];
            }, $this->clinic->getMedicinesForPos());

            echo json_encode([
                'status' => 'success',
                'data' => array_merge($products, $medicines),
            ]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function apiGetProducts()
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            echo json_encode([
                'status' => 'success',
                'data' => $this->clinic->getProductsForPos(true),
            ]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function apiSaveProduct()
    {
        header('Content-Type: application/json; charset=utf-8');
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = $_POST;
        }

        $id = isset($input['id']) ? (int) $input['id'] : 0;
        $name = trim($input['ten_vat_tu'] ?? '');
        $unit = trim($input['don_vi'] ?? '');
        $quantity = max(0, (int) ($input['so_luong'] ?? 0));
        $manufacturer = trim($input['hang_san_xuat'] ?? '');
        $category = trim($input['danh_muc'] ?? 'tieu hao');
        $price = max(0, (float) ($input['gia_nhap'] ?? 0));
        $expiry = trim($input['han_su_dung'] ?? '');
        $saleStatus = isset($input['trang_thai_su_dung']) ? (int) $input['trang_thai_su_dung'] : 1;
        $saleStatus = in_array($saleStatus, [0, 1], true) ? $saleStatus : 1;

        if ($name === '' || $unit === '' || $manufacturer === '' || $expiry === '') {
            echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập đầy đủ thông tin sản phẩm.']);
            exit;
        }

        $stockStatus = $quantity > 0 ? 'con hang' : 'het hang';
        $expiryStatus = strtotime($expiry) !== false && strtotime($expiry) < strtotime(date('Y-m-d')) ? 'het han' : 'con han';

        try {
            if ($id > 0) {
                $current = $this->clinic->findMaterials($id);
                if (!$current || (int) ($current['type'] ?? 0) !== 1) {
                    echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy sản phẩm cần sửa.']);
                    exit;
                }

                $ok = $this->clinic->updateMaterials(
                    $name,
                    $unit,
                    $quantity,
                    $manufacturer,
                    $category,
                    $stockStatus,
                    $expiryStatus,
                    $price,
                    $expiry,
                    $id,
                    1,
                    $saleStatus
                );
            } else {
                $ok = $this->clinic->insertMaterials([
                    $name,
                    $unit,
                    $quantity,
                    $manufacturer,
                    $category,
                    $stockStatus,
                    $price,
                    $expiry,
                    $expiryStatus,
                    1,
                    $saleStatus,
                ]);
            }

            echo json_encode([
                'status' => $ok ? 'success' : 'error',
                'message' => $ok ? 'Lưu sản phẩm thành công.' : 'Không thể lưu sản phẩm.',
            ]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function apiToggleProduct()
    {
        header('Content-Type: application/json; charset=utf-8');
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = $_POST;
        }

        $id = isset($input['id']) ? (int) $input['id'] : 0;
        $status = isset($input['status']) ? (int) $input['status'] : 1;
        $status = in_array($status, [0, 1], true) ? $status : 1;

        if ($id <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Sản phẩm không hợp lệ.']);
            exit;
        }

        $product = $this->clinic->findMaterials($id);
        if (!$product || (int) ($product['type'] ?? 0) !== 1) {
            echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy sản phẩm.']);
            exit;
        }

        $ok = $this->clinic->updateProductSaleStatus($id, $status);
        echo json_encode([
            'status' => $ok ? 'success' : 'error',
            'message' => $ok ? 'Cập nhật trạng thái sản phẩm thành công.' : 'Cập nhật thất bại.',
        ]);
        exit;
    }

    public function apiSearchPatient()
    {
        header('Content-Type: application/json; charset=utf-8');
        $keyword = $_GET['q'] ?? '';
        if (empty($keyword)) {
            echo json_encode(['status' => 'success', 'data' => []]);
            exit;
        }

        $patients = $this->clinic->searchPatientsForPos($keyword);
        echo json_encode(['status' => 'success', 'data' => $patients]);
        exit;
    }

    public function apiAddPatientQuick()
    {
        header('Content-Type: application/json; charset=utf-8');
        $input = json_decode(file_get_contents('php://input'), true);

        $ho_ten = $input['ho_ten'] ?? '';
        $so_dien_thoai = $input['so_dien_thoai'] ?? '';

        if (empty($ho_ten) || empty($so_dien_thoai)) {
            echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập đầy đủ thông tin']);
            exit;
        }

        try {
            $newId = $this->clinic->addPatientQuickForPos($ho_ten, $so_dien_thoai);
            echo json_encode([  
                'status' => 'success',
                'data' => [
                    'id' => $newId,
                    'ho_ten' => $ho_ten,
                    'so_dien_thoai' => $so_dien_thoai,
                ],
            ]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
        exit;
    }

    public function apiProcessCheckout()
    {
        header('Content-Type: application/json; charset=utf-8');
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input) {
            echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']);
            exit;
        }

        $patientId = $input['patient_id'] ?? null;
        $appointmentId = $input['appointment_id'] ?? null;
        $cart = $input['cart'] ?? [];
        $paymentMethod = $input['payment_method'] ?? 'cash';
        $discount = max(0, (float) ($input['discount'] ?? 0));

        if (!$patientId) {
            echo json_encode(['status' => 'error', 'message' => 'Vui lòng chọn khách hàng.']);
            exit;
        }

        if (empty($cart)) {
            echo json_encode(['status' => 'error', 'message' => 'Giỏ hàng trống']);
            exit;
        }

        try {
            $productLines = [];
            $medicineLines = [];

            foreach ($cart as $item) {
                $type = $item['type'] ?? '';
                $id = (int) ($item['id'] ?? 0);
                $qty = max(1, (int) ($item['quantity'] ?? 1));

                if ($type === 'product') {
                    $product = $this->clinic->findMaterials($id);
                    if (!$product || (int) ($product['type'] ?? 0) !== 1 || (int) ($product['trang_thai_su_dung'] ?? 1) !== 1) {
                        throw new Exception('Sản phẩm không hợp lệ hoặc đang bị khóa.');
                    }
                    if ((int) $product['so_luong'] < $qty) {
                        throw new Exception('Không đủ tồn kho sản phẩm: ' . $product['ten_vat_tu']);
                    }

                    $price = (float) $product['gia_nhap'];
                    $productLines[] = [
                        'id' => (int) $product['id'],
                        'qty' => $qty,
                        'price' => $price,
                        'total' => $price * $qty,
                    ];
                    continue;
                }

                if ($type === 'medicine') {
                    $medicine = $this->clinic->getMedicineByID($id);
                    if (!$medicine || (int) ($medicine['trang_thai_su_dung'] ?? 1) !== 1) {
                        throw new Exception('Thuốc không hợp lệ hoặc đang ngừng sử dụng.');
                    }
                    if ((int) $medicine['so_luong'] < $qty) {
                        throw new Exception('Không đủ tồn kho thuốc: ' . $medicine['ten_thuoc']);
                    }

                    $dosage = trim($item['dosage'] ?? '');
                    $usage = trim($item['usage'] ?? '');
                    if ($dosage === '' || $usage === '') {
                        throw new Exception('Vui lòng nhập liều lượng và cách uống cho thuốc: ' . $medicine['ten_thuoc']);
                    }

                    $price = (float) $medicine['gia_nhap'];
                    $medicineLines[] = [
                        'id' => (int) $medicine['id'],
                        'qty' => $qty,
                        'price' => $price,
                        'total' => $price * $qty,
                        'dosage' => $dosage,
                        'usage' => $usage,
                    ];
                    continue;
                }

                throw new Exception('Mặt hàng không hợp lệ.');
            }

            $productTotal = array_sum(array_column($productLines, 'total'));
            $medicineTotal = array_sum(array_column($medicineLines, 'total'));
            $total = $productTotal + $medicineTotal;
            $discount = min($discount, $total);

            $productDiscount = 0;
            $medicineDiscount = 0;
            if ($total > 0) {
                $productDiscount = $productTotal > 0 ? round($discount * $productTotal / $total) : 0;
                $productDiscount = min($productDiscount, $productTotal);
                $medicineDiscount = $discount - $productDiscount;
                $medicineDiscount = min($medicineDiscount, $medicineTotal);
            }

            $conn = $this->clinic->getConnection();
            $conn->beginTransaction();

            $invoiceIds = [];

            if (!empty($productLines)) {
                $invoiceIds['product'] = $this->clinic->insertHoaDon([
                    $patientId,
                    null,
                    $appointmentId,
                    $productTotal,
                    $productDiscount,
                    max(0, $productTotal - $productDiscount),
                    $paymentMethod,
                    1,
                ]);

                foreach ($productLines as $line) {
                    $this->clinic->insertChiTietHoaDon([
                        $invoiceIds['product'],
                        'san_pham',
                        $line['id'],
                        $line['qty'],
                        $line['price'],
                        $line['total'],
                    ]);
                    $this->clinic->updateQuantity($line['id'], $line['qty']);
                }
            }

            if (!empty($medicineLines)) {
                $invoiceIds['medicine'] = $this->clinic->insertHoaDon([
                    $patientId,
                    null,
                    $appointmentId,
                    $medicineTotal,
                    $medicineDiscount,
                    max(0, $medicineTotal - $medicineDiscount),
                    $paymentMethod,
                    1,
                ]);

                foreach ($medicineLines as $line) {
                    $this->clinic->insertChiTietHoaDon([
                        $invoiceIds['medicine'],
                        'thuoc',
                        $line['id'],
                        $line['qty'],
                        $line['price'],
                        $line['total'],
                        $line['dosage'],
                        $line['usage'],
                    ]);
                    $this->clinic->updateMedicineQuantity($line['id'], $line['qty']);
                }
            }

            $conn->commit();

            echo json_encode([
                'status' => 'success',
                'invoice_id' => reset($invoiceIds),
                'invoice_ids' => $invoiceIds,
            ]);
        } catch (Exception $e) {
            if (isset($conn) && $conn->inTransaction()) {
                $conn->rollBack();
            }
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function invoicePrint()
    {
        $id = $_GET['id'] ?? '';
        if (!$id) {
            die("Không có mã hóa đơn");
        }

        require_once "backend/views/pos/invoice_print.php";
    }
}
