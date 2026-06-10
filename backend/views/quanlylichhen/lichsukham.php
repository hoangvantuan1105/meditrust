<div class="container-fluid mt-4">

    <?php $isAdmin = ($_SESSION['admin']['role'] === 'admin'); ?>

    <h1 class="h3 mb-3 text-gray-800">
        <?= $isAdmin ? 'Tất cả lịch sử khám' : 'Lịch sử khám của tôi' ?>
    </h1>

    <a href="admin.php?admin=listLichKham" class="btn btn-primary mb-3">Quay lại danh sách lịch khám</a>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Danh sách lịch khám
            </h6>
        </div>

        <div class="card-body">

            <!-- 🔎 Ô tìm kiếm -->
            <div class="mb-3">
                <input type="text" id="keyword" class="form-control"
                    placeholder="Tìm theo HSBN-ID hoặc chẩn đoán...">
            </div>

            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="text-center">
                        <th>ID</th>
                        <th>HSBN-ID</th>
                        <th>Chẩn đoán</th>
                        <th>Hướng điều trị</th>
                        <th>Ghi chú</th>
                        <th>Ngày khám</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>

                <!-- 🔥 Thêm id cho tbody -->
                <tbody id="resultTable">
                    <?php foreach ($listLichSuKham as $lk): ?>
                        <tr>
                            <td><?= $lk['id'] ?></td>
                            <td><?= $lk['ho_so_benh_nhan_id'] ?></td>
                            <td><?= $lk['chan_doan'] ?></td>
                            <td><?= $lk['huong_dieu_tri'] ?></td>
                            <td><?= !empty($lk['ghi_chu']) ? $lk['ghi_chu'] : 'Không có ghi chú' ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($lk['ngay_kham'])) ?></td>
                            <td class="text-center">
                                <a href="admin.php?admin=chiTietLichSuKham&id=<?= $lk['id'] ?>"
                                    class="btn btn-sm btn-info">Chi tiết</a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        /* ===============================
           PHẦN SWEET ALERT (giữ nguyên)
        ================================ */

        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');

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

            window.history.replaceState({}, document.title, window.location.pathname + "?admin=listLichKham");
        }


        /* ===============================
           🔎 PHẦN AJAX TÌM KIẾM
        ================================ */

        document.getElementById("keyword").addEventListener("keyup", function() {

            let keyword = this.value;

            fetch("admin.php?admin=searchLichSuKham", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: "keyword=" + encodeURIComponent(keyword)
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById("resultTable").innerHTML = data;
                });

        });

    });
</script>