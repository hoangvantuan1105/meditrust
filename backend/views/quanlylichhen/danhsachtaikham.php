<div class="container-fluid mt-4">

    <!-- ===== TOAST THÔNG BÁO ===== -->
    <?php if (!empty($_GET['msg'])): ?>
        <?php $msgType = ($_GET['msg_type'] ?? 'success') === 'error' ? 'danger' : 'success'; ?>
        <div class="alert alert-<?= $msgType ?> alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-<?= $msgType === 'danger' ? 'exclamation-circle' : 'check-circle' ?> mr-2"></i>
            <?= htmlspecialchars(urldecode($_GET['msg'])) ?>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    <?php endif; ?>

    <!-- ===== HEADER ===== -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-sync-alt text-info mr-2"></i> Danh sách bệnh nhân tái khám
        </h1>
        <form method="POST" action="admin.php?admin=guiNhacNhoTatCa"
              onsubmit="return confirm('Gửi email nhắc nhở cho tất cả bệnh nhân có lịch trong 7 ngày tới và có email?')">
            <button type="submit" class="btn btn-info shadow-sm">
                <i class="fas fa-envelope mr-1"></i> Gửi nhắc nhở hàng loạt (7 ngày tới)
            </button>
        </form>
    </div>

    <!-- ===== THỐNG KÊ NHANH ===== -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tổng tái khám</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $tongTatCa ?></div>
                        </div>
                        <div class="col-auto"><i class="fas fa-list fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Chờ khám</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $choKham ?></div>
                        </div>
                        <div class="col-auto"><i class="fas fa-clock fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Hôm nay</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $homNay ?></div>
                        </div>
                        <div class="col-auto"><i class="fas fa-calendar-day fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Sắp đến (7 ngày)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $sapDen ?></div>
                        </div>
                        <div class="col-auto"><i class="fas fa-bell fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== BỘ LỌC ===== -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-filter mr-1"></i> Bộ lọc</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="admin.php" class="form-inline flex-wrap" style="gap:10px">
                <input type="hidden" name="admin" value="listTaiKham">

                <select name="trang_thai" class="form-control">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="cho_kham"  <?= ($_GET['trang_thai'] ?? '') === 'cho_kham'  ? 'selected' : '' ?>>Chờ khám</option>
                    <option value="dang_kham" <?= ($_GET['trang_thai'] ?? '') === 'dang_kham' ? 'selected' : '' ?>>Đang khám</option>
                    <option value="da_kham"   <?= ($_GET['trang_thai'] ?? '') === 'da_kham'   ? 'selected' : '' ?>>Đã khám</option>
                    <option value="vang_mat"  <?= ($_GET['trang_thai'] ?? '') === 'vang_mat'  ? 'selected' : '' ?>>Không đến khám</option>
                </select>

                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Từ</span>
                    </div>
                    <input type="date" name="tu_ngay" class="form-control" value="<?= htmlspecialchars($_GET['tu_ngay'] ?? '') ?>">
                </div>

                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Đến</span>
                    </div>
                    <input type="date" name="den_ngay" class="form-control" value="<?= htmlspecialchars($_GET['den_ngay'] ?? '') ?>">
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Lọc
                </button>
                <a href="admin.php?admin=listTaiKham" class="btn btn-secondary">
                    <i class="fas fa-redo"></i> Đặt lại
                </a>
            </form>
        </div>
    </div>

    <!-- ===== BẢNG ===== -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách tái khám</h6>
            <span class="badge badge-info"><?= count($listTaiKham) ?> bản ghi</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th>STT</th>
                            <th>Bệnh nhân</th>
                            <th>SĐT</th>
                            <th>Email</th>
                            <th>Bác sĩ</th>
                            <th>Dịch vụ / Bước</th>
                            <th>Ngày tái khám</th>
                            <th>Còn lại</th>
                            <th>Ghi chú</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($listTaiKham)): ?>
                            <?php foreach ($listTaiKham as $i => $row): ?>
                                <?php
                                $conLai   = (int) $row['con_lai'];
                                $isToday  = $conLai === 0;
                                $isLate   = $conLai < 0;
                                $isSoon   = $conLai > 0 && $conLai <= 7;
                                $rowClass = $isLate ? 'table-danger' : ($isToday ? 'table-warning' : ($isSoon ? 'table-info' : ''));
                                $tt       = $row['trang_thai'] ?? '';
                                $hasEmail = !empty($row['email']);
                                $hasGoc   = !empty($row['lich_kham_goc_id']);
                                ?>
                                <tr class="<?= $rowClass ?>">
                                    <td class="text-center"><?= $i + 1 ?></td>

                                    <td class="font-weight-bold">
                                        <?= htmlspecialchars($row['ten_benh_nhan'] ?? '---') ?>
                                    </td>

                                    <td class="text-center">
                                        <?= htmlspecialchars($row['sdt'] ?? '---') ?>
                                    </td>

                                    <td class="text-center" style="font-size:13px;">
                                        <?php if ($hasEmail): ?>
                                            <span class="text-primary" title="<?= htmlspecialchars($row['email']) ?>">
                                                <i class="fas fa-envelope mr-1"></i><?= htmlspecialchars($row['email']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted"><i class="fas fa-minus"></i></span>
                                        <?php endif; ?>
                                    </td>

                                    <td><?= htmlspecialchars($row['ten_bac_si'] ?? '---') ?></td>

                                    <td>
                                        <?= htmlspecialchars($row['ten_dich_vu'] ?? '---') ?>
                                        <?php if (!empty($row['ten_buoc'])): ?>
                                            <br>
                                            <span class="badge badge-primary" style="font-size:11px;">
                                                B<?= $row['buoc_thu_tu'] ?>
                                                <?php if (!empty($row['tong_buoc'])): ?>/<?= $row['tong_buoc'] ?><?php endif; ?>:
                                                <?= htmlspecialchars($row['ten_buoc']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center font-weight-bold">
                                        <?= !empty($row['ngay_kham']) ? date('d/m/Y', strtotime($row['ngay_kham'])) : '---' ?>
                                    </td>

                                    <td class="text-center">
                                        <?php if ($isLate): ?>
                                            <span class="badge badge-danger">Quá <?= abs($conLai) ?> ngày</span>
                                        <?php elseif ($isToday): ?>
                                            <span class="badge badge-warning">Hôm nay</span>
                                        <?php elseif ($isSoon): ?>
                                            <span class="badge badge-info">Còn <?= $conLai ?> ngày</span>
                                        <?php else: ?>
                                            <span class="text-muted">Còn <?= $conLai ?> ngày</span>
                                        <?php endif; ?>
                                    </td>

                                    <td><?= htmlspecialchars($row['ghi_chu'] ?? '') ?></td>

                                    <td class="text-center">
                                        <?php if ($tt === 'cho_kham'): ?>
                                            <span class="badge badge-secondary px-2">Chờ khám</span>
                                        <?php elseif ($tt === 'dang_kham'): ?>
                                            <span class="badge badge-primary px-2">Đang khám</span>
                                        <?php elseif ($tt === 'da_kham'): ?>
                                            <span class="badge badge-success px-2">Đã khám</span>
                                        <?php elseif ($tt === 'vang_mat'): ?>
                                            <span class="badge badge-danger px-2">Không đến khám</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Hành động -->
                                    <td class="text-center" style="min-width:190px;">
                                        <div class="d-flex flex-column" style="gap:4px;">

                                            <?php if ($tt !== 'da_kham' && $tt !== 'vang_mat'): ?>
                                            <div class="d-flex" style="gap:4px;">
                                                <!-- Đã khám -->
                                                <form method="POST" action="admin.php?admin=capNhatTrangThaiTaiKham" class="flex-fill">
                                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                    <input type="hidden" name="trang_thai" value="da_kham">
                                                    <button type="submit" class="btn btn-success btn-sm btn-block"
                                                        onclick="return confirm('Xác nhận bệnh nhân đã tái khám?')">
                                                        <i class="fas fa-check"></i> Đã khám
                                                    </button>
                                                </form>
                                                <!-- Không đến -->
                                                <form method="POST" action="admin.php?admin=capNhatTrangThaiTaiKham" class="flex-fill">
                                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                    <input type="hidden" name="trang_thai" value="vang_mat">
                                                    <button type="submit" class="btn btn-danger btn-sm btn-block"
                                                        onclick="return confirm('Xác nhận bệnh nhân không đến tái khám?')">
                                                        <i class="fas fa-times"></i> Không đến
                                                    </button>
                                                </form>
                                            </div>
                                            <?php endif; ?>

                                            <!-- Gửi nhắc nhở email -->
                                            <?php if ($hasEmail && $tt === 'cho_kham'): ?>
                                            <form method="POST" action="admin.php?admin=guiNhacNhoTaiKham">
                                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                <button type="submit" class="btn btn-outline-info btn-sm btn-block"
                                                    onclick="return confirm('Gửi email nhắc nhở đến <?= htmlspecialchars(addslashes($row['ten_benh_nhan'])) ?>?')">
                                                    <i class="fas fa-envelope mr-1"></i> Gửi nhắc nhở
                                                </button>
                                            </form>
                                            <?php elseif (!$hasEmail && $tt === 'cho_kham'): ?>
                                            <button class="btn btn-outline-secondary btn-sm btn-block" disabled
                                                title="Bệnh nhân chưa có email trong hồ sơ">
                                                <i class="fas fa-envelope-open mr-1"></i> Không có email
                                            </button>
                                            <?php endif; ?>

                                            <!-- Link lịch khám gốc -->
                                            <?php if ($hasGoc): ?>
                                            <a href="admin.php?admin=chiTietLichSuKham&id=<?= $row['lich_kham_goc_id'] ?>"
                                               class="btn btn-outline-primary btn-sm btn-block" target="_blank">
                                                <i class="fas fa-file-medical mr-1"></i> Xem lịch khám gốc
                                            </a>
                                            <?php endif; ?>

                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11" class="text-center text-muted py-5">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                    Không có bệnh nhân tái khám nào
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Chú thích màu sắc -->
<div class="container-fluid mb-4">
    <small class="text-muted">
        <span class="badge badge-danger mr-1">&nbsp;</span> Quá hạn &nbsp;
        <span class="badge badge-warning mr-1">&nbsp;</span> Hôm nay &nbsp;
        <span class="badge badge-info mr-1">&nbsp;</span> Trong 7 ngày tới &nbsp;
        <span class="badge badge-light border mr-1">&nbsp;</span> Bình thường
    </small>
</div>