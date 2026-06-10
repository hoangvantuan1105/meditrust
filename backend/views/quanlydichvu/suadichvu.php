<div class="container-fluid mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">✏️ Sửa dịch vụ nha khoa</h5>
        </div>

        <div class="card-body">
            <form method="POST" action="admin.php?admin=capNhatDichVu&idAdmin=<?= $dichvu['id'] ?>"
                enctype="multipart/form-data">

                <!-- Tên dịch vụ -->
                <div class="row mb-3">
                    <label class="col-md-4 col-form-label fw-bold">
                        Tên dịch vụ <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-8">
                        <input type="text" name="ten_dich_vu" class="form-control"
                            value="<?= htmlspecialchars($dichvu['ten_dich_vu'] ?? '') ?>" required>
                    </div>
                </div>

                <!-- Loại dịch vụ -->
                <div class="row mb-3">
                    <label class="col-md-4 col-form-label fw-bold">
                        Loại danh mục <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-8">
                        <select name="danhmuc" class="form-select" required>
                            <option value="">-- Chọn loại danh mục --</option>
                            <option value="kham va tu van" <?= ($dichvu['danhmuc'] == 'kham va tu van') ? 'selected' : '' ?>>Khám và tư vấn</option>
                            <option value="dieu tri tong quat" <?= ($dichvu['danhmuc'] == 'dieu tri tong quat') ? 'selected' : '' ?>>Điều trị tổng quát</option>
                            <option value="rang su va phuc hinh" <?= ($dichvu['danhmuc'] == 'rang su va phuc hinh') ? 'selected' : '' ?>>Răng sứ phục hình</option>
                            <option value="cay ghep Implant" <?= ($dichvu['danhmuc'] == 'cay ghep Implant') ? 'selected' : '' ?>>Cấy ghép Implant</option>
                            <option value="chinh nha nieng rang" <?= ($dichvu['danhmuc'] == 'chinh nha nieng rang') ? 'selected' : '' ?>>Chỉnh nha niềng răng</option>
                            <option value="tham my nha khoa" <?= ($dichvu['danhmuc'] == 'tham my nha khoa') ? 'selected' : '' ?>>Thẩm mỹ nha khoa</option>

                            <option value="nha khoa tre em" <?= ($dichvu['danhmuc'] == 'nha khoa tre em') ? 'selected' : '' ?>>Nha khoa trẻ em</option>
                            <option value="dich vu can lam sang" <?= ($dichvu['danhmuc'] == 'dich vu can lam sang') ? 'selected' : '' ?>>Dịch vụ lân cận lâm sàng</option>
                        </select>
                    </div>
                </div>

                <!-- Giá -->
                <div class="row mb-3">
                    <label class="col-md-4 col-form-label fw-bold">
                        Giá công khám (VNĐ)
                    </label>
                    <div class="col-md-8">
                        <!-- Trừ tiền vật tư ra để hiển thị giá công khám gốc -->
                        <input type="number" name="gia" class="form-control" min="0" max="1000000000"
                            value="<?= (int) (($dichvu['gia'] ?? 0) - ($tongTienVatTu ?? 0)) ?>" required
                            oninput="if(this.value < 0) { alert('Giá dịch vụ không được nhỏ hơn 0!'); this.value = 0; }">
                        <small class="text-muted">Nhập giá công khám. Hệ thống sẽ tự động cộng thêm
                            <?= number_format($tongTienVatTu ?? 0) ?>đ tiền vật tư vào tổng giá.</small>
                    </div>
                </div>
                <!-- Ảnh hiện tại -->
                <div class="row mb-3">
                    <label class="col-md-4 col-form-label fw-bold">
                        Ảnh hiện tại
                    </label>
                    <div class="col-md-8">
                        <?php if (!empty($dichvu['image'])): ?>
                            <img src="backend/uploads/services/<?= htmlspecialchars($dichvu['image']) ?>" width="120"
                                class="img-thumbnail mb-2">
                        <?php else: ?>
                            <div class="text-muted">Chưa có ảnh</div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Đổi ảnh -->
                <div class="row mb-3">
                    <label class="col-md-4 col-form-label fw-bold">
                        Đổi ảnh (tuỳ chọn)
                    </label>
                    <div class="col-md-8">
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                </div>

                <!-- Mô tả -->
                <div class="row mb-3">
                    <label class="col-md-4 col-form-label fw-bold">
                        Mô tả
                    </label>
                    <div class="col-md-8">
                        <textarea name="mo_ta" class="form-control"
                            rows="4"><?= htmlspecialchars($dichvu['mo_ta'] ?? '') ?></textarea>
                    </div>
                </div>

                <!-- ===== VẬT TƯ ===== -->
                <div class="row mb-3">
                    <label class="col-md-4 col-form-label fw-bold">Quản lý vật tư</label>
                    <div class="col-md-8">
                        <a href="admin.php?admin=vattusua&idAdmin=<?= $dichvu['id'] ?>" target="_blank" rel="opener"
                            class="btn btn-info">
                            <i class="fas fa-box-open"></i> Sửa vật tư
                        </a>
                        <small class="d-block mt-2 text-muted">Bấm vào để quản lý vật tư trong một tab mới.</small>

                        <!-- Bảng hiển thị vật tư đã chọn -->
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-hover" id="table-selected-materials">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tên vật tư</th>
                                        <th>Đơn vị</th>
                                        <th>Giá nhập</th>
                                        <th style="width: 120px;">Số lượng</th>
                                        <th>Thành tiền</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $tongTienVatTu = 0;
                                    if (!empty($vatTuDaChon)):
                                        foreach ($vatTuDaChon as $vt):
                                            $thanhTien = $vt['gia_nhap'] * $vt['so_luong'];
                                            $tongTienVatTu += $thanhTien;
                                            ?>
                                            <tr data-id="<?= $vt['id'] ?>">
                                                <td>
                                                    <?= htmlspecialchars($vt['ten_vat_tu']) ?>
                                                    <input type="hidden" name="vat_tu[<?= $vt['id'] ?>]"
                                                        value="<?= $vt['so_luong'] ?>" class="input-qty-hidden">
                                                </td>
                                                <td><?= htmlspecialchars($vt['don_vi']) ?></td>
                                                <td data-price="<?= $vt['gia_nhap'] ?>">
                                                    <?= number_format($vt['gia_nhap'], 0, ',', '.') ?> đ</td>


                                                <td class="text-center">
                                                    <?= (int) $vt['so_luong'] ?>
                                                </td>


                                                <td class="row-total"><?= number_format($thanhTien, 0, ',', '.') ?> đ</td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="removeRow(this)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; endif; ?>

                                    <tr id="no-material-row" style="<?= !empty($vatTuDaChon) ? 'display:none' : '' ?>">
                                        <td colspan="6" class="text-center text-muted">Chưa có vật tư nào được chọn</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-end fw-bold">Tổng tiền vật tư:</td>
                                        <td colspan="2" class="fw-bold text-danger" id="total-materials-price">
                                            <?= number_format($tongTienVatTu, 0, ',', '.') ?> đ</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <script>
                            window.receiveMaterials = function (materials) {
                                const tbody = document.querySelector('#table-selected-materials tbody');

                                // Xóa tất cả các dòng dữ liệu cũ (trừ dòng thông báo 'chưa có vật tư')
                                const existingRows = tbody.querySelectorAll('tr[data-id]');
                                existingRows.forEach(row => row.remove());

                                const noRow = document.getElementById('no-material-row');

                                if (materials.length === 0) {
                                    if (noRow) noRow.style.display = '';
                                    updateTotal();
                                    return;
                                }

                                if (noRow) noRow.style.display = 'none';

                                materials.forEach(m => {
                                    const tr = document.createElement('tr');
                                    tr.setAttribute('data-id', m.id);
                                    const formattedPrice = new Intl.NumberFormat('vi-VN').format(m.gia_nhap);
                                    const totalPrice = new Intl.NumberFormat('vi-VN').format(m.gia_nhap * m.so_luong);
                                    tr.innerHTML = `<td>${m.ten_vat_tu}<input type="hidden" name="vat_tu[${m.id}]" value="${m.so_luong}" class="input-qty-hidden"></td><td>${m.don_vi}</td><td data-price="${m.gia_nhap}">${formattedPrice} đ</td><td><input type="number" class="form-control input-qty" value="${m.so_luong}" readonly></td><td class="row-total">${totalPrice} đ</td><td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="fas fa-trash"></i></button></td>`;
                                    tbody.appendChild(tr);
                                });
                                updateTotal();
                            };

                            function updateRow(input) {
                                const row = input.closest('tr');
                                row.querySelector('.input-qty-hidden').value = input.value;
                                updateRowPrice(row);
                                updateTotal();
                            }
                            function updateRowPrice(row) {
                                const price = parseInt(row.querySelector('td[data-price]').dataset.price);
                                const qty = parseInt(row.querySelector('.input-qty').value);
                                row.querySelector('.row-total').innerText = new Intl.NumberFormat('vi-VN').format(price * qty) + ' đ';
                            }
                            function removeRow(btn) { btn.closest('tr').remove(); updateTotal(); }
                            function updateTotal() {
                                let total = 0;
                                document.querySelectorAll('#table-selected-materials tbody tr').forEach(row => {
                                    if (row.id === 'no-material-row' || row.style.display === 'none') return;
                                    const price = parseInt(row.querySelector('td[data-price]').dataset.price);
                                    const qty = parseInt(row.querySelector('.input-qty').value);
                                    total += price * qty;
                                });
                                document.getElementById('total-materials-price').innerText = new Intl.NumberFormat('vi-VN').format(total) + ' đ';
                            }
                        </script>
                    </div>
                </div>

                <!-- Nút -->
                <div class="row">
                    <div class="col-md-12 text-end">
                        <a href="admin.php?admin=qlydichvu" class="btn btn-secondary px-4 me-2">
                            ❌ Hủy
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            💾 Lưu thay đổi
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>