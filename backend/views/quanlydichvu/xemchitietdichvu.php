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
            <?php if (!empty($chitetdichvudichvu)):
                $tongTienVatTu = 0;
                if (!empty($vatTuSuDung)) {
                    foreach ($vatTuSuDung as $vt)
                        $tongTienVatTu += $vt['gia_nhap'] * $vt['so_luong'];
                }
                $dichVuId = $chitetdichvudichvu['id'];
            ?>
                <div class="row mb-3">
                    <!-- Ảnh -->
                    <div class="col-md-4 text-center">
                        <img src="backend/uploads/services/<?= htmlspecialchars($chitetdichvudichvu['image'] ?? '') ?>"
                            width="150" class="img-thumbnail rounded" alt="Ảnh dịch vụ">
                    </div>
                    <!-- Thông tin -->
                    <div class="col-md-8">
                        <h4 class="font-weight-bold mb-3"><?= htmlspecialchars($chitetdichvudichvu['ten_dich_vu'] ?? '') ?></h4>
                        <p><strong>Loại danh mục:</strong> <?= htmlspecialchars($chitetdichvudichvu['danhmuc'] ?? '') ?></p>
                        <p>
                            <strong>Giá công khám:</strong>
                            <span class="font-weight-bold h5">
                                <?= number_format((float)(($chitetdichvudichvu['gia'] ?? 0) - $tongTienVatTu), 0, ',', '.') ?> đ
                            </span>
                        </p>
                        <p>
                            <strong>Giá trọn gói:</strong>
                            <span class="text-danger font-weight-bold h5">
                                <?= number_format((float)($chitetdichvudichvu['gia'] ?? 0), 0, ',', '.') ?> đ
                            </span>
                        </p>
                        <p>
                            <strong>Trạng thái:</strong>
                            <?php if (($chitetdichvudichvu['trang_thai'] ?? '') === 'Hoạt động'): ?>
                                <span class="badge badge-success">Hoạt động</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Ngừng hoạt động</span>
                            <?php endif; ?>
                        </p>
                        <p class="text-muted"><?= htmlspecialchars($chitetdichvudichvu['mo_ta'] ?? '') ?></p>
                    </div>
                </div>

                <!-- ===== VẬT TƯ ===== -->
                <div class="card shadow-sm mt-3">
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
                                <?php if (!empty($vatTuSuDung)): ?>
                                    <?php foreach ($vatTuSuDung as $vt):
                                        $thanhTien = $vt['gia_nhap'] * $vt['so_luong']; ?>
                                        <tr>
                                            <td><?= htmlspecialchars($vt['ten_vat_tu']) ?></td>
                                            <td class="text-center"><?= number_format($vt['gia_nhap'], 0, ',', '.') ?> đ</td>
                                            <td class="text-center"><?= (int)$vt['so_luong'] ?></td>
                                            <td class="text-center font-weight-bold"><?= number_format($thanhTien, 0, ',', '.') ?> đ</td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr class="table-secondary">
                                        <td colspan="3" class="text-right font-weight-bold">Tổng tiền vật tư:</td>
                                        <td class="text-center font-weight-bold text-danger">
                                            <?= number_format($tongTienVatTu, 0, ',', '.') ?> đ
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Chưa có vật tư nào</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ===== CÁC BƯỚC THỰC HIỆN ===== -->
                <div class="card shadow-sm mt-4">
                    <div class="card-header py-2 d-flex align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-list-ol mr-1"></i> Các bước thực hiện dịch vụ
                        </h6>
                        <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalThemBuoc">
                            <i class="fas fa-plus mr-1"></i> Thêm bước
                        </button>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($danhSachBuoc)): ?>
                            <table class="table table-bordered table-hover align-middle mb-0">
                                <thead class="thead-light">
                                    <tr class="text-center">
                                        <th width="70">Thứ tự</th>
                                        <th>Tên bước</th>
                                        <th>Mô tả</th>
                                        <th width="120">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($danhSachBuoc as $buoc): ?>
                                        <tr>
                                            <td class="text-center font-weight-bold text-primary"><?= $buoc['thu_tu'] ?></td>
                                            <td class="font-weight-bold"><?= htmlspecialchars($buoc['ten_buoc']) ?></td>
                                            <td class="text-muted"><?= htmlspecialchars($buoc['mo_ta'] ?? '') ?></td>
                                            <td class="text-center">
                                                <button class="btn btn-warning btn-sm"
                                                    onclick="moModalSuaBuoc(<?= $buoc['id'] ?>, <?= $buoc['thu_tu'] ?>, '<?= addslashes(htmlspecialchars($buoc['ten_buoc'])) ?>', '<?= addslashes(htmlspecialchars($buoc['mo_ta'] ?? '')) ?>')">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <a href="admin.php?admin=deleteBuocDichVu&id=<?= $buoc['id'] ?>&dich_vu_id=<?= $dichVuId ?>"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Xóa bước này?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p class="text-muted text-center mb-0">
                                <i class="fas fa-info-circle mr-1"></i>
                                Dịch vụ này chưa có bước nào. Nhấn <strong>Thêm bước</strong> để định nghĩa quy trình.
                            </p>
                        <?php endif; ?>
                    </div>
                </div>

            <?php else: ?>
                <div class="alert alert-danger">Không tìm thấy thông tin dịch vụ</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ===== MODAL THÊM BƯỚC ===== -->
<div class="modal fade" id="modalThemBuoc" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="admin.php?admin=addBuocDichVu">
                <input type="hidden" name="dich_vu_id" value="<?= $chitetdichvudichvu['id'] ?? '' ?>">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle mr-1 text-success"></i> Thêm bước thực hiện
                    </h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Thứ tự <span class="text-danger">*</span></label>
                        <input type="number" name="thu_tu" class="form-control" min="1"
                            value="<?= count($danhSachBuoc ?? []) + 1 ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Tên bước <span class="text-danger">*</span></label>
                        <input type="text" name="ten_buoc" class="form-control"
                            placeholder="VD: Lấy khuôn răng, Đặt răng sứ..." required>
                    </div>
                    <div class="form-group mb-0">
                        <label>Mô tả</label>
                        <textarea name="mo_ta" class="form-control" rows="2"
                            placeholder="Mô tả chi tiết bước này (tuỳ chọn)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save mr-1"></i> Lưu bước
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ===== MODAL SỬA BƯỚC ===== -->
<div class="modal fade" id="modalSuaBuoc" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="admin.php?admin=editBuocDichVu">
                <input type="hidden" name="dich_vu_id" value="<?= $chitetdichvudichvu['id'] ?? '' ?>">
                <input type="hidden" name="buoc_id" id="edit_buoc_id">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-edit mr-1 text-warning"></i> Sửa bước thực hiện
                    </h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Thứ tự <span class="text-danger">*</span></label>
                        <input type="number" name="thu_tu" id="edit_thu_tu" class="form-control" min="1" required>
                    </div>
                    <div class="form-group">
                        <label>Tên bước <span class="text-danger">*</span></label>
                        <input type="text" name="ten_buoc" id="edit_ten_buoc" class="form-control" required>
                    </div>
                    <div class="form-group mb-0">
                        <label>Mô tả</label>
                        <textarea name="mo_ta" id="edit_mo_ta" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save mr-1"></i> Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function moModalSuaBuoc(id, thuTu, tenBuoc, moTa) {
    document.getElementById('edit_buoc_id').value  = id;
    document.getElementById('edit_thu_tu').value   = thuTu;
    document.getElementById('edit_ten_buoc').value = tenBuoc;
    document.getElementById('edit_mo_ta').value    = moTa;
    $('#modalSuaBuoc').modal('show');
}
</script>