<style>
    /* Select2 giống input Bootstrap 4 SB Admin */

    .select2-container--default .select2-selection--single {
        height: 38px !important;
        padding: 6px 12px !important;
        border: 1px solid #d1d3e2 !important;
        border-radius: 0.35rem !important;
        background-color: #fff !important;
    }

    .select2-container--default .select2-selection__rendered {
        line-height: 24px !important;
        padding-left: 0 !important;
        color: #6e707e !important;
    }

    .select2-container--default .select2-selection__arrow {
        height: 36px !important;
        right: 10px;
    }

    .select2-container {
        width: 100% !important;
    }
</style>
<div class="container-fluid mt-4">

    <!-- ===== HEADER ===== -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Sửa yêu cầu đặt lịch</h1>
        <a href="admin.php?admin=listYcLichHen" class="btn btn-secondary btn-sm">
            ← Quay lại
        </a>
    </div>

    <!-- ===== FORM ===== -->
    <div class="card shadow">
        <div class="card-body">

            <form method="post" action="admin.php?admin=suaYeuCauDatLich">

                <input type="hidden" name="id" value="<?= $yeuCau['id'] ?>">

                <div class="row">

                    <!-- Họ tên -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Họ tên bệnh nhân</label>
                        <input type="text" name="ho_ten" class="form-control"
                            value="<?= htmlspecialchars($yeuCau['ho_ten']) ?>" required>
                    </div>

                    <!-- SĐT -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="so_dien_thoai" class="form-control"
                            value="<?= htmlspecialchars($yeuCau['so_dien_thoai']) ?>" required>
                    </div>
                    <!-- Bác sĩ -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Bác sĩ</label>
                        <select id="doctor" name="bac_si_id" class="form-control">
                            <option value="">-- Chọn bác sĩ --</option>
                            <?php if (!empty($dsBacSi)): ?>
                                <?php foreach ($dsBacSi as $bs): ?>
                                    <option value="<?= $bs['id'] ?>" <?= (!empty($yeuCau['bac_si_id']) && (int) $bs['id'] === (int) $yeuCau['bac_si_id'])
                                          ? 'selected'
                                          : '' ?>>
                                        <?= htmlspecialchars($bs['ten_bac_si']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Dịch vụ -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Dịch vụ</label>
                        <select name="dich_vu_id" class="form-control">
                            <option value="">-- Chọn dịch vụ --</option>

                            <?php foreach ($dsDichVu as $dv): ?>
                                <option value="<?= $dv['id'] ?>" <?= ((int) $dv['id'] === (int) $yeuCau['dich_vu_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($dv['ten_dich_vu']) ?>
                                </option>
                            <?php endforeach; ?>

                        </select>

                    </div>
                    <!-- Ngày mong muốn -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ngày mong muốn</label>
                        <input type="date" id="date" name="ngay_mong_muon" class="form-control"
                            value="<?= $yeuCau['ngay_mong_muon'] ?>" required>
                    </div>
                    <!-- Giờ Khám -->
                    <div class="form-group col-md-6">
                        <label class="form-label fw-semibold">
                            Chọn Giờ Khám <span class="text-danger">*</span>
                        </label>
                        <select name="gio_bat_dau" id="examTime" class="form-control" required>
                            <option value="">-- Chọn Giờ Khám --</option>
                        </select>
                    </div>


                    <!-- Mô tả -->
                    <div class="col-12 mb-3">
                        <label class="form-label">Mô tả triệu chứng</label>
                        <textarea name="mo_ta_trieu_chung" class="form-control"
                            rows="4"><?= htmlspecialchars($yeuCau['mo_ta_trieu_chung']) ?></textarea>
                    </div>

                    <!-- Trạng thái -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Trạng thái</label>
                        <select name="trang_thai" class="form-control">
                            <option value="cho_xu_ly" <?= $yeuCau['trang_thai'] == 'cho_xu_ly' ? 'selected' : '' ?>>
                                Chờ xử lý
                            </option>
                            <option value="da_xac_nhan" <?= $yeuCau['trang_thai'] == 'da_xac_nhan' ? 'selected' : '' ?>>
                                Đã xác nhận
                            </option>
                            <option value="da_huy" <?= $yeuCau['trang_thai'] == 'da_huy' ? 'selected' : '' ?>>
                                Đã huỷ
                            </option>
                        </select>
                    </div>

                </div>

                <!-- BUTTON -->
                <div class="text-end">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-save me-1"></i> Cập nhật
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {

        const doctor = document.getElementById("doctor");
        const date = document.getElementById("date");
        const select = document.getElementById("examTime");

        const selectedTime = "<?= $yeuCau['gio_bat_dau'] ?? '' ?>";

        if (!doctor || !date || !select) return;

        doctor.addEventListener("change", loadTime);
        date.addEventListener("change", loadTime);

        function loadTime() {

            let doctorValue = doctor.value;
            let dateValue = date.value;

            if (!doctorValue || !dateValue) {
                select.innerHTML = "<option value=''>-- Vui lòng chọn bác sĩ và ngày --</option>";
                return;
            }

            fetch("admin.php?admin=getAvailableTime", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "bac_si_id=" + doctorValue + "&ngay=" + dateValue
            })
                .then(res => res.json())
                .then(data => {

                    select.innerHTML = "";

                    if (!data || data.length === 0) {
                        select.innerHTML = "<option value=''>Hết lịch</option>";
                    } else {

                        select.innerHTML = "<option value=''>-- Chọn giờ khám --</option>";

                        data.forEach(time => {

                            let selected = (time === selectedTime) ? "selected" : "";

                            select.innerHTML += `<option value="${time}" ${selected}>${time}</option>`;
                        });
                    }
                })
                .catch(() => {
                    select.innerHTML = "<option value=''>Lỗi tải dữ liệu</option>";
                });
        }

        // 👉 Load ngay khi vào trang
        loadTime();
    });
</script>