<?php
$error = $_SESSION['auth_error'] ?? '';
unset($_SESSION['auth_error']);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Đăng ký - MediTrust</title>
    <meta name="description" content="Đăng ký tài khoản bệnh nhân MediTrust">
    <meta name="keywords" content="đăng ký, tài khoản bệnh nhân, MediTrust">

    <link href="frontend/assets/img/favicon.png" rel="icon">
    <link href="frontend/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <link href="frontend/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="frontend/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="frontend/assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="frontend/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="frontend/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="frontend/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">

    <link href="frontend/assets/css/main.css" rel="stylesheet">
</head>

<body class="auth-page">
    <?php require_once __DIR__ . '/../header.php'; ?>

    <main class="main">
        <section class="auth-shell section">
            <div class="container">
                <div class="auth-card auth-card--reverse">
                    <div class="auth-visual">
                        <div class="auth-visual-inner">
                            <span class="auth-pill">Patient Account</span>
                            <h1>Tạo tài khoản chăm sóc nụ cười</h1>
                            <p>Đăng ký tài khoản để theo dõi kế hoạch niềng răng, lịch kiểm tra định kỳ và kết quả điều trị.</p>
                            <ul class="auth-list">
                                <li><i class="bi bi-shield-check"></i>Bảo mật thông tin cá nhân</li>
                                <li><i class="bi bi-calendar2-check"></i>Đặt lịch khám nhanh theo bác sĩ</li>
                                <li><i class="bi bi-chat-heart"></i>Nhận tư vấn và nhắc lịch tự động</li>
                            </ul>
                        </div>
                    </div>

                    <div class="auth-form">
                        <div class="auth-form-header">
                            <h2>Đăng ký</h2>
                            <p>Vui lòng cung cấp thông tin để tạo tài khoản bệnh nhân.</p>
                        </div>

                        <?php if (!empty($error)) : ?>
                            <div class="auth-alert error">
                                <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="index.php?page=register" class="auth-form-body">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="ho_ten">Họ và tên</label>
                                    <div class="auth-input-wrap">
                                        <i class="bi bi-person"></i>
                                        <input type="text" id="ho_ten" name="ho_ten" placeholder="Nguyễn Văn A" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="so_dien_thoai">Số điện thoại</label>
                                    <div class="auth-input-wrap">
                                        <i class="bi bi-telephone"></i>
                                        <input type="text" id="so_dien_thoai" name="so_dien_thoai" maxlength="10" pattern="0[0-9]{9}"
                                            oninput="this.value=this.value.replace(/[^0-9]/g,'')" placeholder="0901234567" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <div class="auth-input-wrap">
                                        <i class="bi bi-envelope"></i>
                                        <input type="email" id="email" name="email" placeholder="email@domain.com">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="gioi_tinh">Giới tính</label>
                                    <div class="auth-input-wrap">
                                        <i class="bi bi-gender-ambiguous"></i>
                                        <select id="gioi_tinh" name="gioi_tinh">
                                            <option value="">Chọn</option>
                                            <option value="Nam">Nam</option>
                                            <option value="Nu">Nữ</option>
                                            <option value="Khac">Khác</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="ngay_sinh">Ngày sinh</label>
                                    <div class="auth-input-wrap">
                                        <i class="bi bi-calendar-date"></i>
                                        <input type="date" id="ngay_sinh" name="ngay_sinh">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="dia_chi">Địa chỉ</label>
                                    <div class="auth-input-wrap">
                                        <i class="bi bi-geo-alt"></i>
                                        <input type="text" id="dia_chi" name="dia_chi" placeholder="Số nhà, đường, quận/huyện">
                                    </div>
                                </div>
                            </div>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="mat_khau">Mật khẩu</label>
                                    <div class="auth-input-wrap">
                                        <i class="bi bi-lock"></i>
                                        <input type="password" id="mat_khau" name="mat_khau" placeholder="Tối thiểu 6 ký tự" required>
                                        <button type="button" class="password-toggle" data-toggle-password="mat_khau" aria-label="Hiện mật khẩu">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="mat_khau_nhap_lai">Nhập lại mật khẩu</label>
                                    <div class="auth-input-wrap">
                                        <i class="bi bi-shield-lock"></i>
                                        <input type="password" id="mat_khau_nhap_lai" name="mat_khau_nhap_lai" placeholder="Nhập lại mật khẩu" required>
                                        <button type="button" class="password-toggle" data-toggle-password="mat_khau_nhap_lai" aria-label="Hiện mật khẩu">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="password-strength" id="passwordStrength" aria-live="polite">
                                <div class="strength-bar"><span></span></div>
                                <small class="strength-text">Mật khẩu nên có chữ hoa, chữ thường, số và ký tự đặc biệt.</small>
                            </div>
                            <div class="password-match" id="passwordMatch" aria-live="polite"></div>

                            <div class="auth-check">
                                <input type="checkbox" id="xac_nhan_dieu_khoan" required>
                                <label for="xac_nhan_dieu_khoan">Tôi đồng ý với điều khoản sử dụng và chính sách bảo mật.</label>
                            </div>

                            <div class="auth-note">
                                <i class="bi bi-info-circle"></i>
                                Bạn có thể cập nhật thêm tiền sử bệnh sau khi đăng nhập tại trang Hồ sơ.
                            </div>

                            <div class="auth-submit-wrap">
                                <button type="submit" class="btn-auth">
                                    Tạo tài khoản
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>

                            <div class="auth-links">
                                <span>Đã có tài khoản?</span>
                                <a href="index.php?page=login">Đăng nhập</a>
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