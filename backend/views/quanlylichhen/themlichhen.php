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
<div class="container-fluid">

    <h1 class="h3 mb-2 text-gray-800">Tạo lịch khám trực tiếp</h1>
    <p class="mb-4">Khách đến trực tiếp tại phòng khám</p>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-body">

            <form method="POST" action="admin.php?admin=themlichhentructiep">

                <input type="hidden" name="loai_dat" value="truc_tiep">
                <input type="hidden" name="trang_thai" value="cho_kham">
                <input type="hidden" name="gio_ket_thuc" id="gio_ket_thuc">

                <div class="form-row">

                    <!-- CHỌN HỒ SƠ -->
                    <!-- HỌ VÀ TÊN -->
                    <div class="form-group col-md-6">
                        <label>Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" name="ho_ten" class="form-control" required>
                    </div>

                    <!-- SỐ ĐIỆN THOẠI -->
                    <div class="form-group col-md-6">
                        <label>Số điện thoại <span class="text-danger">*</span></label>
                        <input type="text" name="so_dien_thoai" class="form-control" required>
                    </div>
                    <!-- BÁC SĨ -->
                    <div class="form-group col-md-6">
                        <label>Bác sĩ <span class="text-danger">*</span></label>
                        <select id="doctor" name="bac_si_id" class="form-control" required>
                            <?php foreach ($dsBacSi as $bs): ?>
                                <option value="<?= $bs['id'] ?>">
                                    <?= $bs['ten_bac_si'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- NGÀY -->
                    <div class="form-group col-md-6">
                        <label>Chọn Ngày Khám <span class="text-danger">*</span></label>
                        <input type="date" id="date" name="ngay_hen" value="<?= date('Y-m-d') ?>" class="form-control"
                            required>
                    </div>
                    <!-- GIỜ -->
                    <div class="form-group col-md-6">
                        <label class="form-label fw-semibold">
                            Chọn Giờ Khám <span class="text-danger">*</span>
                        </label>
                        <select name="exam_time" id="examTime" class="form-control" required>
                            <option value="">-- Chọn Giờ Khám --</option>
                        </select>
                    </div>


                    <!-- DỊCH VỤ -->
                    <div class="form-group col-md-6">
                        <label>Dịch vụ <span class="text-danger">*</span></label>
                        <select name="dich_vu_id" class="form-control" required>
                            <?php foreach ($dsDichVu as $dv): ?>
                                <option value="<?= $dv['id'] ?>">
                                    <?= $dv['ten_dich_vu'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-danger">
                    Tạo lịch trực tiếp
                </button>

            </form>

        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {

        const doctor = document.getElementById("doctor");
        const date = document.getElementById("date");
        const select = document.getElementById("examTime");

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
                            select.innerHTML += `<option value="${time}">${time}</option>`;
                        });
                    }
                })
                .catch(() => {
                    select.innerHTML = "<option value=''>Lỗi tải dữ liệu</option>";
                });
        }

    });
</script>