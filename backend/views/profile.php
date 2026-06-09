<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Hồ sơ cá nhân</h1>
    </div>

    <div class="row">
        <!-- Profile Card -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary">
                    <h6 class="m-0 font-weight-bold text-white">Thông tin tài khoản</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">

                        <div class="col-md-3 text-center">

                            <?php
                            $avatar = !empty($admin['avatar'])
                                ? "backend/uploads/avatar/" . $admin['avatar']
                                : "assets/img/te.jpg";
                            ?>

                            <!-- FORM -->
                            <form id="avatarForm"
                                action="admin.php?admin=updateAvatar"
                                method="POST"
                                enctype="multipart/form-data">

                                <!-- Ảnh đại diện -->
                                <label for="avatarInput" style="cursor:pointer;">
                                    <img id="avatarPreview"
                                        src="<?= $avatar ?>"
                                        class="rounded-circle shadow"
                                        style="
                    width:120px;
                    height:120px;
                    object-fit:cover;
                    border:4px solid #0d6efd;
                    transition:0.3s;
                "
                                        onmouseover="this.style.opacity=0.8"
                                        onmouseout="this.style.opacity=1">
                                </label>

                                <!-- Input ẩn -->
                                <input type="file"
                                    name="avatar"
                                    id="avatarInput"
                                    accept="image/*"
                                    style="display:none"
                                    onchange="previewAndSubmit(event)">

                                <small class="d-block mt-2 text-muted">
                                    Nhấn vào ảnh để thay đổi
                                </small>

                            </form>

                        </div>

                        <div class="col-md-9">
                            <form>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-4 col-form-label font-weight-bold">Tên người dùng:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" disabled value="<?= htmlspecialchars($admin['ten_nguoi_su_dung'] ?? '') ?>">
                                    </div>
                                </div>

                                <!-- <div class="form-group row mb-3">
                                    <label class="col-sm-4 col-form-label font-weight-bold">Email:</label>
                                    <div class="col-sm-8">
                                        <input type="email" class="form-control" disabled
                                            value="<?= htmlspecialchars($admin['email'] ?? '') ?>">
                                    </div>
                                </div> -->

                                <div class="form-group row mb-3">
                                    <label class="col-sm-4 col-form-label font-weight-bold">Số điện thoại:</label>
                                    <div class="col-sm-8">
                                        <input type="tel" class="form-control" disabled
                                            value="<?= htmlspecialchars($admin['sdt'] ?? '') ?>">
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <label class="col-sm-4 col-form-label font-weight-bold">Vị trí:</label>
                                    <div class="col-sm-8">
                                        <?php
                                        $mapRole = [
                                            'admin'  => 'Quản trị viên',
                                            'bac_si' => 'Bác sĩ',
                                            'le_tan' => 'Lễ tân',
                                        ];

                                        $role = $admin['role'] ?? 'admin';
                                        $textRole = $mapRole[$role] ?? 'Quản trị viên';
                                        ?>
                                        <input type="text"
                                            class="form-control"
                                            disabled
                                            value="<?= htmlspecialchars($textRole) ?>">
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-weight-bold">Ngôn ngữ:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control">
                                            <option selected>Tiếng Việt</option>
                                            <option>English</option>
                                            <option>中文</option>
                                        </select>
                                    </div>
                                </div>
                            </form>

                            <!-- <hr>
                            <button class="btn btn-primary btn-sm mr-2">Chỉnh sửa thông tin</button>
                            <button class="btn btn-secondary btn-sm">Hủy</button> -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Change Password Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-warning">
                    <h6 class="m-0 font-weight-bold text-white">Thay đổi mật khẩu</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="admin.php?admin=changePassword">
                        <div class="form-group">
                            <label>Mật khẩu hiện tại</label>
                            <input type="password" name="old_password" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Mật khẩu mới</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Xác nhận mật khẩu mới</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-warning btn-sm">
                            Cập nhật mật khẩu
                        </button>
                    </form>

                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-info">
                    <h6 class="m-0 font-weight-bold text-white">Thống kê nhanh</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3 pb-3 border-bottom">
                        <p class="mb-1"><strong>Bệnh nhân quản lý:</strong></p>
                        <p class="h5 text-info"><?= $benhNhan ?></p>
                    </div>
                    <div class="mb-3 pb-3 border-bottom">
                        <p class="mb-1">
                            <strong>Lượt khám tháng <?= $month ?>/<?= $year ?>:</strong>
                        </p>
                        <p class="h5 text-success">
                            <?= $soLuotKham ?>
                        </p>
                    </div>

                    <div class="mb-3">
                        <p class="mb-1"><strong>Lần đăng nhập gần đây:</strong></p>
                        <?php if (!empty($lastLogin)): ?>

                            <p class="small text-muted">
                                <?= date('d/m/Y H:i', strtotime($lastLogin['created_at'])) ?>

                                <br>
                                <small>IP: <?= $lastLogin['ip_address'] ?></small>
                            </p>
                        <?php else: ?>
                            <p class="small text-muted">Chưa có dữ liệu</p>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

            <!-- Security Card -->
            <!-- <div class="card shadow mb-4">
                <div class="card-header py-3 bg-danger">
                    <h6 class="m-0 font-weight-bold text-white">Bảo mật</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="mb-2"><i class="fas fa-check-circle text-success"></i> Xác thực 2 lớp</p>
                        <small class="text-muted">Bảo vệ tài khoản của bạn</small>
                    </div>
                    <div class="mb-3">
                        <p class="mb-2"><i class="fas fa-times-circle text-danger"></i> Biometric</p>
                        <small class="text-muted">Chưa được thiết lập</small>
                    </div>
                    <hr>
                    <button class="btn btn-sm btn-danger btn-block">Đăng xuất toàn bộ thiết bị</button>
                </div>
            </div> -->

            <!-- Activity Card -->
            <div class="card shadow">
                <div class="card-header py-3 bg-secondary">
                    <h6 class="m-0 font-weight-bold text-white">Hoạt động gần đây</h6>
                </div>
                <div class="card-body">

                    <?php if (!empty($loginLogs)): ?>

                        <?php foreach ($loginLogs as $index => $log): ?>
                            <div class="mb-3 pb-3 <?= $index < 2 ? 'border-bottom' : '' ?>">

                                <p class="mb-1">
                                    <small>
                                        <strong>
                                            <?php if ($log['status'] == 'SUCCESS'): ?>
                                                Đăng nhập thành công
                                            <?php else: ?>
                                                Đăng nhập thất bại
                                            <?php endif; ?>
                                        </strong>
                                    </small>
                                </p>

                                <p class="small text-muted">
                                    <?= date("d", strtotime($log['created_at'])) ?>
                                    Tháng <?= date("m", strtotime($log['created_at'])) ?>,
                                    <?= date("Y - h:i A", strtotime($log['created_at'])) ?>
                                    <br>
                                    IP: <?= $log['ip_address'] ?>
                                </p>

                            </div>
                        <?php endforeach; ?>

                    <?php else: ?>
                        <p class="text-muted">Chưa có hoạt động nào.</p>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function previewAndSubmit(event) {
        const input = event.target;
        const file = input.files[0];

        if (!file) return;

        const reader = new FileReader();

        reader.onload = function(e) {
            document.getElementById('avatarPreview').src = e.target.result;
        };

        reader.readAsDataURL(file);

        setTimeout(() => {
            document.getElementById('avatarForm').submit();
        }, 500);
    }
</script>