<style>
    .ai-summary-body {
        background: #f8f9fc;
        border-radius: 8px;
        padding: 20px;
        font-size: 14px;
        line-height: 1.8;
        white-space: pre-wrap;
    }
    .ai-summary-body strong { color: #4e73df; }
    #aiSummaryResult h6 { margin-top: 12px; }
    .ai-loading-dots span {
        animation: blink 1.4s infinite;
        animation-fill-mode: both;
        font-size: 20px;
    }
    .ai-loading-dots span:nth-child(2) { animation-delay: .2s; }
    .ai-loading-dots span:nth-child(3) { animation-delay: .4s; }
    @keyframes blink {
        0%   { opacity: .2; }
        20%  { opacity: 1; }
        100% { opacity: .2; }
    }
</style>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi tiết hồ sơ bệnh nhân</h1>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-primary shadow-sm" onclick="requestAiSummary('<?php echo htmlspecialchars($item['id'] ?? ''); ?>')">
                <i class="fas fa-robot fa-sm"></i> AI Tóm tắt hồ sơ
            </button>
            <a href="admin.php?admin=dsbenhnhan" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin cá nhân</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($item)): ?>
                        <form>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bold">ID:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext"><?php echo htmlspecialchars($item['id'] ?? ''); ?></p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bold">Họ Tên:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext"><?php echo htmlspecialchars($item['ho_ten'] ?? ''); ?></p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bold">Giới tính:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext"><?php echo htmlspecialchars($item['gioi_tinh'] ?? ''); ?></p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bold">Ngày sinh:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext"><?php echo htmlspecialchars($item['ngay_sinh'] ?? ''); ?></p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bold">Số Điện Thoại:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext"><?php echo htmlspecialchars($item['so_dien_thoai'] ?? ''); ?></p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bold">Email:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext"><?php echo htmlspecialchars($item['email'] ?? ''); ?></p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bold">Địa chỉ:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext"><?php echo htmlspecialchars($item['dia_chi'] ?? ''); ?></p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bold">CMND/CCCD:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext"><?php echo htmlspecialchars($item['cmnd_cccd'] ?? ''); ?></p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bold">Bảo Hiểm Y Tế:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext"><?php echo htmlspecialchars($item['bao_hiem_y_te'] ?? ''); ?></p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bold">Tiền sử bệnh:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext"><?php echo htmlspecialchars($item['tien_su_benh'] ?? ''); ?></p>
                                </div>
                            </div>

                           
                            

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bold">Ngày tạo:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext"><?php echo htmlspecialchars($item['ngay_tao'] ?? ''); ?></p>
                                </div>
                            </div>
                        </form>

                        <hr>
                        <div class="d-flex gap-2">
                            <a href="admin.php?admin=edit&id=<?php echo $item['id']; ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Sửa thông tin
                            </a>
                            <a href="admin.php?admin=delete&id=<?php echo $item['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa bệnh nhân này?')">
                                <i class="fas fa-trash"></i> Xóa bệnh nhân
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            Không tìm thấy thông tin bệnh nhân
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Người liên hệ khẩn cấp</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($item)): ?>
                        <div class="mb-3">
                            <label class="font-weight-bold">Tên người liên hệ:</label>
                            <p><?php echo htmlspecialchars($item['nguoi_lien_he_khan_cap'] ?? ''); ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="font-weight-bold">Quan hệ:</label>
                            <p><?php echo htmlspecialchars($item['quan_he'] ?? ''); ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="font-weight-bold">Số điện thoại:</label>
                            <p><?php echo htmlspecialchars($item['sdt_nguoi_lien_he'] ?? ''); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Summary Card -->
    <div class="row" id="aiSummaryCard" style="display:none !important;">
        <div class="col-12">
            <div class="card shadow mb-4 border-left-primary">
                <div class="card-header py-3 d-flex justify-content-between align-items-center bg-gradient-primary">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-robot mr-2"></i> Tóm tắt hồ sơ bằng AI
                    </h6>
                    <button class="btn btn-sm btn-light" onclick="document.getElementById('aiSummaryCard').style.display='none'">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div id="aiLoadingState" class="text-center py-4" style="display:none;">
                        <div class="ai-loading-dots text-primary mb-2">
                            <span>&#9679;</span><span>&#9679;</span><span>&#9679;</span>
                        </div>
                        <p class="text-muted mb-0">AI đang phân tích hồ sơ bệnh nhân...</p>
                    </div>
                    <div id="aiSummaryResult" class="ai-summary-body" style="display:none;"></div>
                    <div id="aiErrorState" class="alert alert-danger" style="display:none;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Lịch sử khám</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($lichSuKham)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 70px;">#</th>
                                        <th>Ngày khám</th>
                                        <th>Bác sĩ</th>
                                        <th>Dịch vụ</th>
                                        <th>Chẩn đoán</th>
                                        <th>Hướng điều trị</th>
                                        <th>Ghi chú</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($lichSuKham as $index => $ls): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= htmlspecialchars($ls['ngay_kham'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($ls['ten_bac_si'] ?? '---') ?></td>
                                            <td><?= htmlspecialchars($ls['danh_sach_dich_vu'] ?? '---') ?></td>
                                            <td><?= htmlspecialchars($ls['chan_doan'] ?? '---') ?></td>
                                            <td><?= htmlspecialchars($ls['huong_dieu_tri'] ?? '---') ?></td>
                                            <td><?= htmlspecialchars($ls['ghi_chu'] ?? '---') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info mb-0">
                            Bệnh nhân này chưa có lịch sử khám.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-pills mr-1"></i> Lịch sử đơn thuốc
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($donThuocList)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width:50px;">#</th>
                                        <th>Ngày kê đơn</th>
                                        <th>Chẩn đoán</th>
                                        <th>Thuốc đã dùng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($donThuocList as $idx => $don): ?>
                                        <tr>
                                            <td><?= $idx + 1 ?></td>
                                            <td><?= htmlspecialchars($don['ngay_ke_don'] ?? '---') ?></td>
                                            <td><?= htmlspecialchars($don['chan_doan'] ?? '---') ?></td>
                                            <td>
                                                <?php
                                                $thuocs = explode('; ', $don['thuoc_list'] ?? '');
                                                foreach ($thuocs as $t): ?>
                                                    <span class="badge badge-light border mb-1" style="font-size:12px; white-space:normal;">
                                                        <i class="fas fa-capsules mr-1 text-primary"></i><?= htmlspecialchars($t) ?>
                                                    </span>
                                                <?php endforeach; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info mb-0">
                            Bệnh nhân này chưa có đơn thuốc nào.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<script>
function requestAiSummary(patientId) {
    const card    = document.getElementById('aiSummaryCard');
    const loading = document.getElementById('aiLoadingState');
    const result  = document.getElementById('aiSummaryResult');
    const errBox  = document.getElementById('aiErrorState');

    card.style.removeProperty('display');
    loading.style.display = 'block';
    result.style.display  = 'none';
    errBox.style.display  = 'none';

    card.scrollIntoView({ behavior: 'smooth', block: 'start' });

    fetch('admin.php?admin=aiSummarizePatient&id=' + encodeURIComponent(patientId))
        .then(r => r.text())
        .then(text => {
            loading.style.display = 'none';
            let data;
            try {
                data = JSON.parse(text);
            } catch (e) {
                errBox.innerHTML     = '<b>Lỗi parse JSON:</b><br><pre style="font-size:11px;white-space:pre-wrap">' + text.substring(0, 500) + '</pre>';
                errBox.style.display = 'block';
                return;
            }
            if (data.error) {
                errBox.textContent   = data.error;
                errBox.style.display = 'block';
                return;
            }
            result.innerHTML     = markdownToHtml(data.summary);
            result.style.display = 'block';
        })
        .catch(err => {
            loading.style.display = 'none';
            errBox.textContent    = 'Fetch thất bại: ' + err.message;
            errBox.style.display  = 'block';
        });
}

function markdownToHtml(text) {
    return text
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
        .replace(/\*(.+?)\*/g, '<em>$1</em>')
        .replace(/\n/g, '<br>');
}
</script>
