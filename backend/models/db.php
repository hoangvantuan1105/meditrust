<?php
class modelClinic
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
    public function getConnection()
    {
        return $this->conn;
    }
    public function getAllMaterials()
    {
        $sql = "
        SELECT *,
        DATEDIFF(han_su_dung, CURDATE()) AS days_left
        FROM vat_tu
    ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function autoUpdateMaterialStatus()
    {
        $sql = "
        UPDATE vat_tu 
        SET 
            trang_thai_han = CASE 
                WHEN han_su_dung < CURDATE() THEN 'het han'
                ELSE 'con han'
            END,
            trang_thai = CASE 
                WHEN so_luong <= 0 THEN 'het hang'
                ELSE 'con hang'
            END
    ";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute();
    }
    public function getWarningMaterials()
    {
        $sql = "
        SELECT *, DATEDIFF(han_su_dung, CURDATE()) AS days_left
        FROM vat_tu
        WHERE 
            so_luong <= 10
            OR DATEDIFF(han_su_dung, CURDATE()) <= 30
    ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findMaterials($id)
    {
        $sql = "SELECT * FROM vat_tu WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertMaterials($data)
    {
        $type = isset($data[9]) ? (int) $data[9] : 0;
        $trang_thai_su_dung = isset($data[10]) ? (int) $data[10] : 1;
        $data = array_slice($data, 0, 9);
        $data[] = $type;
        $data[] = $trang_thai_su_dung;

        $sql = "INSERT INTO vat_tu
        (ten_vat_tu, don_vi, so_luong, hang_san_xuat, danh_muc, trang_thai, gia_nhap, han_su_dung, trang_thai_han, type, trang_thai_su_dung)
        VALUES (?,?,?,?,?,?,?,?,?,?,?)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function getMaterialAlerts()
    {
        $stmt = $this->conn->prepare("SELECT ten_vat_tu, so_luong, han_su_dung FROM vat_tu");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $alerts = [];
        $today = date('Y-m-d');

        foreach ($rows as $m) {
            $qty = (int) $m['so_luong'];
            $exp = $m['han_su_dung'];

            if ($qty == 0) {
                $alerts[] = [
                    'type' => 'danger',
                    'icon' => 'fa-times-circle',
                    'msg' => $m['ten_vat_tu'] . ' đã hết hàng'
                ];
            } elseif ($qty <= 5) {
                $alerts[] = [
                    'type' => 'warning',
                    'icon' => 'fa-exclamation-triangle',
                    'msg' => $m['ten_vat_tu'] . ' sắp hết'
                ];
            }

            if ($exp <= $today) {
                $alerts[] = [
                    'type' => 'danger',
                    'icon' => 'fa-calendar-times',
                    'msg' => $m['ten_vat_tu'] . ' đã hết hạn'
                ];
            } elseif (strtotime($exp) <= strtotime('+7 days')) {
                $alerts[] = [
                    'type' => 'warning',
                    'icon' => 'fa-calendar-alt',
                    'msg' => $m['ten_vat_tu'] . ' sắp hết hạn'
                ];
            }
        }

        return $alerts;
    }



    public function updateMaterials(
        $ten_vat_tu,
        $don_vi,
        $so_luong,
        $hang_san_xuat,
        $danh_muc,
        $trang_thai,
        $trang_thai_han,
        $gia_nhap,
        $han_su_dung,
        $id,
        $type = 0,
        $trang_thai_su_dung = 1
    ) {
        $sql = "UPDATE vat_tu 
        SET ten_vat_tu = ?, 
            don_vi = ?, 
            so_luong = ?, 
            hang_san_xuat = ?,
            danh_muc = ?,
            trang_thai = ?,
            trang_thai_han = ?,
            gia_nhap = ?, 
            han_su_dung = ?,
            type = ?,
            trang_thai_su_dung = ?
        WHERE id = ?";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            $ten_vat_tu,
            $don_vi,
            $so_luong,
            $hang_san_xuat,
            $danh_muc,
            $trang_thai,
            $trang_thai_han,
            $gia_nhap,
            $han_su_dung,
            (int) $type,
            (int) $trang_thai_su_dung,
            $id
        ]);
    }



    public function deleteMaterials($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM vat_tu WHERE id=?");
        return $stmt->execute([$id]);
    }



    public function searchMaterials($keyword)
    {
        $sql = "SELECT ten_vat_tu, don_vi, gia_nhap, hang_san_xuat, danh_muc, type, trang_thai_su_dung 
            FROM vat_tu 
            WHERE ten_vat_tu LIKE ? 
            LIMIT 10";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["%$keyword%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // xuất vật tư
    public function insertExport($data)
    {
        $sql = "INSERT INTO xuat_vat_tu(vat_tu_id, so_luong, ly_do, ngay_xuat, bac_si_id, thanh_tien)
            VALUES (?,?,?,?,?,?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }



    public function updateQuantity($id, $qty)
    {
        $sql = "UPDATE vat_tu SET so_luong = so_luong - ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$qty, $id]);
    }


    public function getExportLogs()
    {
        $sql = "
        SELECT xvt.*, vt.ten_vat_tu, vt.gia_nhap, bs.ten_bac_si
        FROM xuat_vat_tu xvt
        JOIN vat_tu vt ON xvt.vat_tu_id = vt.id
        LEFT JOIN bac_si bs ON xvt.bac_si_id = bs.id
        ORDER BY xvt.id DESC
    ";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getAllDoctors()
    {
        $sql = "SELECT * FROM bac_si ORDER BY ten_bac_si";
        return $this->conn->query($sql)->fetchAll();
    }

    public function historyExportMaterials($bac_si_id = null)
    {
        $sql = "
        SELECT 
            xvt.id,
            xvt.so_luong,
              xvt.ly_do,
            xvt.ngay_xuat,
            xvt.thanh_tien,
            vt.ten_vat_tu,
            vt.don_vi,
            vt.gia_nhap,
            bs.ten_bac_si
        FROM xuat_vat_tu xvt
        JOIN vat_tu vt ON xvt.vat_tu_id = vt.id
        JOIN bac_si bs ON xvt.bac_si_id = bs.id
        WHERE 1=1
    ";

        if ($bac_si_id) {
            $sql .= " AND xvt.bac_si_id = ?";
        }

        $sql .= " ORDER BY xvt.id DESC";

        $stmt = $this->conn->prepare($sql);

        $bac_si_id ? $stmt->execute([$bac_si_id]) : $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }




    public function countXuatVatTu($id)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM xuat_vat_tu WHERE vat_tu_id=?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn();
    }
    public function deleteMaterialsForce($id)
    {
        $this->conn->beginTransaction();

        try {
            $stmt1 = $this->conn->prepare("DELETE FROM xuat_vat_tu WHERE vat_tu_id=?");
            $stmt1->execute([$id]);

            $stmt2 = $this->conn->prepare("DELETE FROM vat_tu WHERE id=?");
            $stmt2->execute([$id]);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // =============================================================================================================================================

    public function findByPhone($sdt)
    {
        $sql = "SELECT * FROM qly_tai_khoan WHERE sdt=?";
        $st = $this->conn->prepare($sql);
        $st->execute([$sdt]);
        return $st->fetch(PDO::FETCH_ASSOC);
    }

    public function resetFail($id)
    {
        $sql = "UPDATE qly_tai_khoan SET sai_mk=0, thoi_gian_tam_khoa=NULL WHERE id=?";
        $st = $this->conn->prepare($sql);
        $st->execute([$id]);
    }

    public function increaseFail($id)
    {
        $sql = "UPDATE qly_tai_khoan SET sai_mk = sai_mk + 1 WHERE id=?";
        $st = $this->conn->prepare($sql);
        $st->execute([$id]);
    }

    public function lockAccount($id)
    {
        $sql = "UPDATE qly_tai_khoan 
            SET thoi_gian_tam_khoa = DATE_ADD(NOW(), INTERVAL 10 MINUTE) 
            WHERE id=?";
        $st = $this->conn->prepare($sql);
        $st->execute([$id]);
    }


    public function logLogin($user_id, $status)
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';

        $sql = "INSERT INTO admin_login_logs(admin_id, ip_address, user_agent, status) 
            VALUES(?,?,?,?)";
        $st = $this->conn->prepare($sql);
        $st->execute([$user_id, $ip, $ua, $status]);
    }


    public function getLastLogin($user_id)
    {
        $sql = "
        SELECT 
            l.ip_address,
            l.created_at,
            u.role,
            u.avatar,
            u.ten_nguoi_su_dung,
            u.sdt
        FROM admin_login_logs l
        JOIN qly_tai_khoan u ON u.id = l.admin_id
        WHERE l.admin_id = ? 
        AND l.status = 'SUCCESS'
        ORDER BY l.id DESC
        LIMIT 1
    ";

        $st = $this->conn->prepare($sql);
        $st->execute([$user_id]);
        return $st->fetch(PDO::FETCH_ASSOC);
    }


    public function insertAccount($data)
    {
        $sql = "INSERT INTO qly_tai_khoan(sdt,password,ten_nguoi_su_dung,role,bac_si_id)
            VALUES (?,?,?,?,?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }


    public function updatePassword($id, $password)
    {
        $sql = "UPDATE qly_tai_khoan SET password=? WHERE id=?";
        $st = $this->conn->prepare($sql);
        return $st->execute([$password, $id]);
    }


    public function logActivity($admin_id, $action)
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';

        $sql = "INSERT INTO admin_login_logs(admin_id, status, ip_address, user_agent)
            VALUES (?,?,?,?)";

        $st = $this->conn->prepare($sql);
        $st->execute([$admin_id, $action, $ip, $ua]);
    }

    public function getAllMaterialsForAI()
    {
        $sql = "SELECT ten_vat_tu, so_luong, han_su_dung 
            FROM vat_tu";
        $st = $this->conn->prepare($sql);
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function increaseMaterial($name, $qty)
    {
        $sql = "UPDATE vat_tu 
            SET so_luong = so_luong + :qty
            WHERE LOWER(TRIM(ten_vat_tu)) = LOWER(TRIM(:name))";

        $st = $this->conn->prepare($sql);
        $st->execute([
            ':qty' => (int) $qty,
            ':name' => trim($name)
        ]);

        return $st->rowCount() > 0;
    }

    // =============================================================================================================================================
    public function insertHoaDon($data)
    {
        $sql = "INSERT INTO hoa_don
            (benh_nhan_id, bac_si_id, lich_kham_id, tong_tien, giam_gia, thanh_tien, phuong_thuc_tt, trang_thai)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);

        return $this->conn->lastInsertId();
    }




    public function insertChiTietHoaDon($data)
    {
        $data = array_pad($data, 8, null);

        $sql = "INSERT INTO chi_tiet_hoa_don 
            (hoa_don_id, loai_item, item_id, so_luong, don_gia, thanh_tien, lieu_luong, cach_uong)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }


    public function getGiaThuoc($id)
    {
        $sql = "SELECT gia_nhap FROM thuoc WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetchColumn();
    }
    public function getGiaDichVu($id)
    {
        $sql = "SELECT gia FROM dich_vu WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetchColumn() ?? 0;
    }


    public function updateTrangThaiLich($id)
    {
        $sql = "UPDATE lich_hen SET trang_thai='da_thanh_toan' WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }


    public function getAllBenhNhan()
    {
        $sql = "SELECT id, ho_ten FROM ho_so_benh_nhan WHERE trang_thai=1";

        return $this->conn->query($sql)->fetchAll();
    }

    public function getAllDichVu()
    {
        $sql = "SELECT id, ten_dich_vu, gia FROM dich_vu";
        return $this->conn->query($sql)->fetchAll();
    }
    public function getAllBacSi()
    {
        $sql = "SELECT * FROM bac_si";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    //    ==========================================================================================================================================

    public function getAllHoaDon()
    {
        $sql = "
        SELECT 
            hd.*,
            hs.ho_ten AS ten_benh_nhan,
            bs.ten_bac_si
        FROM hoa_don hd
        JOIN ho_so_benh_nhan hs ON hd.benh_nhan_id = hs.id
        JOIN bac_si bs ON hd.bac_si_id = bs.id
        ORDER BY hd.id DESC
    ";

        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateHoaDonStatus($id, $status)
    {
        $sql = "UPDATE hoa_don SET trang_thai = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$status, $id]);
    }

    // Tuấn thần đèn
    public function getLichKhamById($id)
    {
        $sql = "
        SELECT lk.*,
               bn.ho_ten AS ten_benh_nhan,
               bn.so_dien_thoai,
               dv.ten_dich_vu
        FROM lich_kham lk
        JOIN ho_so_benh_nhan bn 
            ON lk.ho_so_benh_nhan_id = bn.id
        LEFT JOIN dich_vu dv
            ON lk.dich_vu_id = dv.id
        WHERE lk.id = ?
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function getDichVuTheoLichKham($lich_kham_id)
    {
        $sql = "
        SELECT 
            ct.dich_vu_id,
            ct.ten_dich_vu,
            dv.gia
        FROM ct_ls_kham ct
        JOIN lich_su_kham ls ON ct.ls_kham_id = ls.id
        JOIN dich_vu dv ON ct.dich_vu_id = dv.id
        WHERE ls.lich_kham_id = ?
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$lich_kham_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getVoucher($code)
    {
        $sql = "SELECT * FROM ma_giam_gia 
            WHERE code = ? 
              AND trang_thai = 1
              AND so_luot > 0
              AND CURDATE() BETWEEN ngay_bat_dau AND ngay_ket_thuc";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$code]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function truLuotVoucher($id)
    {
        return $this->conn
            ->prepare("UPDATE ma_giam_gia SET so_luot = so_luot - 1 WHERE id = ?")
            ->execute([$id]);
    }
    public function checkHoaDonByLich($lich_kham_id)
    {
        $sql = "SELECT id FROM hoa_don WHERE lich_kham_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$lich_kham_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tuấn thần đèn ngày 5/02/2026

    public function getAllDiscount()
    {
        $sql = "SELECT * FROM ma_giam_gia ";

        return $this->conn->query($sql)->fetchAll();
    }
    public function findVoucher($id)
    {
        $sql = "SELECT * FROM ma_giam_gia WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function insertVoucher($data)
    {
        $sql = "INSERT INTO ma_giam_gia(code,loai,gia_tri,so_luot,ngay_bat_dau,ngay_ket_thuc,trang_thai)
            VALUES (?,?,?,?,?,?,?)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }


    public function updateVoucher($data)
    {
        $sql = "UPDATE ma_giam_gia 
            SET code = ?, 
                loai = ?, 
                gia_tri = ?, 
                so_luot = ?, 
                ngay_bat_dau = ?, 
                ngay_ket_thuc = ?, 
                trang_thai = ?
            WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function deleteVoucher($id)
    {
        return $this->conn->query("DELETE FROM ma_giam_gia WHERE id=?", [$id]);
    }




    // tuấn 7/02/2026
    // =============================================================================================================================================


    public function getGiaDichVuHoaDon($id)
    {
        $stmt = $this->conn->prepare("SELECT gia FROM dich_vu WHERE id=?");
        $stmt->execute([$id]);
        return (int) $stmt->fetchColumn();
    }
    // tuấn thần đèn 14/02/2026
    // ==================================================================================================================================================

    public function countAllBenhNhan()
    {
        $sql = "SELECT COUNT(*) as total FROM ho_so_benh_nhan";
        $stmt = $this->conn->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function countAllLichKham()
    {
        $sql = "SELECT COUNT(*) as total FROM lich_kham";
        $stmt = $this->conn->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
    public function countLichKhamByBacSi($bacSiId)
    {
        $sql = "SELECT COUNT(*) as total 
            FROM lich_kham 
            WHERE bac_si_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$bacSiId]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
    public function countAllBacSi()
    {
        $sql = "SELECT COUNT(*) as total FROM bac_si";
        $stmt = $this->conn->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
    public function doanhThuDichVu()
    {
        $sql = "SELECT SUM(ct.thanh_tien) as total
            FROM chi_tiet_hoa_don ct
            JOIN hoa_don hd ON ct.hoa_don_id = hd.id
            WHERE hd.trang_thai = 1";

        $stmt = $this->conn->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }

    public function doanhThuTheoTungDichVu()
    {
        $sql = "SELECT dv.ten_dich_vu,
                   SUM(ct.thanh_tien) as total,
                   SUM(ct.so_luong) as so_lan
            FROM chi_tiet_hoa_don ct
            JOIN hoa_don hd ON ct.hoa_don_id = hd.id
            JOIN dich_vu dv ON ct.dich_vu_id = dv.id
            WHERE hd.trang_thai = 1
            GROUP BY dv.id
            ORDER BY total DESC";

        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function doanhThuThang()
    {
        $sql = "SELECT SUM(ct.thanh_tien) as total
            FROM chi_tiet_hoa_don ct
            JOIN hoa_don hd ON ct.hoa_don_id = hd.id
            WHERE hd.trang_thai = 1
            AND MONTH(hd.ngay_lap) = MONTH(CURDATE())
            AND YEAR(hd.ngay_lap) = YEAR(CURDATE())";

        return $this->conn->query($sql)->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }
    public function doanhThu12Thang()
    {
        $sql = "SELECT MONTH(ngay_lap) as thang,
                   SUM(thanh_tien) as total
            FROM hoa_don
            WHERE trang_thai = 1
              AND YEAR(ngay_lap) = YEAR(CURDATE())
            GROUP BY MONTH(ngay_lap)";

        $result = $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        $data = array_fill(1, 12, 0);

        foreach ($result as $row) {
            $data[$row['thang']] = (float) $row['total'];
        }

        return array_values($data);
    }

    public function topDichVu()
    {
        $sql = "SELECT dv.ten_dich_vu,
                   SUM(ct.thanh_tien) as total
            FROM chi_tiet_hoa_don ct
            JOIN hoa_don hd ON ct.hoa_don_id = hd.id
            JOIN dich_vu dv ON dv.id = ct.item_id
            WHERE hd.trang_thai = 1
              AND ct.loai_item = 'dich_vu'
            GROUP BY dv.id
            ORDER BY total DESC
            LIMIT 5";

        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }


    public function tongDoanhThu()
    {
        $sql = "SELECT IFNULL(SUM(thanh_tien),0) as total
            FROM hoa_don
            WHERE trang_thai = 1";

        return $this->conn->query($sql)
            ->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function lichKhamHomNayChiTiet($bacSiId = null)
    {
        $sql = "SELECT
                    lk.id,
                    lk.gio_bat_dau,
                    lk.gio_ket_thuc,
                    lk.trang_thai,
                    hs.ho_ten AS ten_benh_nhan,
                    hs.so_dien_thoai,
                    dv.ten_dich_vu,
                    bs.ten_bac_si
                FROM lich_kham lk
                JOIN ho_so_benh_nhan hs ON lk.ho_so_benh_nhan_id = hs.id
                LEFT JOIN dich_vu dv ON lk.dich_vu_id = dv.id
                LEFT JOIN bac_si bs ON lk.bac_si_id = bs.id
                WHERE DATE(lk.ngay_kham) = CURDATE()";
        $params = [];
        if ($bacSiId !== null) {
            $sql .= " AND lk.bac_si_id = ?";
            $params[] = $bacSiId;
        }
        $sql .= " ORDER BY lk.gio_bat_dau ASC LIMIT 10";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function thongKeTrangThaiHomNay($bacSiId = null)
    {
        $sql = "SELECT trang_thai, COUNT(*) as so_luong
                FROM lich_kham
                WHERE DATE(ngay_kham) = CURDATE()";
        $params = [];
        if ($bacSiId !== null) {
            $sql .= " AND bac_si_id = ?";
            $params[] = $bacSiId;
        }
        $sql .= " GROUP BY trang_thai";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $rows   = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = ['cho_kham' => 0, 'dang_kham' => 0, 'da_kham' => 0];
        foreach ($rows as $row) {
            if (isset($result[$row['trang_thai']])) {
                $result[$row['trang_thai']] = (int) $row['so_luong'];
            }
        }
        return $result;
    }

    public function doanhThuHomNay()
    {
        $sql = "SELECT IFNULL(SUM(thanh_tien), 0) as total
                FROM hoa_don
                WHERE trang_thai = 1
                AND DATE(ngay_lap) = CURDATE()";
        return $this->conn->query($sql)->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }

    public function lichKhamHomNay($bacSiId = null)
    {
        $sql = "SELECT COUNT(*) as total FROM lich_kham WHERE DATE(ngay_kham) = CURDATE()";
        $params = [];
        if ($bacSiId !== null) {
            $sql .= " AND bac_si_id = ?";
            $params[] = $bacSiId;
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }

    public function benhNhanTheo7Ngay($bacSiId = null)
    {
        $sql = "SELECT DATE(ngay_kham) as ngay, COUNT(*) as so_luong
                FROM lich_kham
                WHERE DATE(ngay_kham) >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)";
        $params = [];
        if ($bacSiId !== null) {
            $sql .= " AND bac_si_id = ?";
            $params[] = $bacSiId;
        }
        $sql .= " GROUP BY DATE(ngay_kham) ORDER BY ngay ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // tuấn thần đèn ngày 15/02/2026
    // =============================================================================================================================================

    public function getAdminLogs()
    {
        $sql = "SELECT l.*, q.sdt, q.ten_nguoi_su_dung
            FROM admin_login_logs l
            LEFT JOIN qly_tai_khoan q ON l.admin_id = q.id
            ORDER BY l.created_at DESC";

        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }


    // tuấn thần đèn ngày 18/02/2026 làm tạo hóa đơn 
    public function getDanhSachKetQuaKham()
    {
        $sql = "
    SELECT 
        lsk.id,
        lsk.lich_kham_id,
        bn.ho_ten AS ten_benh_nhan,
        GROUP_CONCAT(dv.ten_dich_vu SEPARATOR ', ') AS dich_vu,
        lsk.chan_doan,
        lsk.ngay_kham
    FROM lich_su_kham lsk
    JOIN ho_so_benh_nhan bn 
        ON lsk.ho_so_benh_nhan_id = bn.id
    LEFT JOIN ct_ls_kham ct 
        ON ct.ls_kham_id = lsk.id
    LEFT JOIN dich_vu dv 
        ON dv.id = ct.dich_vu_id
    GROUP BY lsk.id
    ORDER BY lsk.ngay_kham DESC
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function getDichVuTheoLich($lich_kham_id)
    {
        $sql = "
        SELECT dv.ten_dich_vu, dv.gia
        FROM chi_tiet_kham ctk
        JOIN dich_vu dv ON ctk.dich_vu_id = dv.id
        WHERE ctk.lich_kham_id = ?
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$lich_kham_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getThuocTheoLich($lich_kham_id)
    {
        $sql = "SELECT 
                t.id,
                t.ten_thuoc,
                t.gia_nhap,
                ct.so_luong,
                ct.lieu_dung,
                ct.thoi_diem_uong
            FROM don_thuoc dt
            JOIN chi_tiet_don_thuoc ct 
                ON ct.don_thuoc_id = dt.ma_don_thuoc
            JOIN thuoc t 
                ON t.id = ct.thuoc_id
            WHERE dt.lich_kham_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$lich_kham_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHoaDonById($id)
    {
        $sql = "SELECT hd.*, 
                   hs.ho_ten as ten_benh_nhan,
                   bs.ten_bac_si as ten_bac_si
            FROM hoa_don hd
            LEFT JOIN ho_so_benh_nhan hs 
                ON hd.benh_nhan_id = hs.id
            LEFT JOIN bac_si bs 
                ON hd.bac_si_id = bs.id
            WHERE hd.id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }




    public function getChiTietHoaDon($hoa_don_id)
    {
        $sql = "
        SELECT ct.*, 
               COALESCE(dv.ten_dich_vu, t.ten_thuoc, vt.ten_vat_tu) as ten
        FROM chi_tiet_hoa_don ct
        LEFT JOIN dich_vu dv 
            ON ct.item_id = dv.id AND ct.loai_item = 'dich_vu'
        LEFT JOIN thuoc t 
            ON ct.item_id = t.id AND ct.loai_item = 'thuoc'
        LEFT JOIN vat_tu vt
            ON ct.item_id = vt.id AND ct.loai_item = 'san_pham'
        WHERE ct.hoa_don_id = ?
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$hoa_don_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateAdminAvatar($id, $avatar)
    {
        $sql = "UPDATE qly_tai_khoan SET avatar = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$avatar, $id]);
    }
    public function getAdminById($id)
    {
        $sql = "SELECT * FROM qly_tai_khoan WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getLastLoginLogs($admin_id)
    {
        $sql = "SELECT * 
            FROM admin_login_logs 
            WHERE admin_id = ?
            ORDER BY created_at DESC
            LIMIT 3";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$admin_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // public function countLichKhamThang($thang, $nam)
    // {
    //     $sql = "SELECT COUNT(*) as total 
    //         FROM lich_kham
    //         WHERE MONTH(ngay_kham) = ?
    //         AND YEAR(ngay_kham) = ?";

    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->execute([$thang, $nam]);
    //     return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    // }

    // public function countAllPatient()
    // {
    //     $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM ho_so_benh_nhan");
    //     $stmt->execute();
    //     return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    // }

    public function countLichKhamThang($thang, $nam, $bacSiId = null)
    {
        $sql = "SELECT COUNT(*) as total 
            FROM lich_kham
            WHERE MONTH(ngay_kham) = ?
            AND YEAR(ngay_kham) = ?";

        $params = [$thang, $nam];


        if ($bacSiId !== null) {
            $sql .= " AND bac_si_id = ?";
            $params[] = $bacSiId;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }
    public function countAllPatient($bacSiId = null)
    {
        $sql = "SELECT COUNT(DISTINCT ho_so_benh_nhan_id) as total
            FROM lich_kham
            WHERE 1=1";

        $params = [];

        if ($bacSiId) {
            $sql .= " AND bac_si_id = ?";
            $params[] = $bacSiId;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
    // Đếm tin chưa đọc
    public function demTinChuaDoc($user_id)
    {
        $sql = "SELECT COUNT(*) as total
                FROM nguoi_nhan_tin_nhan
                WHERE nguoi_nhan_id = ?
                AND da_doc = 0";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // Lấy tất cả tin
    public function layTatCaTin()
    {
        $sql = "SELECT t.*, n.da_doc
            FROM nguoi_nhan_tin_nhan n
            JOIN tin_nhan t ON t.id = n.tin_nhan_id
            ORDER BY t.ngay_tao DESC";

        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết
    public function layChiTietTin($id)
    {
        $sql = "SELECT * FROM tin_nhan WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Đánh dấu đã đọc
    public function danhDauDaDoc($tin_nhan_id, $user_id)
    {
        $sql = "UPDATE nguoi_nhan_tin_nhan
                SET da_doc = 1
                WHERE tin_nhan_id = ?
                AND nguoi_nhan_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$tin_nhan_id, $user_id]);
    }
    public function layTinGanNhat($user_id)
    {
        $sql = "SELECT t.*, n.da_doc
            FROM nguoi_nhan_tin_nhan n
            JOIN tin_nhan t ON t.id = n.tin_nhan_id
            WHERE n.nguoi_nhan_id = ?
            ORDER BY t.ngay_tao DESC
            LIMIT 5";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // tuấn idol ngày 20/02/2026
    public function luuPhanHoi($tin_id, $noi_dung, $file = null)
    {
        $sql = "INSERT INTO phan_hoi_tin (tin_nhan_id, noi_dung, file_dinh_kem)
            VALUES (?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$tin_id, $noi_dung, $file]);
    }

    public function daTraLoi($tin_id)
    {
        $sql = "SELECT COUNT(*) FROM phan_hoi_tin WHERE tin_nhan_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$tin_id]);
        return $stmt->fetchColumn() > 0;
    }


    // ===========================================================================================================================================

    public function modelListMedicine()
    {
        $stmt = $this->conn->prepare("select * from thuoc where trang_thai_su_dung = 1");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addMedicine($medicineName, $classMedicine, $dosageForm, $drugContent, $unit, $quantity, $expirationDate, $price, $manufacturer, $countryProduction, $description)
    {
        $stmt = $this->conn->prepare("insert into thuoc(ten_thuoc, nhom_thuoc, dang_bao_che, ham_luong, don_vi_tinh, so_luong, han_su_dung, gia_nhap, hang_san_xuat, nuoc_san_xuat, ghi_chu) values(?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([$medicineName, $classMedicine, $dosageForm, $drugContent, $unit, $quantity, $expirationDate, $price, $manufacturer, $countryProduction, $description]);
        return $stmt->rowCount() > 0;
    }

    public function getMedicineByID($idAdmin)
    {
        $stmt = $this->conn->prepare("select * from thuoc where id = ?");
        $stmt->execute([$idAdmin]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function editMedicine($medicineName, $classMedicine, $dosageForm, $drugContent, $unit, $quantity, $expirationDate, $price, $manufacturer, $countryProduction, $description, $idAdmin)
    {
        $stmt = $this->conn->prepare("update thuoc set ten_thuoc = ?, nhom_thuoc = ?, dang_bao_che = ?, ham_luong = ?, don_vi_tinh = ?, so_luong = ?, han_su_dung = ?, gia_nhap = ?, hang_san_xuat = ?, nuoc_san_xuat = ?, ghi_chu = ? where id = ?");
        $stmt->execute([$medicineName, $classMedicine, $dosageForm, $drugContent, $unit, $quantity, $expirationDate, $price, $manufacturer, $countryProduction, $description, $idAdmin]);
        return $stmt->rowCount() > 0;
    }

    public function deleteMedicine($idAdmin)
    {
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare("
            UPDATE thuoc 
            SET trang_thai_su_dung = 0 
            WHERE id = ?
        ");

            $stmt->execute([$idAdmin]);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Lỗi cập nhật trạng thái thuốc: " . $e->getMessage());
            return false;
        }
    }

    public function dispenseMedicine($idMedicine, $quantityDispense, $reasonDispense, $dateDispense)
    {
        try {

            $stmt = $this->conn->prepare("insert into xuat_thuoc(thuoc_id, so_luong, ly_do, ngay_xuat) values(?,?,?,?)");
            $stmt->execute([$idMedicine, $quantityDispense, $reasonDispense, $dateDispense]);

            $stmtUpdateMedicine = $this->conn->prepare("update thuoc set so_luong = so_luong - ? where id = ?");
            $stmtUpdateMedicine->execute([$quantityDispense, $idMedicine]);

            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Lỗi xuất thuốc: " . $e->getMessage());
            return false;
        }
    }

    public function getAllDispenseMedicine()
    {
        $stmt = $this->conn->prepare("
        SELECT xt.*, t.ten_thuoc 
            FROM xuat_thuoc xt
            INNER JOIN thuoc t ON xt.thuoc_id = t.id 
            ORDER BY xt.ngay_xuat DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllPatient()
    {
        $stmt = $this->conn->prepare("select * from ho_so_benh_nhan");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllDoctor()
    {
        $stmt = $this->conn->prepare("select * from bac_si");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function prescription($patient, $doctor, $diagnose, $idMedicine, $quantityMedicine, $dosage)
    {
        $stmt_ls = $this->conn->prepare("SELECT id, ngay_kham FROM lich_su_kham WHERE ho_so_benh_nhan_id = ? ORDER BY id DESC LIMIT 1");
        $stmt_ls->execute([$patient]);
        $lsk = $stmt_ls->fetch();

        if (!$lsk) {
            return false;
        }

        $lich_su_kham_id = $lsk['id'];
        $ngay_kham_tu_ls = $lsk['ngay_kham'];

        $stmt = $this->conn->prepare("INSERT INTO don_thuoc (ho_so_benh_nhan_id, bac_si_id, lich_su_kham_id, ngay_ke_don, chan_doan) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$patient, $doctor, $lich_su_kham_id, $ngay_kham_tu_ls, $diagnose]);

        $don_thuoc_id = $this->conn->lastInsertId();

        $stmt_ct = $this->conn->prepare("INSERT INTO chi_tiet_don_thuoc (ma_don_thuoc, thuoc_id, so_luong, lieu_dung) VALUES (?, ?, ?, ?)");

        foreach ($idMedicine as $key => $id_thuoc) {
            if (!empty($id_thuoc)) {
                $stmt_ct->execute([
                    $don_thuoc_id,
                    $id_thuoc,
                    $quantityMedicine[$key],
                    $dosage[$key]
                ]);

                $stmt_update = $this->conn->prepare("UPDATE thuoc SET so_luong = so_luong - ? WHERE id = ?");
                $stmt_update->execute([$quantityMedicine[$key], $id_thuoc]);
            }
        }
        return true;
    }

    public function modelListPrescription()
    {
        $sql = "SELECT 
                dt.ma_don_thuoc, 
                dt.ngay_ke_don, 
                dt.chan_doan,
                dt.lich_su_kham_id,
                bn.ho_ten AS ho_so_benh_nhan,
                bs.ten_bac_si AS bac_si_id,
                ls.ngay_kham
            FROM don_thuoc dt
            INNER JOIN ho_so_benh_nhan bn ON dt.ho_so_benh_nhan_id = bn.id
            INNER JOIN bac_si bs ON dt.bac_si_id = bs.id
            LEFT JOIN lich_su_kham ls ON dt.lich_su_kham_id = ls.id 
            ORDER BY dt.ma_don_thuoc DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPrescriptionByID($idAdmin)
    {
        $sql = "SELECT 
                ct.*, 
                t.ten_thuoc, 
                t.ham_luong, 
                t.don_vi_tinh,
                t.gia_nhap,
                (ct.so_luong * t.gia_nhap) as thanh_tien
            FROM chi_tiet_don_thuoc ct
            INNER JOIN thuoc t ON ct.thuoc_id = t.id
            WHERE ct.ma_don_thuoc = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$idAdmin]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // đây là của dũng
    // Lấy tất cả bệnh nhân
    public function getAll()
    {
        $stmt = $this->conn->prepare("SELECT * FROM ho_so_benh_nhan ORDER BY ngay_tao DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy bệnh nhân theo ID
    public function getById($id)
    {
        $sql = "SELECT * FROM ho_so_benh_nhan WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm bệnh nhân mới
    public function insert($data)
    {
        $sql = "INSERT INTO ho_so_benh_nhan
        (id, ho_ten, so_dien_thoai, email, gioi_tinh, ngay_sinh, dia_chi,
         tien_su_benh, cmnd_cccd, bao_hiem_y_te, nguoi_lien_he_khan_cap,
         quan_he, sdt_nguoi_lien_he, trang_thai, ngay_tao)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    // Cập nhật bệnh nhân
    public function update($data)
    {
        $sql = "UPDATE ho_so_benh_nhan 
                SET ho_ten=?, so_dien_thoai=?, email=?, gioi_tinh=?, ngay_sinh=?, dia_chi=?, tien_su_benh=?, cmnd_cccd=?, bao_hiem_y_te=?, nguoi_lien_he_khan_cap=?, quan_he=?, sdt_nguoi_lien_he=?, trang_thai=? 
                WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    // Xóa bệnh nhân
    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM ho_so_benh_nhan WHERE id=?");
        return $stmt->execute([$id]);
    }
    public function checkDuplicate($so_dien_thoai, $email, $cmnd_cccd, $bao_hiem_y_te)
    {
        $sql = "SELECT 
                so_dien_thoai,
                email,
                cmnd_cccd,
                bao_hiem_y_te
            FROM ho_so_benh_nhan
            WHERE so_dien_thoai = ?
               OR email = ?
               OR cmnd_cccd = ?
               OR bao_hiem_y_te = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$so_dien_thoai, $email, $cmnd_cccd, $bao_hiem_y_te]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // end của dũng





    // <!-- Coder Vi Đức Được    FB/duocXdangiu    Zalo/0389232813    Github/coderDuoc --> 

    // Tạo Hồ Sơ Bệnh Nhân
    public function checkUserExists($so_dien_thoai)
    {
        $sql = "SELECT id FROM tai_khoan_benh_nhan WHERE so_dien_thoai = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$so_dien_thoai]);
        return $stmt->rowCount() > 0;
    }
    // thêm tài khoản bệnh nhân
    public function findAccountByEmailOrPhone($email, $phone)
    {
        $sql = "SELECT * FROM tai_khoan_benh_nhan
            WHERE email = ? OR so_dien_thoai = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email, $phone]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function insertUser($data)
    {
        $sql = "INSERT INTO tai_khoan_benh_nhan
            (ho_so_benh_nhan_id, email, so_dien_thoai, mat_khau, trang_thai, ngay_tao, otp, otp_expired)
            VALUES (?, ?, ?, ?, ?, NOW(), ?, ?)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function getAllPatientAccounts()
    {
        // List Danh Sách Tài Khoản Bệnh Nhân
        $stmt = $this->conn->prepare("SELECT * FROM tai_khoan_benh_nhan ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAllIDHoSoBenhNhan()
    {
        $stmt = $this->conn->prepare(
            "SELECT id, ho_ten, so_dien_thoai FROM ho_so_benh_nhan"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function checkPhoneExistsInAccount($phone)
    {
        $sql = "SELECT id FROM tai_khoan_benh_nhan WHERE so_dien_thoai = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$phone]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // Check 1 hồ sơ chỉ có 1 tài khoản
    public function checkHoSoHasAccount($hoSoId)
    {
        $sql = "SELECT id FROM tai_khoan_benh_nhan WHERE ho_so_benh_nhan_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$hoSoId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // Thêm tài khoản bệnh nhân
    public function insertPatientAccount($data)
    {
        $sql = "INSERT INTO tai_khoan_benh_nhan
                (ho_so_benh_nhan_id, so_dien_thoai, mat_khau, trang_thai)
                VALUES (?,?,?,?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }
    public function getPatientAccountById($id)
    {
        $sql = "SELECT * FROM tai_khoan_benh_nhan WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function checkPhoneExistsExceptId($phone, $id)
    {
        $sql = "SELECT id FROM tai_khoan_benh_nhan 
            WHERE so_dien_thoai = ? AND id <> ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$phone, $id]);
        return $stmt->fetch();
    }
    public function updatePatientAccountPhone($id, $phone)
    {
        $sql = "UPDATE tai_khoan_benh_nhan 
            SET so_dien_thoai = ?
            WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$phone, $id]);
    }

    // Update 02/02
    public function layThongTinLeTan()
    {
        $sql = "SELECT * FROM le_tan ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Thêm lễ tân
    public function themTaiKhoanLeTan($ten, $sdt, $email, $gioi_tinh, $ca_lam, $trang_thai)
    {
        $sql = "INSERT INTO le_tan 
        (ten_le_tan, sdt, email, gioi_tinh, ca_lam, trang_thai, ngay_tao)
        VALUES (?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$ten, $sdt, $email, $gioi_tinh, $ca_lam, $trang_thai]);
    }
    // Kiểm tra trùng số điện thoại và email lễ tân
    public function checkTrungSDT($sdt)
    {
        $sql = "SELECT COUNT(*) FROM le_tan WHERE sdt = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$sdt]);
        return $stmt->fetchColumn();
    }

    public function checkTrungEmail($email)
    {
        $sql = "SELECT COUNT(*) FROM le_tan WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetchColumn();
    }


    // Sửa lễ tân
    public function layLeTanTheoID($id)
    {
        $sql = "SELECT * FROM le_tan WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cập nhật lễ tân
    public function suaTaiKhoanLeTan($id, $data)
    {
        $sql = "UPDATE le_tan SET
                    ten_le_tan = ?,
                    sdt = ?,
                    email = ?,
                    gioi_tinh = ?,
                    ca_lam = ?,
                    trang_thai = ?
                WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['ten_le_tan'],
            $data['sdt'],
            $data['email'],
            $data['gioi_tinh'],
            $data['ca_lam'],
            $data['trang_thai'],
            $id
        ]);
    }
    public function kiemTraTrungSDT($sdt, $id)
    {
        $sql = "SELECT id FROM le_tan WHERE sdt = ? AND id != ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$sdt, $id]);
        return $stmt->fetch();
    }
    public function kiemTraTrungEmail($email, $id)
    {
        $sql = "SELECT id FROM le_tan WHERE email = ? AND id != ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email, $id]);
        return $stmt->fetch();
    }
    public function layThongTinYeuCauLichHen()
    {
        $sql = "
        SELECT 
            yc.*,
            bs.ten_bac_si,
            dv.ten_dich_vu,
            dv.gia
        FROM yeu_cau_dat_lich yc
        LEFT JOIN bac_si bs ON yc.bac_si_id = bs.id
        LEFT JOIN dich_vu dv ON yc.dich_vu_id = dv.id
        ORDER BY yc.created_at DESC
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function layYeuCauDatLichById($id)
    {
        $sql = "
        SELECT yc.*,
               bs.ten_bac_si,
               dv.ten_dich_vu
        FROM yeu_cau_dat_lich yc
        LEFT JOIN bac_si bs ON yc.bac_si_id = bs.id
        LEFT JOIN dich_vu dv ON yc.dich_vu_id = dv.id
        WHERE yc.id = ?
    ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function layBacSiDangLamKemBacSiCu($bacSiId)
    {
        $sql = "SELECT * FROM bac_si
            WHERE trang_thai = 'dang_lam'
               OR id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id' => (int) $bacSiId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function layTatCaDichVu()
    {
        $sql = "SELECT * FROM dich_vu WHERE trang_thai = 1";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }



    public function updateLH($id, $data)
    {
        // Ghép ngày + giờ bắt đầu
        $startDateTime = new DateTime(
            $data['ngay_mong_muon'] . ' ' . $data['gio_bat_dau']
        );

        // Tính giờ kết thúc = +2 tiếng
        $endDateTime = clone $startDateTime;
        $endDateTime->modify('+1 hours');

        $gio_bat_dau = $startDateTime->format('H:i:s');
        $gio_ket_thuc = $endDateTime->format('H:i:s');

        $sql = "UPDATE yeu_cau_dat_lich SET
        ho_ten = ?,
        so_dien_thoai = ?,
        dich_vu_id = ?,
        bac_si_id = ?,
        ngay_mong_muon = ?,
        gio_bat_dau = ?,
        gio_ket_thuc = ?,
        mo_ta_trieu_chung = ?,
        trang_thai = ?
        WHERE id = ?";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            $data['ho_ten'],
            $data['so_dien_thoai'],
            $data['dich_vu_id'],
            $data['bac_si_id'],
            $data['ngay_mong_muon'],
            $gio_bat_dau,
            $gio_ket_thuc,
            $data['mo_ta_trieu_chung'],
            $data['trang_thai'],
            $id
        ]);
    }
    // Check trùng lịch hẹn
    public function checkLichHenExists($ngayHen, $bacSiId)
    {
        $sql = "SELECT id FROM lich_hen WHERE ngay_hen = ? AND bac_si_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$ngayHen, $bacSiId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function insertLichHenFromYeuCau($yeuCau, $hoSoBenhNhanId)
    {
        // Ghép ngày + giờ bắt đầu
        $startDateTime = new DateTime(
            $yeuCau['ngay_mong_muon'] . ' ' . $yeuCau['gio_bat_dau']
        );

        // Cộng thêm 2 tiếng
        $endDateTime = clone $startDateTime;
        $endDateTime->modify('+1 hours');

        // Lấy lại đúng format cho DB
        $gio_bat_dau = $startDateTime->format('H:i:s');
        $gio_ket_thuc = $endDateTime->format('H:i:s');

        $sql = "
        INSERT INTO lich_hen
        (yeu_cau_dat_lich_id, ho_ten, so_dien_thoai, ho_so_benh_nhan_id, bac_si_id, dich_vu_id, ngay_hen, gio_bat_dau, gio_ket_thuc, trang_thai, ghi_chu, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            $yeuCau['id'],
            $yeuCau['ho_ten'],
            $yeuCau['so_dien_thoai'],
            $hoSoBenhNhanId,
            $yeuCau['bac_si_id'],
            $yeuCau['dich_vu_id'],
            $yeuCau['ngay_mong_muon'],
            $gio_bat_dau,
            $gio_ket_thuc,
            'cho_kham',
            'Tạo từ yêu cầu đặt lịch'
        ]);
    }
    public function updateYeuCauDatLich($id, $data)
    {
        // Ghép ngày + giờ bắt đầu
        $startDateTime = new DateTime(
            $data['ngay_mong_muon'] . ' ' . $data['gio_bat_dau']
        );

        // Giờ kết thúc = +1 tiếng
        $endDateTime = clone $startDateTime;
        $endDateTime->modify('+1 hour');

        $gio_bat_dau = $startDateTime->format('H:i:s');
        $gio_ket_thuc = $endDateTime->format('H:i:s');

        $sql = "UPDATE yeu_cau_dat_lich 
        SET ho_ten = ?, 
            sdt = ?, 
            email = ?, 
            dich_vu_id = ?, 
            bac_si_id = ?, 
            ngay_mong_muon = ?, 
            gio_bat_dau = ?, 
            gio_ket_thuc = ?, 
            mo_ta_trieu_chung = ?, 
            trang_thai = ?
        WHERE id = ?";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            $data['ho_ten'],
            $data['sdt'],
            $data['email'],
            $data['dich_vu_id'],
            $data['bac_si_id'],
            $data['ngay_mong_muon'],
            $gio_bat_dau,
            $gio_ket_thuc,
            $data['mo_ta_trieu_chung'],
            $data['trang_thai'],
            $id
        ]);
    }
    public function findHoSoByPhone($phone)
    {
        $sql = "SELECT * FROM ho_so_benh_nhan WHERE so_dien_thoai = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$phone]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function checkTrungLichBacSi($bacSiId, $ngayHen, $gioBatDau, $excludeId = null)
    {
        $sql = "SELECT id FROM lich_hen
                WHERE bac_si_id = ?
                AND ngay_hen   = ?
                AND gio_bat_dau = ?
                AND trang_thai IN ('cho_kham', 'da_xac_nhan', 'dang_kham')";

        $params = [$bacSiId, $ngayHen, $gioBatDau];

        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function layThongTinLichHen()
    {
        $sql = "
      SELECT 
    lh.id,
    lh.ngay_hen,
    lh.gio_bat_dau,
    lh.gio_ket_thuc,
    lh.loai_dat,
    lh.trang_thai,

    -- nếu là trực tiếp thì dùng lh.ho_ten
    -- nếu là online thì dùng ycdl.ho_ten
    CASE 
        WHEN lh.loai_dat = 'online' THEN ycdl.ho_ten
        ELSE lh.ho_ten
    END AS ho_ten,

    CASE 
        WHEN lh.loai_dat = 'online' THEN ycdl.so_dien_thoai
        ELSE lh.so_dien_thoai
    END AS so_dien_thoai,

    bs.ten_bac_si,
    dv.ten_dich_vu

FROM lich_hen lh

LEFT JOIN yeu_cau_dat_lich ycdl 
    ON lh.yeu_cau_dat_lich_id = ycdl.id

LEFT JOIN bac_si bs 
    ON lh.bac_si_id = bs.id

LEFT JOIN dich_vu dv 
    ON lh.dich_vu_id = dv.id

ORDER BY lh.ngay_hen DESC, lh.gio_bat_dau DESC
    ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
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
            $number = (int) substr($lastId, 4); // HSBN001 -> 001
            $newNumber = $number + 1;
        } else {
            $newNumber = 1;
        }

        return 'HSBN' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
    public function ganHoSoVaoLichHen($hoSoId, $lichHenId)
    {
        $sql = "UPDATE lich_hen 
        SET ho_so_benh_nhan_id = ?, trang_thai = 'da_tiep_nhan'
        WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$hoSoId, $lichHenId]);
    }
    public function taoLichKhamTuLichHen($lichHenId)
    {
        // Lấy dữ liệu từ lịch hẹn
        $sql = "SELECT 
                lh.id AS lich_hen_id,
                lh.bac_si_id,
                lh.dich_vu_id,
                lh.ngay_hen,
                hs.id AS ho_so_benh_nhan_id,
                hs.ho_ten AS ten_benh_nhan,
                hs.so_dien_thoai AS sdt
            FROM lich_hen lh
            JOIN ho_so_benh_nhan hs ON lh.ho_so_benh_nhan_id = hs.id
            WHERE lh.id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$lichHenId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row)
            return false;

        $sqlInsert = "INSERT INTO lich_kham (
                    lich_hen_id,
                    ho_so_benh_nhan_id,
                    bac_si_id,
                    dich_vu_id,
                    ten_benh_nhan,
                    sdt,
                    ngay_kham,
                    ghi_chu,
                    trang_thai,
                    ngay_tao
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt2 = $this->conn->prepare($sqlInsert);

        return $stmt2->execute([
            $row['lich_hen_id'],
            $row['ho_so_benh_nhan_id'],
            $row['bac_si_id'],
            $row['dich_vu_id'],
            $row['ten_benh_nhan'],
            $row['sdt'],
            $row['ngay_hen'],          // map từ lich_hen.ngay_hen -> lich_kham.ngay_kham
            null,                      // ghi_chu (nếu chưa có)
            'cho_kham'                 // hoặc 1 nếu bạn dùng INT
        ]);
    }





    public function getLichHenById($id)
    {
        $sql = "SELECT 
                lh.id,
                lh.ho_ten,
                lh.so_dien_thoai,
                lh.ngay_hen,
                lh.gio_bat_dau,
                lh.gio_ket_thuc,
                lh.bac_si_id,
                lh.dich_vu_id
            FROM lich_hen lh
            WHERE lh.id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // add lịch khám
    public function daTonTaiLichKham($lichHenId)
    {
        $sql = "SELECT id FROM lich_kham WHERE lich_hen_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$lichHenId]);
        return $stmt->fetch();
    }

    public function insertLichKham($data)
    {
        $sql = "INSERT INTO lich_kham 
    (lich_hen_id, ho_so_benh_nhan_id, bac_si_id, dich_vu_id, 
     ngay_kham, gio_bat_dau, gio_ket_thuc, ghi_chu, trang_thai)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function getLichKhamByBacSi($bacSiId)
    {
        $sql = "SELECT 
                lk.id,
                lk.ngay_kham,
                lk.trang_thai,
                lk.ghi_chu,
                hs.ho_ten AS ten_benh_nhan,
                hs.so_dien_thoai,
                dv.ten_dich_vu
            FROM lich_kham lk
            JOIN ho_so_benh_nhan hs ON lk.ho_so_benh_nhan_id = hs.id
            LEFT JOIN dich_vu dv ON lk.dich_vu_id = dv.id
            WHERE lk.bac_si_id = ?
            ORDER BY lk.ngay_kham ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$bacSiId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAllLichKham()
    {
        $sql = "SELECT 
            lk.id,
            lk.ngay_kham,
            lk.gio_bat_dau,
            lk.gio_ket_thuc,
            lk.trang_thai,
            lk.ghi_chu,
            hs.ho_ten AS ten_benh_nhan,
            hs.so_dien_thoai,
            dv.ten_dich_vu,
            bs.ten_bac_si
        FROM lich_kham lk
        JOIN ho_so_benh_nhan hs ON lk.ho_so_benh_nhan_id = hs.id
        LEFT JOIN dich_vu dv ON lk.dich_vu_id = dv.id
        LEFT JOIN bac_si bs ON lk.bac_si_id = bs.id
        ORDER BY lk.ngay_kham ASC, lk.gio_bat_dau ASC";

        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }


    public function capNhatTrangThaiLichKham($id, $trangThai)
    {
        $sql = "UPDATE lich_kham SET trang_thai = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$trangThai, $id]);
    }

    public function capNhatTrangThaiLichHen($id, $trangThai)
    {
        $sql = "UPDATE lich_hen SET trang_thai = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$trangThai, $id]);
    }

    public function getLichKhamById2($id)
    {
        $sql = "SELECT * FROM lich_kham WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getLichKhamByLichHenId($lichHenId)
    {
        $sql = "SELECT * FROM lich_kham WHERE lich_hen_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$lichHenId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getLichKhamChiTietById($id)
    {
        $sql = "SELECT 
                lk.id AS lich_kham_id,
                lk.ngay_kham,
                lk.trang_thai AS trang_thai_kham,
                lk.ghi_chu,

                hs.id AS ho_so_id,
                hs.ho_ten,
                hs.so_dien_thoai,
                hs.ngay_sinh,
                hs.gioi_tinh,
                hs.cmnd_cccd,
                hs.dia_chi,
                hs.tien_su_benh,
                hs.bao_hiem_y_te,

                hs.nguoi_lien_he_khan_cap,
                hs.sdt_nguoi_lien_he,
                hs.quan_he,
                lh.id AS lich_hen_id,
                lh.ngay_hen,

                dv.id AS dich_vu_id,
                dv.ten_dich_vu
            FROM lich_kham lk
            JOIN ho_so_benh_nhan hs ON lk.ho_so_benh_nhan_id = hs.id
            LEFT JOIN lich_hen lh ON lk.lich_hen_id = lh.id
            LEFT JOIN dich_vu dv ON lh.dich_vu_id = dv.id
            WHERE lk.id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getAllThuocConHang()
    {
        $sql = "SELECT id, ten_thuoc, ham_luong, gia_nhap, so_luong 
            FROM thuoc 
            WHERE so_luong > 0";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Check giờ đã đặt
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
    // thêm mới
    public function insertLichHenTrucTiep($data)
    {
        $sql = "INSERT INTO lich_hen 
            (yeu_cau_dat_lich_id, ho_ten, so_dien_thoai, bac_si_id, dich_vu_id,
             ngay_hen, gio_bat_dau, gio_ket_thuc, loai_dat, trang_thai, ghi_chu, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ADDTIME(?, '02:00:00'), ?, ?, ?, NOW())";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            $data['yeu_cau_dat_lich_id'],
            $data['ho_ten'],
            $data['so_dien_thoai'],
            $data['bac_si_id'],
            $data['dich_vu_id'],
            $data['ngay_hen'],
            $data['gio_bat_dau'],
            $data['gio_bat_dau'], // dùng để +2 tiếng
            $data['loai_dat'],
            $data['trang_thai'],
            $data['ghi_chu']
        ]);
    }
    // End <!-- Coder Vi Đức Được    FB/duocXdangiu    Zalo/0389232813    Github/coderDuoc --> /

    //==========Của Quang nè===================

    public function getAll_dich_vu()
    {
        $sql = "SELECT dv.*, 
                (SELECT COALESCE(SUM(vt.gia_nhap * dvvt.so_luong), 0) 
                 FROM dich_vu_vat_tu dvvt 
                 JOIN vat_tu vt ON dvvt.id_vat_tu = vt.id 
                 WHERE dvvt.id_dich_vu = dv.id) as tong_tien_vat_tu
                FROM dich_vu dv";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm dịch vụ

    public function insertDichVu($ten_dich_vu, $danhmuc, $gia, $mo_ta, $image = '', $id_loai = null)
    {
        // Nếu không pass `id_loai` từ form, dùng mặc định 1 để tránh lỗi NOT NULL
        if ($id_loai === null) {
            $id_loai = 1;
        }

        $sql = "INSERT INTO dich_vu(ten_dich_vu, id_loai, danhmuc, gia, mo_ta, image) VALUES(?,?,?,?,?,?)";
        $params = [$ten_dich_vu, $id_loai, $danhmuc, $gia, $mo_ta, $image];

        $stmt = $this->conn->prepare($sql);
        $ok = $stmt->execute($params);

        if ($ok) {
            return $this->conn->lastInsertId(); // ID dịch vụ mới
        }
        return false;
    }

    public function getDichVuById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM dich_vu WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ================= VẬT TƯ =================

    public function getAllVatTu()
    {
        $stmt = $this->conn->query("SELECT * FROM vat_tu WHERE danh_muc = 'tieu hao' AND type = 0");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm vật tư vào dịch vụ (không trùng)
    public function addVatTuToDichVu($id_dich_vu, $id_vat_tu, $so_luong)
    {
        $check = $this->conn->prepare("
            SELECT id FROM dich_vu_vat_tu 
            WHERE id_dich_vu = ? AND id_vat_tu = ?
        ");
        $check->execute([$id_dich_vu, $id_vat_tu]);

        if ($check->fetch()) {
            $sql = "UPDATE dich_vu_vat_tu 
                    SET so_luong = so_luong + ?
                    WHERE id_dich_vu = ? AND id_vat_tu = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$so_luong, $id_dich_vu, $id_vat_tu]);
        } else {
            $sql = "INSERT INTO dich_vu_vat_tu(id_dich_vu, id_vat_tu, so_luong)
                    VALUES(?,?,?)";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$id_dich_vu, $id_vat_tu, $so_luong]);
        }
    }

    // Lấy vật tư theo dịch vụ
    public function getVatTuByDichVu($id)
    {
        $sql = "SELECT vt.*, dvvt.so_luong
                FROM dich_vu_vat_tu dvvt
                JOIN vat_tu vt ON vt.id = dvvt.id_vat_tu
                WHERE dvvt.id_dich_vu = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteAllVatTuByDichVu($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM dich_vu_vat_tu WHERE id_dich_vu=?");
        return $stmt->execute([$id]);
    }

    // ================= SỬA DỊCH VỤ =================
    public function updateDichVu($id, $ten, $danhmuc, $gia, $mo_ta, $image, $id_loai = null)
    {
        // Only update id_loai when a value is provided to avoid unintentionally clearing it.
        if ($id_loai !== null) {
            $sql = "UPDATE dich_vu 
                SET ten_dich_vu = ?, id_loai = ?, danhmuc = ?, gia = ?, mo_ta = ?, image = ?
                WHERE id = ?";
            $params = [$ten, $id_loai, $danhmuc, $gia, $mo_ta, $image, $id];
        } else {
            $sql = "UPDATE dich_vu 
                SET ten_dich_vu = ?, danhmuc = ?, gia = ?, mo_ta = ?, image = ?
                WHERE id = ?";
            $params = [$ten, $danhmuc, $gia, $mo_ta, $image, $id];
        }
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }
    public function getHinhAnhDichVuById($id)
    {
        $sql = "SELECT image FROM dich_vu WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetchColumn();
    }



    // ================= SET VẬT TƯ CHO DỊCH VỤ =================
    public function setVatTuForDichVu($id_dich_vu, $vatTuArr)
    {

        // Xóa vật tư cũ
        $this->deleteAllVatTuByDichVu($id_dich_vu);

        // Thêm lại vật tư mới
        foreach ($vatTuArr as $id_vat_tu => $so_luong) {
            $so_luong = (int) $so_luong;
            if ($so_luong > 0) {
                $this->addVatTuToDichVu($id_dich_vu, $id_vat_tu, $so_luong);
            }
        }
    }
    //======================tràng thái dịch vụ======================
    public function updateTrangThaiDichVu($id, $trangthai)
    {
        $sql = "UPDATE dich_vu SET trang_thai = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$trangthai, $id]);
    }

    // ================= TÍNH TỔNG TIỀN VẬT TƯ =================
    public function getTongTienVatTuByDichVu($id)
    {
        $sql = "SELECT SUM(vt.gia_nhap * dvvt.so_luong) 
                FROM dich_vu_vat_tu dvvt 
                JOIN vat_tu vt ON dvvt.id_vat_tu = vt.id 
                WHERE dvvt.id_dich_vu = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return (int) $stmt->fetchColumn();
    }

    public function updateGiaDichVu($id, $gia)
    {
        $sql = "UPDATE dich_vu SET gia = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$gia, $id]);
    }

    //=============Hết cuả Quang rồi nè=======

    //=============Vinh=======================
    public function getBacSiByID($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM bac_si WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function themBacSi($ten_bac_si, $sdt, $email, $chuyen_mon, $gioi_tinh, $ca_lam, $trang_thai, $photo_url = null)
    {
        try {
            // Chuẩn hóa dữ liệu
            $ten_bac_si = trim($ten_bac_si);
            $sdt = trim($sdt);
            $email = trim($email);
            $chuyen_mon = trim($chuyen_mon);
            $gioi_tinh = trim($gioi_tinh);
            $ca_lam = trim($ca_lam);
            $trang_thai = trim($trang_thai);
            $photo_url = $photo_url ? trim($photo_url) : null;

            // Thời gian tạo
            $now = date('Y-m-d H:i:s');

            // Thêm bản ghi vào DB
            $sql = "INSERT INTO bac_si
                    (ten_bac_si, sdt, email, chuyen_mon, gioi_tinh, ca_lam, trang_thai, ngay_tao, photo_url)
                    VALUES
                    (:ten_bac_si, :sdt, :email, :chuyen_mon, :gioi_tinh, :ca_lam, :trang_thai, :ngay_tao, :photo_url)";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindValue(':ten_bac_si', $ten_bac_si);
            $stmt->bindValue(':sdt', $sdt);
            $stmt->bindValue(':email', $email !== '' ? $email : null);
            $stmt->bindValue(':chuyen_mon', $chuyen_mon !== '' ? $chuyen_mon : null);
            $stmt->bindValue(':gioi_tinh', $gioi_tinh !== '' ? $gioi_tinh : null);
            $stmt->bindValue(':ca_lam', $ca_lam !== '' ? $ca_lam : null);
            $stmt->bindValue(':trang_thai', $trang_thai !== '' ? $trang_thai : null);
            $stmt->bindValue(':ngay_tao', $now);
            $stmt->bindValue(':photo_url', $photo_url);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('themBacSi error: ' . $e->getMessage());
            return false;
        }
    }
    public function kiemTraTrungSDTBacSi($sdt, $id = 0)
    {
        $sql = "SELECT id FROM bac_si WHERE sdt = ? AND id != ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$sdt, $id]);
        return $stmt->fetch(); // trả về row nếu có, false/null nếu không
    }

    public function kiemTraTrungEmailBacSi($email, $id = 0)
    {
        $sql = "SELECT id FROM bac_si WHERE email = ? AND id != ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email, $id]);
        return $stmt->fetch();
    }
    public function updateBacSi($id, array $data)
    {
        // Chuẩn hóa dữ liệu
        $ten = isset($data['ten_bac_si']) ? trim($data['ten_bac_si']) : null;
        $sdt = isset($data['sdt']) ? trim($data['sdt']) : null;
        $email = isset($data['email']) && $data['email'] !== '' ? trim($data['email']) : null;
        $chuyen_mon = isset($data['chuyen_mon']) && $data['chuyen_mon'] !== '' ? trim($data['chuyen_mon']) : null;
        $gioi_tinh = isset($data['gioi_tinh']) && $data['gioi_tinh'] !== '' ? trim($data['gioi_tinh']) : null;
        $ca_lam = isset($data['ca_lam']) && $data['ca_lam'] !== '' ? trim($data['ca_lam']) : null;
        $trang_thai = isset($data['trang_thai']) && $data['trang_thai'] !== '' ? trim($data['trang_thai']) : null;
        $photo_url = isset($data['photo_url']) && $data['photo_url'] !== '' ? trim($data['photo_url']) : null;

        $sql = "UPDATE bac_si
                SET ten_bac_si = ?, sdt = ?, email = ?, chuyen_mon = ?, gioi_tinh = ?, ca_lam = ?, trang_thai = ?, photo_url = ?
                WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $ok = $stmt->execute([
            $ten,
            $sdt,
            $email,
            $chuyen_mon,
            $gioi_tinh,
            $ca_lam,
            $trang_thai,
            $photo_url,
            (int) $id
        ]);

        return $ok;
    }
    public function deleteBacSi($id)
    {
        try {
            $id = (int) $id;
            if ($id <= 0) {
                return false;
            }

            $sql = "DELETE FROM bac_si WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log('deleteBacSi error: ' . $e->getMessage());
            return false;
        }
    }
    // Lấy bác sĩ theo id
    public function layBacSiById($id)
    {
        $sql = "SELECT * FROM bac_si WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([(int) $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Kiểm tra bác sĩ có lịch hẹn tương lai hay không
    // Trả về true nếu có ít nhất 1 lịch hẹn với ngay_hen > NOW()
    public function kiemTraLichHenTuongLaiTheoBacSi($bacSiId)
    {
        $sql = "SELECT 1 FROM lich_hen WHERE bac_si_id = ? AND ngay_hen > NOW() LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([(int) $bacSiId]);
        return (bool) $stmt->fetchColumn();
    }

    // (Tùy chọn) Kiểm tra có bất kỳ lịch hẹn nào (không phân biệt thời gian)
    // Trả về true nếu có
    public function kiemTraLichHenTheoBacSi($bacSiId)
    {
        $sql = "SELECT 1 FROM lich_hen WHERE bac_si_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([(int) $bacSiId]);
        return (bool) $stmt->fetchColumn();
    }

    // Cập nhật trạng thái bác sĩ
    public function updateTrangThaiBacSi($id, $trang_thai)
    {
        try {
            $sql = "UPDATE bac_si SET trang_thai = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([trim($trang_thai), (int) $id]);
        } catch (PDOException $e) {
            error_log('updateTrangThaiBacSi error: ' . $e->getMessage());
            return false;
        }
    }
    //=============Hết Vinh==================

    // ========== Mạnh đẹp zai ==========
    public function saveResultEx($lich_kham_id, $danh_sach_dv, $chan_doan, $don_thuoc)
    {
        try {
            $this->conn->beginTransaction();

            // 1. Insert lịch sử khám
            $sql = "INSERT INTO lich_su_kham
                (ho_so_benh_nhan_id, lich_kham_id, bac_si_id, chan_doan, huong_dieu_tri, ghi_chu, ngay_kham)
                SELECT 
                    lk.ho_so_benh_nhan_id,
                    lk.id,
                    lk.bac_si_id,
                    :chan_doan,
                    null,
                    NULL, 
                    NOW()
                FROM lich_kham lk
                WHERE lk.id = :lich_kham_id";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':chan_doan' => $chan_doan,
                ':lich_kham_id' => $lich_kham_id
            ]);

            $lich_su_kham_id = $this->conn->lastInsertId();


            // 2. Thêm dịch vụ cho lần khám
            if (!empty($danh_sach_dv)) {

                $sqlInsertDV = "INSERT INTO ct_ls_kham
                            (ls_kham_id, ten_dich_vu, dich_vu_id)
                            VALUES
                            (:ls_kham_id, :ten_dich_vu, :dich_vu_id)";

                $stmtInsertDV = $this->conn->prepare($sqlInsertDV);

                foreach ($danh_sach_dv as $dv) {
                    $stmtInsertDV->execute([
                        ':ls_kham_id' => $lich_su_kham_id,
                        ':ten_dich_vu' => $dv['ten_dich_vu'],
                        ':dich_vu_id' => $dv['dich_vu_id']
                    ]);
                }
            }


            // 3. Lấy thông tin bệnh nhân + bác sĩ
            $sqlLich = "SELECT ho_so_benh_nhan_id, bac_si_id 
                    FROM lich_kham 
                    WHERE id = :lich_kham_id";

            $stmtLich = $this->conn->prepare($sqlLich);
            $stmtLich->execute([':lich_kham_id' => $lich_kham_id]);

            $info = $stmtLich->fetch(PDO::FETCH_ASSOC);

            if (!$info) {
                throw new Exception("Không tìm thấy lịch khám.");
            }


            // 🔥 4. CẬP NHẬT TIỀN SỬ BỆNH (PHẦN TAO THÊM CHO M)
            $sqlUpdateTienSu = "UPDATE ho_so_benh_nhan 
                            SET tien_su_benh = 
                                CASE 
                                    WHEN tien_su_benh IS NULL OR tien_su_benh = '' 
                                    THEN :chan_doan
                                    ELSE CONCAT(tien_su_benh, ', ', :chan_doan)
                                END
                            WHERE id = :ho_so_benh_nhan_id";

            $stmtUpdateTienSu = $this->conn->prepare($sqlUpdateTienSu);
            $stmtUpdateTienSu->execute([
                ':chan_doan' => $chan_doan,
                ':ho_so_benh_nhan_id' => $info['ho_so_benh_nhan_id']
            ]);


            // 5. Kiểm tra đã có đơn thuốc chưa
            $checkSql = "SELECT ma_don_thuoc 
                     FROM don_thuoc 
                     WHERE lich_kham_id = :lich_kham_id";

            $stmtCheck = $this->conn->prepare($checkSql);
            $stmtCheck->execute([':lich_kham_id' => $lich_kham_id]);

            if ($stmtCheck->fetch()) {
                throw new Exception("Lịch khám đã có đơn thuốc.");
            }


            // 6. Insert đơn thuốc
            $sqlInsertDon = "INSERT INTO don_thuoc
                        (ho_so_benh_nhan_id, lich_kham_id, lich_su_kham_id, bac_si_id, chan_doan)
                        VALUES
                        (:ho_so_benh_nhan_id, :lich_kham_id, :lich_su_kham_id, :bac_si_id, :chan_doan)";

            $stmtInsertDon = $this->conn->prepare($sqlInsertDon);
            $stmtInsertDon->execute([
                ':ho_so_benh_nhan_id' => $info['ho_so_benh_nhan_id'],
                ':lich_kham_id' => $lich_kham_id,
                ':lich_su_kham_id' => $lich_su_kham_id,
                ':bac_si_id' => $info['bac_si_id'],
                ':chan_doan' => $chan_doan
            ]);

            $ma_don_thuoc = $this->conn->lastInsertId();


            // 7. Insert chi tiết đơn thuốc + trừ kho
            $sqlInsertCT = "INSERT INTO chi_tiet_don_thuoc
                        (don_thuoc_id, thuoc_id, lieu_dung, thoi_diem_uong, so_luong)
                        VALUES
                        (:don_thuoc_id, :thuoc_id, :lieu_dung, :thoi_diem_uong, :so_luong)";

            $stmtInsertCT = $this->conn->prepare($sqlInsertCT);

            $sqlUpdateThuoc = "UPDATE thuoc
                           SET so_luong = so_luong - :so_luong
                           WHERE id = :thuoc_id";

            $stmtUpdate = $this->conn->prepare($sqlUpdateThuoc);

            foreach ($don_thuoc as $thuoc) {

                $stmtInsertCT->execute([
                    ':don_thuoc_id' => $ma_don_thuoc,
                    ':thuoc_id' => $thuoc['thuoc_id'],
                    ':lieu_dung' => $thuoc['lieu_dung'],
                    ':thoi_diem_uong' => $thuoc['thoi_diem_uong'],
                    ':so_luong' => $thuoc['so_luong']
                ]);

                $stmtUpdate->execute([
                    ':so_luong' => $thuoc['so_luong'],
                    ':thuoc_id' => $thuoc['thuoc_id']
                ]);
            }


            // 8. Update trạng thái lịch khám
            $sqlStatus = "UPDATE lich_kham 
                      SET trang_thai = 'da_kham' 
                      WHERE id = :lich_kham_id";

            $stmtStatus = $this->conn->prepare($sqlStatus);
            $stmtStatus->execute([':lich_kham_id' => $lich_kham_id]);


            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            die($e->getMessage());
        }
    }

    public function getThuocByID($id)
    {
        $sql = "SELECT * FROM thuoc WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateTrangThaiLichKham($id, $trang_thai)
    {
        $sql = "UPDATE lich_kham SET trang_thai = :trang_thai WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':trang_thai' => $trang_thai,
            ':id' => $id
        ]);
    }

    public function getMedicalHistoryByHoSoId($id)
    {
        $sql = "SELECT lsk.*, bs.ten_bac_si
            FROM lich_su_kham lsk
            JOIN lich_kham lk 
                ON lk.ho_so_benh_nhan_id = lsk.ho_so_benh_nhan_id
            LEFT JOIN bac_si bs
                ON bs.id = lsk.bac_si_id
            WHERE lk.id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLichSuKhamByHoSoId($hoSoId)
    {
        $sql = "SELECT 
                    lsk.id,
                    lsk.ngay_kham,
                    lsk.chan_doan,
                    lsk.huong_dieu_tri,
                    lsk.ghi_chu,
                    bs.ten_bac_si,
                    GROUP_CONCAT(DISTINCT ct.ten_dich_vu ORDER BY ct.ten_dich_vu SEPARATOR ', ') AS danh_sach_dich_vu
                FROM lich_su_kham lsk
                LEFT JOIN bac_si bs ON bs.id = lsk.bac_si_id
                LEFT JOIN ct_ls_kham ct ON ct.ls_kham_id = lsk.id
                WHERE lsk.ho_so_benh_nhan_id = ?
                GROUP BY 
                    lsk.id,
                    lsk.ngay_kham,
                    lsk.chan_doan,
                    lsk.huong_dieu_tri,
                    lsk.ghi_chu,
                    bs.ten_bac_si
                ORDER BY lsk.ngay_kham DESC, lsk.id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$hoSoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // lấy toàn bộ lịch sử khám của bệnh nhân
    public function getLichSuKham()
    {
        $sql = "SELECT * FROM lich_su_kham";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getChiTietLichSuKhamById($ho_so_id)
    {
        $sql = "SELECT 
    lsk.ho_so_benh_nhan_id,
    bs.ten_bac_si,
    lsk.chan_doan,
    hsbn.tien_su_benh,
    hsbn.ho_ten,
    hsbn.bao_hiem_y_te,
    GROUP_CONCAT(ct.ten_dich_vu SEPARATOR ', ') AS danh_sach_dich_vu

FROM lich_su_kham lsk

LEFT JOIN bac_si bs 
    ON bs.id = lsk.bac_si_id

LEFT JOIN ho_so_benh_nhan hsbn 
    ON hsbn.id = lsk.ho_so_benh_nhan_id

LEFT JOIN ct_ls_kham ct 
    ON ct.ls_kham_id = lsk.id

WHERE lsk.id = :id

GROUP BY lsk.id;";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $ho_so_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // search lịch sử khám
    public function searchLichSuKham($keyword)
    {

        $sql = "SELECT * FROM lich_su_kham 
            WHERE id LIKE :keyword
            OR ho_so_benh_nhan_id LIKE :keyword
            OR chan_doan LIKE :keyword
            OR huong_dieu_tri LIKE :keyword
            OR ghi_chu LIKE :keyword
            OR ngay_kham LIKE :keyword";

        // Nếu không phải admin thì chỉ được xem của mình
        if ($_SESSION['admin']['role'] !== 'admin') {
            $sql .= " AND user_id = :user_id";
        }

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':keyword', "%$keyword%");

        if ($_SESSION['admin']['role'] !== 'admin') {
            $stmt->bindValue(':user_id', $_SESSION['admin']['id']);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Hết Mạnh đẹp zai

    public function getPatientPrescriptionsForAI($hoSoId)
    {
        $sql = "SELECT
                    dt.ngay_ke_don,
                    dt.chan_doan,
                    GROUP_CONCAT(
                        CONCAT(t.ten_thuoc, ' (SL: ', ct.so_luong, ', liều: ', ct.lieu_dung, ')')
                        SEPARATOR '; '
                    ) AS thuoc_list
                FROM don_thuoc dt
                JOIN chi_tiet_don_thuoc ct ON ct.don_thuoc_id = dt.ma_don_thuoc
                JOIN thuoc t ON t.id = ct.thuoc_id
                WHERE dt.ho_so_benh_nhan_id = ?
                GROUP BY dt.ma_don_thuoc, dt.ngay_ke_don, dt.chan_doan
                ORDER BY dt.ngay_ke_don DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$hoSoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================================================================
    // CÁC HÀM DÀNH CHO POS (BÁN HÀNG TẠI QUẦY)
    // =========================================================================

    public function getProductsForPos($includeLocked = false)
    {
        $sql = "
            SELECT *, DATEDIFF(han_su_dung, CURDATE()) AS days_left
            FROM vat_tu
            WHERE type = 1
        ";

        if (!$includeLocked) {
            $sql .= " AND trang_thai_su_dung = 1";
        }

        $sql .= " ORDER BY ten_vat_tu ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMedicinesForPos()
    {
        $sql = "
            SELECT id, ten_thuoc, ham_luong, don_vi_tinh, gia_nhap, so_luong
            FROM thuoc
            WHERE trang_thai_su_dung = 1
            ORDER BY ten_thuoc ASC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateProductSaleStatus($id, $status)
    {
        $stmt = $this->conn->prepare("UPDATE vat_tu SET trang_thai_su_dung = ? WHERE id = ? AND type = 1");
        return $stmt->execute([(int) $status, (int) $id]);
    }

    public function updateMedicineQuantity($id, $qty)
    {
        $sql = "UPDATE thuoc SET so_luong = so_luong - ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([(int) $qty, (int) $id]);
    }

    public function searchPatientsForPos($keyword)
    {
        $sql = "SELECT id, ho_ten, so_dien_thoai FROM ho_so_benh_nhan 
                WHERE (ho_ten LIKE ? OR so_dien_thoai LIKE ?) AND trang_thai=1
                LIMIT 10";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["%$keyword%", "%$keyword%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addPatientQuickForPos($ho_ten, $so_dien_thoai)
    {
        $newId = $this->generateHoSoBenhNhanId();
        $sql = "INSERT INTO ho_so_benh_nhan
                (id, ho_ten, so_dien_thoai, trang_thai, ngay_tao)
                VALUES (?, ?, ?, 1, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$newId, $ho_ten, $so_dien_thoai]);
        return $newId;
    }

    public function getAppointmentsForPos()
    {
        // Lấy danh sách khám hôm nay để POS kéo bill
        $sql = "SELECT lk.id, lk.ngay_kham, hs.ho_ten, hs.so_dien_thoai, lk.ho_so_benh_nhan_id 
                FROM lich_kham lk
                JOIN ho_so_benh_nhan hs ON lk.ho_so_benh_nhan_id = hs.id
                WHERE DATE(lk.ngay_kham) = CURDATE() AND lk.trang_thai = 'da_kham'";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
