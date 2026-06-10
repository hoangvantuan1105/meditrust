<div class="container-fluid mt-4">

    <!-- ===== HEADER ===== -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý lịch hẹn</h1>
        <a href="admin.php?admin=themlichhenmoi" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm Lịch Hẹn
        </a>
    </div>

    <!-- ===== TABLE ===== -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Danh sách lịch hẹn
            </h6>
        </div>

        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle" id="dataTable">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th>ID</th>
                            <th>Bệnh nhân</th>
                            <th>SĐT</th>
                            <th>Bác sĩ</th>
                            <th>Dịch vụ</th>
                            <th>Ngày hẹn</th>
                            <th>Hình thức đặt</th>
                            <th>Trạng thái</th>
                            <th width="120">Thao tác</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($listLichHen)): ?>
                            <?php foreach ($listLichHen as $lh): ?>
                                <tr>
                                    <td class="text-center">
                                        <?= $lh['id'] ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($lh['ho_ten'] ?? 'Chưa có hồ sơ') ?>
                                    </td>

                                    <td class="text-center">
                                        <?= htmlspecialchars($lh['so_dien_thoai'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($lh['ten_bac_si'] ?? 'Chưa phân') ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($lh['ten_dich_vu'] ?? '-') ?>
                                    </td>

                                    <td class="text-center">
                                        <?= !empty($lh['ngay_hen']) ? date('d/m/Y', strtotime($lh['ngay_hen'])) : 'Chưa có ngày' ?>
                                        <?= !empty($lh['gio_bat_dau']) ? date('H:i', strtotime($lh['gio_bat_dau'])) : '--:--' ?>
                                        -
                                        <?= !empty($lh['gio_ket_thuc']) ? date('H:i', strtotime($lh['gio_ket_thuc'])) : '--:--' ?>
                                    </td>
                                    <td>
                                        <?php
                                        $loai = $lh['loai_dat'] ?? '';

                                        if ($loai === 'online') {
                                            echo '<span class="badge badge-success">Online</span>';
                                        } elseif ($loai === 'truc_tiep') {
                                            echo '<span class="badge badge-danger">Trực tiếp</span>';
                                        } else {
                                            echo '-';
                                        }
                                        ?>
                                    </td>

                                    <td class="text-center">
                                        <?php if ($lh['trang_thai'] === 'cho_kham'): ?>
                                            <span class="badge badge-warning px-3">Chờ khám</span>
                                        <?php elseif ($lh['trang_thai'] === 'da_tiep_nhan'): ?>
                                            <span class="badge badge-info px-3">Đã Tiếp Nhận</span>
                                        <?php elseif ($lh['trang_thai'] === 'dang_kham'): ?>
                                            <span class="badge badge-success px-3">Đang khám</span>
                                        <?php elseif ($lh['trang_thai'] === 'da_kham'): ?>
                                            <span class="badge badge-secondary px-3">Đã khám</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger px-3">Vắng Mặt</span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center">

                                        <?php if ($lh['trang_thai'] !== 'da_tiep_nhan'): ?>
                                            <a href="admin.php?admin=formSuaLichHen&id=<?= $lh['id'] ?>"
                                                class="btn btn-success btn-sm" title="Sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        <?php endif; ?>

                                        <?php if ($lh['trang_thai'] === 'cho_kham'): ?>
                                            <a href="admin.php?admin=dsbenhnhan&lich_hen_id=<?= $lh['id'] ?>"
                                                class="btn btn-sm btn-success"
                                                onclick="return confirm('Bạn có chắc muốn tiếp nhận bệnh nhân này không?');">
                                                Tiếp nhận
                                            </a>
                                        <?php endif; ?>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted">
                                    Chưa có lịch hẹn nào
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                </table>

            </div>
        </div>
    </div>
</div>