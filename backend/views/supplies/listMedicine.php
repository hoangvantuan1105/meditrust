<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách thuốc</title>
    <link rel="stylesheet" href="../assets/css/admin-second.css">
</head>

<body>
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Quản lý thuốc</h1>

            <div>
                <a href="admin.php?admin=formAddMedicine" class="btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-plus fa-sm text-white-50"></i> Thêm thuốc mới
                </a>
                <a href="admin.php?admin=listDispenseMedicine" class="btn btn-sm btn-primary">
                    Quản lý xuất thuốc
                </a>

            </div>

        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Danh sách chi tiết</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Mã thuốc</th>
                                <th>Tên thuốc</th>
                                <th>Nhóm thuốc</th>
                                <th>Số lượng</th>
                                <th>Hạn sử dụng</th>
                                <th>Giá nhập</th>
                                <th style="width: 150px;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($listMedicine as $list) { ?>
                                <tr style="cursor:pointer">
                                    <td><?= $list['id'] ?></td>
                                    <td><?= $list['ten_thuoc'] ?></td>
                                    <td><?= $list['nhom_thuoc'] ?></td>
                                    <td><?= $list['so_luong'] ?></td>
                                    <td><?= $list['han_su_dung'] ?></td>
                                    <td><?= $list['gia_nhap'] ?></td>
                                    <td>
                                        <a href="admin.php?admin=formEditMedicine&idAdmin=<?= $list['id'] ?>" class="btn btn-success btn-circle btn-sm" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="admin.php?admin=deleteMedicine&idAdmin=<?= $list['id'] ?>" class="btn btn-danger btn-circle btn-sm" title="Xóa" onclick="return confirm('Bạn có chắc muốn xoá thuốc này không?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <a href="admin.php?admin=detailMedicine&idAdmin=<?= $list['id'] ?>" class="btn btn-info btn-circle btn-sm" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Chờ trang load xong mới quét URL
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Lấy query string từ thanh địa chỉ (ví dụ: ?status=out_of_stock)
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');

        // 2. Nếu có status thì mới gọi SweetAlert
        if (status) {
            if (status === 'deleteSuccess') {
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: 'Đã xóa thuốc và cập nhật kho.',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else if (status === 'errorDelete') {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Xóa thuốc không thành công.',
                    timer: 2000,
                    showConfirmButton: true
                })
            } else if (status === 'addError') {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Thêm thuốc không thành công.',
                    timer: 2000,
                    showConfirmButton: true
                })
            } else if (status === 'addSuccess') {
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: 'Thêm thuốc thành công.',
                    timer: 2000,
                    showConfirmButton: false
                })
            } else if (status === 'updateError') {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Sửa thuốc không thành công.',
                    timer: 2000,
                    showConfirmButton: true
                })
            } else if (status === 'updateSuccess') {
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: 'Sửa thuốc thành công.',
                    timer: 2000,
                    showConfirmButton: false
                })
            }

            // 3. (Tùy chọn) Xóa cái tham số trên URL để khi F5 không bị hiện lại thông báo
            window.history.replaceState({}, document.title, window.location.pathname + "?admin=formDispenseMedicine");
        }
    });
</script>

</html>