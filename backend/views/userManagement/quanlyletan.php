<div class="container-fluid mt-4">

    <!-- ===== HEADER ===== -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý lễ tân</h1>
        <a href="admin.php?admin=formThemTaiKhoanLeTan" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm lễ tân
        </a>
    </div>

    <!-- ===== THÔNG BÁO ===== -->
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-1"></i>
            <?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle mr-1"></i>
            <?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- ===== TABLE ===== -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách lễ tân</h6>
            <span class="badge badge-primary"><?= count($listLeTan) ?> lễ tân</span>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th>STT</th>
                            <th>Họ tên</th>
                            <th>SĐT</th>
                            <th>Email</th>
                            <th>Giới tính</th>
                            <th>Ca làm</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($listLeTan)): ?>
                            <?php foreach ($listLeTan as $i => $lt): ?>
                                <tr>
                                    <td class="text-center"><?= $i + 1 ?></td>

                                    <td><?= htmlspecialchars($lt['ten_le_tan'] ?? '---') ?></td>

                                    <td class="text-center"><?= htmlspecialchars($lt['sdt'] ?? '---') ?></td>

                                    <td><?= htmlspecialchars($lt['email'] ?? '---') ?></td>

                                    <td class="text-center">
                                        <?php
                                        $gt = $lt['gioi_tinh'] ?? '';
                                        if ($gt === 'nam') echo 'Nam';
                                        elseif ($gt === 'nu') echo 'Nữ';
                                        elseif ($gt === 'khac') echo 'Khác';
                                        else echo '---';
                                        ?>
                                    </td>

                                    <td class="text-center">
                                        <?php
                                        $ca = $lt['ca_lam'] ?? '';
                                        if ($ca === 'sang') echo 'Sáng';
                                        elseif ($ca === 'chieu') echo 'Chiều';
                                        elseif ($ca === 'full') echo 'Full ngày';
                                        else echo htmlspecialchars($ca ?: '---');
                                        ?>
                                    </td>

                                    <td class="text-center">
                                        <?php
                                        $tt = $lt['trang_thai'] ?? '';
                                        if ($tt === 'dang_lam' || $tt === '1' || $tt == 1):
                                        ?>
                                            <span class="badge badge-success px-3">Đang làm việc</span>
                                        <?php elseif ($tt === 'nghi' || $tt === '0' || $tt == 0): ?>
                                            <span class="badge badge-warning px-3">Tạm nghỉ</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary px-3"><?= htmlspecialchars($tt) ?></span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center">
                                        <?= !empty($lt['ngay_tao']) ? date('d/m/Y H:i', strtotime($lt['ngay_tao'])) : '---' ?>
                                    </td>

                                    <td class="text-center">
                                        <a href="admin.php?admin=formSuaTaiKhoanLeTan&id=<?= urlencode($lt['id']) ?>"
                                            class="btn btn-success btn-circle btn-sm" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="admin.php?admin=xoaTaiKhoanLeTan&id=<?= urlencode($lt['id']) ?>"
                                            class="btn btn-danger btn-circle btn-sm" title="Xóa"
                                            onclick="return confirm('Bạn có chắc muốn xóa lễ tân này?');">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                    Chưa có lễ tân nào
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
