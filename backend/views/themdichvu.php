<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">➕ Thêm dịch vụ nha khoa</h5>
    </div>

    <div class="card-body">
        <form action="admin.php?page=addDich_vu" method="post">

            <!-- Tên dịch vụ -->
            <div class="row mb-3">
                <label class="col-md-4 col-form-label fw-bold">
                    Tên dịch vụ <span class="text-danger">*</span>
                </label>
                <div class="col-md-8">
                    <input
                        type="text"
                        name="ten_dich_vu"
                        class="form-control"
                        placeholder="Nhập tên dịch vụ"
                        required>
                </div>
            </div>

            <!-- Loại dịch vụ -->
            <div class="row mb-3">
                <label class="col-md-4 col-form-label fw-bold">
                    Loại dịch vụ <span class="text-danger">*</span>
                </label>
                <div class="col-md-8">
                    <select name="id_loai" class="form-select" required>
                        <option value="">-- Chọn loại dịch vụ --</option>
                        <option value="1">Khám & tư vấn</option>
                        <option value="2">Điều trị</option>
                        <option value="3">Thẩm mỹ răng</option>
                        <option value="4">Phẫu thuật</option>
                    </select>
                </div>
            </div>

            <!-- Giá -->
            <div class="row mb-3">
                <label class="col-md-4 col-form-label fw-bold">
                    Giá dịch vụ (VNĐ)
                </label>
                <div class="col-md-8">
                    <input
                        type="number"
                        name="gia"
                        class="form-control"
                        min="0"
                        placeholder="Nhập giá dịch vụ"
                        required>
                </div>
            </div>

            <!-- Mô tả -->
            <div class="row mb-3">
                <label class="col-md-4 col-form-label fw-bold">
                    Mô tả
                </label>
                <div class="col-md-8">
                    <textarea
                        name="mo_ta"
                        class="form-control"
                        rows="4"
                        placeholder="Mô tả chi tiết dịch vụ"></textarea>
                </div>
            </div>

            <!-- Nút -->
            <div class="row">
                <div class="col-md-12 text-end">
                    <button type="submit" class="btn btn-primary px-4">
                        💾 Lưu dịch vụ
                    </button>
                    <a href="admin.php?page=qlydichvu" class="btn btn-secondary px-4 ms-2">
                        ❌ Hủy
                    </a>
                </div>
            </div>

        </form>
    </div>
</div>