<?php $lichHenId = $_GET['lich_hen_id'] ?? null; ?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý bệnh nhân</h1>

        <?php if (!empty($_GET['lich_hen_id'])): ?>
            <a href="admin.php?admin=form<?= $lichHenId ? '&from=tiep_nhan&lich_hen_id=' . $lichHenId : '' ?>"
                class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Thêm hồ sơ bệnh nhân
            </a>
        <?php else: ?>
            <a href="admin.php?admin=form" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Thêm bệnh nhân mới
            </a>
        <?php endif; ?>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $_GET['msg']; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách chi tiết</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Họ Tên</th>
                            <th>Số Điện Thoại</th>
                            <th>Email</th>
                            <th>CMND/CCCD</th>
                            <th>Bảo Hiểm Y Tế</th>
                            <th>Người Liên Hệ</th>
                            <th>SDT Liên Hệ</th>
                            <!--<th>Trạng Thái</th>-->
                            <th>Ngày Tạo</th>
                            <th style="width: 150px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data)): ?>
                            <?php foreach ($data as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['id'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($item['ho_ten'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($item['so_dien_thoai'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($item['email'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($item['cmnd_cccd'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($item['bao_hiem_y_te'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($item['nguoi_lien_he_khan_cap'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($item['sdt_nguoi_lien_he'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($item['ngay_tao'] ?? '') ?></td>
                                    <td>
                                        <a href="admin.php?admin=detail&id=<?= $item['id'] ?>"
                                            class="btn btn-info btn-circle btn-sm" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <?php if ($lichHenId): ?>
                                            <a href="admin.php?admin=ganHoSo&ho_so_id=<?= $item['id'] ?>&lich_hen_id=<?= $lichHenId ?>"
                                                class="btn btn-primary btn-circle btn-sm"
                                                onclick="return confirm('Gán hồ sơ bệnh nhân này vào lịch hẹn?')">
                                                <i class="fas fa-link"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="admin.php?admin=edit&id=<?= $item['id'] ?>"
                                                class="btn btn-success btn-circle btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="admin.php?admin=delete&id=<?= $item['id'] ?>"
                                                class="btn btn-danger btn-circle btn-sm"
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11" class="text-center">Không có dữ liệu</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
