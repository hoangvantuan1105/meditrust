<style>
    .filter-bar {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-bottom: 15px;
    }

    .filter-bar select {
        border-radius: 8px;
        padding: 6px 10px;
    }

    .filter-bar button {
        border-radius: 8px;
        padding: 6px 16px;
    }

    .btn-export {
        float: right;
        margin-bottom: 10px;
        border-radius: 20px;
        padding: 6px 18px;
        font-size: 14px;
    }





    .money {
        font-weight: bold;
        color: #e74a3b;
    }

    .card-header {
        background: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
    }

    .table-responsive {
        border-radius: 10px;
        overflow: hidden;
    }
</style>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Lịch sử xuất vật tư</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách chi tiết</h6>
        </div>
        <form method="get" class="filter-bar">
            <input type="hidden" name="admin" value="historyExportMaterial">

            <select name="bac_si_id" class="form-control" style="width:250px">
                <option value="">-- Tất cả bác sĩ --</option>
                <?php foreach ($doctors as $d): ?>
                    <option value="<?= $d['id'] ?>"
                        <?= ($_GET['bac_si_id'] ?? '') == $d['id'] ? 'selected' : '' ?>>
                        <?= $d['ten_bac_si'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button class="btn btn-primary">Lọc</button>

            <a href="admin.php?admin=exportExcel" class="btn btn-success btn-export">
                Xuất Excel
            </a>
        </form>

        <div class="card-body">
            <div class="table-responsive">



                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên vật Tư</th>
                            <th>Giá Nhập</th>
                            <th>Thành Tiền</th>
                            <th>Lý do</th>
                            <th>Số Lượng</th>
                            <th>Tên bác sĩ</th>
                            <th>Ngày xuất</th>



                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Tên vật Tư</th>
                            <th>Giá Nhập</th>
                            <th>Thành Tiền</th>
                            <th>Lý do</th>
                            <th>Số Lượng</th>
                            <th>Tên bác sĩ</th>
                            <th>Ngày xuất</th>


                        </tr>
                    </tfoot>
                    <tbody>



                        <?php foreach ($historyMaterials as $m): ?>

                            <?php $tong = 0; ?>
                            <?php $tong += $m['thanh_tien']; ?>
                            <tr>
                                <td><?= $m['id']  ?></td>
                                <td><?= $m['ten_vat_tu'] ?></td>
                                <td><?= $m['gia_nhap'] ?></td>
                                <td><?= number_format($m['thanh_tien']) ?> đ</td>
                                <td><?= $m['ly_do'] ?></td>
                                <th><?= $m['so_luong'] ?></th>
                                <td><?= $m['ten_bac_si'] ?></td>
                                <td><?= $m['ngay_xuat'] ?></td>

                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>