<?php
$error = $_SESSION['auth_error'] ?? '';
$success = $_SESSION['auth_success'] ?? '';
unset($_SESSION['auth_error'], $_SESSION['auth_success']);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <title>Quên mật khẩu - MediTrust</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="frontend/assets/img/favicon.png" rel="icon">
    <link href="frontend/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <link href="frontend/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="frontend/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="frontend/assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="frontend/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="frontend/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="frontend/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">

    <link href="frontend/assets/css/main.css" rel="stylesheet">

    <style>
        /* FIX TRANG QUÊN MẬT KHẨU BỊ HEADER ĐÈ */
        .forgot-page .main {
            padding-top: 120px;
        }

        .forgot-page .auth-shell {
            padding-top: 25px;
            padding-bottom: 60px;
        }

        .forgot-page .auth-card {
            margin-top: 0;
            max-width: 1180px;
            min-height: 560px;
        }

        .forgot-page .auth-form {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .forgot-page .auth-form-header h2 {
            font-family: var(--heading-font);
            font-weight: 700;
            margin-bottom: 8px;
        }

        .forgot-page .auth-form-header p {
            font-size: 19px;
            color: #65758b;
            margin-bottom: 35px;
        }

        .forgot-page .auth-input-wrap {
            height: 62px;
        }

        .forgot-page .btn-auth {
            height: 64px;
            margin-top: 8px;
        }

        .forgot-page .auth-links {
            display: flex;
            justify-content: center;
            margin-top: 26px;
        }

        .forgot-page .auth-links a {
            color: #1d3f95;
            font-weight: 700;
            text-decoration: none;
        }

        .forgot-page .auth-links a:hover {
            color: #0b8a83;
        }

        @media (max-width: 991px) {
            .forgot-page .auth-card {
                grid-template-columns: 1fr;
            }

            .forgot-page .auth-visual {
                display: none;
            }

            .forgot-page .auth-form {
                padding: 45px 28px;
            }

            .forgot-page .auth-form-header h2 {
                font-size: 36px;
            }

            .forgot-page .auth-form-header p {
                font-size: 16px;
            }
        }
    </style>
</head>

<body class="auth-page forgot-page">
    <?php require_once __DIR__ . '/../header.php'; ?>

    <main class="main">
        <section class="auth-shell section">
            <div class="container">
                <div class="auth-card auth-card--login">

                    <div class="auth-visual">
                        <div class="auth-visual-inner">
                            <span class="auth-pill">Cổng Thông Tin Nha Khoa</span>

                            <h1>Lấy lại quyền truy cập tài khoản</h1>

                            <p>
                                Nhận mã xác nhận qua email để đặt lại mật khẩu và tiếp tục theo dõi hồ sơ,
                                lịch hẹn cùng kế hoạch điều trị của bạn.
                            </p>

                            <div class="auth-metrics">
                                <div class="metric">
                                    <strong>OTP</strong>
                                    <span>Xác nhận qua email</span>
                                </div>

                                <div class="metric">
                                    <strong>10'</strong>
                                    <span>Hiệu lực mã</span>
                                </div>

                                <div class="metric">
                                    <strong>1</strong>
                                    <span>Tài khoản bảo mật</span>
                                </div>
                            </div>

                            <div class="auth-quote">
                                <i class="bi bi-shield-lock"></i>
                                <p>“Bảo mật tài khoản giúp bảo vệ thông tin điều trị và lịch hẹn của bạn.”</p>
                            </div>
                        </div>
                    </div>

                    <div class="auth-form">
                        <div class="auth-form-header">
                            <h2>Quên mật khẩu</h2>
                            <p>Nhập email hoặc số điện thoại đã đăng ký để nhận mã xác nhận.</p>
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

                        <form method="POST" action="index.php?page=forgot_password" class="auth-form-body">

                            <div class="form-group">
                                <label for="email">Nhập email đã đăng ký</label>
                                <div class="auth-input-wrap">
                                    <i class="bi bi-envelope"></i>
                                    <input type="email"
                                        id="email"
                                        name="email"
                                        placeholder="Nhập Email đã đăng ký"
                                        required>
                                </div>
                            </div>

                            <button type="submit" class="btn-auth">
                                Gửi mã xác nhận
                                <i class="bi bi-send"></i>
                            </button>

                            <div class="auth-links">
                                <a href="index.php?page=login">
                                    <i class="bi bi-arrow-left"></i> Quay lại đăng nhập
                                </a>
                            </div>

                            <div class="auth-note">
                                <i class="bi bi-shield-check"></i>
                                Mã xác nhận sẽ được gửi về email đang liên kết với tài khoản của bạn.
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
    <script src="frontend/assets/js/main.js"></script>
</body>

</html>