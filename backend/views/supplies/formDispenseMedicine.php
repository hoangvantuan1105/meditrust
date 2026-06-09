<div class="container-fluid">
    <div class="mb-4">
        <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Phiếu Xuất Thuốc</h1>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4 border-left-primary">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin chi tiết phiếu xuất</h6>
                </div>
                <div class="card-body">
                    <form action="admin.php?admin=dispenseMedicine" method="POST">
                        
                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Tên Thuốc <span class="text-danger">*</span></label>
                                    <select class="form-control" name="idMedicine" required>
                                        <option value="">-- Chọn thuốc trong kho --</option>
                                        <?php foreach($listMedicine as $list){ ?>
                                            <option value="<?= $list['id'] ?>"><?= $list['ten_thuoc'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 col-lg-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Số lượng xuất <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="quantityDispense" min="1" value="1" required>
                                </div>
                            </div>
                            
                            <div class="col-md-3 col-lg-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Lý do xuất <span class="text-danger">*</span></label>
                                    <select class="form-control" name="reasonDispense" required>
                                        <option value="Điều trị tại chỗ">Điều trị tại chỗ</option>
                                        <option value="Hủy thuốc hết hạn">Hủy thuốc hết hạn</option>
                                        <option value="Trả hàng nhà cung cấp">Trả hàng nhà cung cấp</option>
                                        <option value="Xuất lẻ">Xuất lẻ (Không đơn)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 col-lg-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Ngày xuất <span class="text-danger">*</span></label>
                                    <input type="datetime-local" class="form-control" name="dateDispense" min="<?= date('Y-m-d\TH:i') ?>" value="<?= date('Y-m-d\TH:i') ?>" required>
                                </div>
                            </div>
                        </div>

                        <hr>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted italic text-danger">* Vui lòng kiểm tra kỹ số lượng trước khi xác nhận.</small>
                            <div>
                              <button type="submit" class="btn btn-primary btn-icon-split px-4 shadow">
                                <span class="icon text-white" style="background: transparent;"><i class="fas fa-check-circle"></i></span>
                                <span class="text">Xác nhận Xuất thuốc</span>
                              </button>
                              <a href="admin.php?admin=listMedicine" class="link-primary p-2">Trở về</a>  
                            </div>
                            
                        </div>
                    </form>
                </div>
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
    const available = urlParams.get('available');

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
        else if (status === 'out_of_stock') {
            Swal.fire({
                icon: 'error',
                title: 'Không đủ thuốc!',
                text: 'Số lượng trong kho hiện chỉ còn: ' + available,
                confirmButtonText: 'Đã hiểu'
            });
        }
        else if (status === 'error') {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: 'Có lỗi xảy ra trong quá trình lưu dữ liệu.',
            });
        }
        else if (status === 'too_little') {
            Swal.fire({
                icon: 'error',
                title: 'Xuất cái j đấy ???',
                text: 'Yêu cầu số lượng xuất ra phải lớn hơn 0!',
            });
        }

        // 3. (Tùy chọn) Xóa cái tham số trên URL để khi F5 không bị hiện lại thông báo
        window.history.replaceState({}, document.title, window.location.pathname + "?admin=formDispenseMedicine");
    }
});
</script>