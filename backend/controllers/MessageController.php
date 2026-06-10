<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MessageController
{
    private $clinic;
    public function __construct()
    {
        require_once __DIR__ . '/../models/db.php';
        $this->clinic = new modelClinic();
        $this->clinic->ketNoiDB();
    }

    public function tatCaTin()
    {
        $user_id = $_SESSION['admin']['id'];

        $danhSachTin = $this->clinic->layTatCaTin($user_id);

        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        include "backend/views/allMessages.php";
        require_once "backend/views/fileJS.php";
    }

    public function chiTietTin()
    {
        $id = $_GET['id'] ?? 0;
        $user_id = $_SESSION['admin']['id'];

        if (!$id) {
            header("Location: admin.php?admin=tatCaTin");
            exit;
        }

        $tin = $this->clinic->layChiTietTin($id);

        if (!$tin) {
            header("Location: admin.php?admin=tatCaTin");
            exit;
        }

        $this->clinic->danhDauDaDoc($id, $user_id);
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        include "backend/views/chiTietTin.php";
        require_once "backend/views/fileJS.php";
    }

    public function hienFormTraLoi()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            die("Không tìm thấy tin nhắn");
        }

        $tin = $this->clinic->layChiTietTin($id);

        if (!$tin) {
            die("Tin không tồn tại");
        }

        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/guiTraLoiTinNhan.php";
        require_once "backend/views/fileJS.php";
    }

    public function xuLyGuiTraLoi()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            exit("Sai phương thức");
        }

        require_once __DIR__ . '/../../libs/vendor/autoload.php';

        $id = $_POST['id'] ?? null;
        $noi_dung = $_POST['noi_dung'] ?? null;

        if (!$id || !$noi_dung) {
            exit("Thiếu dữ liệu");
        }

        $tin = $this->clinic->layChiTietTin($id);

        if (!$tin)
            exit("Tin không tồn tại");

        $mail = new PHPMailer(true);

        try {

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'tenmienfree26@gmail.com';
            $mail->Password = 'dtnzvyakgmauszqs';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('tenmienfree26@gmail.com', 'MediTrust Admin');
            $mail->addAddress($tin['email_nguoi_gui']);
            $mail->SMTPDebug = 0;
            $mail->Debugoutput = 'html';

            $fileName = null;

            if (!empty($_FILES['file']['name'])) {

                $uploadDir = "uploads/";

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileName = time() . "_" . $_FILES['file']['name'];

                move_uploaded_file(
                    $_FILES['file']['tmp_name'],
                    $uploadDir . $fileName
                );

                $mail->addAttachment($uploadDir . $fileName);
            }

            $noiDungHTML = "
<div style='font-family:Segoe UI,Arial,sans-serif;background:#f4f6fb;padding:30px'>

    <div style='max-width:600px;margin:auto;background:#ffffff;
                border-radius:12px;overflow:hidden;
                box-shadow:0 4px 20px rgba(0,0,0,0.08)'>

        <div style='background:#4e73df;color:#fff;padding:20px 24px'>
            <h2 style='margin:0;font-size:20px'>📩 Phản hồi từ MediTrust</h2>
        </div>

        <div style='padding:24px;color:#333;line-height:1.6;font-size:15px'>
            <p>Xin chào,</p>

            <div style='background:#f8f9fc;
                        border-left:4px solid #4e73df;
                        padding:16px;
                        border-radius:6px;
                        margin:16px 0'>
                " . nl2br(htmlspecialchars($noi_dung)) . "
            </div>

            <p style='margin-top:24px'>
                Nếu bạn cần hỗ trợ thêm, vui lòng phản hồi lại email này.
            </p>

            <p style='margin-top:30px'>
                Trân trọng,<br>
                <b>Đội ngũ MediTrust</b>
            </p>
        </div>

        <div style='background:#f1f3f9;
                    padding:14px 24px;
                    font-size:12px;
                    color:#888;
                    text-align:center'>
            © " . date('Y') . " MediTrust. All rights reserved.
        </div>

    </div>
</div>
";

            $mail->isHTML(true);
            $mail->Subject = "Phản hồi: " . $tin['tieu_de'];
            $mail->Body = $noiDungHTML;
            $mail->AltBody = strip_tags($noi_dung);
            $mail->send();

            $this->clinic->luuPhanHoi($id, $noi_dung, $fileName);

            header("Location: admin.php?admin=tatCaTin");
            exit;
        } catch (Exception $e) {
            echo "Lỗi gửi mail: {$mail->ErrorInfo}";
        }
    }
}
