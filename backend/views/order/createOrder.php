<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .invoice-wrapper {
        background: #f4f6fb;
        padding: 40px;
        min-height: 100vh;
        font-family: Segoe UI;
    }

    .invoice-card {
        max-width: 650px;
        margin: auto;
        background: #fff;
        border-radius: 16px;
        padding: 25px 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .1);
    }

    .invoice-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .invoice-header i {
        font-size: 40px;
        color: #4f46e5;
    }

    .invoice-header h2 {
        margin: 10px 0 0;
    }

    .patient-box {
        background: #f8f9ff;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 15px;
    }

    .section {
        margin: 15px 0;
    }

    .radio-group {
        display: flex;
        gap: 20px;
        margin-top: 5px;
    }

    .service-row {
        display: flex;
        gap: 10px;
        margin-bottom: 8px;
    }

    .service-row select {
        flex: 1;
        padding: 8px;
        border-radius: 8px;
        border: 1px solid #ddd;
    }

    .btn-remove {
        background: #ef4444;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 0 12px;
        cursor: pointer;
    }

    .btn-add {
        margin-top: 6px;
        background: #4f46e5;
        color: #fff;
        border: none;
        padding: 6px 14px;
        border-radius: 8px;
        cursor: pointer;
    }

    .voucher-row {
        display: flex;
        gap: 8px;
    }

    .voucher-row input {
        flex: 1;
        padding: 8px;
        border-radius: 8px;
        border: 1px solid #ddd;
    }

    .voucher-row button {
        background: #16a34a;
        border: none;
        color: white;
        border-radius: 8px;
        padding: 0 14px;
    }

    .summary-box {
        background: #f3f4f6;
        padding: 15px;
        border-radius: 10px;
        margin: 15px 0;
    }

    .summary-box div {
        display: flex;
        justify-content: space-between;
        margin: 4px 0;
    }

    .summary-box .total {
        font-size: 18px;
        color: #4f46e5;
    }

    .bill-table {
        width: 100%;
        border-collapse: collapse;
    }

    .bill-table th,
    .bill-table td {
        padding: 8px;
        border-bottom: 1px solid #eee;
    }

    .btn-submit {
        width: 100%;
        margin-top: 20px;
        padding: 12px;
        background: #4f46e5;
        border: none;
        color: white;
        border-radius: 10px;
        font-size: 16px;
        cursor: pointer;
    }

    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .4);
        display: none;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: white;
        padding: 20px;
        border-radius: 14px;
        text-align: center;
    }

    .price-badge {
        margin: 10px 0;
        font-size: 20px;
        color: #16a34a;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

<div class="invoice-wrapper">
    <div class="invoice-card">

        <div class="invoice-header">
            <i class="fa-solid fa-file-invoice"></i>
            <h2>Tạo hóa đơn</h2>
            <p>Kiểm tra thông tin trước khi xác nhận</p>
        </div>

        <form method="post" action="admin.php?admin=createInvoice">

            <input type="hidden" name="lich_kham_id" value="<?= $lich['id'] ?>">
            <input type="hidden" name="benh_nhan_id" value="<?= $lich['ho_so_benh_nhan_id'] ?>">
            <input type="hidden" name="bac_si_id" value="<?= $lich['bac_si_id'] ?>">

            <div class="patient-box">
                <h4>Thông tin bệnh nhân</h4>
                <div><b>Tên:</b> <?= $lich['ten_benh_nhan'] ?></div>
                <div><b>SĐT:</b> <?= $lich['so_dien_thoai'] ?></div>
                <div><b>Dịch vụ đăng ký:</b> <?= $lich['ten_dich_vu'] ?></div>
            </div>

            <div class="section">
                <label>Phương thức thanh toán</label>
                <div class="radio-group">
                    <label><input type="radio" name="phuong_thuc_tt" value="Tien mat" checked> Tiền mặt</label>
                    <label><input type="radio" name="phuong_thuc_tt" value="Chuyen khoan"> Chuyển khoản</label>
                </div>
            </div>

            <div class="section">


                <div class="section">
                    <label>Dịch vụ thực hiện</label>

                    <?php foreach ($services as $s): ?>
                        <div class="service-item" data-gia="<?= $s['gia'] ?? 0 ?>" data-soluong=" 1">

                            <?= $s['ten_dich_vu'] ?>
                            (<?= number_format($s['gia']) ?>đ)
                        </div>

                        <input type="hidden" name="dich_vu_id[]" value="<?= $s['dich_vu_id'] ?>">
                    <?php endforeach; ?>

                </div>


            </div>
            <div class="section">
                <label>Thuốc đã kê</label>

                <?php foreach ($thuocs as $t): ?>
                    <div class="thuoc-item" data-gia="<?= $t['gia_nhap'] ?>" data-soluong="<?= $t['so_luong'] ?>">
                        <?= $t['ten_thuoc'] ?>
                        (<?= $t['so_luong'] ?> x <?= number_format($t['gia_nhap']) ?>đ)
                    </div>

                    <input type="hidden" name="thuoc_id[]" value="<?= $t['id'] ?>">
                    <input type="hidden" name="so_luong[]" value="<?= $t['so_luong'] ?>">
                <?php endforeach; ?>

            </div>
            <div class="section">
                <label>Mã giảm giá</label>
                <div class="voucher-row">
                    <input type="text" id="voucherInput" placeholder="Nhập voucher">
                    <button type="button" onclick="applyVoucher()">Áp dụng</button>
                </div>
                <input type="hidden" name="voucher" id="voucherHidden">
            </div>

            <div class="summary-box">
                <div><span>Tổng</span><b id="tongTien">0</b></div>
                <div><span>Giảm</span><b id="giamGia">0</b></div>
                <div class="total"><span>Thanh toán</span><b id="thanhTien">0</b></div>
            </div>

            <table class="bill-table">
                <thead>
                    <tr>
                        <th>Dịch vụ</th>
                        <th>Giá</th>
                    </tr>
                </thead>
                <tbody id="billBody"></tbody>
            </table>

            <button class="btn-submit">Xác nhận hóa đơn</button>
        </form>
    </div>
</div>

<div id="qr_modal" class="modal-overlay">
    <div class="modal-content">
        <h3>Quét VietQR</h3>
        <img src="" alt="QR">
        <div class="price-badge"></div>
        <button onclick="closeQR()">Đóng</button>
    </div>
</div>



<script>
    const billBody = document.getElementById('billBody');

    function getTotal() {
        let total = 0;

        // ===== DỊCH VỤ =====
        document.querySelectorAll('.service-item').forEach(item => {
            total += Number(item.dataset.gia || 0);
        });

        // ===== THUỐC =====
        document.querySelectorAll('.thuoc-item').forEach(item => {
            let gia = Number(item.dataset.gia || 0);
            let sl = Number(item.dataset.soluong || 0);
            total += gia * sl;
        });

        return total;
    }

    function calcBill() {
        let total = 0;
        billBody.innerHTML = '';

        // ===== DỊCH VỤ =====
        document.querySelectorAll('.service-item').forEach(item => {
            let gia = Number(item.dataset.gia || 0);
            total += gia;

            let tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${item.innerText}</td>
                <td>${gia.toLocaleString()}đ</td>
            `;
            billBody.appendChild(tr);
        });

        // ===== THUỐC =====
        document.querySelectorAll('.thuoc-item').forEach(item => {
            let gia = Number(item.dataset.gia || 0);
            let sl = Number(item.dataset.soluong || 0);
            let thanhTien = gia * sl;
            total += thanhTien;

            let tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${item.innerText}</td>
                <td>${thanhTien.toLocaleString()}đ</td>
            `;
            billBody.appendChild(tr);
        });

        document.getElementById('tongTien').innerText = total.toLocaleString() + 'đ';
        document.getElementById('thanhTien').innerText = total.toLocaleString() + 'đ';
    }

    function resetVoucher() {
        document.getElementById('voucherHidden').value = '';
        document.getElementById('voucherInput').value = '';
        document.getElementById('giamGia').innerText = 0;
    }

    function applyVoucher() {
        let code = document.getElementById('voucherInput').value;
        let tong = getTotal();

        fetch('admin.php?admin=checkVoucher', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `code=${code}&tong=${tong}`
        })
            .then(res => res.json())
            .then(data => {
                if (!data.status) {
                    alert(data.msg);
                    return;
                }

                document.getElementById('giamGia').innerText = data.giam.toLocaleString() + 'đ';
                document.getElementById('thanhTien').innerText = data.thanh_tien.toLocaleString() + 'đ';
                document.getElementById('voucherHidden').value = code;
            });
    }

    calcBill();
</script>