<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo Đơn Thuốc Nha Khoa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .prescription-card {
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: none;
        }

        .card-header {
            border-radius: 15px 15px 0 0 !important;
        }

        .table-input {
            border: 1px solid transparent;
            background: transparent;
            width: 100%;
            padding: 5px;
            transition: 0.3s;
        }

        .table-input:focus {
            outline: none;
            border-bottom: 1px solid #0d6efd;
            background: #fff;
        }

        .ts-wrapper.select-drug .ts-control {
            border: none !important;
            background: transparent !important;
            box-shadow: none !important;
            padding: 0 !important;
        }

        .table td {
            vertical-align: middle;
        }

        /* Ẩn mũi tên của TomSelect để giống ô Input bình thường */
        .ts-wrapper.select-drug .ts-control::after {
            display: none !important;
        }

        .ts-wrapper .ts-control {
            border: none !important;
            padding: 8px !important;
            background: transparent !important;
        }

        /* Làm cho danh sách gợi ý đổ xuống gọn gàng */
        .ts-dropdown {
            margin-top: 5px !important;
            border-radius: 8px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="card prescription-card">
            <div class="card-header bg-primary text-white p-3">
                <h4 class="mb-0"><i class="bi bi-file-earmark-medical me-2"></i>TẠO ĐƠN THUỐC MỚI</h4>
            </div>
            <div class="card-body p-4">
                <form id="prescriptionForm" method="post" action="admin.php?admin=prescription">
                    <div class="row mb-4">
                        <div class="col-md-5 mb-3">
                            <label class="form-label fw-bold">Tìm kiếm bệnh nhân</label>
                            <select id="select-patient" name="patient_id" class="form-select" required>
                                <option value="">Gõ tên hoặc mã BN để tìm...</option>
                                <?php foreach ($namePatient as $np): ?>
                                    <option value="<?= $np['id'] ?>"><?= $np['ho_ten'] ?> (Mã: <?= $np['id'] ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Bác sĩ kê đơn</label>
                            <select id="select-doctor" name="doctor_id" class="form-select" required>
                                <option value="">Tìm tên bác sĩ...</option>
                                <?php foreach ($doctor as $dt): ?>
                                    <option value="<?= $dt['id'] ?>"><?= $dt['ten_bac_si'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>



                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Chẩn đoán</label>
                            <textarea name="diagnose" class="form-control" rows="2" placeholder="Điền chẩn đoán bệnh...."></textarea>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                            <h5 class="text-primary mb-0"><i class="bi bi-list-check me-2"></i>2. Danh mục thuốc</h5>
                            <button type="button" class="btn btn-sm btn-success" onclick="addRow()"><i class="bi bi-plus-circle"></i> Thêm thuốc</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle" id="drugTable">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th style="width: 30%;">Tên thuốc</th>
                                        <th style="width: 15%;">Hàm lượng</th>
                                        <th style="width: 10%;">Số lượng</th>
                                        <th style="width: 10%;">Đơn vị</th>
                                        <th style="width: 10%;">Liều dùng</th>
                                        <th style="width: 5%;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input type="text"
                                                name="nameMedicine[]"
                                                class="table-input thuoc-input"
                                                list="list-thuoc"
                                                placeholder="Nhập tên thuốc..."
                                                oninput="handleDrugInput(this)">

                                            <datalist id="list-thuoc">
                                                <?php foreach ($medicines as $m): ?>
                                                    <option value="<?= $m['ten_thuoc'] ?>"
                                                        data-id="<?= $m['id'] ?>"
                                                        data-hamluong="<?= $m['ham_luong'] ?>"
                                                        data-donvi="<?= $m['don_vi_tinh'] ?>">
                                                    <?php endforeach; ?>
                                            </datalist>

                                            <input type="hidden" name="idMedicine[]" class="thuoc-id-hidden">
                                        </td>

                                        <td><input type="text" name="drugContent[]" class="table-input ham-luong-input" placeholder="..." readonly></td>
                                        <td><input type="number" name="quantityMedicine[]" class="table-input text-center" value="1" min="1"></td>
                                        <td>
                                            <select name="unitMedicine[]" class="table-input don-vi-select">
                                                <option value="viên">Viên</option>
                                                <option value="ống">Ống</option>
                                                <option value="hộp">Hộp</option>
                                            </select>
                                        </td>
                                        <td><input type="text" name="dosage[]" class="table-input text-center" placeholder="Liều dùng"></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm border-0" onclick="deleteRow(this)"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="reset" class="btn btn-outline-secondary px-4">Làm mới</button>
                        <button type="submit" name="save_prescription" class="btn btn-dark px-4 shadow-sm">
                            <i class="bi bi-printer me-2"></i>Lưu và In Đơn
                        </button>
                        <a href="admin.php?admin=listPrescription" class="link-primary p-2">Trở về</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function handleDrugInput(input) {
            const inputValue = input.value;
            const row = input.closest('tr');
            const datalist = document.getElementById('list-thuoc');

            // Tìm option khớp với tên thuốc vừa nhập/chọn
            const option = Array.from(datalist.options).find(opt => opt.value === inputValue);

            if (option) {
                // 1. Điền ID vào input ẩn để submit form
                row.querySelector('.thuoc-id-hidden').value = option.getAttribute('data-id');

                // 2. Tự động điền Hàm lượng
                row.querySelector('.ham-luong-input').value = option.getAttribute('data-hamluong') || '';

                // 3. Tự động chọn Đơn vị tính
                const donVi = option.getAttribute('data-donvi');
                const donViSelect = row.querySelector('.don-vi-select');
                if (donViSelect) {
                    donViSelect.value = donVi;
                }
            } else {
                // Nếu xóa trắng hoặc nhập tên không có trong danh sách thì xóa dữ liệu cũ
                row.querySelector('.thuoc-id-hidden').value = '';
                row.querySelector('.ham-luong-input').value = '';
            }
        }

        // Hàm thêm dòng mới (Cập nhật lại cho đúng cấu trúc input)
        function addRow() {
            const tbody = document.querySelector('#drugTable tbody');
            const newRow = tbody.insertRow();
            newRow.innerHTML = `
        <td>
            <input type="text" name="nameMedicine[]" class="table-input thuoc-input" list="list-thuoc" placeholder="Nhập tên thuốc..." oninput="handleDrugInput(this)">
            <input type="hidden" name="idMedicine[]" class="thuoc-id-hidden">
        </td>
        <td><input type="text" name="drugContent[]" class="table-input ham-luong-input" readonly></td>
        <td><input type="number" name="quantityMedicine[]" class="table-input text-center" value="1"></td>
        <td>
            <select name="unitMedicine[]" class="table-input don-vi-select">
                <option value="Viên">Viên</option><option value="Ống">Ống</option><option value="Gói">Gói</option>
            </select>
        </td>
        <td><input type="text" name="dosage[]" class="table-input text-center" placeholder="Liều dùng"></td>
       <td class="text-center">
    <button type="button" class="btn btn-outline-danger btn-sm border-0" onclick="deleteRow(this)">
        <i class="bi bi-trash"></i>
    </button>
</td>
    `;
        }

        function deleteRow(btn) {
            const tbody = document.querySelector('#drugTable tbody');
            const rowCount = tbody.querySelectorAll('tr').length;

            // Nếu còn nhiều hơn 1 dòng thì mới cho xóa
            if (rowCount > 1) {
                btn.closest('tr').remove();
            } else {
                alert("Đơn thuốc phải có ít nhất một loại thuốc!");
            }
        }
    </script>

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
                if (status === 'error') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: 'Lỗi bệnh nhân này không có lịch sử khám!',
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