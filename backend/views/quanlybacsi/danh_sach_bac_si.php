<div class="container-fluid mt-4">

    <!-- ===== HEADER ===== -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý bác sĩ</h1>
        <a href="admin.php?admin=formThemBacSi" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm bác sĩ
        </a>
    </div>

    <!-- ===== TABLE ===== -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Danh sách bác sĩ
            </h6>
        </div>

        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle" id="dataTable">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th>ID</th>
                            <th>Ảnh</th>
                            <th>Họ tên</th>
                            <th>SĐT</th>
                            <th>Email</th>
                            <th>Chuyên môn</th>
                            <th>Giới tính</th>
                            <th>Ca làm</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($listBacSi)): ?>
                            <?php foreach ($listBacSi as $bs): ?>
                                <tr>
                                    <!-- ID -->
                                    <td class="text-center"><?= htmlspecialchars($bs['id']) ?></td>

                                    <!-- ảnh -->
                                    <td class="text-center">
                                        <?php if (!empty($bs['photo_url'])): ?>
                                            <img src="<?= htmlspecialchars($bs['photo_url']) ?>"
                                                alt="Ảnh bác sĩ"
                                                width="50" height="50"
                                                style="object-fit:cover; border-radius:50%;">
                                        <?php else: ?>
                                            <span class="text-muted">---</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Họ tên -->
                                    <td><?= htmlspecialchars($bs['ten_bac_si'] ?? '---') ?></td>

                                    <!-- SĐT -->
                                    <td class="text-center"><?= htmlspecialchars($bs['sdt'] ?? '---') ?></td>

                                    <!-- Email -->
                                    <td class="text-center"><?= htmlspecialchars($bs['email'] ?? '---') ?></td>

                                    <!-- Chuyên môn -->
                                    <td class="text-center"><?= htmlspecialchars($bs['chuyen_mon'] ?? '---') ?></td>

                                    <!-- Giới tính -->
                                    <td class="text-center">
                                        <?php
                                        $gt = $bs['gioi_tinh'] ?? '';
                                        if ($gt === 'nam') echo 'Nam';
                                        elseif ($gt === 'nu') echo 'Nữ';
                                        elseif ($gt === 'khac') echo 'Khác';
                                        else echo '---';
                                        ?>
                                    </td>

                                    <!-- Ca làm -->
                                    <td class="text-center"><?= htmlspecialchars($bs['ca_lam'] ?? '---') ?></td>

                                    <!-- Trạng thái -->
                                    <td class="text-center">
                                        <?php if (($bs['trang_thai'] ?? '') === 'dang_lam'): ?>
                                            <span class="badge badge-success px-3">Đang làm việc</span>
                                        <?php elseif (($bs['trang_thai'] ?? '') === 'nghi'): ?>
                                            <span class="badge badge-warning px-3">Tạm ngưng</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary px-3">Không hoạt động</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Ngày tạo -->
                                    <td class="text-center">
                                        <?= !empty($bs['ngay_tao']) ? date('d/m/Y H:i', strtotime($bs['ngay_tao'])) : '---' ?>
                                    </td>

                                    <!-- Thao tác -->
                                    <td class="text-center">
                                        <a href="admin.php?admin=formSuaBacSi&idAdmin=<?= urlencode($bs['id']) ?>"
                                            class="btn btn-success btn-circle btn-sm" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <?php if (($bs['trang_thai'] ?? '') === 'dang_lam'): ?>
                                            <a href="admin.php?admin=toggleBacSi&idAdmin=<?= urlencode($bs['id']) ?>&action=khoa"
                                                class="btn btn-secondary btn-circle btn-sm"
                                                title="Khóa bác sĩ"
                                                onclick="return confirm('Bạn có chắc muốn khóa bác sĩ này? Bác sĩ sẽ không thể nhận lịch mới.');">
                                                <i class="fas fa-lock"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="admin.php?admin=toggleBacSi&idAdmin=<?= urlencode($bs['id']) ?>&action=mo"
                                                class="btn btn-info btn-circle btn-sm"
                                                title="Mở lại"
                                                onclick="return confirm('Bạn có chắc muốn mở lại bác sĩ này?');">
                                                <i class="fas fa-unlock"></i>
                                            </a>
                                        <?php endif; ?>

                                        <a href="admin.php?admin=xoaBacSi&idAdmin=<?= urlencode($bs['id']) ?>"
                                            class="btn btn-danger btn-circle btn-sm"
                                            title="Xóa"
                                            onclick="return confirm('Bạn có chắc muốn xóa bác sĩ này? Hành động không thể hoàn tác.');">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center text-muted">Chưa có bác sĩ</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                </table>

            </div>
        </div>
    </div>
</div>