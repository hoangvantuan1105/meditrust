<?php

class FrontendAiController
{
    private $clinic;
    private $groqApiKey;

    public function __construct()
    {
        require_once __DIR__ . '/../model/frontend-db.php';
        require_once __DIR__ . '/../../config/ai.php';

        $this->clinic = new frontendDB();
        $this->clinic->ketNoiDB();

        $this->groqApiKey = getenv('GROQ_API_KEY')
            ?: (defined('GROQ_API_KEY') ? GROQ_API_KEY : '');
    }

    public function dentalAi()
    {
        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/dental-ai.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    public function dentalAnalyze()
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Phương thức không hợp lệ']); exit;
        }
        if ($this->groqApiKey === '') {
            echo json_encode(['error' => 'Hệ thống chưa cấu hình AI. Liên hệ quản trị viên.']); exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);

        // Hỗ trợ cả single image lẫn images[]
        $images = [];
        if (!empty($input['images']) && is_array($input['images'])) {
            $images = array_values(array_slice($input['images'], 0, 4));
        } elseif (!empty($input['image'])) {
            $images = [$input['image']];
        }

        if (empty($images)) {
            echo json_encode(['error' => 'Không có dữ liệu ảnh']); exit;
        }

        foreach ($images as $img) {
            if (!preg_match('/^[a-zA-Z0-9+\/=]+$/', trim($img))) {
                echo json_encode(['error' => 'Dữ liệu ảnh không hợp lệ']); exit;
            }
        }

        // Load dịch vụ thực tế từ DB
        $dbServices  = $this->clinic->layDanhSachDichVu();
        $serviceLines = [];
        foreach ($dbServices as $s) {
            $id   = $s['id'] ?? '';
            $name = $s['ten_dich_vu'] ?? $s['ten'] ?? '';
            $gia  = $s['gia'] ?? '';
            $serviceLines[] = "  {\"service_id\": $id, \"service\": \"$name\", \"gia\": \"$gia\"}";
        }
        $serviceJson = "[\n" . implode(",\n", $serviceLines) . "\n]";
        $imageCount  = count($images);

        $prompt = "Bạn là chuyên gia phân tích hình ảnh nha khoa. Bạn được cung cấp $imageCount ảnh răng (có thể là các góc khác nhau hoặc khung từ video). Hãy phân tích tổng hợp TẤT CẢ ảnh và trả về JSON thuần (không markdown, không ```):
{
  \"health_score\": <số nguyên 0-100>,
  \"overall_status\": \"<green|amber|red>\",
  \"status_title\": \"<tiêu đề ngắn>\",
  \"status_description\": \"<1-2 câu tổng quan>\",
  \"issues\": [{\"name\":\"<tên>\",\"severity\":\"<green|amber|red>\",\"description\":\"<ngắn>\"}],
  \"recommendations\": [{\"service_id\":<id>, \"service\":\"<tên>\",\"icon\":\"<1 emoji>\",\"priority\":\"<urgent|soon|routine>\",\"reason\":\"<lý do>\",\"cost_range\":\"<VNĐ>\"}],
  \"general_advice\": \"<1-2 câu lời khuyên>\"
}

DANH SÁCH DỊCH VỤ THỰC TẾ CỦA PHÒNG KHÁM (BẮT BUỘC chỉ dùng các dịch vụ này, dùng đúng service_id):
$serviceJson

Quy tắc:
- urgent = cần điều trị trong 1-2 tuần
- soon   = nên điều trị trong 1-3 tháng
- routine = kiểm tra định kỳ
- recommendations tối đa 4 dịch vụ, ưu tiên theo mức độ nghiêm trọng
- Nếu không có ảnh răng nào: {\"error\":\"<lý do>\"}
- Trả lời hoàn toàn bằng tiếng Việt (trừ key JSON và service_id)";

        // Build content với tất cả ảnh
        $contentParts = [];
        foreach ($images as $img) {
            $contentParts[] = ['type' => 'image_url', 'image_url' => ['url' => 'data:image/jpeg;base64,' . trim($img)]];
        }
        $contentParts[] = ['type' => 'text', 'text' => $prompt];

        $payload = [
            'model'       => 'meta-llama/llama-4-scout-17b-16e-instruct',
            'messages'    => [['role' => 'user', 'content' => $contentParts]],
            'max_tokens'  => 1200,
            'temperature' => 0.25,
        ];

        $ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_TIMEOUT        => 45,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                "Authorization: Bearer {$this->groqApiKey}",
            ],
            CURLOPT_POSTFIELDS => json_encode($payload),
        ]);

        $res      = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (!$res || $httpCode !== 200) {
            $err = $httpCode === 401 ? 'API key không hợp lệ' :
                  ($httpCode === 429 ? 'Quá nhiều yêu cầu, thử lại sau' : "Lỗi kết nối AI (HTTP $httpCode)");
            echo json_encode(['error' => $err]); exit;
        }

        $result  = json_decode($res, true);
        $content = $result['choices'][0]['message']['content'] ?? '';

        preg_match('/\{[\s\S]*\}/', $content, $matches);
        if (empty($matches[0])) {
            echo json_encode(['error' => 'Phản hồi AI không hợp lệ']); exit;
        }

        $analysis = json_decode($matches[0], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(['error' => 'Không thể đọc kết quả từ AI']); exit;
        }
        if (isset($analysis['error'])) {
            echo json_encode(['error' => $analysis['error']]); exit;
        }

        echo json_encode($analysis);
        exit;
    }

    public function chat()
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($this->groqApiKey === '') {
            echo json_encode(["reply" => "Hệ thống chưa cấu hình AI."]);
            exit;
        }

        $input = json_decode(file_get_contents("php://input"), true);
        $messages = $input['messages'] ?? [];

        if (!is_array($messages)) {
            echo json_encode(["reply" => "Dữ liệu không hợp lệ."]);
            exit;
        }

        $doctors = $this->clinic->layDanhSachBacSi();
        $services = $this->clinic->layDanhSachDichVu();
        $patientInfo = '';

        if (session_status() === PHP_SESSION_NONE)
            session_start();
        $sessionUserId = $_SESSION['patient']['id'] ?? null;
        if (!empty($sessionUserId)) {
            $hoSo = $this->clinic->getHoSoFullByUserId($sessionUserId);
            if ($hoSo) {
                $patientInfo = "Thông tin bệnh nhân đăng nhập:\n" .
                    "- Họ tên: " . ($hoSo['ho_ten'] ?? '') . "\n" .
                    "- SĐT: " . ($hoSo['so_dien_thoai'] ?? '') . "\n" .
                    "- Email: " . ($hoSo['email'] ?? '') . "\n" .
                    "- Ngày sinh: " . ($hoSo['ngay_sinh'] ?? '') . "\n" .
                    "- Giới tính: " . ($hoSo['gioi_tinh'] ?? '') . "\n" .
                    "- Tiền sử bệnh: " . ($hoSo['tien_su_benh'] ?? '') . "\n" .
                    "- Ghi chú: " . ($hoSo['dia_chi'] ?? '');
            }
        }

        $systemPrompt = "Bạn là lễ tân AI của phòng khám nha khoa MediTrust.
- Tư vấn lịch sự, ngắn gọn
- Không chẩn đoán, không kê đơn
- Hướng dẫn đặt lịch nhanh (ngày, giờ, bác sĩ, lý do)
- Nếu đã có thông tin bệnh nhân (đang đăng nhập), bạn không cần hỏi lại họ tên và số điện thoại
- Khi người dùng cung cấp ngày và bác sĩ, đề xuất các khung giờ có sẵn

" . ($patientInfo !== '' ? $patientInfo . "\n\n" : "") . "

Danh sách bác sĩ:
" . $this->formatDoctorsForPrompt($doctors) . "

Danh sách dịch vụ:
" . $this->formatServicesForPrompt($services);

        $payload = [
            "model" => "llama-3.1-8b-instant",
            "messages" => array_merge(
                [["role" => "system", "content" => $systemPrompt]],
                $this->sanitizeMessages($messages)
            ),
            "temperature" => 0.4,
            "max_tokens" => 512
        ];

        $ch = curl_init("https://api.groq.com/openai/v1/chat/completions");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$this->groqApiKey}"
            ],
            CURLOPT_POSTFIELDS => json_encode($payload)
        ]);

        $res = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($res, true);
        $reply = $result['choices'][0]['message']['content'] ?? "AI đang bận.";

        echo json_encode(["reply" => $reply]);
        exit;
    }

    public function aiData()
    {
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode([
            "doctors" => $this->simplifyDoctors($this->clinic->layDanhSachBacSi()),
            "services" => $this->simplifyServices($this->clinic->layDanhSachDichVu())
        ]);
        exit;
    }

    public function aiUserData()
    {
        header('Content-Type: application/json; charset=utf-8');

        if (session_status() === PHP_SESSION_NONE)
            session_start();
        $sessionUserId = $_SESSION['patient']['id'] ?? null;
        if (empty($sessionUserId)) {
            echo json_encode(["logged_in" => false]);
            exit;
        }

        $hoSo = $this->clinic->getHoSoFullByUserId($sessionUserId);
        if (!$hoSo) {
            echo json_encode(["logged_in" => false]);
            exit;
        }

        echo json_encode([
            "logged_in" => true,
            "ho_so_benh_nhan_id" => $hoSo['id'] ?? '',
            "ho_ten" => $hoSo['ho_ten'] ?? '',
            "so_dien_thoai" => $hoSo['so_dien_thoai'] ?? '',
            "email" => $hoSo['email'] ?? '',
            "tien_su_benh" => $hoSo['tien_su_benh'] ?? ''
        ]);
        exit;
    }

    public function aiAppointment()
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(["ok" => false, "message" => "Method không hợp lệ"]);
            exit;
        }

        $input = json_decode(file_get_contents("php://input"), true);
        if (!is_array($input)) {
            echo json_encode(["ok" => false, "message" => "JSON không hợp lệ"]);
            exit;
        }

        $requestedDateTime = $this->normalizeAppointmentDateTime(
            trim($input['date'] ?? ''),
            trim($input['time'] ?? ($input['time_slot'] ?? ''))
        );
        $requestedTimestamp = $requestedDateTime !== '' ? strtotime($requestedDateTime) : false;

        $data = [
            'ho_ten' => trim($input['name'] ?? ''),
            'email' => trim($input['email'] ?? ''),
            'so_dien_thoai' => preg_replace('/\D+/', '', (string) ($input['phone'] ?? '')),
            'dich_vu_id' => $input['service_id'] ?? null,
            'bac_si_id' => $input['doctor_id'] ?? null,
            'ngay_mong_muon' => $requestedDateTime,
            'gio_bat_dau' => $requestedTimestamp ? date('H:i:s', $requestedTimestamp) : null,
            'gio_ket_thuc' => $requestedTimestamp ? date('H:i:s', strtotime('+1 hour', $requestedTimestamp)) : null,
            'mo_ta_trieu_chung' => trim($input['message'] ?? '')
        ];

        if (session_status() === PHP_SESSION_NONE)
            session_start();
        $sessionUserId = $_SESSION['patient']['id'] ?? null;
        if ((!$data['ho_ten'] || !$data['so_dien_thoai']) && !empty($sessionUserId)) {
            $hoSo = $this->clinic->getHoSoFullByUserId($sessionUserId);
            if ($hoSo) {
                $data['ho_ten'] = $data['ho_ten'] ?: ($hoSo['ho_ten'] ?? '');
                $data['so_dien_thoai'] = $data['so_dien_thoai'] ?: ($hoSo['so_dien_thoai'] ?? '');
                $data['email'] = $data['email'] ?: ($hoSo['email'] ?? '');
            }
        }

        if (
            $data['ho_ten'] === '' ||
            $data['so_dien_thoai'] === '' ||
            !$data['bac_si_id'] ||
            $data['ngay_mong_muon'] === ''
        ) {
            echo json_encode(["ok" => false, "message" => "Thiếu thông tin bắt buộc"]);
            exit;
        }

        if ($requestedTimestamp === false) {
            echo json_encode(["ok" => false, "message" => "Ngày giờ khám không hợp lệ."]);
            exit;
        }

        if (strtotime($data['ngay_mong_muon']) < time()) {
            echo json_encode(["ok" => false, "message" => "Ngày giờ khám phải lớn hơn thời điểm hiện tại."]);
            exit;
        }

        if (!$data['dich_vu_id']) {
            $defaultService = $this->clinic->layDichVuMacDinh();
            $data['dich_vu_id'] = $defaultService['id'] ?? null;
        }

        if (!$data['dich_vu_id']) {
            echo json_encode(["ok" => false, "message" => "Không tìm thấy dịch vụ mặc định để tạo lịch."]);
            exit;
        }

        if ($this->clinic->themYeuCauLichHen($data)) {
            echo json_encode(["ok" => true, "message" => "Đã gửi yêu cầu đặt lịch. Vui lòng chờ phòng khám xác nhận."]);
            exit;
        }

        echo json_encode(["ok" => false, "message" => "Không thể gửi yêu cầu"]);
        exit;
    }

    /* ====================== HELPERS ====================== */

    private function sanitizeMessages($messages)
    {
        $out = [];
        foreach ($messages as $m) {
            if (isset($m['role'], $m['content']) && in_array($m['role'], ['user', 'assistant'])) {
                $out[] = [
                    'role' => $m['role'],
                    'content' => mb_substr($m['content'], 0, 1500)
                ];
            }
        }
        return $out;
    }

    private function simplifyDoctors($doctors)
    {
        $res = [];
        foreach ($doctors as $d) {
            $photoUrl = $this->resolveDoctorAvatar($d);
            $res[] = [
                "id" => $d['id'] ?? null,
                "ten" => $d['ten_bac_si'] ?? $d['ho_ten'] ?? '',
                "chuyen_mon" => $d['chuyen_mon'] ?? '',
                "dich_vu_id" => $d['dich_vu_id'] ?? null,
                "photo_url" => $photoUrl,
                "avatar" => $photoUrl
            ];
        }
        return $res;
    }

    private function resolveDoctorAvatar($doctor)
    {
        $path = trim((string) ($doctor['photo_url'] ?? ''));
        if ($path === '') {
            return '';
        }

        $path = str_replace('\\', '/', $path);

        if (
            strpos($path, 'http://') === 0 ||
            strpos($path, 'https://') === 0 ||
            strpos($path, '//') === 0
        ) {
            return $path;
        }

        if (preg_match('~(?:^|/)(uploads/doctors/[^?#]+)$~i', $path, $m)) {
            return $m[1];
        }

        $path = preg_replace('~^(?:\./)+~', '', $path);
        return ltrim((string) $path, '/');
    }

    private function simplifyServices($services)
    {
        $res = [];
        foreach ($services as $s) {
            $res[] = [
                "id" => $s['id'] ?? null,
                "ten" => $s['ten_dich_vu'] ?? $s['ten'] ?? '',
                "gia" => $s['gia'] ?? ''
            ];
        }
        return $res;
    }

    private function formatDoctorsForPrompt($doctors)
    {
        $out = [];
        foreach ($this->simplifyDoctors($doctors) as $d) {
            $out[] = "- {$d['ten']} ({$d['chuyen_mon']})";
        }
        return $out ? implode("\n", $out) : "Chưa có bác sĩ.";
    }

    private function formatServicesForPrompt($services)
    {
        $out = [];
        foreach ($this->simplifyServices($services) as $s) {
            $out[] = "- {$s['ten']} | Giá: {$s['gia']}";
        }
        return $out ? implode("\n", $out) : "Chưa có dịch vụ.";
    }

    private function normalizeAppointmentDateTime($dateInput, $timeInput = '')
    {
        $dateInput = trim((string) $dateInput);
        $timeInput = trim((string) $timeInput);

        if ($dateInput === '') {
            return '';
        }

        if ($timeInput !== '') {
            $dateInput = preg_replace('/\s+/', ' ', $dateInput);
            if (!preg_match('/\d{2}:\d{2}(:\d{2})?$/', $dateInput)) {
                $dateInput .= ' ' . $timeInput;
            }
        }

        $dateInput = str_replace('T', ' ', $dateInput);
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateInput)) {
            $dateInput .= ' 09:00:00';
        } elseif (preg_match('/^\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}$/', $dateInput)) {
            $dateInput .= ':00';
        }

        $ts = strtotime($dateInput);
        if ($ts === false) {
            return '';
        }

        return date('Y-m-d H:i:s', $ts);
    }
}
