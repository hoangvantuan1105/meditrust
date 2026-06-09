<div class="container-fluid">

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">
                Chi tiết tin nhắn
            </h5>
        </div>

        <div class="card-body">

            <p><strong>Người gửi:</strong>
                <?= htmlspecialchars($tin['ten_nguoi_gui']) ?>
            </p>

            <p><strong>Email:</strong>
                <?= htmlspecialchars($tin['email_nguoi_gui']) ?>
            </p>

            <p><strong>Tiêu đề:</strong>
                <?= htmlspecialchars($tin['tieu_de']) ?>
            </p>

            <p><strong>Ngày gửi:</strong>
                <?= date("d/m/Y H:i", strtotime($tin['ngay_tao'])) ?>
            </p>

            <hr>

            <div style="white-space: pre-line;">
                <?= htmlspecialchars($tin['noi_dung']) ?>
            </div>

            <hr>

            <a href="admin.php?admin=tatCaTin" class="btn btn-secondary">
                ← Quay lại
            </a>

        </div>
    </div>

</div>