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

        <form id="invoiceForm" method="post" action="admin.php?admin=createInvoice">

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

<!-- Modal QR SePay -->
<div id="sepayModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:16px;padding:30px;text-align:center;max-width:420px;width:92%;box-shadow:0 20px 60px rgba(0,0,0,.3);">
        <h3 style="margin:0 0 6px;color:#1e293b;">Quét mã để thanh toán</h3>
        <p style="color:#64748b;font-size:13px;margin:0 0 14px;">Mở app ngân hàng và quét mã QR bên dưới</p>

        <img id="sepayQrImg" src="" alt="QR"
             style="width:220px;height:220px;border:1px solid #e2e8f0;border-radius:10px;object-fit:contain;">

        <div style="margin:12px 0 6px;font-size:24px;font-weight:700;color:#4f46e5;" id="sepayAmount"></div>

        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:8px 12px;margin-bottom:14px;">
            <div style="font-size:11px;color:#94a3b8;margin-bottom:2px;">Nội dung chuyển khoản</div>
            <div id="sepayContent" style="font-family:monospace;font-weight:600;color:#0f172a;letter-spacing:1px;"></div>
        </div>

        <div id="sepaySpinner" style="color:#64748b;font-size:13px;margin-bottom:10px;">
            <svg style="animation:spin 1s linear infinite;width:16px;vertical-align:middle;margin-right:4px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
            Đang chờ thanh toán...
        </div>
        <div id="sepaySuccess" style="display:none;color:#16a34a;font-weight:600;font-size:15px;margin-bottom:10px;">
            ✓ Thanh toán thành công! Đang chuyển hướng...
        </div>

        <button onclick="cancelSepay()"
                style="background:#6b7280;color:#fff;border:none;padding:8px 22px;border-radius:8px;cursor:pointer;font-size:13px;">
            Hủy
        </button>
    </div>
</div>

<style>
@keyframes spin { to { transform: rotate(360deg); } }
</style>

<script>
    const billBody = document.getElementById('billBody');
    let pollTimer = null;

    function getTotal() {
        let total = 0;
        document.querySelectorAll('.service-item').forEach(item => {
            total += Number(item.dataset.gia || 0);
        });
        document.querySelectorAll('.thuoc-item').forEach(item => {
            total += Number(item.dataset.gia || 0) * Number(item.dataset.soluong || 0);
        });
        return total;
    }

    function calcBill() {
        let total = 0;
        billBody.innerHTML = '';

        document.querySelectorAll('.service-item').forEach(item => {
            let gia = Number(item.dataset.gia || 0);
            total += gia;
            let tr = document.createElement('tr');
            tr.innerHTML = `<td>${item.innerText}</td><td>${gia.toLocaleString()}đ</td>`;
            billBody.appendChild(tr);
        });

        document.querySelectorAll('.thuoc-item').forEach(item => {
            let gia = Number(item.dataset.gia || 0);
            let sl  = Number(item.dataset.soluong || 0);
            let tt  = gia * sl;
            total += tt;
            let tr = document.createElement('tr');
            tr.innerHTML = `<td>${item.innerText}</td><td>${tt.toLocaleString()}đ</td>`;
            billBody.appendChild(tr);
        });

        document.getElementById('tongTien').innerText  = total.toLocaleString() + 'đ';
        document.getElementById('thanhTien').innerText = total.toLocaleString() + 'đ';
    }

    function resetVoucher() {
        document.getElementById('voucherHidden').value = '';
        document.getElementById('voucherInput').value  = '';
        document.getElementById('giamGia').innerText   = 0;
    }

    function applyVoucher() {
        let code = document.getElementById('voucherInput').value;
        let tong = getTotal();

        fetch('admin.php?admin=checkVoucher', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `code=${code}&tong=${tong}`
        })
        .then(r => r.json())
        .then(data => {
            if (!data.status) { alert(data.msg); return; }
            document.getElementById('giamGia').innerText  = data.giam.toLocaleString() + 'đ';
            document.getElementById('thanhTien').innerText = data.thanh_tien.toLocaleString() + 'đ';
            document.getElementById('voucherHidden').value = code;
        });
    }

    // ===== SePay =====
    const form = document.getElementById('invoiceForm');
    const btn  = form.querySelector('.btn-submit');

    // Cập nhật label nút theo phương thức
    document.querySelectorAll('input[name="phuong_thuc_tt"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'Chuyen khoan') {
                btn.textContent = 'Tạo QR & Thanh toán';
            } else {
                btn.textContent = 'Xác nhận hóa đơn';
                delete form.dataset.submitting;
                delete form.dataset.hoaDonId;
                delete form.dataset.thanhTien;
            }
        });
    });

    // Vẫn giữ submit listener cho trường hợp user nhấn nút
    form.addEventListener('submit', function(e) {
        const method = document.querySelector('input[name="phuong_thuc_tt"]:checked').value;
        if (method !== 'Chuyen khoan') return;

        e.preventDefault();
        if (!form.dataset.submitting) submitBankTransfer(form);
    });

    function submitBankTransfer(f) {
        if (f.dataset.submitting) {
            // Đã có invoice → mở lại modal
            if (f.dataset.hoaDonId) {
                showSepayQR(parseInt(f.dataset.hoaDonId), parseInt(f.dataset.thanhTien));
            }
            return;
        }

        f.dataset.submitting = 'true';
        btn.disabled    = true;
        btn.textContent = 'Đang tạo hóa đơn...';

        fetch('admin.php?admin=createInvoice', { method: 'POST', body: new FormData(f) })
            .then(r => r.json())
            .then(data => {
                btn.disabled    = false;
                btn.textContent = 'Tạo QR & Thanh toán';

                if (!data.ok) {
                    delete f.dataset.submitting;
                    alert('Lỗi: ' + (data.message || 'Không tạo được hóa đơn'));
                    return;
                }

                // Lưu lại để tránh tạo lại khi nhấn nút lần 2
                f.dataset.hoaDonId  = data.hoa_don_id;
                f.dataset.thanhTien = data.thanh_tien;

                showSepayQR(data.hoa_don_id, data.thanh_tien);
            })
            .catch(() => {
                delete f.dataset.submitting;
                btn.disabled    = false;
                btn.textContent = 'Tạo QR & Thanh toán';
                alert('Lỗi kết nối, vui lòng thử lại.');
            });
    }

    function showSepayQR(hoaDonId, thanhTien) {
        const prefix  = '<?= SEPAY_PREFIX ?>';
        const bank    = '<?= SEPAY_BANK_CODE ?>';
        const account = '<?= SEPAY_ACCOUNT_NUMBER ?>';
        const name    = encodeURIComponent('<?= SEPAY_ACCOUNT_NAME ?>');
        const content = prefix + hoaDonId;
        const qrUrl   = `https://img.vietqr.io/image/${bank}-${account}-compact2.png`
                      + `?amount=${thanhTien}&addInfo=${content}&accountName=${name}`;

        document.getElementById('sepayQrImg').src    = qrUrl;
        document.getElementById('sepayAmount').textContent = thanhTien.toLocaleString('vi-VN') + ' đ';
        document.getElementById('sepayContent').textContent = content;

        document.getElementById('sepaySpinner').style.display = 'block';
        document.getElementById('sepaySuccess').style.display = 'none';
        document.getElementById('sepayModal').style.display   = 'flex';

        // Poll mỗi 3 giây
        pollTimer = setInterval(() => pollPayment(hoaDonId), 3000);
    }

    function pollPayment(hoaDonId) {
        fetch(`admin.php?admin=checkPaymentStatus&id=${hoaDonId}`)
            .then(r => r.json())
            .then(data => {
                if (data.paid) {
                    clearInterval(pollTimer);
                    document.getElementById('sepaySpinner').style.display = 'none';
                    document.getElementById('sepaySuccess').style.display = 'block';
                    setTimeout(() => { window.location.href = 'admin.php?admin=getAllOrder'; }, 1500);
                }
            });
    }

    function cancelSepay() {
        clearInterval(pollTimer);
        document.getElementById('sepayModal').style.display = 'none';
    }

    calcBill();
</script>