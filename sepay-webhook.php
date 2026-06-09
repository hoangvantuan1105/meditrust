<?php
// Endpoint nhận webhook từ SePay — không yêu cầu đăng nhập
require_once __DIR__ . '/config/sepay.php';
require_once __DIR__ . '/backend/models/db.php';

header('Content-Type: application/json');

// Xác thực API key nếu đã cấu hình
if (SEPAY_API_KEY !== '') {
    $auth = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    if ($auth !== 'Apikey ' . SEPAY_API_KEY) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }
}

$body = file_get_contents('php://input');
$data = json_decode($body, true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
    exit;
}

// Chỉ xử lý giao dịch tiền vào
if (($data['transferType'] ?? '') !== 'in') {
    echo json_encode(['success' => false, 'message' => 'Not an incoming transfer']);
    exit;
}

$content = strtoupper(preg_replace('/\s+/', '', $data['content'] ?? $data['description'] ?? ''));
$prefix  = strtoupper(SEPAY_PREFIX);

if (!preg_match('/' . preg_quote($prefix, '/') . '(\d+)/', $content, $matches)) {
    echo json_encode(['success' => false, 'message' => 'No matching invoice in content']);
    exit;
}

$hoa_don_id = (int) $matches[1];

$db = new modelClinic();
$db->ketNoiDB();

$hoa_don = $db->getHoaDonById($hoa_don_id);

if (!$hoa_don) {
    echo json_encode(['success' => false, 'message' => 'Invoice not found']);
    exit;
}

if ($hoa_don['trang_thai'] == 1) {
    echo json_encode(['success' => true, 'message' => 'Already paid']);
    exit;
}

$db->updateHoaDonStatus($hoa_don_id, 1);

echo json_encode([
    'success' => true,
    'message' => 'Invoice #' . $hoa_don_id . ' marked as paid',
    'amount'  => $data['transferAmount'] ?? 0,
]);