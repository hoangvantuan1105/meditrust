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
    <h1 class="h3 mb-2 text-gray-800">Cập Nhật vật Tư Mới</h1>
    <p class="mb-4">Điền các thông tin vật tư vào hệ thống.</p>

    <!-- Form Thêm Người Dùng -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Biểu Mẫu Cập Nhật Vật Tư</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="admin.php?admin=editMaterials">
                <input type="hidden" name="id" value="<?= $item['id'] ?>">

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Tên Vật Tư *</label>
                        <input type="text" class="form-control"
                            name="ten_vat_tu"
                            value="<?= $item['ten_vat_tu'] ?? '' ?>"
                            required>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Đơn vị *</label>
                        <input type="text" class="form-control"
                            name="don_vi"
                            value="<?= $item['don_vi'] ?? '' ?>"
                            required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Số lượng *</label>
                        <input type="number" class="form-control"
                            name="so_luong"
                            value="<?= $item['so_luong'] ?? '' ?>"
                            required>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Nhà phân phối *</label>
                        <select class="form-control" name="hang_san_xuat" required>
                            <option value="">-- Chọn Nhà Phân Phối --</option>
                            <?php
                            $hangs = [
                                '3M' => '3M Oral Care',
                                'Dentsply' => 'Dentsply Sirona',
                                'GC' => 'GC Dental',
                                'Ivoclar' => 'Ivoclar Vivadent',
                                'Kerr' => 'Kerr Dental',
                                'Coltene' => 'Coltene Group',
                                'Septodont' => 'Septodont',
                                'Shofu' => 'Shofu Dental',
                                'Tokuyama' => 'Tokuyama Dental',
                                'VietDental' => 'Việt Dental',
                                'SaigonDental' => 'Sài Gòn Dental'
                            ];
                            foreach ($hangs as $k => $v):
                            ?>
                                <option value="<?= $k ?>" <?= ($item['hang_san_xuat'] == $k) ? 'selected' : '' ?>>
                                    <?= $v ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Danh mục *</label>
                        <select class="form-control" name="danh_muc" required>
                            <option value="tieu hao" <?= $item['danh_muc'] == 'tieu hao' ? 'selected' : '' ?>>Tiêu hao</option>
                            <option value="tai su dung" <?= $item['danh_muc'] == 'tai su dung' ? 'selected' : '' ?>>Tái sử dụng</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Trạng thái *</label>
                        <select class="form-control" name="trang_thai" required>
                            <option value="con hang" <?= $item['trang_thai'] == 'con hang' ? 'selected' : '' ?>>Còn vật tư</option>
                            <option value="het hang" <?= $item['trang_thai'] == 'het hang' ? 'selected' : '' ?>>Hết vật tư</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Hạn sử dụng *</label>
                        <select class="form-control" name="trang_thai_han" required>
                            <option value="con han" <?= $item['trang_thai_han'] == 'con han' ? 'selected' : '' ?>>Còn hạn</option>
                            <option value="het han" <?= $item['trang_thai_han'] == 'het han' ? 'selected' : '' ?>>Hết hạn</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Giá nhập *</label>
                        <input type="number" class="form-control"
                            name="gia_nhap"
                            value="<?= $item['gia_nhap'] ?? '' ?>"
                            required>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Hạn sử dụng *</label>
                        <input type="date" class="form-control"
                            name="han_su_dung"
                            value="<?= date('Y-m-d', strtotime($item['han_su_dung'])) ?>"
                            required>
                    </div>
                </div>

                <hr>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Cập Nhật Vật Tư
                </button>

                <a href="admin.php?admin=materials" class="btn btn-info">
                    <i class="fas fa-arrow-left"></i> Quay Lại
                </a>
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