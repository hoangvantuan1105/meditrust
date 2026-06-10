<?php

class FrontendAppointmentController
{
    private $clinic;

    public function __construct()
    {
        require_once __DIR__ . '/../model/frontend-db.php';
        $this->clinic = new frontendDB();
        $this->clinic->ketNoiDB();
    }

    public function appointment()
    {
        $danhSachDichVu = $this->clinic->layDanhSachDichVu();
        $danhSachBacSi = $this->clinic->layDanhSachBacSi();
        require_once __DIR__ . '/../views/header.php';
        require_once __DIR__ . '/../views/appointment.php';
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

            if (!preg_match("/^[\p{L}\s]{2,100}$/u", $name)) {
                $_SESSION['error'] = "Tên không hợp lệ";
                header('Location: index.php?page=appointment');
                exit;
            }

            if (!preg_match('/^0[0-9]{8,10}$/', $phone)) {
                $_SESSION['error'] = "Số điện thoại không hợp lệ";
                header('Location: index.php?page=appointment');
                exit;
            }

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

    public function getAvailableTime()
    {
        header('Content-Type: application/json');

        $bac_si_id = $_POST['bac_si_id'] ?? null;
        $ngay = $_POST['ngay'] ?? null;

        if (!$bac_si_id || !$ngay) {
            echo json_encode([]);
            exit;
        }

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
}
