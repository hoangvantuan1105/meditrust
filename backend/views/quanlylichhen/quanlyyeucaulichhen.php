<div class="container-fluid mt-4">

    <!-- ===== HEADER ===== -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý yêu cầu lịch hẹn</h1>
    </div>

    <!-- ===== TABLE ===== -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Danh sách yêu cầu đặt lịch khám
            </h6>
        </div>

        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle" id="dataTable">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th>ID</th>
                            <th>Họ tên</th>
                            <th>SĐT</th>
                            <th>Dịch vụ</th>
                            <th>Bác sĩ</th>
                            <th>Ngày mong muốn</th>
                            <th>Mô tả triệu chứng</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($listYcLichHen)): ?>
                            <?php foreach ($listYcLichHen as $lh): ?>
                                <tr>
                                    <td class="text-center"><?= $lh['id'] ?></td>

                                    <td><?= htmlspecialchars($lh['ho_ten']) ?></td>

                                    <td class="text-center">
                                        <?= htmlspecialchars($lh['so_dien_thoai']) ?>
                                    </td>
                                    <td class="text-center">
                                        <?= htmlspecialchars($lh['ten_dich_vu'] ?? '---') ?>
                                    </td>

                                    <td class="text-center">
                                        <?= htmlspecialchars($lh['ten_bac_si'] ?? '---') ?>
                                    </td>

                                    <td class="text-center">
                                        <?= !empty($lh['ngay_mong_muon']) ? date('d/m/Y', strtotime($lh['ngay_mong_muon'])) : 'Chưa có ngày' ?>
                                        <?= !empty($lh['gio_bat_dau']) ? date('H:i', strtotime($lh['gio_bat_dau'])) : '--:--' ?>
                                        -
                                        <?= !empty($lh['gio_ket_thuc']) ? date('H:i', strtotime($lh['gio_ket_thuc'])) : '--:--' ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($lh['mo_ta_trieu_chung']) ?>
                                    </td>

                                    <td class="text-center">
                                        <?php if ($lh['trang_thai'] == 'cho_xu_ly'): ?>
                                            <span class="badge badge-warning px-3">Chờ xử lý</span>
                                        <?php elseif ($lh['trang_thai'] == 'da_xac_nhan'): ?>
                                            <span class="badge badge-success px-3">Đã xác nhận</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger px-3">Đã hủy</span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center">
                                        <?= date('d/m/Y H:i', strtotime($lh['created_at'])) ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($lh['trang_thai'] === 'da_xac_nhan'): ?>
                                            <button type="button" class="btn btn-secondary btn-circle btn-sm"
                                                onclick="alert('❌ Yêu cầu này đã được xác nhận, không thể chỉnh sửa!')"
                                                title="Không thể sửa">
                                                <i class="fas fa-lock"></i>
                                            </button>
                                        <?php else: ?>
                                            <a href="admin.php?admin=formSuaYeuCauDatLich&id=<?= $lh['id'] ?>"
                                                class="btn btn-success btn-circle btn-sm" title="Sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center text-muted">
                                    Chưa có yêu cầu lịch hẹn
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                </table>

            </div>
        </div>
    </div>
</div>