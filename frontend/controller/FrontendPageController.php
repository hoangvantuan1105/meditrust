<?php

class FrontendPageController
{
    private $clinic;

    public function __construct()
    {
        require_once __DIR__ . '/../model/frontend-db.php';
        $this->clinic = new frontendDB();
        $this->clinic->ketNoiDB();
    }

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

    public function errorsPage()
    {
        require_once __DIR__ . '/../views/404.php';
    }

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
