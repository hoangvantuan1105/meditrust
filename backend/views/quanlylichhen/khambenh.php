<div class="container-fluid mt-4">
    <div class="row">

        <!-- ========== CỘT TRÁI ========== -->
        <div class="col-md-7">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="m-0 font-weight-bold text-primary">Bắt đầu khám bệnh</h5>
                </div>
                <div class="card-body">

                    <form method="POST" action="admin.php?admin=luuKetQuaKham&id=<?= $_GET['id'] ?>">

                        <input type="hidden" name="lich_kham_id"
                            value="<?php echo isset($_GET['id']) ? (int) $_GET['id'] : ''; ?>">
                        <!-- ===== Dịch vụ ===== -->
                        <div class="card p-3 mt-3">
                            <h6 class="text-primary">➕ Thêm dịch vụ</h6>

                            <div class="form-row align-items-end">
                                <div class="col-md-6">
                                    <label>Dịch vụ</label>
                                    <select id="dich_vu_id" class="form-control">
                                        <?php foreach ($listDV as $dv): ?>
                                            <option value="<?= $dv['id'] ?>" data-ten="<?= htmlspecialchars($dv['ten_dich_vu']) ?> " <?= ($dv['id'] == ($data['dich_vu_id'] ?? null)) ? 'selected' : '' ?>>
                                                <?= $dv['ten_dich_vu'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>


                                <div class="col-md-2">
                                    <button type="button" class="btn btn-success" onclick="themDichVu()">+</button>
                                </div>
                            </div>
                        </div>

                        <h6 class="mt-4 text-primary">📋 Danh sách dịch vụ</h6>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Dịch vụ</th>
                                    <th>Xóa</th>
                                </tr>
                            </thead>
                            <tbody id="gioDichVuBody"></tbody>
                        </table>

                        <div id="inputDichVuHidden"></div>

                        <!-- ===== Kết luận ===== -->
                        <div class="form-group">
                            <label>Kết luận</label>
                            <textarea name="ket_luan" class="form-control" rows="3"></textarea>
                        </div>

                        <!-- ===== Thêm thuốc ===== -->
                        <div class="card p-3 mt-3">
                            <h6 class="text-primary">➕ Kê đơn thuốc</h6>

                            <div class="form-row align-items-end">
                                <div class="col-md-4">
                                    <label>Thuốc</label>
                                    <select id="thuoc_id" class="form-control">
                                        <?php foreach ($listThuoc as $t): ?>
                                            <option value="<?= $t['id'] ?>"
                                                data-ten="<?= htmlspecialchars($t['ten_thuoc']) ?>">
                                                <?= $t['ten_thuoc'] ?> (<?= $t['ham_luong'] ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label>Liều dùng / ngày</label>
                                    <input id="lieu_dung" class="form-control" placeholder="VD: 2 viên/ngày">
                                </div>

                                <div class="col-md-2">
                                    <label>Thời điểm uống</label>
                                    <select id="thoi_diem_uong" class="form-control">
                                        <option value="sau_an">Sau ăn</option>
                                        <option value="truoc_an">Trước ăn</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label>Số vỉ</label>
                                    <input id="so_luong" type="number" class="form-control" min="1" value="1">
                                </div>

                                <div class="col-md-1">
                                    <button type="button" class="btn btn-success" onclick="themThuoc()">+</button>
                                </div>
                            </div>
                        </div>


                        <h6 class="mt-4 text-primary">🧾 Đơn thuốc</h6>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Thuốc</th>
                                    <th>Liều dùng/ngày</th>
                                    <th>Thời điểm uống</th>
                                    <th>Số vỉ</th>
                                    <th>Xóa</th>
                                </tr>
                            </thead>
                            <tbody id="gioThuocBody"></tbody>
                        </table>

                        <div id="inputThuocHidden"></div>
                        <button type="submit" class="btn btn-primary">💾 Lưu kết quả khám</button>
                        <a href="admin.php?admin=listLichKham" class="btn btn-secondary">Trở về</a>
                    </form>

                </div>
            </div>
        </div>

        <!-- ========== CỘT PHẢI ========== -->
        <div class="col-md-5">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="m-0 font-weight-bold text-info">Thông tin bệnh nhân</h5>
                </div>
                <div class="card-body">
                    <p><strong>Họ tên:</strong> <?= htmlspecialchars($data['ho_ten'] ?? '-') ?></p>
                    <p><strong>SĐT:</strong> <?= htmlspecialchars($data['so_dien_thoai'] ?? '-') ?></p>
                    <p><strong>Ngày sinh:</strong> <?= htmlspecialchars($data['ngay_sinh'] ?? '-') ?></p>
                    <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($data['dia_chi'] ?? '-') ?></p>

                    <hr>

                    <p><strong>Người liên hệ:</strong> <?= htmlspecialchars($data['nguoi_lien_he_khan_cap'] ?? '-') ?>
                    </p>
                    <p><strong>SĐT liên hệ:</strong> <?= htmlspecialchars($data['sdt_nguoi_lien_he'] ?? '-') ?></p>
                    <p><strong>Quan hệ:</strong> <?= htmlspecialchars($data['quan_he'] ?? '-') ?></p>

                    <hr>

                    <p><strong>Dịch vụ đã hẹn:</strong> <?= htmlspecialchars($data['ten_dich_vu'] ?? '-') ?></p>
                    <p><strong>Ngày khám:</strong>
                        <?= isset($data['ngay_kham']) ? date('d/m/Y H:i', strtotime($data['ngay_kham'])) : '-' ?></p>
                </div>

                <div class="card-footer">
                    <h6>Lịch sử khám bệnh:</h6>
                    <?php if (!empty($medicalHistory)): ?>
                        <ul class="list-group">
                            <?php foreach ($medicalHistory as $history): ?>
                                <li class="list-group-item">
                                <div>
                                    <strong>Ngày khám: </strong>
                                    <?= !empty($history['ngay_kham']) ? date('d/m/Y', strtotime($history['ngay_kham'])) : '' ?>
                                </div>
                                <div>
                                    <strong>Tên bác sĩ: </strong>
                                    <?= htmlspecialchars($history['ten_bac_si'] ?? 'Chưa cập nhật') ?>
                                </div>
                                <div>
                                    <strong>Chuẩn đoán: </strong>
                                    <?= !empty($history['chan_doan']) 
                                        ? htmlspecialchars($history['chan_doan']) 
                                        : '<span class="text-muted">Chưa cập nhật</span>' ?>
                                </div>


                                    <a href="admin.php?admin=chiTietLichSuKham&id=<?= $history['id'] ?>" class="btn btn-sm btn-info">Chi tiết</a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>Chưa có lịch sử khám bệnh.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    let gioThuoc = [];

    function themThuoc() {
        const select = document.getElementById('thuoc_id');
        const thuocId = select.value;
        const tenThuoc = select.options[select.selectedIndex].dataset.ten;
        const lieu = document.getElementById('lieu_dung').value;
        const thoiDiem = document.getElementById('thoi_diem_uong').value;
        const soLuong = document.getElementById('so_luong').value;

        if (!lieu || !soLuong) {
            alert("Vui lòng nhập đủ liều dùng và số lượng");
            return;
        }

        gioThuoc.push({
            thuocId,
            tenThuoc,
            lieu,
            thoiDiem,
            soLuong
        });
        renderGioThuoc();
    }

    function xoaThuoc(index) {
        gioThuoc.splice(index, 1);
        renderGioThuoc();
    }

    function renderGioThuoc() {
        const tbody = document.getElementById('gioThuocBody');
        const hidden = document.getElementById('inputThuocHidden');
        tbody.innerHTML = '';
        hidden.innerHTML = '';

        gioThuoc.forEach((t, index) => {
            tbody.innerHTML += `
                <tr>
                    <td>${t.tenThuoc}</td>
                    <td>${t.lieu}</td>
                    <td>${t.thoiDiem === 'sau_an' ? 'Sau ăn' : 'Trước ăn'}</td>
                    <td>${t.soLuong}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm"
                            onclick="xoaThuoc(${index})">Xóa</button>
                    </td>
                </tr>
            `;

            hidden.innerHTML += `
                <input type="hidden" name="thuoc[${index}][thuoc_id]" value="${t.thuocId}">
                <input type="hidden" name="thuoc[${index}][lieu_dung]" value="${t.lieu}">
                <input type="hidden" name="thuoc[${index}][thoi_diem_uong]" value="${t.thoiDiem}">
                <input type="hidden" name="thuoc[${index}][so_luong]" value="${t.soLuong}">
            `;
        });
    }
    let gioDichVu = [];

    function themDichVu() {
        const select = document.getElementById('dich_vu_id');
        const dichVuId = select.value;
        const tenDV = select.options[select.selectedIndex].dataset.ten;

        gioDichVu.push({
            dichVuId,
            tenDV
        });
        renderGioDichVu();
    }

    function xoaDichVu(index) {
        gioDichVu.splice(index, 1);
        renderGioDichVu();
    }

    function renderGioDichVu() {
        const tbody = document.getElementById('gioDichVuBody');
        const hidden = document.getElementById('inputDichVuHidden');
        tbody.innerHTML = '';
        hidden.innerHTML = '';

        gioDichVu.forEach((dv, index) => {
            tbody.innerHTML += `
            <tr>
                <td>${dv.tenDV}</td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm"
                        onclick="xoaDichVu(${index})">Xóa</button>
                </td>
            </tr>
        `;

            hidden.innerHTML += `
    <input type="hidden" name="dich_vu[${index}][dich_vu_id]" value="${dv.dichVuId}">
    <input type="hidden" name="dich_vu[${index}][ten_dich_vu]" value="${dv.tenDV}">
`;
        });
    }
</script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Chờ trang load xong mới quét URL
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Lấy query string từ thanh địa chỉ (ví dụ: ?status=out_of_stock)
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');
        const available = urlParams.get('available');

        // 2. Nếu có status thì mới gọi SweetAlert
        if (status) {
            if (status === 'error') {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Vui lòng nhập chẩn đoán, dịch vụ hoặc đơn thuốc trước khi lưu kết quả khám!',
                });
            }

            // 3. (Tùy chọn) Xóa cái tham số trên URL để khi F5 không bị hiện lại thông báo
            window.history.replaceState({}, document.title, window.location.pathname + "?admin=listLichKham");
        }
    });
</script>