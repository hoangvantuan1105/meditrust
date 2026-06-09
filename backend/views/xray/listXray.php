<div class="container-fluid mt-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-x-ray mr-2 text-primary"></i> Quản Lý X-Quang
        </h1>
        <a href="admin.php?admin=listLichKham" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Về Lịch Khám
        </a>
    </div>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- Thống kê nhanh -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tổng X-Quang</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($listXray) ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Chờ Đọc</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?= count(array_filter($listXray, fn($x) => $x['trang_thai'] === 'cho_doc')) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Đã Đọc</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?= count(array_filter($listXray, fn($x) => $x['trang_thai'] === 'da_doc')) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Có AI Phân Tích</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?= count(array_filter($listXray, fn($x) => !empty($x['ai_phan_tich']))) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh Sách X-Quang</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="xrayTable">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Ảnh</th>
                            <th>Bệnh Nhân</th>
                            <th>Loại</th>
                            <th>Vị Trí</th>
                            <th>Bác Sĩ</th>
                            <th>Ngày Chụp</th>
                            <th>AI</th>
                            <th>Trạng Thái</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($listXray)): ?>
                            <tr><td colspan="10" class="text-center text-muted py-4">Chưa có X-quang nào</td></tr>
                        <?php else: ?>
                        <?php foreach ($listXray as $i => $x): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td>
                                <a href="admin.php?admin=viewXray&idAdmin=<?= $x['id'] ?>">
                                    <img src="backend/uploads/xray/<?= htmlspecialchars($x['file_path']) ?>"
                                         style="width:60px;height:45px;object-fit:cover;border-radius:4px;"
                                         onerror="this.src='backend/assets/img/undraw_profile.svg'">
                                </a>
                            </td>
                            <td><?= htmlspecialchars($x['ho_ten'] ?? '---') ?></td>
                            <td>
                                <?php $loaiMap = [
                                    'toan_ham'  => ['label'=>'Toàn Hàm',    'color'=>'primary'],
                                    'rang_cu'   => ['label'=>'Răng Cụ Thể', 'color'=>'info'],
                                    'cat_loc'   => ['label'=>'Cắn Lọc',     'color'=>'secondary'],
                                    'cat_ngang' => ['label'=>'Cắt Ngang',   'color'=>'dark'],
                                    'cbct_3d'   => ['label'=>'CBCT 3D',     'color'=>'warning'],
                                ]; $l = $loaiMap[$x['loai_xray']] ?? ['label'=>$x['loai_xray'],'color'=>'secondary']; ?>
                                <span class="badge badge-<?= $l['color'] ?>"><?= $l['label'] ?></span>
                            </td>
                            <td><?= htmlspecialchars($x['vi_tri'] ?? '---') ?></td>
                            <td><?= htmlspecialchars($x['ten_bac_si'] ?? '---') ?></td>
                            <td><?= date('d/m/Y', strtotime($x['ngay_chup'])) ?></td>
                            <td class="text-center">
                                <?php if (!empty($x['ai_phan_tich'])): ?>
                                    <span class="badge badge-success"><i class="fas fa-robot"></i> Có</span>
                                <?php else: ?>
                                    <span class="badge badge-light text-muted">Chưa</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($x['trang_thai'] === 'da_doc'): ?>
                                    <span class="badge badge-success">Đã Đọc</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Chờ Đọc</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="admin.php?admin=detailXray&idAdmin=<?= $x['id'] ?>"
                                   class="btn btn-sm btn-primary" title="Chi tiết">
                                    <i class="fas fa-file-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    if ($.fn.DataTable) {
        $('#xrayTable').DataTable({ order: [[6,'desc']], language: { url: '' } });
    }
});
</script>
