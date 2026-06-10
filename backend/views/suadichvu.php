<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">✏️ Sửa dịch vụ nha khoa</h5>
        </div>

        <div class="card-body">
            <form action="admin.php?admin=editDichVu&idAdmin=<?php echo $dichvu['id']; ?>" method="POST">

                <div class="row mb-3">
                    <label class="col-md-4 col-form-label fw-bold">
                        Tên dịch vụ <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-8">
                        <input type="text" name="ten_dich_vu" class="form-control"
                            value="<?= htmlspecialchars($dichvu['ten_dich_vu'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-md-4 col-form-label fw-bold">
                        Loại dịch vụ <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-8">
                        <select name="id_loai" class="form-select" required>
                            <option value="">-- Chọn loại dịch vụ --</option>
                            <option value="1" <?= ($dichvu['id_loai'] == 1) ? 'selected' : '' ?>>Khám & tư vấn</option>
                            <option value="2" <?= ($dichvu['id_loai'] == 2) ? 'selected' : '' ?>>Điều trị</option>
                            <option value="3" <?= ($dichvu['id_loai'] == 3) ? 'selected' : '' ?>>Thẩm mỹ răng</option>
                            <option value="4" <?= ($dichvu['id_loai'] == 4) ? 'selected' : '' ?>>Phẫu thuật</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-md-4 col-form-label fw-bold">
                        Giá dịch vụ (VNĐ)
                    </label>
                    <div class="col-md-8">
                        <input type="number" name="gia" class="form-control" min="0"
                            value="<?= htmlspecialchars($dichvu['gia'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-md-4 col-form-label fw-bold">
                        Mô tả
                    </label>
                    <div class="col-md-8">
                        <textarea name="mo_ta" class="form-control" rows="4"><?= htmlspecialchars($dichvu['mo_ta'] ?? '') ?></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-end">
                        <a href="admin.php?page=qlydichvu" class="btn btn-secondary px-4 me-2">
                            ❌ Hủy
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            💾 Lưu thay đổi
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

</body>

</html>