# MediTrust - Hệ Thống Quản Lý Phòng Khám Nha Khoa

## Tổng Quan

MediTrust là một hệ thống quản lý phòng khám nha khoa toàn diện, được xây dựng bằng PHP thuần và MySQL. Hệ thống bao gồm hai phần chính: cổng thông tin dành cho bệnh nhân (frontend) và bảng điều khiển quản trị (backend), tích hợp AI để tư vấn sức khỏe và quản lý vật tư.

---

## Công Nghệ Sử Dụng

| Thành phần | Công nghệ |
|---|---|
| Backend | PHP 7.4+, PDO |
| Cơ sở dữ liệu | MySQL/MariaDB |
| Frontend | HTML5, CSS3, Bootstrap 5.3 |
| JavaScript | ES6+, Chart.js, AOS.js, GLightbox, Isotope |
| Email | PHPMailer 7.0 (Gmail SMTP) |
| PDF/QR | DOMPDF 3.1, phpqrcode |
| AI | Google Gemini API, Groq API |
| Icon | Bootstrap Icons, Font Awesome |

---

## Cấu Trúc Thư Mục

```
meditrust/
├── index.php               # Router frontend
├── admin.php               # Router backend/admin
├── router.php              # Lớp routing URL
├── config/
│   └── ai.php              # Cấu hình API key AI
├── core/
│   └── Gemini.php          # Service tích hợp AI (OpenAI-compatible)
├── frontend/
│   ├── controller/
│   │   └── frontendController.php
│   ├── model/
│   │   └── frontend-db.php
│   ├── views/              # 20+ file giao diện bệnh nhân
│   ├── forms/              # Form đặt lịch, liên hệ
│   ├── helpers/
│   │   └── MailHelper.php  # Gửi email OTP
│   └── assets/             # CSS, JS, hình ảnh
├── backend/
│   ├── controllers/
│   │   └── backendController.php  # ~2500 dòng
│   ├── models/
│   │   └── db.php          # ~2586 dòng, toàn bộ query DB
│   ├── views/              # 65+ file giao diện admin
│   └── assets/             # Theme SB Admin 2
├── libs/                   # Composer packages
│   ├── vendor/             # DOMPDF, PHPMailer, ...
│   └── phpqrcode/
├── PHPMailer/              # PHPMailer bản standalone
├── uploads/                # Ảnh tải lên (bác sĩ, dịch vụ, avatar)
└── public_ftp/             # Thư mục FTP
```

---

## Cơ Sở Dữ Liệu

**Tên database:** `quan_ly_phong_kham_nha_khoa`

### Các bảng chính

| Nhóm | Bảng | Mô tả |
|---|---|---|
| Bệnh nhân | `ho_so_benh_nhan` | Hồ sơ bệnh nhân (tên, SĐT, email, địa chỉ, tiền sử bệnh) |
| Bệnh nhân | `tai_khoan_benh_nhan` | Tài khoản đăng nhập, OTP xác thực |
| Nhân sự | `bac_si` | Thông tin bác sĩ, chuyên khoa, trạng thái |
| Nhân sự | `le_tan` | Tài khoản lễ tân, ca làm việc |
| Lịch hẹn | `yeu_cau_dat_lich` | Yêu cầu đặt lịch (trạng thái, dịch vụ, triệu chứng) |
| Lịch hẹn | `lich_hen` | Lịch hẹn đã xác nhận (ngày, giờ, bác sĩ, dịch vụ) |
| Khám bệnh | `lich_su_kham` | Lịch sử khám (chẩn đoán, ghi chú điều trị) |
| Khám bệnh | `chi_tiet_kham` | Chi tiết dịch vụ trong mỗi lần khám |
| Dịch vụ | `dich_vu` | Danh mục dịch vụ nha khoa (tên, giá, mô tả) |
| Vật tư | `vat_tu` | Kho vật tư (số lượng, hạn dùng, giá, nhà SX) |
| Vật tư | `xuat_vat_tu` | Lịch sử xuất vật tư |
| Thuốc | `thuoc` | Kho thuốc (tên, liều, số lượng, hạn dùng, giá) |
| Thuốc | `don_thuoc` / `chi_tiet_don_thuoc` | Đơn thuốc và chi tiết |
| Tài chính | `hoa_don` | Hóa đơn (bệnh nhân, bác sĩ, tổng tiền, trạng thái) |
| Tài chính | `chi_tiet_hoa_don` | Chi tiết hóa đơn (dịch vụ, thuốc) |
| Tài chính | `ma_giam_gia` | Mã voucher/giảm giá |
| Quản trị | `qly_tai_khoan` | Tài khoản nhân viên/admin |
| Quản trị | `admin_login_logs` | Lịch sử đăng nhập (IP, thời gian, user agent) |
| Tin nhắn | `tin_nhan` / `phan_hoi_tin` | Tin nhắn liên hệ và phản hồi |

---

## Tính Năng

### Dành Cho Bệnh Nhân (Frontend)
- Trang chủ, giới thiệu phòng khám, danh sách dịch vụ, bác sĩ, thư viện ảnh
- Đăng ký tài khoản với xác thực OTP qua email
- Đăng nhập, đổi mật khẩu, quản lý hồ sơ cá nhân
- Đặt lịch hẹn trực tuyến, chọn bác sĩ và dịch vụ
- Kiểm tra khung giờ còn trống theo thời gian thực
- Xem lịch sử khám, đơn thuốc, hóa đơn
- Chat AI tư vấn sức khỏe (Groq + Gemini)

### Dành Cho Quản Trị (Backend)
- **Dashboard:** Tổng số bệnh nhân, lịch hẹn, bác sĩ, doanh thu theo tháng, biểu đồ dịch vụ
- **Quản lý bệnh nhân:** CRUD hồ sơ, kiểm tra trùng SĐT/email/CCCD
- **Quản lý lịch hẹn:** Tiếp nhận yêu cầu, tạo/cập nhật lịch hẹn, nhập kết quả khám
- **Quản lý bác sĩ:** Thêm/sửa/xóa, bật/tắt trạng thái hoạt động
- **Quản lý dịch vụ:** CRUD dịch vụ, gắn vật tư, upload ảnh
- **Quản lý kho vật tư:** Theo dõi tồn kho, hạn sử dụng, cảnh báo hết hàng (≤10) / gần hết hạn (≤7 ngày)
- **Quản lý dược:** Kho thuốc, xuất thuốc, tạo đơn thuốc
- **Tài chính:** Tạo hóa đơn, quản lý mã giảm giá, báo cáo doanh thu, xuất PDF/Excel
- **Tin nhắn:** Xem và trả lời tin nhắn liên hệ từ bệnh nhân
- **Nhật ký:** Lịch sử đăng nhập admin (IP, thời gian, trạng thái)

---

## Xác Thực & Bảo Mật

| Đối tượng | Cơ chế |
|---|---|
| Bệnh nhân | Email + OTP (hết hạn sau 5 phút), `password_hash()` |
| Nhân viên/Admin | SĐT + mật khẩu, khóa tạm 10 phút sau 5 lần sai |
| Session | PHP Session, phân quyền theo vai trò (admin, bác sĩ, lễ tân, nhân viên) |
| Nhật ký | Ghi IP, timestamp, user agent mỗi lần đăng nhập |

---

## Tích Hợp AI

- **Groq API** + **Google Gemini API** — tư vấn sức khỏe cho bệnh nhân, tư vấn vật tư
- **`core/Gemini.php`** — wrapper tương thích chuẩn OpenAI
- Gợi ý đặt lịch hẹn dựa trên AI
- Phân tích dữ liệu người dùng bằng AI

---

## Cấu Hình

### Database (`backend/models/db.php`, `frontend/model/frontend-db.php`)
```
Host:     localhost
DB:       quan_ly_phong_kham_nha_khoa
User:     root
Password: (rỗng)
```

### Email (`frontend/helpers/MailHelper.php`)
```
SMTP:   smtp.gmail.com:587 (TLS)
Sender: tenmienfree26@gmail.com
```

### AI (`config/ai.php`)
```
Google Gemini API Key
Groq API Key
```

---

## Cài Đặt

1. Clone/copy project vào thư mục web (ví dụ: `laragon/www/meditrust`)
2. Tạo database MySQL và import file `quan_ly_phong_kham_nha_khoa.sql`
3. Cấu hình thông tin kết nối DB trong `backend/models/db.php` và `frontend/model/frontend-db.php`
4. Cấu hình SMTP email trong `frontend/helpers/MailHelper.php`
5. Cấu hình API key AI trong `config/ai.php`
6. Cài Composer dependencies: `cd libs && composer install`
7. Truy cập:
   - Frontend: `http://localhost/meditrust`
   - Admin: `http://localhost/meditrust/admin.php`

---

## Vai Trò Trong Hệ Thống

| Vai trò | Quyền hạn |
|---|---|
| Admin | Toàn quyền |
| Bác sĩ | Xem lịch hẹn của mình, nhập kết quả khám, kê đơn |
| Lễ tân | Tiếp nhận bệnh nhân, quản lý lịch hẹn |
| Nhân viên | Truy cập hạn chế theo phân công |
| Bệnh nhân | Đặt lịch, xem hồ sơ cá nhân |
