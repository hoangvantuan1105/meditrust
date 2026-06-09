<!-- Bảng Dữ Liệu -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<div class="container-fluid">
    <!-- Tiêu Đề Trang -->
    <h1 class="h3 mb-2 text-gray-800">Tạo Hóa Đơn</h1>

    <!-- Ví Dụ DataTables - Bảng Danh Sách Nhân Viên -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tạo Hóa Đơn</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <!-- Bảng nhân viên với côt: Tên, Chức Vụ, Văn Phòng, Tuổi, Ngày Bắt Đầu, Lương -->
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Bệnh nhân</th>
                            <th>Chẩn đoán</th>
                            <th>Dịch vụ</th>
                            <th>Thuốc</th>
                            <th>Ngày khám</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Bệnh nhân</th>
                            <th>Chẩn đoán</th>
                            <th>Dịch vụ</th>
                            <th>Thuốc</th>
                            <th>Ngày khám</th>
                            <th>Hành động</th>
                        </tr>
                    </tfoot>
                    <tbody>

                        <?php foreach ($list as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']) ?></td>
                                <td><?= htmlspecialchars($row['ten_benh_nhan']) ?></td>
                                <td><?= htmlspecialchars($row['chan_doan']) ?></td>

                                <td>
                                    <?= !empty($row['dich_vu']) ? nl2br(htmlspecialchars($row['dich_vu'])) : 'Không có' ?>
                                </td>


                                <td>
                                    <?php if (!empty($row['thuoc']) && is_array($row['thuoc'])): ?>
                                        <?php foreach ($row['thuoc'] as $t): ?>
                                            - <?= htmlspecialchars($t['ten_thuoc']) ?>
                                            (<?= $t['so_luong'] ?>) <br>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        Không có
                                    <?php endif; ?>
                                </td>


                                <td><?= $row['ngay_kham'] ?></td>

                                <td class="text-center">
                                    <?php if ($row['la_tai_kham']): ?>
                                        <span class="badge badge-info px-3 py-2">
                                            <i class="fas fa-sync-alt mr-1"></i> Tái khám - Miễn phí
                                        </span>
                                    <?php elseif ($row['da_tao_hoa_don']): ?>
                                        <span class="badge badge-success px-3 py-2">
                                            <i class="fas fa-check-circle mr-1"></i> Đã tạo hóa đơn
                                        </span>
                                    <?php else: ?>
                                        <a href="admin.php?admin=formCreateOrder&lich_kham_id=<?= $row['lich_kham_id'] ?>" class="btn btn-warning btn-circle btn-sm" title="Tạo hóa đơn">
                                            <i class="fa-solid fa-file-invoice"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>