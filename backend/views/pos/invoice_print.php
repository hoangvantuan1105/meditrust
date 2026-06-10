<?php
$db = new modelClinic();
$db->ketNoiDB();
$conn = $db->getConnection();

$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("SELECT hd.*, hs.ho_ten, hs.so_dien_thoai, bs.ten_bac_si
                        FROM hoa_don hd
                        LEFT JOIN ho_so_benh_nhan hs ON hd.benh_nhan_id = hs.id
                        LEFT JOIN bac_si bs ON hd.bac_si_id = bs.id
                        WHERE hd.id = ?");
$stmt->execute([$id]);
$invoice = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$invoice) {
    die("Hóa đơn không tồn tại.");
}

$stmt_ct = $conn->prepare("SELECT ct.*,
                            COALESCE(t.ten_thuoc, vt.ten_vat_tu, dv.ten_dich_vu) AS ten_san_pham
                           FROM chi_tiet_hoa_don ct
                           LEFT JOIN thuoc t ON ct.item_id = t.id AND ct.loai_item = 'thuoc'
                           LEFT JOIN vat_tu vt ON ct.item_id = vt.id AND ct.loai_item = 'san_pham'
                           LEFT JOIN dich_vu dv ON ct.item_id = dv.id AND ct.loai_item = 'dich_vu'
                           WHERE ct.hoa_don_id = ?");
$stmt_ct->execute([$id]);
$details = $stmt_ct->fetchAll(PDO::FETCH_ASSOC);

$types = array_unique(array_column($details, 'loai_item'));
$invoiceTitle = 'HÓA ĐƠN THANH TOÁN';
if (count($types) === 1 && reset($types) === 'thuoc') {
    $invoiceTitle = 'HÓA ĐƠN THUỐC';
} elseif (count($types) === 1 && reset($types) === 'san_pham') {
    $invoiceTitle = 'HÓA ĐƠN SẢN PHẨM';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>In Hóa Đơn #<?= htmlspecialchars($id) ?></title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            font-size: 12px;
        }
        .print-container {
            width: 80mm;
            margin: 10px auto;
            background: #fff;
            padding: 5mm;
            box-shadow: 0 0 5px rgba(0,0,0,0.2);
        }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .header h3 { margin: 5px 0; font-size: 16px; }
        .header p { margin: 2px 0; }
        .divider { border-top: 1px dashed #000; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 3px 0; vertical-align: top; }
        .item-name { display: block; max-width: 50mm; word-wrap: break-word; }
        .medicine-note { display: block; font-size: 11px; margin-top: 2px; }

        @media print {
            body { background: #fff; }
            .print-container {
                width: 100%;
                margin: 0;
                padding: 0;
                box-shadow: none;
            }
            @page { margin: 0; }
        }
    </style>
</head>
<body>
    <div class="print-container">
        <div class="header text-center">
            <h3>NHA KHOA MEDITRUST</h3>
            <p>Địa chỉ: 123 Đường ABC, Quận XYZ, TP.HCM</p>
            <p>Điện thoại: 0123 456 789</p>
            <p class="font-bold" style="font-size: 14px;"><?= $invoiceTitle ?></p>
        </div>

        <div class="divider"></div>

        <div>
            <p><strong>Số HĐ:</strong> #<?= str_pad((string) $id, 6, '0', STR_PAD_LEFT) ?></p>
            <p><strong>Ngày:</strong> <?= date('d/m/Y H:i', strtotime($invoice['ngay_lap'])) ?></p>
            <?php if (!empty($invoice['ho_ten'])): ?>
                <p><strong>Khách hàng:</strong> <?= htmlspecialchars($invoice['ho_ten']) ?></p>
                <p><strong>SĐT:</strong> <?= htmlspecialchars($invoice['so_dien_thoai']) ?></p>
            <?php endif; ?>
        </div>

        <div class="divider"></div>

        <table>
            <thead>
                <tr>
                    <th class="text-left" style="width: 52%;">Tên</th>
                    <th class="text-center" style="width: 13%;">SL</th>
                    <th class="text-right" style="width: 35%;">T.Tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($details as $item): ?>
                    <tr>
                        <td class="text-left">
                            <span class="item-name"><?= htmlspecialchars($item['ten_san_pham'] ?? '') ?></span>
                            <small><?= number_format((float) $item['don_gia']) ?> đ</small>
                            <?php if ($item['loai_item'] === 'thuoc'): ?>
                                <span class="medicine-note"><strong>Liều:</strong> <?= htmlspecialchars($item['lieu_luong'] ?? '') ?></span>
                                <span class="medicine-note"><strong>Cách uống:</strong> <?= htmlspecialchars($item['cach_uong'] ?? '') ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center"><?= (int) $item['so_luong'] ?></td>
                        <td class="text-right"><?= number_format((float) $item['thanh_tien']) ?> đ</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="divider"></div>

        <table>
            <tr>
                <td>Tổng tiền:</td>
                <td class="text-right font-bold"><?= number_format((float) $invoice['tong_tien']) ?> đ</td>
            </tr>
            <tr>
                <td>Giảm giá:</td>
                <td class="text-right"><?= number_format((float) $invoice['giam_gia']) ?> đ</td>
            </tr>
            <tr>
                <td class="font-bold" style="font-size: 14px;">THANH TOÁN:</td>
                <td class="text-right font-bold" style="font-size: 14px;"><?= number_format((float) $invoice['thanh_tien']) ?> đ</td>
            </tr>
        </table>

        <div class="divider"></div>

        <div class="text-center" style="margin-top: 10px;">
            <p>Cảm ơn Quý khách!</p>
            <p>Hẹn gặp lại</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
