
<div class="container-fluid mt-4">

    <?php $isAdmin = ($_SESSION['admin']['role'] === 'admin'); ?>

    <h1 class="h3 mb-3 text-gray-800">
        <?= $isAdmin ? 'Tất cả lịch khám' : 'Lịch khám của tôi' ?>
    </h1>

    <button onclick="window.location.href='admin.php?admin=listLichSuKham'" class="btn btn-sm btn-primary mb-3">Lịch sử khám</button>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Danh sách lịch khám
            </h6>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-hover" id="dataTable">
                <thead>
                    <tr class="text-center">
                        <th>ID</th>

                        <?php if ($isAdmin): ?>
                            <th>Bác sĩ</th>
                        <?php endif; ?>

                        <th>Bệnh nhân</th>
                        <th>SĐT</th>
                        <th>Dịch vụ</th>
                        <th>Ngày khám</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($listLichKham)): ?>
                        <?php foreach ($listLichKham as $lk): ?>
                            <tr>
                                <td class="text-center"><?= $lk['id'] ?></td>

                                <?php if ($isAdmin): ?>
                                    <td><?= htmlspecialchars($lk['ten_bac_si'] ?? '-') ?></td>
                                <?php endif; ?>

                                <td><?= htmlspecialchars($lk['ten_benh_nhan']) ?></td>
                                <td class="text-center"><?= htmlspecialchars($lk['so_dien_thoai']) ?></td>
                                <td><?= htmlspecialchars($lk['ten_dich_vu'] ?? '-') ?></td>

                                 <td class="text-center">
                                    <?= date('d/m/Y', strtotime($lk['ngay_kham'])) ?>
                                    <br>
                                    <?= date('H:i', strtotime($lk['gio_bat_dau'])) ?>
                                    -
                                    <?= date('H:i', strtotime($lk['gio_ket_thuc'])) ?>
                                </td>

                                <td class="text-center">
                                    <?php if ($lk['trang_thai'] === 'cho_kham'): ?>
                                        <span class="badge badge-warning">Chờ khám</span>

                                    <?php elseif ($lk['trang_thai'] === 'dang_kham'): ?>
                                        <span class="badge badge-info">Đang khám</span>

                                    <?php elseif ($lk['trang_thai'] === 'da_kham'): ?>
                                        <span class="badge badge-success">Đã khám</span>

                                    <?php else: ?>
                                        <span class="badge badge-secondary">
                                            <?= htmlspecialchars($lk['trang_thai']) ?>
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td class="text-center">
                                    <?php if ($lk['trang_thai'] === 'cho_kham'): ?>

                                        <a href="admin.php?admin=tiepNhanKham&id=<?= $lk['id'] ?>"
                                            class="btn btn-sm btn-success"
                                            onclick="return confirm('Xác nhận tiếp nhận bệnh nhân và bắt đầu khám?')">
                                            Tiếp nhận
                                        </a>

                                    <?php elseif ($lk['trang_thai'] === 'dang_kham'): ?>

                                        <a href="admin.php?admin=formKham&id=<?= $lk['id'] ?>"
                                            class="btn btn-sm btn-primary">
                                            Bắt đầu khám
                                        </a>


                                    <?php elseif ($lk['trang_thai'] === 'da_kham'): ?>

                                        <span class="badge badge-success">
                                            Đã khám
                                        </span>

                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    <?php else: ?>
                        <tr>
                            <td colspan="<?= $isAdmin ? 8 : 7 ?>"
                                class="text-center text-muted">
                                Chưa có lịch khám
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Chờ trang load xong mới quét URL
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Lấy query string từ thanh địa chỉ (ví dụ: ?status=out_of_stock)
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');
        const available = urlParams.get('available');

        // 2. Nếu có status thì mới gọi SweetAlert
        if (status) {
            if (status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: 'Đã lưu đơn thuốc cho lịch khám này!',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else if (status === 'error') {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Lịch khám này đã có đơn thuốc rồi!',
                });
            }

            // 3. (Tùy chọn) Xóa cái tham số trên URL để khi F5 không bị hiện lại thông báo
            window.history.replaceState({}, document.title, window.location.pathname + "?admin=listLichKham");
        }
    });
</script>