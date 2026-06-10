<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Chi tiết dịch vụ</title>
    <link rel="stylesheet" href="backend/assets/css/admin-main.css">
    <link rel="stylesheet" href="backend/assets/css/admin-main2.css">
    <link href="backend/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container-fluid mt-4">

        <!-- ===== HEADER ===== -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Chi tiết dịch vụ</h1>
            <div>
                <?php if (!empty($chitetdichvudichvu)): ?>
                    <a href="admin.php?admin=suadichvu&idAdmin=<?= $chitetdichvudichvu['id'] ?>"
                        class="btn btn-success btn-sm shadow-sm">
                        <i class="fas fa-edit"></i> Sửa dịch vụ
                    </a>
                <?php endif; ?>
                <a href="admin.php?admin=qlydichvu" class="btn btn-secondary btn-sm shadow-sm">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>

        <!-- ===== CARD ===== -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Thông tin dịch vụ</h6>
            </div>
            <div class="card-body">
                <?php if (!empty($chitetdichvudichvu)): ?>
                    <?php
                    // Tính tổng tiền vật tư trước để dùng cho việc hiển thị
                    $tongTienVatTu = 0;
                    if (!empty($vatTuSuDung)) {
                        foreach ($vatTuSuDung as $vt)
                            $tongTienVatTu += $vt['gia_nhap'] * $vt['so_luong'];
                    }
                    ?>
                    <div class="row mb-3">
                        <!-- Ảnh dịch vụ -->
                        <div class="col-md-4 text-center">
                            <img src="backend/uploads/services/<?= htmlspecialchars($chitetdichvudichvu['image'] ?? 'uploads/no-image.png') ?>"
                                width="150" class="img-thumbnail rounded" alt="Ảnh dịch vụ">
                        </div>

                        <!-- Thông tin dịch vụ -->
                        <div class="col-md-8">
                            <h4 class="fw-bold mb-3">
                                <?= htmlspecialchars($chitetdichvudichvu['ten_dich_vu'] ?? '') ?>
                            </h4>

                            <p><strong>Loại danh mục:</strong> <?= htmlspecialchars($chitetdichvudichvu['danhmuc'] ?? '') ?>
                            </p>

                            <p>
                                <strong>Giá công khám:</strong>
                                <span class="fw-bold fs-5">
                                    <!-- Giá công = Giá trọn gói (DB) - Tổng vật tư -->
                                    <?= number_format((float) (($chitetdichvudichvu['gia'] ?? 0) - $tongTienVatTu), 0, ',', '.') ?>
                                    đ
                                </span>
                            </p>
                            <p>
                                <strong>Giá trọn gói (Tổng):</strong>
                                <span class="text-danger fw-bold fs-5">
                                    <?= number_format((float) ($chitetdichvudichvu['gia'] ?? 0), 0, ',', '.') ?> đ
                                </span>
                            </p>
                            <p>
                                <strong>Trạng thái:</strong>
                                <?php if (($chitetdichvudichvu['trang_thai'] ?? '') === 'Hoạt động'): ?>
                                    <span class="badge bg-success">Hoạt động</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Ngừng hoạt động</span>
                                <?php endif; ?>
                            </p>



                            <p class="text-muted">
                                <?= htmlspecialchars($chitetdichvudichvu['mo_ta'] ?? '') ?>
                            </p>
                        </div>
                    </div>

                    <!-- Vật tư sử dụng -->
                    <div class="card shadow-sm mt-4">
                        <div class="card-header py-2">
                            <h6 class="m-0 font-weight-bold text-primary">Vật tư sử dụng</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="thead-light">
                                    <tr class="text-center">
                                        <th>Tên vật tư</th>
                                        <th>Đơn giá</th>
                                        <th>Số lượng</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($vatTuSuDung)):
                                        ?>
                                        <?php foreach ($vatTuSuDung as $vt):
                                            $thanhTien = $vt['gia_nhap'] * $vt['so_luong'];
                                            ?>
                                            <tr>
                                                <td><?= htmlspecialchars($vt['ten_vat_tu']) ?></td>
                                                <td class="text-center"><?= number_format($vt['gia_nhap'], 0, ',', '.') ?> đ</td>
                                                <td class="text-center"><?= (int) $vt['so_luong'] ?></td>
                                                <td class="text-center fw-bold"><?= number_format($thanhTien, 0, ',', '.') ?> đ</td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <tr class="table-secondary">
                                            <td colspan="3" class="text-end fw-bold">Tổng tiền vật tư:</td>
                                            <td class="text-center fw-bold text-danger">
                                                <?= number_format($tongTienVatTu, 0, ',', '.') ?> đ</td>
                                        </tr>
                                        <!-- <tr class="table-info">
                                        <td colspan="3" class="text-end fw-bold">GIÁ TRỌN GÓI:</td>
                                        <td class="text-center fw-bold text-primary fs-5">
                                            <?= number_format(($chitetdichvudichvu['gia'] ?? 0), 0, ',', '.') ?> đ
                                        </td>
                                    </tr> -->
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">
                                                Chưa có vật tư nào cho dịch vụ này
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                <?php else: ?>
                    <div class="alert alert-danger">
                        Không tìm thấy thông tin dịch vụ
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

</body>

</html>