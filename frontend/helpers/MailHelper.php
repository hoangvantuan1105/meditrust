<?php

require_once __DIR__ . '/../../PHPMailer/src/Exception.php';
require_once __DIR__ . '/../../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailHelper
{
    public static function sendOTP($email, $otp)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'tenmienfree26@gmail.com';
            $mail->Password = 'dtnzvyakgmauszqs';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('tenmienfree26@gmail.com', 'MediTrust');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Ma OTP Dang Nhap';
            $mail->Body = "<h3>Ma OTP cua ban la: <b>$otp</b></h3>";

            $mail->send();
            return true;
        } catch (Exception $e) {
            echo "Loi gui mail: " . $mail->ErrorInfo;
            return false;
        }
    }
    public static function sendTaiKhamReminder($email, $tenBenhNhan, $ngayTaiKham, $tenBacSi, $tenDichVu, $ghiChu = '')
    {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'tenmienfree26@gmail.com';
            $mail->Password   = 'dtnzvyakgmauszqs';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom('tenmienfree26@gmail.com', 'MediTrust');
            $mail->addAddress($email, $tenBenhNhan);

            $mail->isHTML(true);
            $mail->Subject = 'Nhắc lịch tái khám - MediTrust';

            $ngayFormatted = date('d/m/Y', strtotime($ngayTaiKham));
            $ghiChuHtml    = $ghiChu ? "<p><b>Ghi chú:</b> " . htmlspecialchars($ghiChu) . "</p>" : '';

            $mail->Body = "
            <div style='font-family:Arial,sans-serif;line-height:1.7;color:#333;max-width:600px;margin:auto;'>
                <div style='background:#1a7fc1;padding:20px;border-radius:8px 8px 0 0;'>
                    <h2 style='color:#fff;margin:0;'>&#127973; MediTrust &ndash; Nh&#7855;c l&#7883;ch t&aacute;i kh&aacute;m</h2>
                </div>
                <div style='background:#f9f9f9;padding:24px;border:1px solid #e0e0e0;border-top:none;border-radius:0 0 8px 8px;'>
                    <p>Xin ch&agrave;o <b>" . htmlspecialchars($tenBenhNhan) . "</b>,</p>
                    <p>Ph&ograve;ng kh&aacute;m MediTrust xin nh&#7855;c nh&#7903; b&#7841;n v&#7873; l&#7883;ch t&aacute;i kh&aacute;m s&#7855;p t&#7899;i:</p>
                    <table style='width:100%;border-collapse:collapse;margin:16px 0;'>
                        <tr style='background:#e8f4fc;'>
                            <td style='padding:10px;border:1px solid #cce0ef;width:40%;'><b>Ng&agrave;y t&aacute;i kh&aacute;m</b></td>
                            <td style='padding:10px;border:1px solid #cce0ef;color:#1a7fc1;font-weight:bold;'>{$ngayFormatted}</td>
                        </tr>
                        <tr>
                            <td style='padding:10px;border:1px solid #cce0ef;'><b>B&aacute;c s&#297;</b></td>
                            <td style='padding:10px;border:1px solid #cce0ef;'>" . htmlspecialchars($tenBacSi) . "</td>
                        </tr>
                        <tr style='background:#f5f5f5;'>
                            <td style='padding:10px;border:1px solid #cce0ef;'><b>D&#7883;ch v&#7909;</b></td>
                            <td style='padding:10px;border:1px solid #cce0ef;'>" . htmlspecialchars($tenDichVu) . "</td>
                        </tr>
                    </table>
                    {$ghiChuHtml}
                    <p>Vui l&ograve;ng &#273;&#7871;n &#273;&uacute;ng gi&#7901;. N&#7871;u c&#7847;n thay &#273;&#7893;i l&#7883;ch, vui l&ograve;ng li&ecirc;n h&#7879; ph&ograve;ng kh&aacute;m.</p>
                    <hr style='border:none;border-top:1px solid #eee;margin:20px 0;'>
                    <p style='color:#888;font-size:13px;'>Email n&agrave;y &#273;&#432;&#7907;c g&#7917;i t&#7921; &#273;&#7897;ng t&#7915; h&#7879; th&#7889;ng MediTrust.</p>
                </div>
            </div>";

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function sendPasswordChangedNotice($email)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;

            $mail->Username = 'tenmienfree26@gmail.com';
            $mail->Password = 'dtnzvyakgmauszqs';

            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->CharSet = 'UTF-8';

            $mail->setFrom('tenmienfree26@gmail.com', 'MediTrust');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Thông báo đổi mật khẩu MediTrust';

            $mail->Body = "
            <div style='font-family: Arial, sans-serif; line-height: 1.6;'>
                <h2>Đổi mật khẩu thành công</h2>
                <p>Mật khẩu tài khoản MediTrust của bạn vừa được thay đổi thành công.</p>
                <p>Nếu chính bạn thực hiện thao tác này, bạn có thể bỏ qua email này.</p>
                <p>Nếu bạn không thực hiện thao tác này, vui lòng liên hệ phòng khám ngay để được hỗ trợ.</p>
                <hr>
                <p style='color:#777;'>Email này được gửi tự động từ hệ thống MediTrust.</p>
            </div>
        ";

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
