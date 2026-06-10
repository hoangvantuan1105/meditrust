<?php
$error = $_SESSION['auth_error'] ?? '';
$success = $_SESSION['auth_success'] ?? '';
unset($_SESSION['auth_error'], $_SESSION['auth_success']);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Đăng nhập - MediTrust</title>
    <meta name="description" content="Đăng nhập tài khoản bệnh nhân MediTrust">
    <meta name="keywords" content="đăng nhập, tài khoản bệnh nhân, MediTrust">

    <link href="frontend/assets/img/favicon.png" rel="icon">
    <link href="frontend/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <link href="frontend/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="frontend/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="frontend/assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="frontend/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="frontend/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="frontend/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">

    <link href="frontend/assets/css/main.css" rel="stylesheet">
</head>
<style>

</style>

<body class="auth-page">
    <?php require_once __DIR__ . '/../header.php'; ?>

    <main class="main">
        <section class="auth-shell section">
            <div class="container">
                <div class="auth-card auth-card--login">
                    <div class="auth-visual">
                        <div class="auth-visual-inner">
                            <span class="auth-pill">Dental Portal</span>
                            <h1>Xin chào, rất vui gặp lại bạn</h1>
                            <p>Đăng nhập để theo dõi hồ sơ răng miệng, lịch hẹn và nhận tư vấn cá nhân hóa.</p>
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
                            <div class="auth-quote">
                                <i class="bi bi-stars"></i>
                                <p>“Mỗi nụ cười khỏe đẹp bắt đầu từ một kế hoạch điều trị phù hợp.”</p>
                            </div>
                        </div>
                    </div>

                    <div class="auth-form">
                        <div class="auth-form-header">
                            <h2>Đăng nhập</h2>
                            <p>Nhập thông tin tài khoản để tiếp tục sử dụng hệ thống.</p>
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

                        <form method="POST" action="index.php?page=login" class="auth-form-body">

                            <div class="form-group">
                                <label for="account">Email hoặc Số điện thoại</label>
                                <div class="auth-input-wrap">
                                    <i class="bi bi-person"></i>
                                    <input type="text" id="account" name="account"
                                        placeholder="Nhập email hoặc số điện thoại" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="mat_khau">Mật khẩu</label>
                                <div class="auth-input-wrap">
                                    <i class="bi bi-lock"></i>
                                    <input type="password" id="mat_khau" name="mat_khau" placeholder="Nhập mật khẩu"
                                        required>
                                    <button type="button" class="password-toggle" data-toggle-password="mat_khau">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <button type="submit" class="btn-auth">
                                Đăng nhập
                                <i class="bi bi-arrow-right"></i>
                            </button>

                            <!-- Nút kích hoạt lần đầu -->
                            <div class="auth-links">
                                <a href="index.php?page=first_login">
                                    <i class="bi bi-key"></i> Kích hoạt tài khoản lần đầu
                                </a>
                            </div>

                            <div class="auth-note">
                                <i class="bi bi-shield-lock"></i>
                                Tài khoản được tạo khi phòng khám lập hồ sơ cho bạn.
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php require_once __DIR__ . '/../footer.php'; ?>

    <script src="frontend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="frontend/assets/vendor/php-email-form/validate.js"></script>
    <script src="frontend/assets/vendor/aos/aos.js"></script>
    <script src="frontend/assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="frontend/assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="frontend/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="frontend/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="frontend/assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="frontend/assets/js/auth-ui.js"></script>
    <script src="frontend/assets/js/main.js"></script>
</body>

</html>