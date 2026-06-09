<?php
/** @var array $xray */
if (!isset($xray)) {
    header("Location: admin.php?admin=listXray");
    exit;
}

$loaiMap = [
    'toan_ham'  => ['label' => 'Toàn Hàm (Panoramic)',        'color' => 'primary'],
    'rang_cu'   => ['label' => 'Răng Cụ Thể (Periapical)',    'color' => 'info'],
    'cat_loc'   => ['label' => 'Cắn Lọc (Bitewing)',          'color' => 'secondary'],
    'cat_ngang' => ['label' => 'Cắt Ngang',                   'color' => 'dark'],
    'cbct_3d'   => ['label' => 'CBCT 3D',                     'color' => 'warning'],
];
$loai = $loaiMap[$xray['loai_xray']] ?? ['label' => $xray['loai_xray'], 'color' => 'secondary'];
?>

<style>
.detail-xray-img {
    max-width: 100%;
    max-height: 480px;
    border-radius: 10px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.18);
    cursor: zoom-in;
    transition: transform .2s;
}
.detail-xray-img:hover { transform: scale(1.015); }
.info-label { color: #6c757d; font-weight: 600; width: 40%; }
.ai-section { background: #f0f4ff; border-left: 4px solid #4e73df; border-radius: 6px; padding: 16px; white-space: pre-wrap; font-size: 14px; line-height: 1.8; }
.ai-section strong { color: #4e73df; }
@media print {
    .no-print { display: none !important; }
    .card { box-shadow: none !important; border: 1px solid #dee2e6 !important; }
}
</style>

<div class="container-fluid mt-4">

    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-x-ray mr-2 text-primary"></i>
            Chi Tiết X-Quang #<?= $xray['id'] ?>
        </h1>
        <div class="no-print">
            <a href="admin.php?admin=listXray" class="btn btn-sm btn-secondary mr-1">
                <i class="fas fa-arrow-left mr-1"></i> Danh Sách
            </a>
           
            <button class="btn btn-sm btn-outline-dark" onclick="window.print()">
                <i class="fas fa-print mr-1"></i> In
            </button>
        </div>
    </div>

    <div class="row">

        <!-- Ảnh X-quang -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-image mr-1"></i> Ảnh X-Quang
                    </h6>
                    <a href="backend/uploads/xray/<?= htmlspecialchars($xray['file_path']) ?>"
                       download class="btn btn-sm btn-outline-primary no-print">
                        <i class="fas fa-download mr-1"></i> Tải Về
                    </a>
                </div>
                <div class="card-body text-center d-flex align-items-center justify-content-center" style="min-height:300px;">
                    <img src="backend/uploads/xray/<?= htmlspecialchars($xray['file_path']) ?>"
                         class="detail-xray-img"
                         onclick="window.open(this.src,'_blank')"
                         onerror="this.style.opacity=.3;this.alt='Không tìm thấy ảnh';"
                         alt="X-quang #<?= $xray['id'] ?>">
                </div>
            </div>
        </div>

        <!-- Thông tin chi tiết -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-info-circle mr-1"></i> Thông Tin Chi Tiết
                    </h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-borderless mb-0">
                        <tbody>
                            <tr class="border-bottom">
                                <td class="info-label pl-3 py-3">Mã X-Quang</td>
                                <td class="py-3 font-weight-bold">#<?= $xray['id'] ?></td>
                            </tr>
                            <tr class="border-bottom">
                                <td class="info-label pl-3 py-3">Bệnh Nhân</td>
                                <td class="py-3"><?= htmlspecialchars($xray['ho_ten'] ?? '---') ?></td>
                            </tr>
                            <tr class="border-bottom">
                                <td class="info-label pl-3 py-3">Loại X-Quang</td>
                                <td class="py-3">
                                    <span class="badge badge-<?= $loai['color'] ?>"><?= $loai['label'] ?></span>
                                </td>
                            </tr>
                            <tr class="border-bottom">
                                <td class="info-label pl-3 py-3">Vị Trí</td>
                                <td class="py-3"><?= htmlspecialchars($xray['vi_tri'] ?: '---') ?></td>
                            </tr>
                            <tr class="border-bottom">
                                <td class="info-label pl-3 py-3">Bác Sĩ</td>
                                <td class="py-3"><?= htmlspecialchars($xray['ten_bac_si'] ?? '---') ?></td>
                            </tr>
                            <tr class="border-bottom">
                                <td class="info-label pl-3 py-3">Ngày Chụp</td>
                                <td class="py-3"><?= date('d/m/Y', strtotime($xray['ngay_chup'])) ?></td>
                            </tr>
                            <tr class="border-bottom">
                                <td class="info-label pl-3 py-3">Trạng Thái</td>
                                <td class="py-3">
                                    <?php if ($xray['trang_thai'] === 'da_doc'): ?>
                                        <span class="badge badge-success"><i class="fas fa-check mr-1"></i>Đã Đọc</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning"><i class="fas fa-clock mr-1"></i>Chờ Đọc</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr class="border-bottom">
                                <td class="info-label pl-3 py-3">AI Phân Tích</td>
                                <td class="py-3">
                                    <?php if (!empty($xray['ai_phan_tich'])): ?>
                                        <span class="badge badge-success"><i class="fas fa-robot mr-1"></i>Có</span>
                                    <?php else: ?>
                                        <span class="badge badge-light text-muted">Chưa có</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php if (!empty($xray['mo_ta'])): ?>
                            <tr>
                                <td class="info-label pl-3 py-3">Ghi Chú KTV</td>
                                <td class="py-3"><?= htmlspecialchars($xray['mo_ta']) ?></td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Kết quả bác sĩ (read-only) -->
            <?php if (!empty($xray['ket_qua_bac_si'])): ?>
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-user-md mr-1"></i> Kết Quả Đọc Phim (Bác Sĩ)
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-success mb-0" style="white-space:pre-wrap;">
                        <?= htmlspecialchars($xray['ket_qua_bac_si']) ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- AI Phân tích -->
    <?php if (!empty($xray['ai_phan_tich'])): ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-robot mr-1"></i> Phân Tích AI (Gemini Vision)
            </h6>
        </div>
        <div class="card-body">
            <?php
            $text = $xray['ai_phan_tich'];
            preg_match('/\*\*TÌNH TRẠNG RĂNG MIỆNG:\*\*([\s\S]*?)(?=\*\*GỢI Ý DỊCH VỤ:|$)/ui', $text, $mTT);
            preg_match('/\*\*GỢI Ý DỊCH VỤ:\*\*([\s\S]*?)$/ui', $text, $mDV);
            ?>
            <div class="row">
                <?php if (!empty($mTT[1])): ?>
                <div class="col-md-6 mb-3">
                    <div class="p-3 rounded h-100" style="background:#fff8e1;border-left:4px solid #ffc107;">
                        <div class="font-weight-bold text-warning mb-2">
                            <i class="fas fa-tooth mr-1"></i> TÌNH TRẠNG RĂNG MIỆNG
                        </div>
                        <div style="font-size:14px;line-height:1.8;">
                            <?= nl2br(htmlspecialchars(trim($mTT[1]))) ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <?php if (!empty($mDV[1])): ?>
                <div class="col-md-6 mb-3">
                    <div class="p-3 rounded h-100" style="background:#e8f5e9;border-left:4px solid #28a745;">
                        <div class="font-weight-bold text-success mb-2">
                            <i class="fas fa-clipboard-list mr-1"></i> GỢI Ý DỊCH VỤ
                        </div>
                        <ul class="list-group list-group-flush">
                            <?php foreach (array_filter(array_map('trim', explode("\n", trim($mDV[1])))) as $line): ?>
                                <?php $line = preg_replace('/^[-*•]\s*/', '', $line); ?>
                                <?php if ($line): ?>
                                <li class="list-group-item py-2" style="font-size:13px;background:transparent;border-color:rgba(40,167,69,.2);">
                                    <i class="fas fa-check-circle text-success mr-2"></i><?= htmlspecialchars($line) ?>
                                </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>
                <?php if (empty($mTT[1]) && empty($mDV[1])): ?>
                <div class="col-12">
                    <div class="ai-section"><?= nl2br(htmlspecialchars($text)) ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>
