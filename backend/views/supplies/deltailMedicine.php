<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Chi tiết thuốc</h5>
            <a href="admin.php?admin=listMedicine" class="link-primary p-2">Trở về</a>
        </div>

        <div class="card-body">

            <!-- Thông tin cơ bản -->
            <h6 class="text-primary mb-3">Thông tin cơ bản</h6>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Mã thuốc:</strong>
                    <p><?= $detailMedicineByID['id'] ?></p>
                </div>
                <div class="col-md-6">
                    <strong>Tên thuốc:</strong>
                    <p><?= $detailMedicineByID['ten_thuoc'] ?></p>
                </div>
                <div class="col-md-6">
                    <strong>Nhóm thuốc:</strong>
                    <p><?= $detailMedicineByID['nhom_thuoc'] ?></p>
                </div>
                <div class="col-md-6">
                    <strong>Dạng bào chế:</strong>
                    <p><?= $detailMedicineByID['dang_bao_che'] ?></p>
                </div>
            </div>

            <hr>

            <!-- Thông tin sử dụng -->
            <h6 class="text-primary mb-3">Thông tin sử dụng</h6>
            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>Hàm lượng:</strong>
                    <p><?= $detailMedicineByID['ham_luong'] ?></p>
                </div>
                <div class="col-md-4">
                    <strong>Đơn vị tính:</strong>
                    <p><?= $detailMedicineByID['don_vi_tinh'] ?></p>
                </div>
                <div class="col-md-4">
                    <strong>Số lượng:</strong>
                    <p><?= $detailMedicineByID['so_luong'] ?></p>
                </div>
                <div class="col-md-4">
                    <strong>Hạn sử dụng:</strong>
                    <p><?= $detailMedicineByID['han_su_dung'] ?></p>
                </div>
                <div class="col-md-4">
                    <strong>Giá nhập:</strong>
                    <p><?= number_format($detailMedicineByID['gia_nhap']) ?> VNĐ</p>
                </div>
            </div>

            <hr>

            <!-- Nguồn gốc -->
            <h6 class="text-primary mb-3">Nguồn gốc & ghi chú</h6>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Hãng sản xuất:</strong>
                    <p><?= $detailMedicineByID['hang_san_xuat'] ?></p>
                </div>
                <div class="col-md-6">
                    <strong>Nước sản xuất:</strong>
                    <p><?= $detailMedicineByID['nuoc_san_xuat'] ?></p>
                </div>
                <div class="col-md-12">
                    <strong>Ghi chú:</strong>
                    <p class="border rounded p-3 bg-light">
                        <?= $detailMedicineByID['ghi_chu'] ?>
                    </p>
                </div>
            </div>

        </div>

        <div class="card-footer text-end">
            <a href="admin.php?admin=formEditMedicine&idAdmin=<?= $detailMedicineByID['id'] ?>" class="btn btn-success btn-circle btn-sm" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
        </div>
    </div>
</div>
