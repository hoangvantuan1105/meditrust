    <!-- Bảng Dữ Liệu -->
    <div class="container-fluid">
        <!-- Tiêu Đề Trang -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Quản lý mã giảm giá</h1>
            <a href="admin.php?admin=createDiscount" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Thêm mã giảm giá mới
            </a>
        </div>

        <!-- Ví Dụ DataTables - Bảng Danh Sách Nhân Viên -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Hóa Đơn</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <!-- Bảng nhân viên với côt: Tên, Chức Vụ, Văn Phòng, Tuổi, Ngày Bắt Đầu, Lương -->
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <td>code</td>
                                <th>Loại Mã</th>

                                <th>Giá</th>
                                <th>Số lượt</th>
                                <th>Ngày Bắt Đầu</th>
                                <th>Ngày Kết Thúc</th>
                                <th>Trạng Thái</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <td>code</td>
                                <th>Loại Mã</th>

                                <th>Giá</th>
                                <th>Số lượt</th>
                                <th>Ngày Bắt Đầu</th>
                                <th>Ngày Kết Thúc</th>
                                <th>Trạng Thái</th>
                                <th>Hành Động</th>
                            </tr>
                        </tfoot>
                        <tbody>

                            <?php foreach ($allDiscount as $d) : ?>
                                <tr>
                                    <td><?= $d['id'] ?></td>
                                    <td><?= $d['code'] ?></td>
                                    <td><?= $d['loai'] ?></td>
                                    <td><?= $d['gia_tri'] ?></td>
                                    <td><?= $d['so_luot'] ?></td>
                                    <td><?= $d['ngay_bat_dau'] ?></td>
                                    <td><?= $d['ngay_ket_thuc'] ?></td>
                                    <td>
                                        <?= $d['trang_thai'] == 1
                                            ? '<span class="badge badge-success">Đang hoạt động</span>'
                                            : '<span class="badge badge-secondary">Tạm khóa</span>'
                                        ?>
                                    </td>


                                    <td>
                                        <a href="admin.php?admin=editDiscount&id=<?= $d['id'] ?>"
                                            class="btn btn-success btn-circle btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>



                                    </td>

                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

    </div>
    <!-- Kết Thúc Nội Dung Trang -->