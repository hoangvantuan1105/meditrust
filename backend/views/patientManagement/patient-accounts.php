<div class="container-fluid mt-4">

    <!-- ===== HEADER ===== -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý tài khoản bệnh nhân</h1>
        <a href="admin.php?admin=formAddPatientAccounts" class="btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Thêm tài khoản
        </a>
    </div>

    <!-- ===== TABLE ===== -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách tài khoản</h6>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ID Hồ sơ Bệnh Nhân</th>
                            <th>Số Điện Thoại</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th style="width:150px">Thao tác</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($listTaiKhoan)): ?>
                            <?php foreach ($listTaiKhoan as $tk): ?>
                                <tr>
                                    <td><?= $tk['id'] ?></td>
                                    <td><?= $tk['ho_so_benh_nhan_id'] ?></td>
                                    <td><?= htmlspecialchars($tk['so_dien_thoai']) ?></td>
                                    <td>
                                        <?php if ($tk['trang_thai'] == 1): ?>
                                            <span class="badge badge-success">Đã kích hoạt</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Chưa kích hoạt</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($tk['ngay_tao'])) ?></td>
                                    <td class="text-center">
                                        <a href="admin.php?admin=formEditPatientAccount&id=<?= $tk['id'] ?>"
                                            class="btn btn-success btn-circle btn-sm" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    Chưa có tài khoản bệnh nhân
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>