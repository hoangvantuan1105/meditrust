<div class="container-fluid mt-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-x-ray mr-2 text-primary"></i>
            X-Quang — <?= htmlspecialchars($benhNhan['ho_ten'] ?? 'Bệnh nhân') ?>
        </h1>
        <a href="admin.php?admin=detail&id=<?= htmlspecialchars($benhNhan['id'] ?? '') ?>"
           class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Về Hồ Sơ
        </a>
    </div>

    <?php if (empty($listXray)): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle mr-1"></i>
            Bệnh nhân này chưa có ảnh X-quang nào.
        </div>
    <?php else: ?>
        <!-- Gallery lưới -->
        <div class="row">
            <?php foreach ($listXray as $x): ?>
            <?php $loaiMap = [
                'toan_ham'=>['label'=>'Toàn Hàm','color'=>'primary'],
                'rang_cu'=>['label'=>'Răng Cụ Thể','color'=>'info'],
                'cat_loc'=>['label'=>'Cắn Lọc','color'=>'secondary'],
                'cat_ngang'=>['label'=>'Cắt Ngang','color'=>'dark'],
                'cbct_3d'=>['label'=>'CBCT 3D','color'=>'warning'],
            ]; $l = $loaiMap[$x['loai_xray']] ?? ['label'=>$x['loai_xray'],'color'=>'secondary']; ?>
            <div class="col-md-4 col-lg-3 mb-4">
                <div class="card shadow-sm h-100">
                    <div style="position:relative; overflow:hidden; height:160px; background:#f8f9fc;">
                        <img src="backend/uploads/xray/<?= htmlspecialchars($x['file_path']) ?>"
                             style="width:100%; height:100%; object-fit:cover; cursor:pointer;"
                             onclick="window.open(this.src,'_blank')"
                             onerror="this.parentElement.innerHTML='<div class=\'d-flex align-items-center justify-content-center h-100 text-muted\'><i class=\'fas fa-image fa-2x\'></i></div>'">
                        <span class="badge badge-<?= $l['color'] ?>"
                              style="position:absolute;top:8px;left:8px;">
                            <?= $l['label'] ?>
                        </span>
                        <?php if (!empty($x['ai_phan_tich'])): ?>
                        <span class="badge badge-success"
                              style="position:absolute;top:8px;right:8px;">
                            <i class="fas fa-robot"></i> AI
                        </span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                <?= date('d/m/Y', strtotime($x['ngay_chup'])) ?>
                            </small>
                            <?php if ($x['trang_thai'] === 'da_doc'): ?>
                                <span class="badge badge-success" style="font-size:10px;">Đã Đọc</span>
                            <?php else: ?>
                                <span class="badge badge-warning" style="font-size:10px;">Chờ Đọc</span>
                            <?php endif; ?>
                        </div>
                        <?php if ($x['vi_tri']): ?>
                            <small class="text-dark"><i class="fas fa-map-marker-alt mr-1 text-danger"></i><?= htmlspecialchars($x['vi_tri']) ?></small><br>
                        <?php endif; ?>
                        <?php if ($x['ten_bac_si']): ?>
                            <small class="text-muted"><i class="fas fa-user-md mr-1"></i><?= htmlspecialchars($x['ten_bac_si']) ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer p-2 bg-white">
                        <a href="admin.php?admin=viewXray&idAdmin=<?= $x['id'] ?>"
                           class="btn btn-sm btn-info btn-block">
                            <i class="fas fa-eye mr-1"></i> Xem Chi Tiết
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
