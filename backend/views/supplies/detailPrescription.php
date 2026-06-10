<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Chi tiết đơn thuốc</h5>
            <a href="admin.php?admin=listPrescription" class="link-primary p-2">Trở về</a>
        </div>

        <div class="card-body">
            
            <?php
            $sumMoney = 0;
            foreach($detailPres as $pres){ 
                $sumMoney += $pres['thanh_tien'];
            ?>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Mã đơn thuốc</strong>
                    <p><?= $pres['ma_don_thuoc'] ?></p>
                </div>
                <div class="col-md-6">
                    <strong>Tên thuốc</strong>
                    <p><?= $pres['ten_thuoc'] ?></p>
                </div>
                <div class="col-md-6">
                    <strong>Số lượng</strong>
                    <p><?= $pres['so_luong'] ?></p>
                </div>
                <div class="col-md-6">
                    <strong>Giá nhập</strong>
                    <p><?= $pres['gia_nhap'] ?></p>
                </div>
                <div class="col-md-6">
                    <strong>Thành tiền</strong>
                    <p><?= $pres['thanh_tien'] ?> VNĐ</p>
                </div>
                <div class="col-md-6">
                    <strong>Liều dùng</strong>
                    <p><?= $pres['lieu_dung'] ?></p>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
