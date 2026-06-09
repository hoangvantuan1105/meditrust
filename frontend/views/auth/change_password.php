<?php
// session_start();

$error = $_SESSION['auth_error'] ?? '';
$success = $_SESSION['auth_success'] ?? '';
$step = $_SESSION['step'] ?? 1;

unset($_SESSION['auth_error'], $_SESSION['auth_success']);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Kích hoạt tài khoản - MediTrust</title>

    <link href="frontend/assets/img/favicon.png" rel="icon">
    <link href="frontend/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="frontend/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="frontend/assets/css/main.css" rel="stylesheet">
</head>

<body class="auth-page">
    <?php require_once __DIR__ . '/../header.php'; ?>

    <main class="main">
        <section class="auth-shell section">
            <div class="container">
                <div class="auth-card auth-card--login">

                    <!-- ================= CỘT TRÁI GIỮ NGUYÊN ================= -->
                    <div class="auth-visual">
                        <div class="auth-visual-inner">
                            <span class="auth-pill">Dental Portal</span>
                            <h1>Kích hoạt tài khoản lần đầu</h1>
                            <p>Thiết lập mật khẩu để bắt đầu sử dụng hệ thống theo dõi hồ sơ và lịch hẹn.</p>

                            <div class="auth-metrics">
                                <div class="metric">
                                    <strong>15+</strong>
                                    <span>Bác sĩ chuyên môn</span>
                                </div>
                                <div class="metric">
                                    <strong>40K+</strong>
                                    <span>Khách hàng tin chọn</span>
                                </div>
                                <div class="metric">
                                    <strong>99%</strong>
                                    <span>Hài lòng sau điều trị</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================= CỘT PHẢI FORM OTP ================= -->
                    <div class="auth-form">

                        <div class="auth-form-header">
                            <h2>Kích hoạt tài khoản</h2>
                            <p>Hoàn tất 3 bước để thiết lập mật khẩu.</p>
                        </div>

                        <?php if (!empty($error)): ?>
                            <div class="auth-alert error">
                                <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($success)): ?>
                            <div class="auth-alert success">
                                <?= htmlspecialchars($success) ?>
                            </div>
                        <?php endif; ?>


                        <!-- ================= STEP 1 ================= -->
                        <?php if ($step == 1): ?>
                            <form method="POST" action="index.php?page=sendLoginOTP" class="auth-form-body">

                                <div class="form-group">
                                    <label>Email tài khoản</label>
                                    <div class="auth-input-wrap">
                                        <i class="bi bi-envelope"></i>
                                        <input type="email" name="email" placeholder="Nhập email đã đăng ký" required>
                                    </div>
                                </div>

                                <button type="submit" class="btn-auth">
                                    Gửi mã OTP
                                    <i class="bi bi-arrow-right"></i>
                                </button>

                            </form>
                        <?php endif; ?>


                        <!-- ================= STEP 2 ================= -->
                        <?php if ($step == 2): ?>
                            <form method="POST" action="index.php?page=verifyLoginOTP" class="auth-form-body">

                                <div class="form-group">
                                    <label>Nhập mã OTP</label>
                                    <div class="auth-input-wrap">
                                        <i class="bi bi-shield-lock"></i>
                                        <input type="text" name="otp" placeholder="Nhập mã OTP đã gửi về email" required>
                                    </div>
                                </div>

                                <button type="submit" class="btn-auth">
                                    Xác nhận OTP
                                    <i class="bi bi-arrow-right"></i>
                                </button>

                            </form>
                        <?php endif; ?>


                        <!-- ================= STEP 3 ================= -->
                        <?php if ($step == 3): ?>
                            <form method="POST" action="index.php?page=update_first_password" class="auth-form-body">

                                <div class="form-group">
                                    <label>Mật khẩu mới</label>
                                    <div class="auth-input-wrap">
                                        <i class="bi bi-lock"></i>
                                        <input type="password" name="new_password" placeholder="Nhập mật khẩu mới" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Xác nhận mật khẩu</label>
                                    <div class="auth-input-wrap">
                                        <i class="bi bi-lock-fill"></i>
                                        <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu"
                                            required>
                                    </div>
                                </div>

                                <button type="submit" class="btn-auth">
                                    Hoàn tất kích hoạt
                                    <i class="bi bi-check-circle"></i>
                                </button>

                            </form>
                        <?php endif; ?>


                        <div class="auth-links mt-3">
                            <a href="index.php?page=login">
                                <i class="bi bi-arrow-left"></i> Quay lại đăng nhập
                            </a>
                        </div>

                    </div>

                </div>
            </div>
        </section>
    </main>

    <?php require_once __DIR__ . '/../footer.php'; ?>

    <script src="frontend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>