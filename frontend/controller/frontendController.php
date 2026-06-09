<?php

class frontendController
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

    /* ====================== PAGES ====================== */

    public function index()
    {
        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/index.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    public function contact()
    {
        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/contact.php';
        require_once __DIR__ . '/../views/footer.php';
    }
    public function themYeuCauLichHen()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ngayMongMuon = $_POST['date'] ?? null;
            if (!empty($ngayMongMuon)) {
                $ngayMongMuon .= ' 00:00:00';
            }
            $name = trim($_POST['name'] ?? '');
            $phone = trim($_POST['so_dien_thoai'] ?? '');
            $date = $_POST['date'] ?? '';
            // Check tên
            if (!preg_match("/^[\p{L}\s]{2,100}$/u", $name)) {
                $_SESSION['error'] = "Tên không hợp lệ";
                header('Location: index.php?page=appointment');
                exit;
            }

            // Check số điện thoại
            if (!preg_match('/^0[0-9]{8,10}$/', $phone)) {
                $_SESSION['error'] = "Số điện thoại không hợp lệ";
                header('Location: index.php?page=appointment');
                exit;
            }

            // Check ngày
            if (empty($date) || strtotime($date) < strtotime(date('Y-m-d'))) {
                $_SESSION['error'] = "Không thể đặt lịch trong quá khứ";
                header('Location: index.php?page=appointment');
                exit;
            }
            $data = [
                'ho_ten' => $_POST['name'],
                'so_dien_thoai' => $_POST['so_dien_thoai'],
                'dich_vu_id' => $_POST['dich_vu_id'],
                'bac_si_id' => $_POST['doctor_id'],
                'ngay_mong_muon' => $_POST['date'],
                'gio_bat_dau' => $_POST['exam_time'],
                'gio_ket_thuc' => date("H:i:s", strtotime($_POST['exam_time'] . " +1 hour")),
                'mo_ta_trieu_chung' => $_POST['message'] ?? ''
            ];

            if ($this->clinic->themYeuCauLichHen($data)) {
                $_SESSION['success'] = 'Yêu cầu đặt lịch khám của bạn đã được gửi thành công!';
            } else {
                $_SESSION['error'] = 'Đặt lịch thất bại, vui lòng thử lại!';
            }

            header('Location: index.php?page=appointment');
            exit;
        }
    }
    public function appointment()
    {
        $danhSachDichVu = $this->clinic->layDanhSachDichVu();
        $danhSachBacSi = $this->clinic->layDanhSachBacSi();
        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/appointment.php';
        require_once __DIR__ . '/../views/footer.php';
    }
    // Lấy giờ đã đặt
    public function getAvailableTime()
    {
        header('Content-Type: application/json');

        $bac_si_id = $_POST['bac_si_id'] ?? null;
        $ngay = $_POST['ngay'] ?? null;

        if (!$bac_si_id || !$ngay) {
            echo json_encode([]);
            exit;
        }

        // Lấy lịch đã đặt (PHẢI trả về gio_bat_dau + gio_ket_thuc)
        $bookedList = $this->clinic->layGioDaDat($bac_si_id, $ngay);

        $allTime = [
            "07:00:00",
            "08:00:00",
            "09:00:00",
            "10:00:00",
            "11:00:00",
            "13:00:00",
            "14:00:00",
            "15:00:00",
            "16:00:00",
            "17:00:00"
        ];

        foreach ($bookedList as $lich) {

            $start = strtotime($lich['gio_bat_dau']);
            $end = strtotime($lich['gio_ket_thuc']);

            foreach ($allTime as $key => $slot) {

                $slotTime = strtotime($slot);

                if ($slotTime >= $start && $slotTime < $end) {
                    unset($allTime[$key]);
                }
            }
        }

        echo json_encode(array_values($allTime));
        exit;
    }


    public function about()
    {
        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/about.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    public function doctors()
    {
        $listBacSi = $this->clinic->layDanhSachBacSi();
        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/doctors.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    public function department()
    {
        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/departments.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    public function departmentDetail()
    {
        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/department-details.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    public function services()
    {
        $listServices = $this->clinic->layDanhSachDichVu();
        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/services.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    public function serviceDetails($id)
    {
        $servicesDetail = $this->clinic->layDichVuTheoID($id);
        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/service-details.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $tai_khoan = trim($_POST['account'] ?? '');
            $mat_khau = $_POST['mat_khau'] ?? '';

            if (empty($tai_khoan) || empty($mat_khau)) {
                $_SESSION['auth_error'] = "Vui lòng nhập đầy đủ thông tin.";
                header("Location: index.php?page=login");
                exit;
            }

            // Tìm theo SĐT hoặc Email
            $account = $this->clinic->findByPhoneOrEmail($tai_khoan);

            if (!$account) {
                $_SESSION['auth_error'] = "Tài khoản không tồn tại.";
                header("Location: index.php?page=login");
                exit;
            }

            // Chưa kích hoạt
            if ((int) $account['trang_thai'] === 0) {
                $_SESSION['auth_error'] = "Tài khoản chưa kích hoạt.";
                header("Location: index.php?page=first_login");
                exit;
            }

            // Sai mật khẩu
            if (!password_verify($mat_khau, $account['mat_khau'])) {
                $_SESSION['auth_error'] = "Sai mật khẩu.";
                header("Location: index.php?page=login");
                exit;
            }

            session_regenerate_id(true);

            $_SESSION['patient'] = [
                'id' => $account['id'],
                'email' => $account['email'],
                'so_dien_thoai' => $account['so_dien_thoai']
            ];

            header("Location: index.php?page=profile");
            exit;
        }

        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function register()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        $mode = $_GET['mode'] ?? 'view';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ho_ten = trim($_POST['ho_ten'] ?? '');
            $so_dien_thoai = trim($_POST['so_dien_thoai'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $gioi_tinh = trim($_POST['gioi_tinh'] ?? '');
            $ngay_sinh = trim($_POST['ngay_sinh'] ?? '');
            $dia_chi = trim($_POST['dia_chi'] ?? '');
            $mat_khau = $_POST['mat_khau'] ?? '';
            $mat_khau_nhap_lai = $_POST['mat_khau_nhap_lai'] ?? '';

            if ($ho_ten === '' || $so_dien_thoai === '' || $mat_khau === '' || $mat_khau_nhap_lai === '') {
                $_SESSION['auth_error'] = "Vui lòng nhập đầy đủ thông tin bắt buộc.";
                header("Location: index.php?page=register");
                exit;
            }

            if (!preg_match('/^0\\d{9}$/', $so_dien_thoai)) {
                $_SESSION['auth_error'] = "Số điện thoại phải đúng 10 số.";
                header("Location: index.php?page=register");
                exit;
            }

            if (strlen($mat_khau) < 6) {
                $_SESSION['auth_error'] = "Mật khẩu phải từ 6 ký tự.";
                header("Location: index.php?page=register");
                exit;
            }

            if ($mat_khau !== $mat_khau_nhap_lai) {
                $_SESSION['auth_error'] = "Xác nhận mật khẩu không khớp.";
                header("Location: index.php?page=register");
                exit;
            }

            if ($this->clinic->checkPhoneExistsInAccount($so_dien_thoai)) {
                $_SESSION['auth_error'] = "Số điện thoại đã có tài khoản.";
                header("Location: index.php?page=register");
                exit;
            }

            $hoSo = $this->clinic->findHoSoByPhone($so_dien_thoai);

            if ($hoSo && $this->clinic->checkHoSoHasAccount($hoSo['id'])) {
                $_SESSION['auth_error'] = "Hồ sơ này đã có tài khoản.";
                header("Location: index.php?page=register");
                exit;
            }

            if (!$hoSo) {
                $newId = $this->clinic->generateHoSoBenhNhanId();
                $insertOk = $this->clinic->insertHoSoBenhNhan([
                    $newId,
                    $ho_ten,
                    $so_dien_thoai,
                    $email,
                    $gioi_tinh,
                    $ngay_sinh,
                    $dia_chi,
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    1
                ]);

                if (!$insertOk) {
                    $_SESSION['auth_error'] = "Không thể tạo hồ sơ bệnh nhân.";
                    header("Location: index.php?page=register");
                    exit;
                }

                $hoSo = ['id' => $newId];
            }

            $hash = password_hash($mat_khau, PASSWORD_BCRYPT);
            $this->clinic->insertPatientAccount([
                $hoSo['id'],
                $so_dien_thoai,
                $hash,
                1
            ]);

            $_SESSION['auth_success'] = "Đăng ký thành công. Vui lòng đăng nhập.";
            header("Location: index.php?page=login");
            exit;
        }

        require_once __DIR__ . '/../views/auth/register.php';
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        unset($_SESSION['patient']);
        header("Location: index.php");
        exit;
    }

    public function profile()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        if (empty($_SESSION['patient'])) {
            header("Location: index.php?page=login");
            exit;
        }

        $userId = $_SESSION['patient']['id'];

        // ================= POST (CẬP NHẬT) =================
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $ho_ten = trim($_POST['ho_ten'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $gioi_tinh = trim($_POST['gioi_tinh'] ?? '');
            $ngay_sinh = trim($_POST['ngay_sinh'] ?? '');
            $dia_chi = trim($_POST['dia_chi'] ?? '');
            $tien_su_benh = trim($_POST['tien_su_benh'] ?? '');
            $cmnd_cccd = trim($_POST['cmnd_cccd'] ?? '');
            $bao_hiem_y_te = trim($_POST['bao_hiem_y_te'] ?? '');
            $nguoi_lien_he_khan_cap = trim($_POST['nguoi_lien_he_khan_cap'] ?? '');
            $quan_he = trim($_POST['quan_he'] ?? '');
            $sdt_nguoi_lien_he = trim($_POST['sdt_nguoi_lien_he'] ?? '');

            if ($ho_ten === '') {
                $_SESSION['profile_error'] = "Họ và tên không được để trống.";
                header("Location: index.php?page=profile&mode=edit");
                exit;
            }

            if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['profile_error'] = "Email không hợp lệ.";
                header("Location: index.php?page=profile&mode=edit");
                exit;
            }

            $ok = $this->clinic->updateHoSoBenhNhanByUserId($userId, [
                $ho_ten,
                $gioi_tinh,
                $ngay_sinh,
                $dia_chi,
                $tien_su_benh,
                $cmnd_cccd,
                $bao_hiem_y_te,
                $nguoi_lien_he_khan_cap,
                $quan_he,
                $sdt_nguoi_lien_he,
                $email
            ]);

            if ($ok) {
                $_SESSION['patient']['email'] = $email;
                $_SESSION['profile_success'] = "Cập nhật hồ sơ thành công.";
            } else {
                $_SESSION['profile_error'] = "Không thể cập nhật hồ sơ.";
                header("Location: index.php?page=profile&mode=edit");
                exit;
            }

            header("Location: index.php?page=profile");
            exit;
        }

        // ================= GET (HIỂN THỊ) =================
        $userId = $_SESSION['patient']['id'];
        $hoSo = $this->clinic->getHoSoFullByUserId($userId);
        $lichSuKham = $this->clinic->getLichSuKhamByHoSoId($hoSo['id']);

        if ($hoSo) {
            $_SESSION['patient']['ho_ten'] = $hoSo['ho_ten'] ?? '';
        }
        require_once __DIR__ . '/../views/auth/profile.php';
    }

    public function errorsPage()
    {
        require_once __DIR__ . '/../views/404.php';
    }

    /* ====================== DENTAL AI PAGE ====================== */

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

        $input     = json_decode(file_get_contents('php://input'), true);
        $imageData = trim($input['image'] ?? '');

        if (empty($imageData)) {
            echo json_encode(['error' => 'Không có dữ liệu ảnh']); exit;
        }
        if (!preg_match('/^[a-zA-Z0-9+\/=]+$/', $imageData)) {
            echo json_encode(['error' => 'Dữ liệu ảnh không hợp lệ']); exit;
        }

        $prompt = 'Bạn là chuyên gia phân tích hình ảnh nha khoa. Phân tích ảnh răng này và trả về JSON thuần (không markdown, không ```):
{
  "health_score": <số nguyên 0-100>,
  "overall_status": "<green|amber|red>",
  "status_title": "<tiêu đề ngắn>",
  "status_description": "<1-2 câu tổng quan>",
  "issues": [{"name":"<tên>","severity":"<green|amber|red>","description":"<ngắn>"}],
  "recommendations": [{"service":"<tên dịch vụ>","icon":"<1 emoji>","priority":"<urgent|soon|routine>","reason":"<lý do>","cost_range":"<VNĐ>"}],
  "general_advice": "<1-2 câu lời khuyên>"
}
Quy tắc: urgent=1-2 tuần; soon=1-3 tháng; routine=định kỳ.
Nếu không phải ảnh răng: {"error":"<lý do>"}.
Trả lời hoàn toàn bằng tiếng Việt (trừ key JSON).';

        $payload = [
            'model'       => 'meta-llama/llama-4-scout-17b-16e-instruct',
            'messages'    => [[
                'role'    => 'user',
                'content' => [
                    ['type' => 'image_url', 'image_url' => ['url' => 'data:image/jpeg;base64,' . $imageData]],
                    ['type' => 'text',      'text'      => $prompt],
                ],
            ]],
            'max_tokens'  => 1000,
            'temperature' => 0.25,
        ];

        $ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_TIMEOUT        => 30,
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

    /* ====================== AI CHAT ====================== */

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

    /* ====================== AI DATA ====================== */

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

   /* ====================== AI APPOINTMENT ====================== */

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
    //  <!-- Coder Vi Đức Được    FB/duocXdangiu    Zalo/0389232813    Github/coderDuoc --> 
    // form đổi mật khẩu
    public function first_login()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['step'] = $_SESSION['step'] ?? 1;

        require_once __DIR__ . '/../views/auth/change_password.php';
    }
    public function changePassword()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['patient'])) {
            header("Location: index.php?page=login");
            exit;
        }

        $accountId = $_SESSION['patient']['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $mat_khau_cu = $_POST['mat_khau_cu'] ?? '';
            $mat_khau_moi = $_POST['mat_khau_moi'] ?? '';
            $xac_nhan = $_POST['xac_nhan_mat_khau'] ?? '';

            if ($mat_khau_moi === '' || $xac_nhan === '') {
                $_SESSION['auth_error'] = "Vui lòng nhập đầy đủ thông tin.";
                header("Location: index.php?page=change_password");
                exit;
            }

            if ($mat_khau_moi !== $xac_nhan) {
                $_SESSION['auth_error'] = "Mật khẩu xác nhận không khớp.";
                header("Location: index.php?page=change_password");
                exit;
            }

            // Lấy account theo id
            $account = $this->clinic->findPatientAccountById($accountId);

            if (!$account) {
                $_SESSION['auth_error'] = "Không tìm thấy tài khoản.";
                header("Location: index.php?page=change_password");
                exit;
            }

            // Kiểm tra mật khẩu cũ
            if (!password_verify($mat_khau_cu, $account['mat_khau'])) {
                $_SESSION['auth_error'] = "Mật khẩu cũ không đúng.";
                header("Location: index.php?page=change_password");
                exit;
            }

            // Hash mật khẩu mới
            $hash = password_hash($mat_khau_moi, PASSWORD_BCRYPT);

            $this->clinic->updatePasswordByEmail($accountId, $hash);

            $_SESSION['auth_success'] = "Đổi mật khẩu thành công.";
            header("Location: index.php?page=profile");
            exit;
        }

        require_once __DIR__ . '/../views/auth/change_password.php';
    }
    public function sendLoginOTP()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $email = $_POST['email'] ?? '';

        $user = $this->clinic->findByEmail($email);

        if (!$user) {
            $_SESSION['auth_error'] = "Email không tồn tại";
            header("Location: index.php?page=first_login");
            exit;
        }

        // 🔥 THÊM ĐOẠN NÀY
        if ($user['trang_thai'] == 1) {
            $_SESSION['auth_error'] = "Tài khoản này đã kích hoạt rồi.";
            header("Location: index.php?page=login");
            exit;
        }

        $otp = rand(100000, 999999);

        $this->clinic->saveOTP($email, $otp);

        require_once 'frontend/helpers/MailHelper.php';

        if (MailHelper::sendOTP($email, $otp)) {

            $_SESSION['otp_email'] = $email;
            $_SESSION['step'] = 2; // 🔥 chuyển sang nhập OTP

            header("Location: index.php?page=first_login");
            exit;

        } else {

            $_SESSION['auth_error'] = "Không gửi được email OTP";
            header("Location: index.php?page=first_login");
            exit;
        }
    }
    public function verifyLoginOTP()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $otp_input = $_POST['otp'] ?? '';
        $email = $_SESSION['otp_email'] ?? '';

        if (!$email) {
            $_SESSION['auth_error'] = "Phiên làm việc không hợp lệ.";
            header("Location: index.php?page=first_login");
            exit;
        }

        $result = $this->clinic->verifyOTP($email, $otp_input);

        if ($result === "expired") {
            $_SESSION['auth_error'] = "OTP đã hết hạn.";
            $_SESSION['step'] = 1;
            header("Location: index.php?page=first_login");
            exit;
        }

        if ($result === false) {
            $_SESSION['auth_error'] = "OTP không đúng.";
            $_SESSION['step'] = 2;
            header("Location: index.php?page=first_login");
            exit;
        }

        // Thành công
        $this->clinic->clearOTP($email); // 🔥 xóa OTP sau khi dùng

        $_SESSION['step'] = 3;

        header("Location: index.php?page=first_login");
        exit;
    }
    public function update_first_password()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $email = $_SESSION['otp_email'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if ($new_password === '' || $confirm_password === '') {
            $_SESSION['auth_error'] = "Vui lòng nhập đầy đủ thông tin.";
            $_SESSION['step'] = 3;
            header("Location: index.php?page=first_login");
            exit;
        }

        if ($new_password !== $confirm_password) {
            $_SESSION['auth_error'] = "Mật khẩu xác nhận không khớp.";
            $_SESSION['step'] = 3;
            header("Location: index.php?page=first_login");
            exit;
        }

        $hash = password_hash($new_password, PASSWORD_BCRYPT);

        // Update mật khẩu
        $this->clinic->updatePasswordByEmail($email, $hash);

        // 🔥 KÍCH HOẠT TÀI KHOẢN
        $this->clinic->activateAccount($email);

        // Xóa OTP
        $this->clinic->clearOTP($email);

        unset($_SESSION['otp_email']);
        unset($_SESSION['step']);

        $_SESSION['auth_success'] = "Kích hoạt tài khoản thành công. Vui lòng đăng nhập.";

        header("Location: index.php?page=login");
        exit;
    }
    // END <!-- Coder Vi Đức Được    FB/duocXdangiu    Zalo/0389232813    Github/coderDuoc --> 
    public function guiLienHe()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $ten = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $tieuDe = $_POST['subject'] ?? '';
            $noiDung = $_POST['message'] ?? '';

            if (!$ten || !$email || !$noiDung) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Thiếu dữ liệu'
                ]);
                exit;
            }

            $this->clinic->luuTinNhan($ten, $email, $tieuDe, $noiDung);

            echo json_encode([
                'status' => 'success',
                'message' => 'Gửi tin nhắn thành công!'
            ]);
            exit;
        }
    }
}
