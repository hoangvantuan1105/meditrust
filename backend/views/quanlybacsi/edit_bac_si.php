<div class="container-fluid mt-4">

    <!-- ===== HEADER ===== -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Sửa bác sĩ</h1>
    </div>

    <!-- ===== FORM ===== -->
    <div class="card shadow">
        <div class="card-body">

            <!-- ✅ thêm enctype -->
            <form method="post" action="admin.php?admin=suaBacSi" enctype="multipart/form-data" novalidate>
                <input type="hidden" name="id" value="<?= htmlspecialchars($bacSi['id'] ?? '') ?>">

                <div class="row">

                    <!-- Họ tên -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Họ tên bác sĩ</label>
                        <input type="text" name="ten_bac_si" class="form-control"
                            value="<?= htmlspecialchars($bacSi['ten_bac_si'] ?? '') ?>" required>
                    </div>

                    <!-- SĐT -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="sdt" class="form-control"
                            value="<?= htmlspecialchars($bacSi['sdt'] ?? '') ?>" required>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                            value="<?= htmlspecialchars($bacSi['email'] ?? '') ?>">
                    </div>

                    <!-- Chuyên môn -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Chuyên môn</label>
                        <input type="text" name="chuyen_mon" class="form-control"
                            value="<?= htmlspecialchars($bacSi['chuyen_mon'] ?? '') ?>">
                    </div>

                    <!-- Giới tính -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Giới tính</label>
                        <?php $gt = strtolower(trim($bacSi['gioi_tinh'] ?? '')); ?>
                        <select name="gioi_tinh" class="form-control" required>
                            <option value="">-- Chọn giới tính --</option>
                            <option value="nam" <?= $gt === 'nam' ? 'selected' : '' ?>>Nam</option>
                            <option value="nu" <?= $gt === 'nu' ? 'selected' : '' ?>>Nữ</option>
                            <option value="khac" <?= $gt === 'khac' ? 'selected' : '' ?>>Khác</option>
                        </select>
                    </div>

                    <!-- Ca làm -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Ca làm</label>
                        <?php $ca = strtolower(trim($bacSi['ca_lam'] ?? '')); ?>
                        <select name="ca_lam" class="form-control" required>
                            <option value="">-- Chọn ca làm --</option>
                            <option value="sang" <?= $ca === 'sang' ? 'selected' : '' ?>>Sáng</option>
                            <option value="chieu" <?= $ca === 'chieu' ? 'selected' : '' ?>>Chiều</option>
                            <option value="full" <?= $ca === 'full' ? 'selected' : '' ?>>Full</option>
                        </select>
                    </div>

                    <!-- Trạng thái -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Trạng thái</label>
                        <?php $tt = strtolower(trim($bacSi['trang_thai'] ?? '')); ?>
                        <select name="trang_thai" class="form-control" required>
                            <option value="">-- Chọn trạng thái --</option>
                            <option value="dang_lam" <?= $tt === 'dang_lam' ? 'selected' : '' ?>>Đang làm việc</option>
                            <option value="nghi" <?= $tt === 'nghi' ? 'selected' : '' ?>>Nghỉ</option>
                        </select>
                    </div>

                    <!-- Ngày tạo -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ngày tạo</label>
                        <input type="text" class="form-control"
                            value="<?= !empty($bacSi['ngay_tao']) ? date('d/m/Y H:i', strtotime($bacSi['ngay_tao'])) : '---' ?>"
                            readonly>
                    </div>

                    <!-- Ảnh bác sĩ -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ảnh bác sĩ</label><br>
                        <?php if (!empty($bacSi['photo_url'])): ?>
                            <img src="<?= htmlspecialchars($bacSi['photo_url']) ?>" alt="Ảnh bác sĩ"
                                width="100" height="100" style="object-fit:cover; border-radius:8px;">
                        <?php else: ?>
                            <span class="text-muted">Chưa có ảnh</span>
                        <?php endif; ?>
                        <input type="file" name="photo" class="form-control mt-2" accept="image/*">
                        <small class="text-muted">Chọn ảnh mới nếu muốn thay đổi</small>
                    </div>

                </div>

                <!-- BUTTON -->
                <div class="text-end">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-save me-1"></i> Cập nhật
                    </button>
                    <a href="admin.php?admin=qlybacsi" class="btn btn-secondary btn-sm">
                        ← Quay lại
                    </a>
                </div>

            </form>

        </div>
    </div>
</div>