<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Thêm Bác Sĩ</title>

    <!-- CSS giống template vật tư -->
    <link rel="stylesheet" href="backend/assets/css/admin-main.css">
    <link rel="stylesheet" href="backend/assets/css/admin-main2.css">
    <link href="backend/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="container-fluid">

        <!-- Tiêu đề -->
        <h1 class="h3 mb-2 text-gray-800">Thêm bác sĩ mới</h1>
        <p class="mb-4">Điền thông tin bác sĩ vào hệ thống.</p>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Biểu Mẫu Thêm Bác Sĩ</h6>
            </div>
            <div class="card-body">
                <!-- ✅ thêm enctype để upload file -->
                <form id="addDoctorForm" method="POST" action="admin.php?admin=addBacSi" enctype="multipart/form-data" novalidate>
                    <div class="form-row">
                        <div class="form-group col-md-6 position-relative">
                            <label for="ten_bac_si">Họ tên bác sĩ *</label>
                            <input type="text" class="form-control" id="ten_bac_si" name="ten_bac_si"
                                placeholder="Nhập họ tên bác sĩ" autocomplete="off" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="sdt">Số điện thoại *</label>
                            <input type="text" class="form-control" id="sdt" name="sdt" placeholder="Nhập SĐT" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Nhập email">
                        </div>

                        <div class="form-group col-md-6 position-relative">
                            <label for="chuyen_mon">Chuyên môn *</label>
                            <input type="text" class="form-control" id="chuyen_mon" name="chuyen_mon"
                                placeholder="Ví dụ: Implant, Chỉnh nha" autocomplete="off" required>
                            <div id="suggest-specialty" class="list-group position-absolute w-100" style="z-index:999;"></div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="gioi_tinh">Giới tính *</label>
                            <select class="form-control" id="gioi_tinh" name="gioi_tinh" required>
                                <option value="">-- Chọn giới tính --</option>
                                <option value="nam">Nam</option>
                                <option value="nu">Nữ</option>
                                <option value="khac">Khác</option>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="ca_lam">Ca làm *</label>
                            <select class="form-control" id="ca_lam" name="ca_lam" required>
                                <option value="">-- Chọn ca làm --</option>
                                <option value="sang">Sáng</option>
                                <option value="chieu">Chiều</option>
                                <option value="full">Full</option>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="trang_thai">Trạng thái *</label>
                            <select class="form-control" id="trang_thai" name="trang_thai" required>
                                <option value="">-- Chọn trạng thái --</option>
                                <option value="dang_lam">Đang làm việc</option>
                                <option value="nghi">Nghỉ</option>
                            </select>
                        </div>
                    </div>

                    <!-- ✅ Thêm trường upload ảnh -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="photo">Ảnh bác sĩ</label>
                            <input type="file" class="form-control-file" id="photo" name="photo" accept="image/*">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="ngay_tao">Ngày tạo</label>
                            <input type="text" class="form-control" id="ngay_tao" name="ngay_tao"
                                value="<?= date('d/m/Y H:i') ?>" readonly>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm bác sĩ
                        </button>

                        <a href="admin.php?admin=qlybacsi" class="btn btn-info">
                            <i class="fas fa-arrow-left"></i> Quay Lại
                        </a>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- JS giống template vật tư -->
    <script src="backend/assets/vendor/jquery/jquery.min.js"></script>
    <script src="backend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="backend/assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="backend/assets/js/sb-admin-2.min.js"></script>

    <!-- Script gợi ý chuyên môn -->
    <script>
        (function() {
            const input = document.getElementById("chuyen_mon");
            const box = document.getElementById("suggest-specialty");

            if (!input) return;

            input.addEventListener("keyup", function() {
                let keyword = this.value.trim();
                if (keyword.length < 1) {
                    box.innerHTML = "";
                    return;
                }

                fetch("admin.php?admin=searchSpecialty&q=" + encodeURIComponent(keyword))
                    .then(res => res.json())
                    .then(data => {
                        box.innerHTML = "";
                        data.forEach(item => {
                            let a = document.createElement("a");
                            a.classList.add("list-group-item", "list-group-item-action");
                            a.textContent = item.chuyen_mon || item.name || item;
                            a.href = "javascript:void(0)";
                            a.onclick = function() {
                                input.value = item.chuyen_mon || item.name || item;
                                box.innerHTML = "";
                            };
                            box.appendChild(a);
                        });
                    })
                    .catch(() => {
                        box.innerHTML = "";
                    });
            });

            const form = document.getElementById('addDoctorForm');
            form.addEventListener('submit', function(e) {
                const ten = document.getElementById('ten_bac_si').value.trim();
                const sdt = document.getElementById('sdt').value.trim();
                if (!ten || !sdt) {
                    e.preventDefault();
                    alert('Vui lòng điền đầy đủ Họ tên và SĐT.');
                }
            });
        })();
    </script>

</body>

</html>