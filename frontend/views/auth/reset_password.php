<?php
$error = $_SESSION['auth_error'] ?? '';
$success = $_SESSION['auth_success'] ?? '';
$reset_email = $_SESSION['reset_email'] ?? '';

unset($_SESSION['auth_error'], $_SESSION['auth_success']);

if (empty($reset_email)) {
    header("Location: index.php?page=forgot_password");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Xác nhận OTP - MediTrust</title>

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

                    <div class="auth-visual">
                        <div class="auth-visual-inner">
                            <span class="auth-pill">Cổng Thông Tin Nha Khoa</span>
                            <h1>Xác nhận mã OTP</h1>
                            <p>Nhập mã OTP đã gửi về Gmail để tiếp tục đặt lại mật khẩu.</p>

                            <div class="auth-metrics">
                                <div class="metric">
                                    <strong>6</strong>
                                    <span>Chữ số OTP</span>
                                </div>
                                <div class="metric">
                                    <strong>10'</strong>
                                    <span>Hiệu lực mã</span>
                                </div>
                                <div class="metric">
                                    <strong>SSL</strong>
                                    <span>Bảo mật dữ liệu</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="auth-form">

                        <div class="auth-form-header">
                            <h2>Xác nhận OTP</h2>
                            <p>Nhập mã xác nhận đã được gửi về Gmail của bạn.</p>
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

                        <div class="auth-note mb-3">
                            <i class="bi bi-envelope-check"></i>
                            Mã đã gửi tới: <b><?= htmlspecialchars($reset_email) ?></b>
                        </div>

                        <form method="POST" action="index.php?page=reset_password" class="auth-form-body">

                            <div class="form-group">
                                <label>Mã OTP</label>
                                <div class="auth-input-wrap">
                                    <i class="bi bi-shield-lock"></i>
                                    <input type="text"
                                           name="otp"
                                           maxlength="6"
                                           placeholder="Nhập mã OTP 6 số"
                                           required>
                                </div>
                            </div>

                            <button type="submit" class="btn-auth">
                                Xác nhận OTP
                                <i class="bi bi-arrow-right"></i>
                            </button>

                        </form>

                        <div class="auth-links mt-3">
                            <a href="index.php?page=forgot_password">
                                <i class="bi bi-arrow-repeat"></i> Gửi lại mã OTP
                            </a>
                        </div>

                        <div class="auth-links mt-2">
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