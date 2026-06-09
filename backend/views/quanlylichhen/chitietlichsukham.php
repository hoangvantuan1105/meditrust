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

            <!-- X-Quang của lần khám này -->
            <h6 class="text-primary mb-3"><i class="fas fa-x-ray mr-1"></i> X-Quang Lần Khám Này</h6>
            <?php
            $xrayList = [];
            if (!empty($data['lich_kham_id'])) {
                require_once __DIR__ . '/../../models/db.php';
                $xrayModel = new modelClinic();
                $xrayModel->ketNoiDB();
                $xrayList = $xrayModel->getXrayByLichKham($data['lich_kham_id']);
            }
            ?>
            <?php if (empty($xrayList)): ?>
                <p class="text-muted">Không có X-quang cho lần khám này.</p>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($xrayList as $x): ?>
                        <?php $loaiMap = [
                            'toan_ham' => 'Toàn Hàm',
                            'rang_cu' => 'Răng Cụ Thể',
                            'cat_loc' => 'Cắn Lọc',
                            'cat_ngang' => 'Cắt Ngang',
                            'cbct_3d' => 'CBCT 3D'
                        ]; ?>
                        <div class="col-md-4 mb-3">
                            <div class="card border">
                                <img src="backend/uploads/xray/<?= htmlspecialchars($x['file_path']) ?>"
                                    style="height:130px;object-fit:cover;cursor:pointer;"
                                    onclick="window.open(this.src,'_blank')"
                                    class="card-img-top">
                                <div class="card-body p-2">
                                    <small class="font-weight-bold"><?= $loaiMap[$x['loai_xray']] ?? $x['loai_xray'] ?></small>
                                    <?php if ($x['vi_tri']): ?>
                                        <br><small class="text-muted">📍 <?= htmlspecialchars($x['vi_tri']) ?></small>
                                    <?php endif; ?>
                                    <?php if (!empty($x['ket_qua_bac_si'])): ?>
                                        <div class="mt-1 p-1 bg-light rounded" style="font-size:11px;">
                                            <?= htmlspecialchars(substr($x['ket_qua_bac_si'], 0, 80)) ?>...
                                        </div>
                                    <?php endif; ?>
                                    <a href="admin.php?admin=viewXray&idAdmin=<?= $x['id'] ?>"
                                        class="btn btn-xs btn-outline-info btn-block mt-1" style="font-size:11px;">
                                        Xem đầy đủ
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>