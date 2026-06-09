<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Thêm Thuốc</title>

    <!-- Custom fonts for this template -->
    <link rel="stylesheet" href="backend/assets/css/admin-main.css">
    <link rel="stylesheet" href="backend/assets/css/admin-main2.css">
    <link href="backend/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
</head>


                <!-- Bắt Đầu Nội Dung Trang -->
                <div class="container-fluid">

                    <!-- Tiêu Đề Trang -->
                    <h1 class="h3 mb-2 text-gray-800">Thêm Thuốc mới</h1>
                    <p class="mb-4">Điền các thông tin dưới đây để thêm thuốc mới vào hệ thống.</p>

                    <!-- Form Thêm Người Dùng -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Biểu Mẫu Thêm thuốc</h6>
                        </div>
                        <div class="card-body">
                            <form id="addUserForm" method="POST" action="admin.php?admin=addMedicine">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="medicineName">Tên Thuốc *</label>
                                        <input type="text" class="form-control" id="medicineName" name="medicineName" placeholder="Nhập tên đầy đủ" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="classMedicine">Nhóm thuốc *</label>
                                        <select class="form-control" id="classMedicine" name="classMedicine" required>
                                            <option value="">-- Chọn nhóm thuốc --</option>
                                            <option value="Giảm đau">Giảm đau</option>
                                            <option value="Kháng sinh">Kháng sinh</option>
                                            <option value="Thuốc tê">Thuốc tê</option>
                                            <option value="Kháng viêm">Kháng viêm</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="dosageForm">Dạng bào chế *</label>
                                        <select class="form-control" id="dosageForm" name="dosageForm" required>
                                            <option value="">-- Chọn dạng bào chế --</option>
                                            <option value="Viên">Viên</option>
                                            <option value="Ống">Ống</option>
                                            <option value="Dung dịch">Dung dịch</option>
                                            <option value="Gel">Gel</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="drugContent">Hàm lượng *</label>
                                        <input type="text" class="form-control" id="drugContent" name="drugContent" placeholder="Nồng độ / liều lượng" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="unit">Đơn vị tính *</label>
                                        <input type="text" class="form-control" id="unit" name="unit" placeholder="Viên / hộp / ống" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="quantity">Số lượng *</label>
                                        <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Điền số lượng thuốc" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="expirationDate">Hạn dùng *</label>
                                        <input type="date" class="form-control" id="expirationDate" name="expirationDate" placeholder="Điền hạn dùng của thuốc" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="price">Giá nhập *</label>
                                        <input type="text" class="form-control" id="price" name="price" placeholder="Giá nhập thuốc" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="manufacturer">Hãng sản xuất *</label>
                                        <input type="text" class="form-control" id="manufacturer" name="manufacturer" placeholder="Hãng sản xuất" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="countryProduction">Nước sản xuất *</label>
                                        <input type="text" class="form-control" id="countryProduction" name="countryProduction" placeholder="Nước sản xuất" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <textarea name="description" id="" placeholder="Ghi chú" class="form-control"></textarea>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                     Thêm thuốc mới
                                </button>

                                <a href="admin.php?admin=listMedicine" class="link-primary p-2">Trở về</a>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Chân Trang -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Bản Quyền &copy; Trang Web Của Bạn 2020</span>
                    </div>
                </div>
            </footer>
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
        // Gọi khi tài liệu sẵn sàng
        $(document).ready(function() {
            // Xác thực form trước khi gửi
            $('#addUserForm').on('submit', function(e) {
                var salary = $('#salary').val();
                var age = $('#age').val();
                
                // Kiểm tra lương
                if (salary && salary < 0) {
                    alert('Lương không được âm!');
                    e.preventDefault();
                    return false;
                }
                
                // Kiểm tra tuổi
                if (age && (age < 18 || age > 80)) {
                    alert('Tuổi phải từ 18 đến 80!');
                    e.preventDefault();
                    return false;
                }
            });
            
            // Định dạng lương khi nhập
            $('#salary').on('keyup', function() {
                var value = $(this).val();
                value = value.replace(/\D/g, '');
                $(this).val(value);
            });
        });
    </script>

</body>

</html>