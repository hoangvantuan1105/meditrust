<?php
/** @var array $xray */
if (!isset($xray)) {
    header("Location: admin.php?admin=listXray");
    exit;
}

function renderAiXray(string $text): string {
    preg_match('/\*\*TÌNH TRẠNG RĂNG MIỆNG:\*\*([\s\S]*?)(?=\*\*GỢI Ý DỊCH VỤ:|$)/ui', $text, $mTT);
    preg_match('/\*\*GỢI Ý DỊCH VỤ:\*\*([\s\S]*?)$/ui', $text, $mDV);

    $html = '';

    if (!empty($mTT[1])) {
        $content = nl2br(preg_replace('/\*\*(.+?)\*\*/u', '<strong>$1</strong>', trim($mTT[1])));
        $html .= '<div class="mb-3 p-3 rounded" style="background:#fff8e1;border-left:4px solid #ffc107;">
            <div class="font-weight-bold text-warning mb-1">
                <i class="fas fa-tooth mr-1"></i> TÌNH TRẠNG RĂNG MIỆNG
            </div>
            <div style="font-size:14px;line-height:1.8;">' . $content . '</div>
        </div>';
    }

    if (!empty($mDV[1])) {
        $lines = array_filter(array_map('trim', explode("\n", trim($mDV[1]))));
        $items = '';
        foreach ($lines as $line) {
            $line = preg_replace('/^[-*•]\s*/', '', $line);
            $line = preg_replace('/\*\*(.+?)\*\*/u', '<strong>$1</strong>', $line);
            if ($line) {
                $items .= '<li class="list-group-item py-2" style="font-size:13px;">
                    <i class="fas fa-check-circle text-success mr-2"></i>' . $line . '</li>';
            }
        }
        $html .= '<div class="p-3 rounded" style="background:#e8f5e9;border-left:4px solid #28a745;">
            <div class="font-weight-bold text-success mb-2">
                <i class="fas fa-clipboard-list mr-1"></i> GỢI Ý DỊCH VỤ
            </div>
            <ul class="list-group list-group-flush">' . $items . '</ul>
        </div>';
    }

    // Fallback nếu không có 2 section
    if (!$html) {
        $html = '<div class="ai-block">' . nl2br(preg_replace('/\*\*(.+?)\*\*/u', '<strong>$1</strong>', $text)) . '</div>';
    }

    return $html;
}
?>

<style>
.xray-viewer-img {
    max-width: 100%; max-height: 500px;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    cursor: zoom-in;
    transition: transform .2s;
}
.xray-viewer-img:hover { transform: scale(1.01); }
.ai-block { background:#f0f4ff; border-left:4px solid #4e73df; border-radius:6px; padding:16px; white-space:pre-wrap; font-size:14px; line-height:1.7; }
.ai-block strong { color:#4e73df; }
</style>

<div class="container-fluid mt-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-x-ray mr-2 text-primary"></i> Chi Tiết X-Quang #<?= $xray['id'] ?>
        </h1>
        <div>
            <a href="admin.php?admin=listXray" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Danh Sách
            </a>
            <?php if ($xray['lich_kham_id']): ?>
            <a href="admin.php?admin=formUploadXray&lich_kham_id=<?= $xray['lich_kham_id'] ?>"
               class="btn btn-sm btn-primary ml-1">
                <i class="fas fa-plus"></i> Thêm X-Quang Ca Này
            </a>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <div class="row">
        <!-- Ảnh X-quang -->
        <div class="col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Ảnh X-Quang</h6>
                    <a href="backend/uploads/xray/<?= htmlspecialchars($xray['file_path']) ?>"
                       download class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-download"></i> Tải về
                    </a>
                </div>
                <div class="card-body text-center">
                    <img src="backend/uploads/xray/<?= htmlspecialchars($xray['file_path']) ?>"
                         class="xray-viewer-img"
                         onclick="window.open(this.src,'_blank')"
                         onerror="this.alt='Không tìm thấy ảnh'; this.style.opacity=.3;">
                </div>
            </div>

            <!-- Kết quả bác sĩ -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-user-md mr-1"></i> Kết Quả Đọc Phim (Bác Sĩ)
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($xray['ket_qua_bac_si'])): ?>
                        <div class="alert alert-success mb-3" style="white-space:pre-wrap;">
                            <?= htmlspecialchars($xray['ket_qua_bac_si']) ?>
                        </div>
                    <?php endif; ?>
                    <form method="POST" action="admin.php?admin=luuKetQuaXray">
                        <input type="hidden" name="id" value="<?= $xray['id'] ?>">
                        <div class="form-group">
                            <label class="font-weight-bold">Nhận xét / Kết luận của bác sĩ</label>
                            <textarea name="ket_qua_bac_si" class="form-control" rows="4"
                                      placeholder="Nhập kết quả đọc phim X-quang..."
                                      ><?= htmlspecialchars($xray['ket_qua_bac_si'] ?? '') ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save mr-1"></i> Lưu Kết Quả
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Thông tin + AI -->
        <div class="col-lg-5">
            <!-- Thông tin cơ bản -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Thông Tin</h6>
                </div>
                <div class="card-body">
                    <?php $loaiMap = [
                        'toan_ham'=>'Toàn Hàm (Panoramic)','rang_cu'=>'Răng Cụ Thể (Periapical)',
                        'cat_loc'=>'Cắn Lọc (Bitewing)','cat_ngang'=>'Cắt Ngang','cbct_3d'=>'CBCT 3D'
                    ]; ?>
                    <table class="table table-sm table-borderless mb-0">
                        <tr><td class="font-weight-bold text-muted" width="40%">Bệnh nhân</td>
                            <td><?= htmlspecialchars($xray['ho_ten'] ?? '---') ?></td></tr>
                        <tr><td class="font-weight-bold text-muted">Loại X-quang</td>
                            <td><span class="badge badge-primary"><?= $loaiMap[$xray['loai_xray']] ?? $xray['loai_xray'] ?></span></td></tr>
                        <tr><td class="font-weight-bold text-muted">Vị trí</td>
                            <td><?= htmlspecialchars($xray['vi_tri'] ?: '---') ?></td></tr>
                        <tr><td class="font-weight-bold text-muted">Bác sĩ</td>
                            <td><?= htmlspecialchars($xray['ten_bac_si'] ?? '---') ?></td></tr>
                        <tr><td class="font-weight-bold text-muted">Ngày chụp</td>
                            <td><?= date('d/m/Y', strtotime($xray['ngay_chup'])) ?></td></tr>
                        <tr><td class="font-weight-bold text-muted">Trạng thái</td>
                            <td>
                                <?php if ($xray['trang_thai'] === 'da_doc'): ?>
                                    <span class="badge badge-success">Đã Đọc</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Chờ Đọc</span>
                                <?php endif; ?>
                            </td></tr>
                        <?php if ($xray['mo_ta']): ?>
                        <tr><td class="font-weight-bold text-muted">Ghi chú KTV</td>
                            <td><?= htmlspecialchars($xray['mo_ta']) ?></td></tr>
                        <?php endif; ?>
                    </table>

                </div>
            </div>

            <!-- AI Phân tích -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-robot mr-1"></i> Phân Tích AI (Gemini Vision)
                    </h6>
                    <button class="btn btn-sm btn-outline-primary" onclick="reAnalyze(<?= $xray['id'] ?>)">
                        <i class="fas fa-sync-alt mr-1"></i>
                        <?= empty($xray['ai_phan_tich']) ? 'Phân Tích' : 'Phân Tích Lại' ?>
                    </button>
                </div>
                <div class="card-body">
                    <?php if (!empty($xray['ai_phan_tich'])): ?>
                        <div id="aiContent"><?= renderAiXray(htmlspecialchars($xray['ai_phan_tich'])) ?></div>
                    <?php else: ?>
                        <div class="text-center text-muted py-4" id="aiPlaceholder">
                            <i class="fas fa-robot fa-2x mb-2 text-primary"></i>
                            <p class="mb-1">AI chưa phân tích ảnh này</p>
                            <small class="d-block mb-3">Phân tích mất khoảng 15–30 giây</small>
                            <button class="btn btn-primary btn-sm" onclick="reAnalyze(<?= $xray['id'] ?>)">
                                <i class="fas fa-magic mr-1"></i> Phân Tích Ngay
                            </button>
                        </div>
                    <?php endif; ?>
                    <div id="aiLoading" style="display:none;" class="text-center py-3">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2 text-muted">Gemini Vision đang phân tích X-quang...</p>
                    </div>
                    <div id="aiResult" style="display:none;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function renderAiResult(text) {
    // Tách 2 block: TÌNH TRẠNG và GỢI Ý DỊCH VỤ
    const tinhtrang = text.match(/\*\*TÌNH TRẠNG RĂNG MIỆNG:\*\*([\s\S]*?)(?=\*\*GỢI Ý DỊCH VỤ:|$)/i);
    const goiY      = text.match(/\*\*GỢI Ý DỊCH VỤ:\*\*([\s\S]*?)$/i);

    let html = '';

    if (tinhtrang) {
        const content = tinhtrang[1].trim()
            .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
            .replace(/\n/g, '<br>');
        html += `
        <div class="mb-3 p-3 rounded" style="background:#fff8e1;border-left:4px solid #ffc107;">
            <div class="font-weight-bold text-warning mb-1">
                <i class="fas fa-tooth mr-1"></i> TÌNH TRẠNG RĂNG MIỆNG
            </div>
            <div style="font-size:14px;line-height:1.7;">${content}</div>
        </div>`;
    }

    if (goiY) {
        // Parse từng dòng dịch vụ
        const lines = goiY[1].trim().split('\n').filter(l => l.trim());
        let serviceHtml = '';
        lines.forEach(line => {
            const clean = line.replace(/^[-*•]\s*/, '').replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
            if (clean.trim()) {
                serviceHtml += `<li class="list-group-item py-2" style="font-size:13px;">
                    <i class="fas fa-check-circle text-success mr-2"></i>${clean}
                </li>`;
            }
        });
        html += `
        <div class="p-3 rounded" style="background:#e8f5e9;border-left:4px solid #28a745;">
            <div class="font-weight-bold text-success mb-2">
                <i class="fas fa-clipboard-list mr-1"></i> GỢI Ý DỊCH VỤ
            </div>
            <ul class="list-group list-group-flush">${serviceHtml}</ul>
        </div>`;
    }

    // Fallback nếu không parse được
    if (!html) {
        html = `<div class="ai-block">${text.replace(/\n/g,'<br>').replace(/\*\*(.+?)\*\*/g,'<strong>$1</strong>')}</div>`;
    }

    return html;
}

function reAnalyze(xrayId) {
    const placeholder = document.getElementById('aiPlaceholder');
    const loading     = document.getElementById('aiLoading');
    const result      = document.getElementById('aiResult');
    const existing    = document.getElementById('aiContent');

    if (placeholder) placeholder.style.display = 'none';
    if (existing)    existing.style.display    = 'none';
    result.style.display = 'none';
    loading.style.display = 'block';

    fetch('admin.php?admin=reAnalyzeXray&idAdmin=' + xrayId)
        .then(r => r.json())
        .then(data => {
            loading.style.display = 'none';
            if (data.result) {
                result.innerHTML     = renderAiResult(data.result);
                result.style.display = 'block';
            } else {
                if (placeholder) placeholder.style.display = 'block';
                alert('Lỗi phân tích: ' + (data.error || 'Không xác định'));
            }
        })
        .catch(() => {
            loading.style.display = 'none';
            if (placeholder) placeholder.style.display = 'block';
            alert('Lỗi kết nối tới server');
        });
}
</script>
