<div class="container-fluid mt-4">

    <!-- ===== HEADER ===== -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Thêm dịch vụ nha khoa</h1>
    </div>

    <!-- ===== CARD ===== -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Cập nhật thông tin dịch vụ</h6>
        </div>

        <div class="card-body">
            <form action="admin.php?admin=addDich_vu" method="post" enctype="multipart/form-data">

                <!-- Tên dịch vụ -->
                <div class="form-group row mb-3">
                    <label class="col-md-4 col-form-label fw-bold">
                        Tên dịch vụ <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-8">
                        <input type="text" name="ten_dich_vu" class="form-control"
                            value="<?= htmlspecialchars($dichvu['ten_dich_vu'] ?? '') ?>" required>
                    </div>
                </div>

                <!-- Loại danh mục -->
                <div class="form-group row mb-3">
                    <label class="col-md-4 col-form-label fw-bold">
                        Loại danh mục <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-8">
                        <select name="danhmuc" class="form-select" required>
                            <option value="">-- Chọn loại danh mục --</option>
                            <option value="kham va tu van" <?= (($dichvu['danhmuc'] ?? '') == 'kham va tu van') ? 'selected' : '' ?>>Khám và tư vấn</option>
                            <option value="dieu tri tong quat" <?= (($dichvu['danhmuc'] ?? '') == 'dieu tri tong quat') ? 'selected' : '' ?>>Điều trị tổng quát</option>
                            <option value="rang su va phuc hinh" <?= (($dichvu['danhmuc'] ?? '') == 'rang su va phuc hinh') ? 'selected' : '' ?>>Răng sứ phục hình</option>
                            <option value="cay ghep Implant" <?= (($dichvu['danhmuc'] ?? '') == 'cay ghep Implant') ? 'selected' : '' ?>>Cấy ghép Implant</option>
                            <option value="chinh nha nieng rang" <?= (($dichvu['danhmuc'] ?? '') == 'chinh nha nieng rang') ? 'selected' : '' ?>>Chỉnh nha niềng răng</option>
                            <option value="tham my nha khoa" <?= (($dichvu['danhmuc'] ?? '') == 'tham my nha khoa') ? 'selected' : '' ?>>Thẩm mỹ nha khoa</option>
                            <option value="nha khoa tre em" <?= (($dichvu['danhmuc'] ?? '') == 'nha khoa tre em') ? 'selected' : '' ?>>Nha khoa trẻ em</option>
                            <option value="dich vu can lam sang" <?= (($dichvu['danhmuc'] ?? '') == 'dich vu can lam sang') ? 'selected' : '' ?>>Dịch vụ lân cận lâm sàng</option>
                        </select>

                    </div>
                </div>

                <!-- Giá -->
                <div class="form-group row mb-3">
                    <label class="col-md-4 col-form-label fw-bold">Giá công khám (VNĐ)</label>
                    <div class="col-md-8">
                        <input type="number" name="gia" class="form-control" min="0" max="1000000000"
                            value="<?= (int) ($dichvu['gia'] ?? 0) ?>" required
                            oninput="if(this.value < 0) { alert('Giá dịch vụ không được nhỏ hơn 0!'); this.value = 0; }">
                        <small class="text-muted">Nhập giá công khám. Giá lưu trong bảng dịch vụ sẽ là: giá công khám +
                            tổng tiền vật tư.</small>
                    </div>
                </div>

                <!-- Mô tả -->
                <div class="form-group row mb-3">
                    <label class="col-md-4 col-form-label fw-bold">Mô tả</label>
                    <div class="col-md-8">
                        <textarea name="mo_ta" class="form-control"
                            rows="4"><?= htmlspecialchars($dichvu['mo_ta'] ?? '') ?></textarea>
                    </div>
                </div>

                <!-- Hình ảnh -->
                <div class="form-group row mb-3">
                    <label class="col-md-4 col-form-label fw-bold">Hình ảnh dịch vụ</label>
                    <div class="col-md-8">
                        <input type="file" name="image" class="form-control" accept="image/*" id="preview-input">
                        <small class="text-muted">Định dạng: JPG, PNG, GIF (Tối đa 5MB)</small>
                        <?php if (!empty($dichvu['image'])): ?>
                            <div class="mt-3">
                                <img src="uploads/services/<?= htmlspecialchars($dichvu['image']) ?>" alt="Ảnh dịch vụ"
                                    style="max-width: 300px; border-radius: 5px;">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quản lý vật tư -->
                <div class="form-group row mb-3">
                    <label class="col-md-4 col-form-label fw-bold">Quản lý vật tư</label>
                    <div class="col-md-8">
                        <a href="admin.php?admin=vattuthem" target="_blank" rel="opener" class="btn btn-info mb-3">
                            <i class="fas fa-box-open"></i> Thêm vật tư
                        </a>
                        <small class="d-block mt-2 text-muted">
                            Bấm vào để quản lý vật tư trong một tab mới.
                        </small>

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
                                    <tr id="no-material-row">
                                        <td colspan="6" class="text-center text-muted">Chưa có vật tư nào được chọn</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-end fw-bold">Tổng tiền vật tư:</td>
                                        <td colspan="2" class="fw-bold text-danger" id="total-materials-price">0 đ</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <script>
                            // Hàm nhận dữ liệu từ cửa sổ popup (vattuthem.php)
                            window.receiveMaterials = function (materials) {
                                const tbody = document.querySelector('#table-selected-materials tbody');
                                const noRow = document.getElementById('no-material-row');

                                if (noRow) noRow.remove();

                                materials.forEach(m => {
                                    // Kiểm tra xem vật tư đã có trong bảng chưa
                                    let existsRow = document.querySelector(`tr[data-id="${m.id}"]`);

                                    if (existsRow) {
                                        // Nếu có rồi thì cộng dồn số lượng
                                        const inputQty = existsRow.querySelector('.input-qty');
                                        const hiddenQty = existsRow.querySelector('.input-qty-hidden');

                                        let newQty = parseInt(inputQty.value) + parseInt(m.so_luong);
                                        inputQty.value = newQty;
                                        hiddenQty.value = newQty;

                                        updateRowPrice(existsRow);
                                    } else {
                                        // Nếu chưa có thì thêm dòng mới
                                        const tr = document.createElement('tr');
                                        tr.setAttribute('data-id', m.id);

                                        // Format giá tiền
                                        const formattedPrice = new Intl.NumberFormat('vi-VN').format(m.gia_nhap);
                                        const totalPrice = new Intl.NumberFormat('vi-VN').format(m.gia_nhap * m.so_luong);

                                        tr.innerHTML = `
                                            <td>
                                                ${m.ten_vat_tu}
                                                <input type="hidden" name="vat_tu[${m.id}]" value="${m.so_luong}" class="input-qty-hidden">
                                            </td>
                                            <td>${m.don_vi}</td>
                                            <td data-price="${m.gia_nhap}">${formattedPrice} đ</td>
                                            <td>
                                                <input type="number" class="form-control input-qty" value="${m.so_luong}" min="1" onchange="updateRow(this)" disabled>
                                            </td>
                                            <td class="row-total">${totalPrice} đ</td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        `;
                                        tbody.appendChild(tr);
                                    }
                                });
                                updateTotal();
                            };

                            function updateRow(input) {
                                const row = input.closest('tr');
                                const hidden = row.querySelector('.input-qty-hidden');
                                const val = parseInt(input.value);

                                if (val < 1) {
                                    input.value = 1;
                                    hidden.value = 1;
                                } else {
                                    hidden.value = val;
                                }

                                updateRowPrice(row);
                                updateTotal();
                            }

                            function updateRowPrice(row) {
                                const price = parseInt(row.querySelector('td[data-price]').dataset.price);
                                const qty = parseInt(row.querySelector('.input-qty').value);
                                const total = price * qty;
                                row.querySelector('.row-total').innerText = new Intl.NumberFormat('vi-VN').format(total) + ' đ';
                            }

                            function removeRow(btn) {
                                btn.closest('tr').remove();
                                // Nếu hết dòng thì hiện lại thông báo
                                const tbody = document.querySelector('#table-selected-materials tbody');
                                if (tbody.children.length === 0) {
                                    tbody.innerHTML = `<tr id="no-material-row"><td colspan="6" class="text-center text-muted">Chưa có vật tư nào được chọn</td></tr>`;
                                }
                                updateTotal();
                            }

                            function updateTotal() {
                                let total = 0;
                                document.querySelectorAll('#table-selected-materials tbody tr').forEach(row => {
                                    if (row.id === 'no-material-row') return;
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
                <div class="row mt-3">
                    <div class="col-md-12 text-end">
                        <a href="admin.php?admin=qlydichvu" class="btn btn-secondary px-4 ms-2">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save"></i> Lưu thay đổi
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>