<?php


$error = $_SESSION['profile_error'] ?? '';
$success = $_SESSION['profile_success'] ?? '';
$isEdit = (($_GET['mode'] ?? 'view') === 'edit');
unset($_SESSION['profile_error'], $_SESSION['profile_success']);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Hồ sơ người dùng - MediTrust</title>
    <meta name="description" content="Hồ sơ người dùng MediTrust">
    <meta name="keywords" content="hồ sơ, bệnh nhân, MediTrust">

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
    .auth-history-box {
        margin-top: 20px;
        background: rgba(255, 255, 255, 0.08);
        padding: 15px;
        border-radius: 12px;
        font-size: 14px;
    }

    .auth-history-box h4 {
        font-size: 16px;
        margin-bottom: 10px;
    }

    .history-item {
        margin-bottom: 10px;
    }
</style>

<body class="auth-page" data-profile-edit="<?= $isEdit ? '1' : '0' ?>">
    <?php require_once __DIR__ . '/../header.php'; ?>

    <main class="main">
        <section class="auth-shell section">
            <div class="container">
                <div class="auth-card auth-card--reverse">
                    <div class="auth-visual">
                        <div class="auth-visual-inner">
                            <span class="auth-pill">Patient Profile</span>
                            <h1>Hồ sơ chăm sóc răng miệng</h1>
                            <p>Thông tin hồ sơ đầy đủ giúp bác sĩ tư vấn chính xác và lên lịch điều trị hiệu quả hơn.
                            </p>
                            <ul class="auth-list">
                                <li><i class="bi bi-shield-check"></i>Thông tin được bảo mật</li>
                                <li><i class="bi bi-journal-medical"></i>Hồ sơ đồng bộ cho mọi lần khám</li>
                                <li><i class="bi bi-clock-history"></i>Cập nhật nhanh khi cần chỉnh sửa</li>
                            </ul>

                            <div class="auth-profile-stats">
                                <div class="metric">
                                    <strong><?= htmlspecialchars($hoSo['id'] ?? 'HSBN---') ?></strong>
                                    <span>Mã hồ sơ</span>
                                </div>
                                <div class="metric">
                                    <strong><?= htmlspecialchars($hoSo['so_dien_thoai'] ?? '---') ?></strong>
                                    <span>Số điện thoại</span>
                                </div>
                            </div>
                            <?php if (!empty($lichSuKham)): ?>
                                <div class="auth-history-box">
                                    <h4><i class="bi bi-clock-history"></i> Lịch sử khám</h4>

                                    <?php foreach ($lichSuKham as $kham): ?>
                                        <div class="history-item">
                                            <div><strong>Ngày:</strong>
                                                <?= htmlspecialchars($kham['ngay_kham'] ?? '') ?>
                                            </div>
                                            <div><strong>Bác sĩ khám:</strong>
                                                <?= htmlspecialchars($kham['ten_bac_si'] ?? 'Không xác định') ?>
                                            </div>
                                            <div><strong>Chẩn đoán:</strong>
                                                <?= htmlspecialchars($kham['chan_doan'] ?? '') ?>
                                            </div>
                                            <hr>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="auth-history-box">
                                    <h4><i class="bi bi-clock-history"></i> Lịch sử khám</h4>
                                    <p>Chưa có dữ liệu khám.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>


                    <div class="auth-form">
                        <div class="auth-form-header">
                            <h2>Hồ sơ người dùng</h2>
                            <p>Xem, chỉnh sửa và lưu thông tin cá nhân của bạn.</p>
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

                        <form method="POST" action="index.php?page=profile" class="auth-form-body" id="profileForm">
                            <div class="profile-toolbar">
                                <button type="button" id="profileEditBtn" class="btn-auth btn-auth--subtle" <?= $isEdit ? 'style="display:none;"' : '' ?>>
                                    <i class="bi bi-pencil-square"></i>
                                    Chỉnh sửa hồ sơ
                                </button>

                                <div class="profile-edit-actions" id="profileEditActions" <?= $isEdit ? '' : 'style="display:none;"' ?>>
                                    <button type="button" class="btn-auth btn-auth--ghost" id="profileCancelBtn">
                                        <i class="bi bi-x-circle"></i>
                                        Hủy
                                    </button>
                                    <button type="submit" class="btn-auth">
                                        <i class="bi bi-save"></i>
                                        Lưu thay đổi
                                    </button>
                                </div>
                            </div>

                            <div class="profile-section">
                                <h3>Thông tin cá nhân</h3>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="ho_ten">Họ và tên</label>
                                        <div class="auth-input-wrap">
                                            <i class="bi bi-person"></i>
                                            <input type="text" id="ho_ten" name="ho_ten"
                                                value="<?= htmlspecialchars($hoSo['ho_ten'] ?? '') ?>" data-editable="1"
                                                <?= $isEdit ? '' : 'disabled' ?> required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="so_dien_thoai">Số điện thoại</label>
                                        <div class="auth-input-wrap">
                                            <i class="bi bi-telephone"></i>
                                            <input type="text" id="so_dien_thoai"
                                                value="<?= htmlspecialchars($hoSo['so_dien_thoai'] ?? '') ?>" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <div class="auth-input-wrap">
                                            <i class="bi bi-envelope"></i>
                                            <input type="email" id="email" name="email"
                                                value="<?= htmlspecialchars($hoSo['email'] ?? '') ?>" data-editable="1"
                                                <?= $isEdit ? '' : 'disabled' ?>>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="gioi_tinh">Giới tính</label>
                                        <?php $gioiTinh = $hoSo['gioi_tinh'] ?? ''; ?>
                                        <div class="auth-input-wrap">
                                            <i class="bi bi-gender-ambiguous"></i>
                                            <select id="gioi_tinh" name="gioi_tinh" data-editable="1" <?= $isEdit ? '' : 'disabled' ?>>
                                                <option value="" <?= $gioiTinh === '' ? 'selected' : '' ?>>Chọn</option>
                                                <option value="Nam" <?= $gioiTinh === 'Nam' ? 'selected' : '' ?>>Nam
                                                </option>
                                                <option value="Nu" <?= $gioiTinh === 'Nu' ? 'selected' : '' ?>>Nữ</option>
                                                <option value="Khac" <?= $gioiTinh === 'Khac' ? 'selected' : '' ?>>Khác
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="ngay_sinh">Ngày sinh</label>
                                        <div class="auth-input-wrap">
                                            <i class="bi bi-calendar-date"></i>
                                            <input type="date" id="ngay_sinh" name="ngay_sinh"
                                                value="<?= htmlspecialchars($hoSo['ngay_sinh'] ?? '') ?>"
                                                data-editable="1" <?= $isEdit ? '' : 'disabled' ?>>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="dia_chi">Địa chỉ</label>
                                        <div class="auth-input-wrap">
                                            <i class="bi bi-geo-alt"></i>
                                            <input type="text" id="dia_chi" name="dia_chi"
                                                value="<?= htmlspecialchars($hoSo['dia_chi'] ?? '') ?>"
                                                data-editable="1" <?= $isEdit ? '' : 'disabled' ?>>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="profile-section">
                                <h3>Thông tin y tế &amp; liên hệ khẩn cấp</h3>
                                <div class="form-grid">


                                    <div class="form-group">
                                        <label for="cmnd_cccd">CMND/CCCD</label>
                                        <div class="auth-input-wrap">
                                            <i class="bi bi-card-text"></i>
                                            <input type="text" id="cmnd_cccd" name="cmnd_cccd"
                                                value="<?= htmlspecialchars($hoSo['cmnd_cccd'] ?? '') ?>"
                                                data-editable="1" <?= $isEdit ? '' : 'disabled' ?>>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="bao_hiem_y_te">Bảo hiểm y tế</label>
                                        <div class="auth-input-wrap">
                                            <i class="bi bi-shield-plus"></i>
                                            <input type="text" id="bao_hiem_y_te" name="bao_hiem_y_te"
                                                value="<?= htmlspecialchars($hoSo['bao_hiem_y_te'] ?? '') ?>"
                                                data-editable="1" <?= $isEdit ? '' : 'disabled' ?>>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="nguoi_lien_he_khan_cap">Người liên hệ khẩn cấp</label>
                                        <div class="auth-input-wrap">
                                            <i class="bi bi-person-badge"></i>
                                            <input type="text" id="nguoi_lien_he_khan_cap" name="nguoi_lien_he_khan_cap"
                                                value="<?= htmlspecialchars($hoSo['nguoi_lien_he_khan_cap'] ?? '') ?>"
                                                data-editable="1" <?= $isEdit ? '' : 'disabled' ?>>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="quan_he">Quan hệ</label>
                                        <div class="auth-input-wrap">
                                            <i class="bi bi-diagram-3"></i>
                                            <input type="text" id="quan_he" name="quan_he"
                                                value="<?= htmlspecialchars($hoSo['quan_he'] ?? '') ?>"
                                                data-editable="1" <?= $isEdit ? '' : 'disabled' ?>>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="sdt_nguoi_lien_he">SĐT người liên hệ</label>
                                        <div class="auth-input-wrap">
                                            <i class="bi bi-telephone-forward"></i>
                                            <input type="text" id="sdt_nguoi_lien_he" name="sdt_nguoi_lien_he"
                                                value="<?= htmlspecialchars($hoSo['sdt_nguoi_lien_he'] ?? '') ?>"
                                                data-editable="1" <?= $isEdit ? '' : 'disabled' ?>>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <noscript>
                                <div class="auth-note">
                                    <i class="bi bi-info-circle"></i>
                                    Nếu bạn đang ở chế độ xem, mở <a href="index.php?page=profile&mode=edit">chế độ
                                        chỉnh sửa</a> để cập nhật hồ sơ.
                                </div>
                            </noscript>
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