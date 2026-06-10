<p><b>Tên:</b> <?= $lich['ho_ten'] ?></p>
<p><b>SĐT:</b> <?= $lich['sdt'] ?></p>

<hr>

<h5>Hồ sơ bệnh nhân</h5>

<form method="post" action="admin.php?admin=saveTiepNhan">
    <input type="hidden" name="lich_kham_id" value="<?= $lich['id'] ?>">

    <label>Họ tên</label>
    <input name="ho_ten" value="<?= $lich['ho_ten'] ?>" class="form-control">

    <label>SĐT</label>
    <input name="so_dien_thoai" value="<?= $lich['sdt'] ?>" class="form-control">

    <label>Ngày sinh</label>
    <input type="date" name="ngay_sinh" class="form-control">

    <label>Địa chỉ</label>
    <input name="dia_chi" class="form-control">

    <button class="btn btn-success">Tiếp nhận</button>
</form>