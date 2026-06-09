<style>
.xray-preview-box {
    width: 100%; height: 220px;
    border: 2px dashed #4e73df;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    background: #f8f9fc;
    cursor: pointer; transition: all .2s;
    overflow: hidden;
}
.xray-preview-box:hover { border-color: #2e59d9; background: #eaecf4; }
.xray-preview-box img { width:100%; height:100%; object-fit:contain; }
.xray-card-thumb {
    width:100%; height:80px; object-fit:cover;
    border-radius:6px; border:1px solid #dee2e6;
    cursor:pointer; transition:.2s;
}
.xray-card-thumb:hover { opacity:.8; transform:scale(1.03); }
</style>

<div class="container-fluid mt-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-x-ray mr-2 text-primary"></i> Upload X-Quang
        </h1>
        <a href="admin.php?admin=formKham&id=<?= htmlspecialchars($lichKham['id'] ?? '') ?>"
           class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại khám bệnh
        </a>
    </div>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="row">
        <!-- Form upload -->
        <div class="col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Thông tin X-quang
                        <?php if (!empty($lichKham)): ?>
                            — <span class="text-dark"><?= htmlspecialchars($lichKham['ten_benh_nhan'] ?? '') ?></span>
                        <?php endif; ?>
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="admin.php?admin=luuXray" enctype="multipart/form-data">
                        <input type="hidden" name="lich_kham_id"       value="<?= htmlspecialchars($lichKham['id'] ?? '') ?>">
                        <input type="hidden" name="ho_so_benh_nhan_id" value="<?= htmlspecialchars($lichKham['ho_so_benh_nhan_id'] ?? '') ?>">

                        <!-- Loại X-quang -->
                        <div class="form-group">
                            <label class="font-weight-bold">Loại X-Quang <span class="text-danger">*</span></label>
                            <select name="loai_xray" class="form-control" required>
                                <option value="">-- Chọn loại --</option>
                                <option value="toan_ham">Toàn hàm (Panoramic)</option>
                                <option value="rang_cu">Răng cụ thể (Periapical)</option>
                                <option value="cat_loc">Cắn lọc (Bitewing)</option>
                                <option value="cat_ngang">Cắt ngang</option>
                                <option value="cbct_3d">CBCT 3D</option>
                            </select>
                        </div>

                        <!-- Vị trí -->
                        <div class="form-group">
                            <label class="font-weight-bold">Vị Trí Răng</label>
                            <input type="text" name="vi_tri" class="form-control"
                                   placeholder="VD: Răng 11, Hàm trên, Răng 36-46...">
                            <small class="text-muted">Để trống nếu là X-quang toàn hàm</small>
                        </div>

                        <!-- Bác sĩ -->
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                        <div class="form-group">
                            <label class="font-weight-bold">Bác Sĩ Chỉ Định</label>
                            <select name="bac_si_id" class="form-control">
                                <option value="">-- Chọn bác sĩ --</option>
                                <?php foreach ($listBacSi as $bs): ?>
                                    <option value="<?= $bs['id'] ?>"
                                        <?= ($bs['id'] == ($lichKham['bac_si_id'] ?? null)) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($bs['ten_bac_si']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php else: ?>
                            <input type="hidden" name="bac_si_id" value="<?= $_SESSION['bac_si_id'] ?? '' ?>">
                        <?php endif; ?>

                        <!-- Ghi chú -->
                        <div class="form-group">
                            <label class="font-weight-bold">Ghi Chú Kỹ Thuật Viên</label>
                            <textarea name="mo_ta" class="form-control" rows="2"
                                      placeholder="Ghi chú khi chụp (tư thế, điều kiện, v.v.)"></textarea>
                        </div>

                        <!-- Upload ảnh -->
                        <div class="form-group">
                            <label class="font-weight-bold">Ảnh X-Quang <span class="text-danger">*</span></label>
                            <div class="xray-preview-box" onclick="document.getElementById('xray_file').click()">
                                <div id="previewPlaceholder" class="text-center text-muted">
                                    <i class="fas fa-cloud-upload-alt fa-3x mb-2"></i>
                                    <p class="mb-0">Click để chọn ảnh X-quang</p>
                                    <small>JPG, PNG, WEBP — tối đa 10MB</small>
                                </div>
                                <img id="previewImg" src="" style="display:none; width:100%; height:100%; object-fit:contain;">
                            </div>
                            <input type="file" name="xray_file" id="xray_file"
                                   accept=".jpg,.jpeg,.png,.webp" style="display:none" required>
                        </div>

                        <div class="d-flex align-items-center">
                            <button type="submit" class="btn btn-primary mr-2">
                                <i class="fas fa-robot mr-1"></i> Lưu & Phân Tích AI
                            </button>
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i>
                                AI sẽ tự động phân tích sau khi upload (15-30 giây)
                            </small>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- X-quang đã có của ca này -->
        <div class="col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        X-Quang Ca Khám Này
                        <span class="badge badge-primary ml-1"><?= count($listXray) ?></span>
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (empty($listXray)): ?>
                        <p class="text-muted text-center py-3">Chưa có ảnh X-quang nào cho ca khám này</p>
                    <?php else: ?>
                        <?php foreach ($listXray as $x): ?>
                            <div class="card mb-2 border">
                                <div class="card-body p-2">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-3">
                                            <img src="backend/uploads/xray/<?= htmlspecialchars($x['file_path']) ?>"
                                                 class="xray-card-thumb"
                                                 onclick="window.open(this.src,'_blank')"
                                                 onerror="this.style.display='none'">
                                        </div>
                                        <div class="col-9 pl-2">
                                            <div class="font-weight-bold" style="font-size:13px;">
                                                <?php $loaiMap = [
                                                    'toan_ham'=>'Toàn Hàm','rang_cu'=>'Răng Cụ Thể',
                                                    'cat_loc'=>'Cắn Lọc','cat_ngang'=>'Cắt Ngang','cbct_3d'=>'CBCT 3D'
                                                ]; ?>
                                                <?= $loaiMap[$x['loai_xray']] ?? $x['loai_xray'] ?>
                                            </div>
                                            <?php if ($x['vi_tri']): ?>
                                                <small class="text-muted">📍 <?= htmlspecialchars($x['vi_tri']) ?></small><br>
                                            <?php endif; ?>
                                            <?php if (!empty($x['ai_phan_tich'])): ?>
                                                <span class="badge badge-success mt-1"><i class="fas fa-robot"></i> AI đã phân tích</span>
                                            <?php else: ?>
                                                <span class="badge badge-light mt-1">Chưa phân tích</span>
                                            <?php endif; ?>
                                            <div class="mt-1">
                                                <a href="admin.php?admin=viewXray&idAdmin=<?= $x['id'] ?>"
                                                   class="btn btn-xs btn-outline-info" style="font-size:11px;">Xem chi tiết</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('xray_file').addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('previewImg').style.display = 'block';
        document.getElementById('previewPlaceholder').style.display = 'none';
    };
    reader.readAsDataURL(file);
});
</script>
