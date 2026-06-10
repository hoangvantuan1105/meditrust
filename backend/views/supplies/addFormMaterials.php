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
    <h1 class="h3 mb-2 text-gray-800">Thêm vật tư / sản phẩm mới</h1>
    <p class="mb-4">Điền thông tin để nhập vật tư hoặc sản phẩm bán tại POS.</p>

    <!-- Form Thêm Người Dùng -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Biểu Mẫu Thêm Vật Tư / Sản Phẩm</h6>
        </div>
        <div class="card-body">
            <form id="addUserForm" method="POST" action="admin.php?admin=addMaterials">
                <div class="form-row">
                    <div class="form-group col-md-6 position-relative">
                        <label for="ten_vat_tu">Tên vật tư / sản phẩm *</label>
                        <input type="text" class="form-control" id="ten_vat_tu" name="ten_vat_tu"
                            placeholder="Nhập tên vật tư hoặc sản phẩm" autocomplete="off" required>

                        <div id="suggest-box" class="list-group position-absolute w-100" style="z-index:999;"></div>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="don_vi">Đơn vị *</label>
                        <input type="text" class="form-control" id="don_vi" name="don_vi" placeholder="Nhập đơn vị" required>
                        <div id="suggest-box" class="list-group position-absolute w-100" style="z-index:999;"></div>

                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="so_luong">Số Lượng *</label>
                        <input type="number" class="form-control" id="so_luong" name="so_luong" placeholder="Ví dụ: 100" required>
                        <div id="suggest-box" class="list-group position-absolute w-100" style="z-index:999;"></div>

                    </div>
                    <div class="form-group col-md-6">
                        <label for="hang_san_xuat">Nhà Phân Phối *</label>
                        <select class="form-control" id="hang_san_xuat" name="hang_san_xuat" required>
                            <option value="">-- Chọn Nhà Phân Phối --</option>
                            <option value="3M">3M Oral Care</option>
                            <option value="Dentsply">Dentsply Sirona</option>
                            <option value="GC">GC Dental</option>
                            <option value="Ivoclar">Ivoclar Vivadent</option>
                            <option value="Kerr">Kerr Dental</option>
                            <option value="Coltene">Coltene Group</option>
                            <option value="Septodont">Septodont</option>
                            <option value="Shofu">Shofu Dental</option>
                            <option value="Tokuyama">Tokuyama Dental</option>
                            <option value="VietDental">Việt Dental</option>
                            <option value="SaigonDental">Sài Gòn Dental</option>
                        </select>
                    </div>


                </div>


                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="type">Loại *</label>
                        <select class="form-control" id="type" name="type" required>
                            <option value="0">Vật tư</option>
                            <option value="1">Sản phẩm</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="trang_thai_su_dung">Trạng thái bán POS</label>
                        <select class="form-control" id="trang_thai_su_dung" name="trang_thai_su_dung">
                            <option value="1">Đang bán</option>
                            <option value="0">Khóa</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="danh_muc">Danh mục *</label>
                        <select class="form-control" id="danh_muc" name="danh_muc" required>
                            <option value="">-- Chọn danh mục của vật tư --</option>
                            <option value="tieu hao">Tiêu hao</option>
                            <option value="tai su dung">Tái sử dụng</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="trang_thai">Trạng thái*</label>
                        <select class="form-control" id="trang_thai" name="trang_thai" required>
                            <option value="">-- Chọn trạng thái của vật tư --</option>
                            <option value="con hang">Còn vật tư</option>
                            <option value="het hang">Hết vật tư</option>

                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="trang_thai_han">Trạng thái hạn sử dụng*</label>
                        <select class="form-control" id="trang_thai_han" name="trang_thai_han" required>
                            <option value="">-- Hạn sử dụng của vật tư --</option>
                            <option value="con han">Còn hạn</option>
                            <option value="het han">Hết hạn</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="gia_nhap">Giá Nhập *</label>
                        <input type="number" class="form-control" id="gia_nhap" name="gia_nhap" placeholder="Giá Nhập" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="han_su_dung">Hạn Sử Dụng *</label>
                        <input type="date" class="form-control" id="han_su_dung" name="han_su_dung" required>
                        <div id="suggest-box" class="list-group position-absolute w-100" style="z-index:999;"></div>

                    </div>

                </div>




                <hr>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Thêm Vật Tư / Sản Phẩm
                    </button>

                    <a href="admin.php?admin=materials" class="btn btn-info">
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


<script>
    const input = document.getElementById("ten_vat_tu");
    const box = document.getElementById("suggest-box");

    input.addEventListener("keyup", function() {
        let keyword = this.value;

        if (keyword.length < 1) {
            box.innerHTML = "";
            return;
        }

        fetch("admin.php?admin=searchMaterials&q=" + encodeURIComponent(keyword))
            .then(res => res.json())
            .then(data => {
                box.innerHTML = "";
                data.forEach(item => {
                    let div = document.createElement("a");
                    div.classList.add("list-group-item", "list-group-item-action");
                    div.textContent = item.ten_vat_tu;

                    div.onclick = function() {
                        input.value = item.ten_vat_tu;
                        document.getElementById("don_vi").value = item.don_vi ?? "";
                        document.getElementById("gia_nhap").value = item.gia_nhap ?? "";
                        document.getElementById("hang_san_xuat").value = item.hang_san_xuat ?? "";
                        document.getElementById("danh_muc").value = item.danh_muc ?? "";
                        document.getElementById("type").value = item.type ?? "0";
                        document.getElementById("trang_thai_su_dung").value = item.trang_thai_su_dung ?? "1";

                        box.innerHTML = "";
                    };

                    box.appendChild(div);
                });
            });
    });
</script>


</body>

</html>
