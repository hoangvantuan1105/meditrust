<?php
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('defaultFont', 'DejaVu Sans'); // hỗ trợ tiếng Việt

$dompdf = new Dompdf($options);

ob_start();
?>

<style>
    body {
        font-family: DejaVu Sans;
        font-size: 14px;
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
    }

    .header h2 {
        margin: 0;
        color: #0ea5e9;
    }

    .info {
        margin-bottom: 15px;
    }

    .info p {
        margin: 4px 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    table th {
        background: #0ea5e9;
        color: white;
        padding: 8px;
        text-align: center;
    }

    table td {
        border: 1px solid #ddd;
        padding: 6px;
        text-align: center;
    }

    .total {
        margin-top: 15px;
        float: right;
        width: 40%;
    }

    .total table {
        border: none;
    }

    .total td {
        border: none;
        text-align: right;
        padding: 4px;
    }

    .bold {
        font-weight: bold;
        color: #e11d48;
    }

    .footer {
        margin-top: 50px;
        text-align: right;
        font-style: italic;
    }
</style>

<div class="header">
    <h2>HÓA ĐƠN THANH TOÁN</h2>
    <p>Phòng Khám Nha Khoa MediTrust</p>
</div>

<div class="info">
    <p><strong>Mã hóa đơn:</strong> <?= $hoa_don['id'] ?></p>
    <p><strong>Bệnh nhân:</strong> <?= $hoa_don['ten_benh_nhan'] ?? '' ?></p>
    <p><strong>Ngày lập:</strong> <?= date('d/m/Y H:i', strtotime($hoa_don['ngay_lap'])) ?></p>
    <p><strong>Phương thức thanh toán:</strong> <?= $hoa_don['phuong_thuc_tt'] ?></p>
</div>

<table>
    <thead>
        <tr>
            <th>STT</th>
            <th>Tên dịch vụ / thuốc</th>
            <th>Số lượng</th>
            <th>Đơn giá (VNĐ)</th>
            <th>Thành tiền (VNĐ)</th>
        </tr>
    </thead>
    <tbody>
        <?php $stt = 1; ?>
        <?php foreach ($chi_tiet as $ct): ?>
            <tr>
                <td><?= $stt++ ?></td>
                <td><?= $ct['ten'] ?></td>
                <td><?= $ct['so_luong'] ?></td>
                <td><?= number_format($ct['don_gia'], 0, ',', '.') ?></td>
                <td><?= number_format($ct['thanh_tien'], 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="total">
    <table>
        <tr>
            <td><strong>Tổng tiền:</strong></td>
            <td><?= number_format($hoa_don['tong_tien'], 0, ',', '.') ?> đ</td>
        </tr>
        <tr>
            <td><strong>Giảm giá:</strong></td>
            <td>- <?= number_format($hoa_don['giam_gia'], 0, ',', '.') ?> đ</td>
        </tr>
        <tr>
            <td class="bold">Thành tiền:</td>
            <td class="bold">
                <?= number_format($hoa_don['thanh_tien'], 0, ',', '.') ?> đ
            </td>
        </tr>
    </table>
</div>

<div style="clear: both;"></div>

<div class="footer">
    <p>Cảm ơn quý khách đã sử dụng dịch vụ!</p>
</div>

<?php
$html = ob_get_clean();

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("hoa_don_" . $hoa_don['id'] . ".pdf", ["Attachment" => false]);
exit;
