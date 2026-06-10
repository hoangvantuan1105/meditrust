<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Sửa gói điều trị</title>

    <!-- CSS admin giống form thêm -->
    <link rel="stylesheet" href="backend/assets/css/admin-main.css">
    <link rel="stylesheet" href="backend/assets/css/admin-main2.css">
    <link href="backend/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
</head>

<body>

    <div class="container-fluid">

        <!-- Tiêu đề -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Sửa gói điều trị</h1>
        </div>

        <!-- Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    Chỉnh sửa thông tin gói điều trị
                </h6>
            </div>

            <div class="card-body">
                <form method="POST"
                    action="<?php
                            echo isset($item)
                                ? 'admin.php?admin=editTreatmentPackage&idAdmin=' . $item['id']
                                : 'admin.php?admin=storeTreatmentPackage';
                            ?>">

                    <!-- Row 1 -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Mã gói <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="ma_goi"
                                value="<?php echo htmlspecialchars($package['ma_goi']); ?>" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Tên gói <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="ten_goi"
                                value="<?php echo htmlspecialchars($package['ten_goi']); ?>" required>
                        </div>
                    </div>

                    <!-- Row 2 -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Loại gói <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="loai_id"
                                value="<?php echo htmlspecialchars($package['loai_id']); ?>" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Giá (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="gia"
                                value="<?php echo htmlspecialchars($package['gia']); ?>" required>
                        </div>
                    </div>

                    <!-- Mô tả -->
                    <div class="form-group">
                        <label>Mô tả</label>
                        <textarea class="form-control" name="mo_ta" rows="4"><?php
                                                                                echo htmlspecialchars($package['mo_ta']);
                                                                                ?></textarea>
                    </div>

                    <!-- Button -->
                    <div class="text-right">
                        <a href="admin.php?admin=treatmentPackageIndex" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Cập nhật
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>

</body>

</html>