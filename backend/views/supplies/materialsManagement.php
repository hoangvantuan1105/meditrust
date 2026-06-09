<style>
    /* Nút Icon nổi (Floating Action Button) */
    #aiChatToggle {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #1a73e8, #1557b0);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        cursor: pointer;
        box-shadow: 0 10px 25px rgba(26, 115, 232, 0.4);
        z-index: 9999;
        transition: 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    #aiChatToggle:hover {
        transform: scale(1.1) rotate(5deg);
    }

    .chat-badge {
        position: absolute;
        top: -2px;
        right: -2px;
        background: #ef4444;
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 10px;
        font-weight: bold;
    }

    /* Khung Chat Wrapper */
    #aiChatWrapper {
        position: fixed;
        bottom: 90px;
        right: 20px;
        width: 350px;
        max-width: 90vw;
        z-index: 9999;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        transform-origin: bottom right;
    }

    .chat-hidden {
        opacity: 0;
        transform: scale(0) translateY(100px);
        pointer-events: none;
    }

    .chat-card {
        border-radius: 20px !important;
        overflow: hidden;
        border: none !important;
    }

    /* Nội dung tin nhắn */
    #aiBox {
        height: 350px;
        overflow-y: auto;
        padding: 15px;
        background: #f8fafc;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    #aiBox div {
        max-width: 85%;
        padding: 10px 14px;
        border-radius: 15px;
        font-size: 14px;
        line-height: 1.4;
    }

    /* Style tin nhắn Bạn/AI */
    #aiBox .user-msg {
        align-self: flex-end;
        background: #1a73e8;
        color: white;
        border-bottom-right-radius: 2px;
    }

    #aiBox .ai-msg {
        align-self: flex-start;
        background: white;
        color: #1e293b;
        border: 1px solid #e2e8f0;
        border-bottom-left-radius: 2px;
    }

    .ai-welcome {
        text-align: center;
        background: transparent !important;
        color: #64748b;
        font-style: italic;
        max-width: 100% !important;
    }

    /* Footer & Input */
    .chat-footer {
        padding: 15px;
        background: white;
        border-top: 1px solid #f1f5f9;
    }

    .chat-footer .form-control {
        border-radius: 10px 0 0 10px;
        border: 1px solid #e2e8f0;
        padding: 10px;
        font-size: 14px;
    }

    .chat-footer .btn {
        border-radius: 0 10px 10px 0;
        padding: 0 15px;
    }

    /* Tùy chỉnh thanh cuộn */
    #aiBox::-webkit-scrollbar {
        width: 5px;
    }

    #aiBox::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý vật tư</h1>
        <a href="admin.php?admin=addFormMaterials" class="btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Thêm vật tư mới
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách chi tiết</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID Vật Tư</th>
                            <th>Tên vật Tư</th>
                            <th>Đơn Vị</th>
                            <th>Số Lượng</th>
                            <th>Giá Nhập</th>
                            <th>Hãng sản xuất</th>
                            <th>Trạng thái sử dụng</th>
                            <th>Trạng thái</th>
                            <th>Hạn Sử Dụng</th>
                            <th>Danh mục</th>
                            <th>Ngày Tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID Vật Tư</th>
                            <th>Tên vật Tư</th>
                            <th>Đơn Vị</th>
                            <th>Số Lượng</th>
                            <th>Giá Nhập</th>
                            <th>Hãng sản xuất</th>
                            <th>Trạng thái sử dụng</th>
                            <th>Trạng thái</th>
                            <th>Hạn Sử Dụng</th>
                            <th>Danh mục</th>
                            <th>Ngày Tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php foreach ($allMaterials as $m): ?>

                            <?php
                            $lowStock = $m['so_luong'] <= 10;
                            $nearExpire = $m['days_left'] <= 30;
                            ?>

                            <tr class="<?= $nearExpire || $lowStock ? 'table-warning' : '' ?>">
                                <td><?= $m['id'] ?></td>
                                <td><?= $m['ten_vat_tu'] ?></td>
                                <td><?= $m['don_vi'] ?></td>
                                <?php
                                $qty = (int)$m['so_luong'];
                                ?>

                                <td>
                                    <?= $qty ?>

                                    <?php if ($qty == 0): ?>
                                        <span class="badge badge-danger ml-1">Hết hàng</span>

                                    <?php elseif ($qty <= 10): ?>
                                        <span class="badge badge-warning ml-1">Sắp hết</span>

                                    <?php endif; ?>
                                </td>


                                <td><?= number_format($m['gia_nhap']) ?>đ</td>
                                <td><?= $m['hang_san_xuat'] ?></td>

                                <td>
                                    <?= $m['trang_thai_han'] == 'con han' ? 'Còn hạn' : 'Hết hạn' ?>
                                </td>

                                <td>
                                    <?= $m['trang_thai'] == 'con hang' ? 'Còn vật tư' : 'Hết vật tư' ?>
                                </td>
                                <?php
                                $today = new DateTime();
                                $expire = new DateTime($m['han_su_dung']);
                                $daysLeft = (int)$today->diff($expire)->format('%r%a');
                                ?>

                                <td>
                                    <?= $m['han_su_dung'] ?>

                                    <?php if ($daysLeft < 0): ?>
                                        <span class="badge badge-danger ml-1">Hết hạn</span>

                                    <?php elseif ($daysLeft == 0): ?>
                                        <span class="badge badge-danger ml-1">Hết hạn hôm nay</span>

                                    <?php elseif ($daysLeft <= 30): ?>
                                        <span class="badge badge-warning ml-1">Sắp hết hạn</span>

                                    <?php endif; ?>
                                </td>


                                <td><?= $m['danh_muc'] == 'tieu hao' ? 'Tiêu hao' : 'Tái sử dụng' ?></td>
                                <td><?= $m['ngay_tao'] ?></td>

                                <td>
                                    <a href="admin.php?admin=updateFormMaterials&id=<?= $m['id'] ?>"
                                        class="btn btn-success btn-circle btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <a href="admin.php?admin=exportMaterial&id=<?= $m['id'] ?>"
                                        class="btn btn-warning btn-circle btn-sm">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </a>

                                    <!-- <a href="admin.php?admin=deleteMaterials&id=<?= $m['id'] ?>"
                                        class="btn btn-danger btn-circle btn-sm btn-delete">
                                        <i class="fas fa-trash"></i>
                                    </a> -->
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            let url = this.getAttribute('href');

            Swal.fire({
                title: 'Bạn chắc chắn?',
                text: "Dữ liệu sẽ bị xoá vĩnh viễn!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Xoá',
                cancelButtonText: 'Huỷ'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });
</script>

<?php if (!empty($warningMaterials)): ?>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            let html = `<table class="table table-bordered text-left">
        <tr>
            <th>Tên</th>
            <th>Số lượng</th>
            <th>Ngày hết hạn</th>
        </tr>`;

            <?php foreach ($warningMaterials as $w): ?>
                html += `
        <tr>
            <td><?= $w['ten_vat_tu'] ?></td>
            <td><?= $w['so_luong'] ?></td>
            <td><?= $w['han_su_dung'] ?></td>
        </tr>`;
            <?php endforeach; ?>

            html += `</table>`;

            Swal.fire({
                title: '⚠ Vật tư cần chú ý',
                html: html,
                width: 700,
                icon: 'warning'
            });

        });
    </script>
<?php endif; ?>

<?php if (!empty($_SESSION['confirm_delete'])): ?>
    <script>
        if (confirm("<?= $_SESSION['confirm_delete'] ?>")) {
            window.location = "admin.php?admin=deleteMaterials&id=<?= $_GET['id'] ?>&force=1";
        }
    </script>
<?php unset($_SESSION['confirm_delete']);
endif; ?>

<!-- <div id="aiChatToggle" onclick="toggleChat()">
    <i class="fa-solid fa-robot"></i>
    <span class="chat-badge">AI</span>
</div> -->

<div id="aiChatWrapper" class="chat-hidden">
    <div class="card shadow-lg chat-card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <span><i class="fa-solid fa-microchip me-2"></i>AI MediTrust</span>
            <button onclick="toggleChat()" class="btn-close btn-close-white" style="font-size: 10px;"></button>
        </div>
        <div class="card-body p-0">
            <div id="aiBox">
                <div class="ai-welcome">Xin chào! Tôi có thể giúp gì cho bạn về vật tư y tế không?</div>
            </div>
            <div class="chat-footer">
                <div class="input-group">
                    <input id="aiInput" class="form-control" placeholder="Nhập câu hỏi...">
                    <button onclick="sendMessage()" class="btn btn-primary">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function sendMessage() {
        let input = document.getElementById("aiInput");
        let box = document.getElementById("aiBox");

        if (!input || !box) return;

        let msg = input.value.trim();
        if (!msg) return;

        box.innerHTML += `<div><b>Bạn:</b> ${msg}</div>`;
        input.value = "";

        fetch("admin.php?admin=aiChatMaterials", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    message: msg
                })
            })
            .then(r => r.json())
            .then(d => {
                box.innerHTML += `<div><b>AI:</b> ${d.reply}</div>`;
                box.scrollTop = box.scrollHeight;

                if (d.reload) {
                    setTimeout(() => location.reload(), 800);
                }
            })
            .catch(err => {
                box.innerHTML += `<div style="color:red"><b>Lỗi:</b> ${err}</div>`;
            });
    }


    document.getElementById("aiInput").addEventListener("keydown", function(e) {
        if (e.key === "Enter") {
            e.preventDefault();
            sendMessage();
        }
    });
</script>


<script>
    // Hàm đóng mở chat
    function toggleChat() {
        const wrapper = document.getElementById("aiChatWrapper");
        wrapper.classList.toggle("chat-hidden");
    }

    function sendMessage() {
        let input = document.getElementById("aiInput");
        let box = document.getElementById("aiBox");

        if (!input || !box) return;

        let msg = input.value.trim();
        if (!msg) return;

        // Thêm tin nhắn của Bạn với class user-msg
        box.innerHTML += `<div class="user-msg"><b>Bạn:</b> ${msg}</div>`;
        input.value = "";
        box.scrollTop = box.scrollHeight;

        fetch("admin.php?admin=aiChatMaterials", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    message: msg
                })
            })
            .then(r => r.json())
            .then(d => {
                // Thêm tin nhắn AI với class ai-msg
                box.innerHTML += `<div class="ai-msg"><b>AI:</b> ${d.reply}</div>`;
                box.scrollTop = box.scrollHeight;

                if (d.reload) {
                    setTimeout(() => location.reload(), 800);
                }
            })
            .catch(err => {
                box.innerHTML += `<div style="color:red; align-self:center"><b>Lỗi:</b> ${err}</div>`;
            });
    }

    document.getElementById("aiInput").addEventListener("keydown", function(e) {
        if (e.key === "Enter") {
            e.preventDefault();
            sendMessage();
        }
    });
</script>