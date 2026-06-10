<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Chi tiết lịch sử khám</h5>
            <button onclick="window.history.back()" class="btn btn-secondary">Quay lại</button>
        </div>

        <div class="card-body">

            <!-- Thông tin bệnh nhân -->
            <h6 class="text-primary mb-3">Thông tin bệnh nhân</h6>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Mã hồ sơ bệnh nhân:</strong>
                    <p><?= $data['ho_so_benh_nhan_id'] ?? 'Chưa có dữ liệu' ?></p>
                </div>

                <div class="col-md-6">
                    <strong>Tên bệnh nhân:</strong>
                    <p><?= $data['ho_ten'] ?? 'Chưa có dữ liệu' ?></p>
                </div>

                <div class="col-md-6">
                    <strong>Bảo hiểm y tế:</strong>
                    <p><?= $data['bao_hiem_y_te'] ?? 'Chưa có dữ liệu' ?></p>
                </div>

                <div class="col-md-6">
                    <strong>Tiền sử bệnh:</strong>
                    <p><?= $data['tien_su_benh'] ?? 'Chưa có dữ liệu' ?></p>
                </div>
            </div>

            <hr>

            <!-- Thông tin khám -->
            <h6 class="text-primary mb-3">Thông tin khám</h6>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Bác sĩ phụ trách:</strong>
                    <p><?= $data['ten_bac_si'] ?? 'Chưa có dữ liệu' ?></p>
                </div>

                <div class="col-md-6">
                    <strong>Chẩn đoán:</strong>
                    <p><?= $data['chan_doan'] ?? 'Chưa có dữ liệu' ?></p>
                </div>

                <div class="col-md-12">
                    <strong>Dịch vụ thực hiện:</strong>
                    <p>
                        <?= $data['danh_sach_dich_vu'] ?? 'Chưa có dịch vụ' ?>
                    </p>
                </div>
            </div>
            <hr>



        </div>
    </div>
</div>