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
            <a href="admin.php?admin=formPrescription" class="btn btn-sm btn-primary">
                kê đơn thuốc
            </a>
            <a href="admin.php?admin=listMedicine" class="link-primary p-2">Trở về</a>
        </div>
        
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách đơn thuốc</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Mã đơn thuốc</th>
                            <th>Hồ sơ bệnh nhân</th>
                            <th>Lịch sử khám</th>
                            <th>Bác sĩ</th>
                            <th>Ngày kê đơn</th>
                            <th>Chẩn đoán</th>
                            <th style="width: 150px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($prescription as $list) { ?>
                        <tr>
                            <td><?= $list['ma_don_thuoc'] ?></td>
                            <td><?= $list['ho_so_benh_nhan'] ?></td>
                            <td><?= $list['ngay_kham'] ?></td>
                            <td><?= $list['bac_si_id'] ?></td>
                            <td><?= $list['ngay_ke_don'] ?></td>
                            <td><?= $list['chan_doan'] ?></td>
                            <td>
                                <a href="admin.php?admin=detailPrescription&idAdmin=<?= $list['ma_don_thuoc'] ?>" class="btn btn-info btn-circle btn-sm" title="Xem chi tiết">
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Chờ trang load xong mới quét URL
document.addEventListener('DOMContentLoaded', function() {
    // 1. Lấy query string từ thanh địa chỉ (ví dụ: ?status=out_of_stock)
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');

    // 2. Nếu có status thì mới gọi SweetAlert
    if (status) {
        if (status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: 'Đã kê đơn thuốc',
                timer: 2000,
                showConfirmButton: false
            });
        } 

        // 3. (Tùy chọn) Xóa cái tham số trên URL để khi F5 không bị hiện lại thông báo
        window.history.replaceState({}, document.title, window.location.pathname + "?admin=formDispenseMedicine");
    }
});
</script>

</body>
</html>