<!-- Bảng Dữ Liệu -->
<div class="container-fluid">
    <!-- Tiêu Đề Trang -->
    <h1 class="h3 mb-2 text-gray-800">Tất cả tin nhắn</h1>

    <!-- Ví Dụ DataTables - Bảng Danh Sách Nhân Viên -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tin Nhắn</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <!-- Bảng nhân viên với côt: Tên, Chức Vụ, Văn Phòng, Tuổi, Ngày Bắt Đầu, Lương -->
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Người gửi</th>
                            <th>Email</th>
                            <th>Tiêu đề</th>
                            <th>Ngày gửi</th>
                            <th>Trạng thái</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Người gửi</th>
                            <th>Email</th>
                            <th>Tiêu đề</th>
                            <th>Ngày gửi</th>
                            <th>Trạng thái</th>
                            <th>Hành Động</th>
                        </tr>
                    </tfoot>
                    <tbody>

                        <?php if (empty($danhSachTin)): ?>
                            <tr>
                                <td colspan="6" class="text-center">Không có tin nhắn</td>
                            </tr>
                        <?php else: ?>

                            <?php foreach ($danhSachTin as $tin): ?>
                                <tr class="<?= $tin['da_doc'] == 0 ? 'table-warning' : '' ?>">
                                    <td><?= htmlspecialchars($tin['ten_nguoi_gui']) ?></td>
                                    <td><?= htmlspecialchars($tin['email_nguoi_gui']) ?></td>
                                    <td><?= htmlspecialchars($tin['tieu_de']) ?></td>
                                    <td><?= date("d/m/Y H:i", strtotime($tin['ngay_tao'])) ?></td>
                                    <td>
                                        <?=
                                            $tin['da_doc'] == 0
                                            ? '<span class="badge badge-danger">Chưa đọc</span>'
                                            : '<span class="badge badge-success">Đã đọc</span>'
                                            ?>
                                    </td>
                                    <td>
                                        <a href="admin.php?admin=chiTietTin&id=<?= $tin['id'] ?>"
                                            class="btn btn-info btn-sm xem-tin">
                                            Xem
                                        </a>
                                        <a href="admin.php?admin=hienFormTraLoi&id=<?= $tin['id'] ?>">
                                            Trả lời
                                        </a>

                                    </td>

                                </tr>
                            <?php endforeach; ?>

                        <?php endif; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- Kết Thúc Nội Dung Trang -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>