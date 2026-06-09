<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý Xuất Thuốc</h1>
        <div>
            <a href="admin.php?admin=formDispenseMedicine" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Xuất Thuốc Tự Do (Hủy/Trả/Điều trị)
            </a>
            <a href="admin.php?admin=listMedicine" class="link-primary p-2">Trở về</a>
        </div>

    </div>



    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-success">
                <i class="fas fa-history mr-2"></i>Lịch Sử Xuất Thuốc Gần Đây
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-striped" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Ngày Xuất</th>
                            <th>Tên Thuốc</th>
                            <th>Số Lượng</th>
                            <th>Loại Xuất</th>
                            <th>Người Thực Hiện</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historyDispenseMedicine as $hisDis) { ?>
                            <tr>
                                <td><?= $hisDis['ngay_xuat'] ?></td>
                                <td><?= $hisDis['ten_thuoc'] ?></td>
                                <td><?= $hisDis['so_luong'] ?></td>
                                <td><span class="text-primary"><?= $hisDis['ly_do'] ?></span></td>
                                <td>Admin McGee</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalXuatThuoc" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-left-primary">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold text-primary">Chi Tiết Đơn Thuốc #DT-001</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Bệnh nhân:</strong> Nguyễn Văn A | <strong>Triệu chứng:</strong> Đau răng cấp</p>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tên thuốc</th>
                            <th>Số lượng kê</th>
                            <th>Hướng dẫn sử dụng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Paracetamol 500mg</td>
                            <td>10 vỉ</td>
                            <td>Uống 1 viên sau ăn</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-success">Xác nhận Xuất thuốc & In hóa đơn</button>
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
                    text: 'Đã xuất thuốc và cập nhật kho.',
                    timer: 2000,
                    showConfirmButton: false
                });
            }

            // 3. (Tùy chọn) Xóa cái tham số trên URL để khi F5 không bị hiện lại thông báo
            window.history.replaceState({}, document.title, window.location.pathname + "?admin=formDispenseMedicine");
        }
    });
</script>