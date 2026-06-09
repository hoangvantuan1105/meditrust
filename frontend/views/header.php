<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$patientSession = $_SESSION['patient'] ?? null;
$patientName = trim($patientSession['ho_ten'] ?? '');
if ($patientName === '') {
    $patientName = 'Tài khoản';
}
$patientInitial = mb_strtoupper(mb_substr($patientName, 0, 1, 'UTF-8'), 'UTF-8');
?>
<style>
    .dental-ai-nav-link {
        background: linear-gradient(135deg, #0f766e, #049ebb) !important;
        color: #fff !important;
        border-radius: 999px !important;
        padding: 6px 14px !important;
        font-weight: 700 !important;
        font-size: 13px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 5px !important;
        transition: opacity .2s !important;
    }

    .dental-ai-nav-link:hover {
        opacity: .88 !important;
        color: #fff !important;
    }

    .dental-ai-nav-link i {
        font-size: 13px;
    }
</style>
<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

        <a href="index.php" class="logo d-flex align-items-center me-auto me-xl-0">
            <!-- Uncomment the line below if you also wish to use an image logo -->
            <!-- <img src="assets/img/logo.webp" alt=""> -->
            <svg class="my-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g id="bgCarrier" stroke-width="0"></g>
                <g id="tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="iconCarrier">
                    <path d="M22 22L2 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                    <path d="M17 22V6C17 4.11438 17 3.17157 16.4142 2.58579C15.8284 2 14.8856 2 13 2H11C9.11438 2 8.17157 2 7.58579 2.58579C7 3.17157 7 4.11438 7 6V22" stroke="currentColor" stroke-width="1.5"></path>
                    <path opacity="0.5" d="M21 22V8.5C21 7.09554 21 6.39331 20.6629 5.88886C20.517 5.67048 20.3295 5.48298 20.1111 5.33706C19.6067 5 18.9045 5 17.5 5" stroke="currentColor" stroke-width="1.5"></path>
                    <path opacity="0.5" d="M3 22V8.5C3 7.09554 3 6.39331 3.33706 5.88886C3.48298 5.67048 3.67048 5.48298 3.88886 5.33706C4.39331 5 5.09554 5 6.5 5" stroke="currentColor" stroke-width="1.5"></path>
                    <path d="M12 22V19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                    <path opacity="0.5" d="M10 12H14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                    <path opacity="0.5" d="M5.5 11H7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                    <path opacity="0.5" d="M5.5 14H7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                    <path opacity="0.5" d="M17 11H18.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                    <path opacity="0.5" d="M17 14H18.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                    <path opacity="0.5" d="M5.5 8H7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                    <path opacity="0.5" d="M17 8H18.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                    <path opacity="0.5" d="M10 15H14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                    <path d="M12 9V5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M14 7L10 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </g>
            </svg>

            <h1 class="sitename">MediTrust</h1>
        </a>

        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="index.php" class="active">Trang chủ</a></li>
                <li><a href="index.php?page=about">Giới thiệu</a></li>
                <li><a href="index.php?page=department">Phòng khám</a></li>
                <li><a href="index.php?page=services">Dịch vụ</a></li>
                <li><a href="index.php?page=doctors">Bác sĩ</a></li>
                <li><a href="index.php?page=contact">Liên hệ</a></li>
                <li>
                    <a href="index.php?page=dental-ai" class="dental-ai-nav-link">
                        <i class="bi bi-cpu-fill"></i> Phân tích răng AI
                    </a>
                </li>
                <?php if (!empty($patientSession)) : ?>
                    <li class="dropdown user-menu-item">
                        <a href="index.php?page=profile" class="user-menu-trigger">
                            <span class="user-avatar-badge"><?= htmlspecialchars($patientInitial) ?></span>
                            <span class="user-name"><?= htmlspecialchars($patientName) ?></span>
                            <i class="bi bi-chevron-down toggle-dropdown"></i>
                        </a>
                        <ul>
                            <li><a href="index.php?page=profile"><i class="bi bi-person-circle"></i> Hồ sơ của tôi</a></li>
                            <li><a href="index.php?page=appointment"><i class="bi bi-calendar2-check"></i> Đặt lịch khám</a></li>
                            <li><a href="index.php?page=logout"><i class="bi bi-box-arrow-right"></i> Đăng xuất</a></li>
                        </ul>
                    </li>
                <?php else : ?>
                    <li class="auth-link"><a href="index.php?page=login">Đăng nhập</a></li>
                <?php endif; ?>
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        <a class="btn-getstarted" href="index.php?page=appointment">Đặt Lịch Ngay</a>

    </div>
</header>