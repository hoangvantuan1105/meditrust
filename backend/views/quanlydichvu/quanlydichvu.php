<div class="container-fluid mt-4">

    <!-- ===== HEADER ===== -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý dịch vụ</h1>
        <a href="admin.php?admin=addDich_vu" class="btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i>
            Thêm dịch vụ
        </a>
    </div>

    <!-- ===== TABLE ===== -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Danh sách dịch vụ
            </h6>
        </div>

        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle" id="dataTable">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th>ID</th>
                            <th>Ảnh</th>
                            <th>Tên dịch vụ</th>
                            <th>Giá công khám</th>
                            <th>Chi phí vật tư</th>
                            <th>Giá trọn gói</th>
                            <th>Danh mục</th>
                            <th>Mô tả</th>
                            <th>Trạng thái</th>
                            <th width="120">Thao tác</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($dichvu)): ?>
                            <?php foreach ($dichvu as $dv): ?>
                                <tr>
                                    <td class="text-center"><?= $dv['id'] ?></td>

                                    <td class="text-center">
                                        <img src="backend/uploads/services/<?= $dv['image'] ?>" width="60" height="60"
                                            class="img-thumbnail rounded" alt="Ảnh dịch vụ">
                                    </td>

                                    <td><?= htmlspecialchars($dv['ten_dich_vu'] ?? '') ?></td>

                                    <td class="text-center">
                                        <!-- Giá công khám = giá dịch vụ - tổng vật tư -->
                                        <?php $giaCong = (int) $dv['gia'] - (int) $dv['tong_tien_vat_tu']; ?>
                                        <?= number_format($giaCong, 0, ',', '.') ?> đ
                                    </td>

                                    <td class="text-center text-warning fw-bold">
                                        <?= number_format($dv['tong_tien_vat_tu'], 0, ',', '.') ?> đ
                                    </td>

                                    <td class="text-center text-danger fw-bold">
                                        <!-- Giá trọn gói = giá dịch vụ đã lưu (giá công + vật tư) -->
                                        <?= number_format($dv['gia'], 0, ',', '.') ?> đ
                                    </td>

                                    <td class="text-center">
                                        <?= htmlspecialchars($dv['danhmuc'] ?? '') ?>
                                    </td>

                                    <td><?= htmlspecialchars($dv['mo_ta'] ?? '') ?></td>
                                    <td class="text-center">
                                        <?php if ($dv['trang_thai'] === 'Hoạt động'): ?>
                                            <span class="badge bg-success">Hoạt động</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Ngừng hoạt động</span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center">
                                        <a href="admin.php?admin=suadichvu&idAdmin=<?= $dv['id'] ?>"
                                            class="btn btn-success btn-circle btn-sm" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <a href="admin.php?admin=toggleTrangThaiDichVu&idAdmin=<?= $dv['id'] ?>"
                                            class="btn btn-circle btn-sm <?= ($dv['trang_thai'] === 'Hoạt động') ? 'btn-success' : 'btn-danger' ?>"
                                            onclick="return confirm('Bạn có chắc muốn đổi trạng thái dịch vụ này?')"
                                            title="<?= ($dv['trang_thai'] === 'Hoạt động') ? 'Ngừng hoạt động' : 'Kích hoạt lại' ?>">

                                            <i
                                                class="fa-solid <?= ($dv['trang_thai'] === 'Hoạt động') ? 'fa-lock' : 'fa-unlock' ?>"></i>
                                        </a>


                                        <a href="admin.php?admin=xemchitietdichvu&idAdmin=<?= $dv['id'] ?>"
                                            class="btn btn-info btn-circle btn-sm" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center text-muted">
                                    Chưa có dữ liệu dịch vụ
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                </table>

            </div>
        </div>
    </div>
</div>