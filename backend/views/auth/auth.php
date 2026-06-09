<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$action = $_GET['admin'] ?? 'dashBoard';


if (in_array($action, ['loginSystem', 'doLogin', 'accessDenied',])) {
    return;
}

if (!isset($_SESSION['admin'])) {
    header("Location: admin.php?admin=loginSystem");
    exit;
}

$currentIP = $_SERVER['REMOTE_ADDR'] ?? '';

if (!isset($_SESSION['admin_ip'])) {
    $_SESSION['admin_ip'] = $currentIP;
}

if ($_SESSION['admin_ip'] !== $currentIP) {
    session_destroy();
    header("Location: admin.php?admin=loginSystem&msg=ip_changed");
    exit;
}

// ===== PHÂN QUYỀN =====
$role = $_SESSION['role'] ?? '';


$permissions = [
    'admin' => ['*'],
    'bac_si' => [
        '',
        'listLichKham',
        'profile',
        'formKham',
        'listXray',
        'formUploadXray',
        'luuXray',
        'viewXray',
        'xrayBenhNhan',
        'luuKetQuaXray',
        'getXrayByLichKham',
        'reAnalyzeXray',
    ],
    'le_tan' => [
        'dashBoard',
        'profile',
        '',
        ''
    ],
];


if (!isset($permissions[$role])) {
    die("Role không hợp lệ");
}

if ($permissions[$role][0] !== '*') {
    if (!in_array($action, $permissions[$role])) {
        header("Location:admin.php?admin=accessDenied");
    }
}
