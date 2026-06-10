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
                        <tr>
                            <th>Bệnh nhân</th>
                            <th>Chẩn đoán</th>
                            <th>Dịch vụ</th>
                            <th>Thuốc</th>
                            <th>Ngày khám</th>
                            <th>Hành động</th>
                        </tr>

                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                        <tr>
                            <th>Bệnh nhân</th>
                            <th>Chẩn đoán</th>
                            <th>Dịch vụ</th>
                            <th>Thuốc</th>
                            <th>Ngày khám</th>
                            <th>Hành động</th>
                        </tr>

                        </tr>
                    </tfoot>
                    <tbody>


                        <?php foreach ($list as $row): ?>
                            <tr>
                                <td><?= $row['ten_benh_nhan'] ?></td>
                                <td><?= $row['chan_doan'] ?></td>

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

                                <td>
                                    <a href="admin.php?admin=formCreateOrder&lich_kham_id=<?= $row['lich_kham_id'] ?>" class="btn btn-warning btn-circle btn-sm">
                                        <i class="fa-solid fa-file-invoice"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>