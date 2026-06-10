<?php
class frontendDB
{
    private $conn;
    public function ketNoiDB()
    {
        $host = "localhost";
        $db_name = "quan_ly_phong_kham";
        $username = "root";
        $password = "";

        try {
            $this->conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
        } catch (PDOException $e) {
            die("Kết nối thất bại: " . $e->getMessage());
        }
    }
    // <!-- Coder Vi Đức Được    FB/duocXdangiu    Zalo/0389232813    Github/coderDuoc --> 
    public function layDanhSachDichVu()
    {
        $query = "SELECT * 
              FROM dich_vu 
              WHERE trang_thai IS NULL
                 OR trang_thai IN ('Hoạt động', 'hoat_dong', 'active')
                 OR CAST(trang_thai AS CHAR) IN ('1', 'true', 'TRUE')
              ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function layDichVuMacDinh()
    {
        $sql = "SELECT id, ten_dich_vu
                FROM dich_vu
                WHERE trang_thai IS NULL
                   OR trang_thai IN ('Hoạt động', 'hoat_dong', 'active')
                   OR CAST(trang_thai AS CHAR) IN ('1', 'true', 'TRUE')
                ORDER BY
                    CASE
                        WHEN LOWER(ten_dich_vu) LIKE '%kham%' THEN 0
                        WHEN LOWER(ten_dich_vu) LIKE '%tu van%' THEN 1
                        ELSE 2
                    END,
                    id ASC
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function layDanhSachBacSi()
    {
        $query = "SELECT * 
              FROM bac_si 
              WHERE trang_thai IN (1, '1', 'dang_lam', 'hoat_dong', 'Hoạt động')
              ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function themYeuCauLichHen($data)
    {
        $sql = "INSERT INTO yeu_cau_dat_lich
    (ho_ten, so_dien_thoai, dich_vu_id, bac_si_id, ngay_mong_muon, gio_bat_dau, gio_ket_thuc, mo_ta_trieu_chung, trang_thai)
    VALUES (:ho_ten, :so_dien_thoai, :dich_vu_id, :bac_si_id, :ngay_mong_muon, :gio_bat_dau, :gio_ket_thuc, :mo_ta_trieu_chung, :trang_thai)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':ho_ten' => $data['ho_ten'],
            ':so_dien_thoai' => $data['so_dien_thoai'],
            ':dich_vu_id' => $data['dich_vu_id'],
            ':bac_si_id' => $data['bac_si_id'],
            ':ngay_mong_muon' => $data['ngay_mong_muon'],
            ':gio_bat_dau' => $data['gio_bat_dau'],
            ':gio_ket_thuc' => $data['gio_ket_thuc'],
            ':mo_ta_trieu_chung' => $data['mo_ta_trieu_chung'],
            ':trang_thai' => 'cho_xu_ly'
        ]);
    }

    // Lọc giừo
    public function layGioDaDat($bac_si_id, $ngay): array
    {
        $sql = "
        SELECT gio_bat_dau, gio_ket_thuc
        FROM lich_hen
        WHERE bac_si_id = ?
        AND ngay_hen = ?
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$bac_si_id, $ngay]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Đăng Nhập
    public function findPatientAccountById($id)
    {
        $sql = "SELECT * FROM tai_khoan_benh_nhan WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePasswordByEmail($email, $hash)
    {
        $sql = "UPDATE tai_khoan_benh_nhan SET mat_khau = ? WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$hash, $email]);
    }
    // 1️⃣ Tìm tài khoản theo email
    // ==============================
    public function findByEmail($email)
    {
        $sql = "SELECT * FROM tai_khoan_benh_nhan WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ==============================
    // 2️⃣ Lưu OTP (hash bảo mật)
    // ==============================
    public function saveOTP($email, $otp)
    {
        $hashedOTP = password_hash($otp, PASSWORD_DEFAULT);
        $expired = date("Y-m-d H:i:s", strtotime("+5 minutes"));

        $sql = "UPDATE tai_khoan_benh_nhan 
                SET otp = ?, otp_expired = ? 
                WHERE email = ?";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$hashedOTP, $expired, $email]);
    }

    // ==============================
    // 3️⃣ Verify OTP
    // ==============================
    public function verifyOTP($email, $otp_input)
    {
        $sql = "SELECT otp, otp_expired 
                FROM tai_khoan_benh_nhan 
                WHERE email = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return false;
        }

        // Kiểm tra hết hạn
        if (strtotime($user['otp_expired']) < time()) {
            return "expired";
        }

        // Kiểm tra OTP
        if (!password_verify($otp_input, $user['otp'])) {
            return false;
        }

        return true;
    }

    // ==============================
    // 4️⃣ Xóa OTP sau khi thành công
    // ==============================
    public function clearOTP($email)
    {
        $sql = "UPDATE tai_khoan_benh_nhan 
                SET otp = NULL, otp_expired = NULL 
                WHERE email = ?";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$email]);
    }
    public function activateAccount($email)
    {
        $sql = "UPDATE tai_khoan_benh_nhan 
            SET trang_thai = 1 
            WHERE email = ?";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$email]);
    }
    // End
    // END <!-- Coder Vi Đức Được    FB/duocXdangiu    Zalo/0389232813    Github/coderDuoc --> 

    // mạnh đẹp zai //

    public function layDichVuTheoID($id)
    {
        $stmt = $this->conn->prepare("select * from dich_vu where id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ====================== TÀI KHOẢN BỆNH NHÂN (FRONTEND) ======================

    public function generateHoSoBenhNhanId()
    {
        $sql = "SELECT id FROM ho_so_benh_nhan 
            WHERE id LIKE 'HSBN%' 
            ORDER BY id DESC 
            LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $lastId = $stmt->fetchColumn();

        if ($lastId) {
            $number = (int) substr($lastId, 4);
            $newNumber = $number + 1;
        } else {
            $newNumber = 1;
        }

        return 'HSBN' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    public function findHoSoByPhone($phone)
    {
        $sql = "SELECT * FROM ho_so_benh_nhan WHERE so_dien_thoai = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$phone]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // public function insertHoSoBenhNhan($data)
    // {
    //     $sql = "INSERT INTO ho_so_benh_nhan
    //     (id, ho_ten, so_dien_thoai, email, gioi_tinh, ngay_sinh, dia_chi,
    //      tien_su_benh, cmnd_cccd, bao_hiem_y_te, nguoi_lien_he_khan_cap,
    //      quan_he, sdt_nguoi_lien_he, trang_thai, ngay_tao)
    //     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    //     $stmt = $this->conn->prepare($sql);
    //     return $stmt->execute($data);
    // }
    public function insertHoSoBenhNhan($data)
    {
        // index 8 là cmnd_cccd
        if (!isset($data[8]) || trim($data[8]) === '') {
            $data[8] = null;
        }

        $sql = "INSERT INTO ho_so_benh_nhan
    (id, ho_ten, so_dien_thoai, email, gioi_tinh, ngay_sinh, dia_chi,
     tien_su_benh, cmnd_cccd, bao_hiem_y_te, nguoi_lien_he_khan_cap,
     quan_he, sdt_nguoi_lien_he, trang_thai, ngay_tao)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }
    public function checkHoSoHasAccount($hoSoId)
    {
        $sql = "SELECT id FROM tai_khoan_benh_nhan WHERE ho_so_benh_nhan_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$hoSoId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function checkPhoneExistsInAccount($phone)
    {
        $sql = "SELECT id FROM tai_khoan_benh_nhan WHERE so_dien_thoai = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$phone]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertPatientAccount($data)
    {
        $sql = "INSERT INTO tai_khoan_benh_nhan
                (ho_so_benh_nhan_id, so_dien_thoai, mat_khau, trang_thai)
                VALUES (?,?,?,?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function findByPhoneOrEmail($input)
    {
        $sql = "SELECT * FROM tai_khoan_benh_nhan 
            WHERE so_dien_thoai = ? OR email = ? 
            LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$input, $input]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getHoSoFullByUserId($userId)
    {
        $sql = "
        SELECT 
            hs.*,
            tk.email,
            tk.so_dien_thoai
        FROM tai_khoan_benh_nhan tk
        LEFT JOIN ho_so_benh_nhan hs 
            ON hs.id = tk.ho_so_benh_nhan_id
        WHERE tk.id = ?
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getDoctorById($id)
    {
        $sql = "SELECT * FROM bac_si WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateHoSoBenhNhanByUserId($userId, $data)
    {
        try {
            $this->conn->beginTransaction();

            // Lấy ho_so_benh_nhan_id trước
            $stmt = $this->conn->prepare(
                "SELECT ho_so_benh_nhan_id FROM tai_khoan_benh_nhan WHERE id = ?"
            );
            $stmt->execute([$userId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row || !$row['ho_so_benh_nhan_id']) {
                return false;
            }

            $hoSoId = $row['ho_so_benh_nhan_id'];

            // UPDATE ho_so_benh_nhan
            $sql1 = "
            UPDATE ho_so_benh_nhan SET
                ho_ten = ?,
                gioi_tinh = ?,
                ngay_sinh = ?,
                dia_chi = ?,
                tien_su_benh = ?,
                cmnd_cccd = ?,
                bao_hiem_y_te = ?,
                nguoi_lien_he_khan_cap = ?,
                quan_he = ?,
                sdt_nguoi_lien_he = ?
            WHERE id = ?
        ";

            $stmt1 = $this->conn->prepare($sql1);
            $stmt1->execute([
                $data[0],
                $data[1],
                $data[2],
                $data[3],
                $data[4],
                $data[5],
                $data[6],
                $data[7],
                $data[8],
                $data[9],
                $hoSoId
            ]);

            // UPDATE email tài khoản
            $sql2 = "UPDATE tai_khoan_benh_nhan SET email = ? WHERE id = ?";
            $stmt2 = $this->conn->prepare($sql2);
            $stmt2->execute([
                $data[10],
                $userId
            ]);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
    public function getLichSuKhamByHoSoId($hoSoId)
    {
        $sql = "
        SELECT 
            lsk.id,
            lsk.ngay_kham,
            lsk.chan_doan,
            lsk.huong_dieu_tri,
            bs.ten_bac_si
        FROM lich_su_kham lsk
        LEFT JOIN lich_kham lk 
            ON lsk.lich_kham_id = lk.id
        LEFT JOIN bac_si bs 
            ON lk.bac_si_id = bs.id
        WHERE lsk.ho_so_benh_nhan_id = ?
        ORDER BY lsk.ngay_kham DESC
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$hoSoId]); // ✅ giờ khớp 1 dấu ? với 1 biến

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function luuTinNhan($ten, $email, $tieuDe, $noiDung)
    {
        $sql = "INSERT INTO tin_nhan
            (ten_nguoi_gui, email_nguoi_gui, tieu_de, noi_dung)
            VALUES (?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$ten, $email, $tieuDe, $noiDung]);

        $tinId = $this->conn->lastInsertId();

        // ⚠️ QUAN TRỌNG: gán người nhận (admin)
        $sql2 = "INSERT INTO nguoi_nhan_tin_nhan
             (tin_nhan_id, nguoi_nhan_id, da_doc)
             VALUES (?, ?, 0)";

        $stmt2 = $this->conn->prepare($sql2);
        $stmt2->execute([$tinId, 1]); // 1 = admin

        return $tinId;
    }
}
