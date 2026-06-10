<?php
$error = '';
$old = null;

if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    $old = $_SESSION['old'] ?? null;
    unset($_SESSION['error'], $_SESSION['old']);
}
?>

<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Sửa tài khoản bệnh nhân</h1>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Thông tin tài khoản</h6>
        </div>

        <div class="card-body">
            <form method="POST" action="admin.php?admin=updatePatientAccount">

                <input type="hidden" name="id" value="<?= $account['id'] ?>">

                <!-- Hồ sơ -->
                <div class="form-group">
                    <label>Hồ sơ bệnh nhân</label>
                    <input type="text" class="form-control" value="<?= $account['ho_so_benh_nhan_id'] ?>" disabled>
                </div>

                <!-- SĐT -->
                <div class="form-group">
                    <label>Số điện thoại <span class="text-danger">*</span></label>
                    <input type="text" name="so_dien_thoai" class="form-control" maxlength="10" pattern="0[0-9]{9}"
                        oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                        value="<?= $old['so_dien_thoai'] ?? $account['so_dien_thoai'] ?>" required>

                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Cập nhật
                </button>
                <a href="admin.php?admin=patient-accounts" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>
</div>