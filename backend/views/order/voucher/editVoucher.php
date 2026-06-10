<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Thêm Vật Tư</title>

    <!-- Custom fonts for this template -->
    <link rel="stylesheet" href="backend/assets/css/admin-main.css">
    <link rel="stylesheet" href="backend/assets/css/admin-main2.css">
    <link href="backend/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
</head>


<!-- Bắt Đầu Nội Dung Trang -->
<div class="container-fluid">

    <!-- Tiêu Đề Trang -->
    <h1 class="h3 mb-2 text-gray-800">Thêm Mã Giảm Giá</h1>


    <!-- Form Thêm Người Dùng -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Biểu Mẫu Thêm Mã Giảm Giá</h6>
        </div>
        <div class="card-body">
            <form id="addUserForm" method="POST" action="admin.php?admin=updateDiscount">
                <input type="hidden" name="id" value="<?= $item['id'] ?>">

                <div class="form-row">
                    <div class="form-group col-md-6 position-relative">
                        <label for="code">Mã Giảm Giá*</label>
                        <input type="text" value="<?= $item['code']  ?>" class="form-control" id="code" name="code"
                            placeholder="Nhập mã giảm giá" autocomplete="off" required>

                        <div id="suggest-box" class="list-group position-absolute w-100" style="z-index:999;"></div>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="gia_tri">Giá trị *</label>
                        <input type="text" value="<?= $item['gia_tri']  ?>" class="form-control" id="gia_tri" name="gia_tri" placeholder="Nhập giá trị" required>
                        <div id="suggest-box" class="list-group position-absolute w-100" style="z-index:999;"></div>

                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="so_luot">Số Lượt *</label>
                        <input type="number" value="<?= $item['so_luot']  ?>" class="form-control" id="so_luot" name="so_luot" placeholder="Ví dụ: 100" required>
                        <div id="suggest-box" class="list-group position-absolute w-100" style="z-index:999;"></div>

                    </div>


                </div>


                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="loai">Loại mã *</label>
                        <select class="form-control" id="loai" name="loai" required>
                            <option value="">-- Chọn loại mã --</option>

                            <option value="phan_tram"
                                <?= ($item['loai'] == 'phan_tram') ? 'selected' : '' ?>>
                                Phần Trăm
                            </option>

                            <option value="tien"
                                <?= ($item['loai'] == 'tien') ? 'selected' : '' ?>>
                                Tiền
                            </option>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="ngay_bat_dau">Ngày Bắt Đầu *</label>
                        <input type="date" value="<?= $item['ngay_bat_dau']  ?>" class="form-control" id="ngay_bat_dau" name="ngay_bat_dau" required>
                        <div id="suggest-box" class="list-group position-absolute w-100" style="z-index:999;"></div>

                    </div>
                    <div class="form-group col-md-4">
                        <label for="ngay_ket_thuc">Ngày Kết Thúc *</label>
                        <input type="date" value="<?= $item['ngay_ket_thuc']  ?>" class="form-control" id="ngay_ket_thuc" name="ngay_ket_thuc" required>
                        <div id="suggest-box" class="list-group position-absolute w-100" style="z-index:999;"></div>

                    </div>
                    <div class="form-group col-md-4">
                        <label for="trang_thai">Trạng thái *</label>
                        <select class="form-control" id="trang_thai" name="trang_thai" required>
                            <option value="">-- Chọn trạng thái --</option>

                            <option value="1"
                                <?= ($item['trang_thai'] == 1) ? 'selected' : '' ?>>
                                Hoạt động
                            </option>

                            <option value="0"
                                <?= ($item['trang_thai'] == 0) ? 'selected' : '' ?>>
                                Tạm tắt
                            </option>
                        </select>
                    </div>

                </div>


                <hr>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Sửa Mã Giảm Giá
                    </button>

                    <a href="admin.php?admin=listDiscount" class="btn btn-info">
                        <i class="fas fa-arrow-left"></i> Quay Lại
                    </a>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- Kết Thúc Nội Dung Chính -->

<!-- Chân Trang -->

<!-- Kết Thúc Chân Trang -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->


<!-- Nút Cuộn Lên Đầu Trang -->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Modal Đăng Xuất -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Sẵn Sàng Rời Đi?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Chọn "Đăng Xuất" bên dưới nếu bạn sẵn sàng kết thúc phiên hiện tại của mình.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Hủy</button>
                <a class="btn btn-primary" href="login.html">Đăng Xuất</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript -->
<script src="backend/assets/vendor/jquery/jquery.min.js"></script>
<script src="backend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript -->
<script src="backend/assets/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages -->
<script src="backend/assets/js/sb-admin-2.min.js"></script>

<!-- Script xử lý form -->




</body>

</html>