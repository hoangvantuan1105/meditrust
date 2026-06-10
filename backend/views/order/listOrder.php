    <!-- Bảng Dữ Liệu -->
    <div class="container-fluid">
        <!-- Tiêu Đề Trang -->
        <h1 class="h3 mb-2 text-gray-800">Bảng Hóa Đơn</h1>

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
                                <th>Bệnh nhân</th>

                                <th>Bác sĩ</th>
                                <th>Tổng tiền</th>
                                <th>Phương thức</th>
                                <th>Trạng thái</th>
                                <th>Ngày lập</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Bệnh nhân</th>

                                <th>Bác sĩ</th>
                                <th>Tổng tiền</th>
                                <th>Phương thức</th>
                                <th>Trạng thái</th>
                                <th>Ngày lập</th>
                                <th>Hành Động</th>
                            </tr>
                        </tfoot>
                        <tbody>


                            <?php foreach ($allOrder as $o): ?>
                                <tr>
                                    <td><?= $o['id'] ?></td>
                                    <td><?= htmlspecialchars($o['ten_benh_nhan']) ?></td>
                                    <td><?= htmlspecialchars($o['ten_bac_si']) ?></td>
                                    <td><?= number_format($o['thanh_tien']) ?> đ</td>
                                    <td><?= $o['phuong_thuc_tt'] ?></td>
                                    <td>
                                        <select class="form-control change-status" data-id="<?= $o['id'] ?>">
                                            <option value="0" <?= $o['trang_thai'] == 0 ? 'selected' : '' ?>>Chưa thanh toán</option>
                                            <option value="1" <?= $o['trang_thai'] == 1 ? 'selected' : '' ?>>Đã thanh toán</option>
                                        </select>
                                    </td>

                                    <td><?= $o['ngay_lap'] ?></td>
                                    <td><a href="admin.php?admin=exportInvoice&id=<?= $o['id'] ?>">Xuất pdf</a></td>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- <script>
        $('.change-status').on('focus', function() {
            $(this).data('old', this.value);
        });

        $('.change-status').on('change', function() {
            let select = $(this);
            let oldVal = select.data('old');
            let id = select.data('id');
            let status = select.val();

            Swal.fire({
                title: 'Xác nhận?',
                text: 'Bạn có chắc muốn đổi trạng thái hóa đơn?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.post('admin.php?admin=updateHoaDonStatus', {
                        id: id,
                        status: status
                    }, function(res) {
                        if (res.status) {
                            Swal.fire('Thành công', 'Cập nhật trạng thái thành công!', 'success');
                        } else {
                            Swal.fire('Lỗi', 'Không thể cập nhật!', 'error');
                            select.val(oldVal);
                        }
                    }, 'json');

                } else {
                    select.val(oldVal);
                }
            });
        });
    </script> -->
    <script>
        $('.change-status').on('focus', function() {
            $(this).data('old', $(this).val()); // lưu giá trị cũ
        });

        $('.change-status').on('change', function() {

            let select = $(this);
            let oldVal = select.data('old'); // giá trị trước khi đổi
            let newVal = select.val(); // giá trị vừa chọn
            let id = select.data('id');

            // 🔥 CHẶN: nếu đã là 1 mà đổi về 0 thì không cho
            if (oldVal == "1" && newVal == "0") {
                Swal.fire(
                    'Không hợp lệ',
                    'Hóa đơn đã thanh toán không thể đổi lại!',
                    'warning'
                );
                select.val(oldVal);
                return;
            }

            Swal.fire({
                title: 'Xác nhận?',
                text: 'Bạn có chắc muốn đổi trạng thái hóa đơn?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.post('admin.php?admin=updateHoaDonStatus', {
                        id: id,
                        status: newVal
                    }, function(res) {

                        if (res.status) {

                            Swal.fire(
                                'Thành công',
                                'Cập nhật trạng thái thành công!',
                                'success'
                            );

                            // nếu vừa chuyển sang đã thanh toán
                            if (newVal == "1") {
                                select.data('old', "1"); // cập nhật lại old
                            }

                        } else {
                            Swal.fire(
                                'Lỗi',
                                'Không thể cập nhật!',
                                'error'
                            );
                            select.val(oldVal);
                        }

                    }, 'json');

                } else {
                    select.val(oldVal);
                }

            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php if (!empty($_SESSION['success'])): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Thành công 🎉',
                text: '<?= $_SESSION['success'] ?>',
                timer: 2000,
                showConfirmButton: false
            });
        </script>
    <?php unset($_SESSION['success']);
    endif; ?>