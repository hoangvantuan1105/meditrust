<div class="container-fluid mt-4">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Thêm lễ tân mới</h1>
        <a href="admin.php?admin=listTaiKhoanLeTan" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Biểu mẫu thêm lễ tân</h6>
        </div>
        <div class="card-body">

            <?php if (!empty($_SESSION['errors'])): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($_SESSION['errors'] as $field => $msg): ?>
                            <li><?= htmlspecialchars($msg) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php unset($_SESSION['errors']); ?>
            <?php endif; ?>

            <form method="POST" action="admin.php?admin=themTaiKhoanLeTan" novalidate>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="ten_le_tan">Họ tên lễ tân <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="ten_le_tan" name="ten_le_tan"
                            value="<?= htmlspecialchars($_SESSION['old']['ten_le_tan'] ?? '') ?>"
                            placeholder="Nhập họ và tên" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="sdt">Số điện thoại <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="sdt" name="sdt"
                            value="<?= htmlspecialchars($_SESSION['old']['sdt'] ?? '') ?>"
                            placeholder="Nhập số điện thoại" required>
                        <?php if (!empty($_SESSION['errors']['sdt'])): ?>
                            <small class="text-danger"><?= htmlspecialchars($_SESSION['errors']['sdt']) ?></small>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?= htmlspecialchars($_SESSION['old']['email'] ?? '') ?>"
                            placeholder="Nhập email" required>
                        <?php if (!empty($_SESSION['errors']['email'])): ?>
                            <small class="text-danger"><?= htmlspecialchars($_SESSION['errors']['email']) ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="gioi_tinh">Giới tính <span class="text-danger">*</span></label>
                        <select class="form-control" id="gioi_tinh" name="gioi_tinh" required>
                            <option value="">-- Chọn giới tính --</option>
                            <option value="nam" <?= (($_SESSION['old']['gioi_tinh'] ?? '') === 'nam') ? 'selected' : '' ?>>Nam</option>
                            <option value="nu"  <?= (($_SESSION['old']['gioi_tinh'] ?? '') === 'nu')  ? 'selected' : '' ?>>Nữ</option>
                            <option value="khac" <?= (($_SESSION['old']['gioi_tinh'] ?? '') === 'khac') ? 'selected' : '' ?>>Khác</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="ca_lam">Ca làm <span class="text-danger">*</span></label>
                        <select class="form-control" id="ca_lam" name="ca_lam" required>
                            <option value="">-- Chọn ca làm --</option>
                            <option value="sang"  <?= (($_SESSION['old']['ca_lam'] ?? '') === 'sang')  ? 'selected' : '' ?>>Sáng</option>
                            <option value="chieu" <?= (($_SESSION['old']['ca_lam'] ?? '') === 'chieu') ? 'selected' : '' ?>>Chiều</option>
                            <option value="full"  <?= (($_SESSION['old']['ca_lam'] ?? '') === 'full')  ? 'selected' : '' ?>>Full ngày</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="trang_thai">Trạng thái <span class="text-danger">*</span></label>
                        <select class="form-control" id="trang_thai" name="trang_thai" required>
                            <option value="">-- Chọn trạng thái --</option>
                            <option value="dang_lam" <?= (($_SESSION['old']['trang_thai'] ?? '') === 'dang_lam') ? 'selected' : '' ?>>Đang làm việc</option>
                            <option value="nghi"     <?= (($_SESSION['old']['trang_thai'] ?? '') === 'nghi')     ? 'selected' : '' ?>>Tạm nghỉ</option>
                        </select>
                    </div>
                </div>

                <?php unset($_SESSION['old']); ?>

                <hr>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu lễ tân
                    </button>
                    <a href="admin.php?admin=listTaiKhoanLeTan" class="btn btn-secondary ml-2">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                </div>
            </form>

        </div>
    </div>
</div>
