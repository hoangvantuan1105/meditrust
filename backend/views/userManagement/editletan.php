<div class="container-fluid mt-4">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Sửa thông tin lễ tân</h1>
        <a href="admin.php?admin=listTaiKhoanLeTan" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Cập nhật thông tin lễ tân</h6>
        </div>
        <div class="card-body">

            <?php if (!empty($_SESSION['errors'])): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($_SESSION['errors'] as $msg): ?>
                            <li><?= htmlspecialchars($msg) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php unset($_SESSION['errors']); ?>
            <?php endif; ?>

            <?php
            $old = $_SESSION['old'] ?? null;
            $ten   = $old['ten_le_tan'] ?? $leTan['ten_le_tan'] ?? '';
            $sdt   = $old['sdt']        ?? $leTan['sdt']        ?? '';
            $email = $old['email']      ?? $leTan['email']      ?? '';
            $gt    = $old['gioi_tinh']  ?? $leTan['gioi_tinh']  ?? '';
            $ca    = $old['ca_lam']     ?? $leTan['ca_lam']     ?? '';
            $tt    = $old['trang_thai'] ?? $leTan['trang_thai'] ?? '';
            unset($_SESSION['old']);
            ?>

            <form method="POST" action="admin.php?admin=suaTaiKhoanLeTan" novalidate>
                <input type="hidden" name="id" value="<?= htmlspecialchars($leTan['id']) ?>">

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="ten_le_tan">Họ tên lễ tân <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="ten_le_tan" name="ten_le_tan"
                            value="<?= htmlspecialchars($ten) ?>" placeholder="Nhập họ và tên" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="sdt">Số điện thoại <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="sdt" name="sdt"
                            value="<?= htmlspecialchars($sdt) ?>" placeholder="Nhập số điện thoại" required>
                        <?php if (!empty($_SESSION['errors']['sdt'])): ?>
                            <small class="text-danger"><?= htmlspecialchars($_SESSION['errors']['sdt']) ?></small>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?= htmlspecialchars($email) ?>" placeholder="Nhập email" required>
                        <?php if (!empty($_SESSION['errors']['email'])): ?>
                            <small class="text-danger"><?= htmlspecialchars($_SESSION['errors']['email']) ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="gioi_tinh">Giới tính <span class="text-danger">*</span></label>
                        <select class="form-control" id="gioi_tinh" name="gioi_tinh" required>
                            <option value="">-- Chọn giới tính --</option>
                            <option value="nam"  <?= $gt === 'nam'  ? 'selected' : '' ?>>Nam</option>
                            <option value="nu"   <?= $gt === 'nu'   ? 'selected' : '' ?>>Nữ</option>
                            <option value="khac" <?= $gt === 'khac' ? 'selected' : '' ?>>Khác</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="ca_lam">Ca làm <span class="text-danger">*</span></label>
                        <select class="form-control" id="ca_lam" name="ca_lam" required>
                            <option value="">-- Chọn ca làm --</option>
                            <option value="sang"  <?= $ca === 'sang'  ? 'selected' : '' ?>>Sáng</option>
                            <option value="chieu" <?= $ca === 'chieu' ? 'selected' : '' ?>>Chiều</option>
                            <option value="full"  <?= $ca === 'full'  ? 'selected' : '' ?>>Full ngày</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="trang_thai">Trạng thái <span class="text-danger">*</span></label>
                        <select class="form-control" id="trang_thai" name="trang_thai" required>
                            <option value="">-- Chọn trạng thái --</option>
                            <option value="dang_lam" <?= $tt === 'dang_lam' ? 'selected' : '' ?>>Đang làm việc</option>
                            <option value="nghi"     <?= $tt === 'nghi'     ? 'selected' : '' ?>>Tạm nghỉ</option>
                        </select>
                    </div>
                </div>

                <hr>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Cập nhật
                    </button>
                    <a href="admin.php?admin=listTaiKhoanLeTan" class="btn btn-secondary ml-2">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                </div>
            </form>

        </div>
    </div>
</div>
