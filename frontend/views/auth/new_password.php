<?php
$error = $_SESSION['auth_error'] ?? '';
$success = $_SESSION['auth_success'] ?? '';

unset($_SESSION['auth_error'], $_SESSION['auth_success']);

if (
    empty($_SESSION['reset_email']) ||
    empty($_SESSION['reset_verified']) ||
    empty($_SESSION['reset_id']) ||
    empty($_SESSION['reset_tai_khoan_id'])
) {
    header("Location: index.php?page=forgot_password");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Tạo mật khẩu mới - MediTrust</title>

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
                            <h1>Tạo mật khẩu mới</h1>
                            <p>Mã OTP đã được xác nhận. Vui lòng thiết lập mật khẩu mới cho tài khoản.</p>

                            <div class="auth-metrics">
                                <div class="metric">
                                    <strong>OK</strong>
                                    <span>OTP hợp lệ</span>
                                </div>
                                <div class="metric">
                                    <strong>6+</strong>
                                    <span>Ký tự mật khẩu</span>
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
                            <h2>Mật khẩu mới</h2>
                            <p>Nhập mật khẩu mới để hoàn tất đặt lại tài khoản.</p>
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

                        <form method="POST" action="index.php?page=new_password" class="auth-form-body">

                            <div class="form-group">
                                <label>Mật khẩu mới</label>
                                <div class="auth-input-wrap">
                                    <i class="bi bi-lock"></i>
                                    <input type="password"
                                           name="mat_khau_moi"
                                           placeholder="Nhập mật khẩu mới"
                                           required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Xác nhận mật khẩu</label>
                                <div class="auth-input-wrap">
                                    <i class="bi bi-lock-fill"></i>
                                    <input type="password"
                                           name="nhap_lai_mat_khau"
                                           placeholder="Nhập lại mật khẩu mới"
                                           required>
                                </div>
                            </div>

                            <button type="submit" class="btn-auth">
                                Hoàn tất đổi mật khẩu
                                <i class="bi bi-check-circle"></i>
                            </button>

                        </form>

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