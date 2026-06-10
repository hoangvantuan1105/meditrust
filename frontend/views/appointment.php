<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Đặt Lịch Khám - Mẫu Trang Web MediTrust</title>
  <meta name="description" content="Đặt lịch khám online tại MediTrust - Nhanh, dễ dàng và tiện lợi">
  <meta name="keywords" content="đặt lịch khám, lịch hẹn bác sĩ, đặt lịch y tế">

  <!-- Favicons -->
  <link href="frontend/assets/img/favicon.png" rel="icon">
  <link href="frontend/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="frontend/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="frontend/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="frontend/assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="frontend/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="frontend/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="frontend/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="frontend/assets/css/main.css" rel="stylesheet">

  <!-- =======================================================
  * Tên Mẫu: MediTrust
  * URL Mẫu: https://bootstrapmade.com/meditrust-bootstrap-hospital-website-template/
  * Cập Nhật: 04 tháng 7 năm 2025 với Bootstrap v5.3.7
  * Tác Giả: BootstrapMade.com
  * Giấy Phép: https://bootstrapmade.com/license/
  ======================================================== -->
  <style>
    .sent-message,
    .error-message {
      display: none;
    }

    .sent-message {
      color: #28a745;
      /* xanh lá */
      font-weight: 600;
    }
  </style>
</head>

<body class="appointment-page">
  <!-- Trang Đặt Lịch Khám - Cho phép người dùng đặt lịch hẹn trực tuyến -->

  <main class="main">

    <!-- Tiêu Đề Trang -->
    <div class="page-title">
      <div class="breadcrumbs">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house"></i> Trang Chủ</a></li>
            <!-- <li class="breadcrumb-item"><a href="#">Danh Mục</a></li> -->
            <li class="breadcrumb-item active current">Đặt Lịch Khám</li>
          </ol>
        </nav>
      </div>

      <div class="title-wrapper">
        <h1>Đặt Lịch Khám</h1>
        <p>Đặt lịch hẹn với bác sĩ của chúng tôi một cách nhanh chóng và dễ dàng. Chúng tôi cam kết cung cấp dịch vụ y
          tế chất lượng cao với thời gian chờ đợi tối thiểu.</p>
      </div>
    </div><!-- Kết Thúc Tiêu Đề Trang -->

    <!-- Phần Đặt Lịch Khám - Cho phép người dùng đặt lịch hẹn với bác sĩ -->
    <section id="appointmnet" class="appointmnet section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">

          <!-- Appointment Info -->
          <div class="col-lg-6">
            <div class="appointment-info">
              <h3>Đặt Lịch Khám Trực Tuyến Nhanh &amp; Dễ Dàng</h3>
              <p class="mb-4">Đặt lịch hẹn của bạn chỉ trong vài bước đơn giản. Các chuyên gia y tế của chúng tôi sẵn
                sàng cung cấp dịch vụ chăm sóc y tế tốt nhất phù hợp với nhu cầu của bạn.</p>

              <!-- Danh sách các lợi ích của việc đặt lịch trực tuyến -->
              <div class="info-items">
                <div class="info-item d-flex align-items-center mb-3" data-aos="fade-up" data-aos-delay="200">
                  <div class="icon-wrapper me-3">
                    <i class="bi bi-calendar-check"></i>
                  </div>
                  <div>
                    <h5>Lịch Biểu Linh Hoạt</h5>
                    <p class="mb-0">Chọn từ các khung giờ có sẵn phù hợp với lịch bận rộn của bạn</p>
                  </div>
                </div><!-- Kết Thúc Mục Thông Tin -->

                <div class="info-item d-flex align-items-center mb-3" data-aos="fade-up" data-aos-delay="250">
                  <div class="icon-wrapper me-3">
                    <i class="bi bi-stopwatch"></i>
                  </div>
                  <div>
                    <h5>Phản Hồi Nhanh</h5>
                    <p class="mb-0">Nhận xác nhận trong vòng 15 phút sau khi gửi yêu cầu của bạn</p>
                  </div>
                </div><!-- Kết Thúc Mục Thông Tin -->

                <div class="info-item d-flex align-items-center mb-3" data-aos="fade-up" data-aos-delay="300">
                  <div class="icon-wrapper me-3">
                    <i class="bi bi-shield-check"></i>
                  </div>
                  <div>
                    <h5>Chăm Sóc Y Tế Chuyên Nghiệp</h5>
                    <p class="mb-0">Bác sĩ và chuyên gia có bằng cấp sẵn sàng phục vụ bạn</p>
                  </div>
                </div><!-- Kết Thúc Mục Thông Tin -->
              </div>

              <!-- Đường dây nóng khẩn cấp -->
              <div class="emergency-contact mt-4" data-aos="fade-up" data-aos-delay="350">
                <div class="emergency-card p-3">
                  <h6 class="mb-2"><i class="bi bi-telephone-fill me-2"></i>Đường Dây Nóng Khẩn Cấp</h6>
                  <p class="mb-0">Gọi <strong>+1 (555) 911-4567</strong> để được hỗ trợ y tế khẩn cấp</p>
                </div>
              </div>

            </div>
          </div><!-- End Appointment Info -->

          <!-- Form Đặt Lịch Khám - Thu thập thông tin từ người dùng -->
          <div class="col-lg-6">
            <div class="appointment-form-wrapper" data-aos="fade-up" data-aos-delay="200">
              <form action="index.php?page=themYeuCauLichHen" method="post" class="appointment-form">
                <div class="row gy-3">

                  <!-- Tên -->
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">Tên đầy đủ <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required>
                  </div>

                  <!-- SĐT -->
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">Số điện thoại<span class="text-danger">*</span></label>
                    <input type="tel" name="so_dien_thoai" class="form-control" required>
                  </div>

                  <!-- Dịch vụ -->
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">Chọn dịch vụ <span class="text-danger">*</span></label>
                    <select name="dich_vu_id" class="form-select" required>
                      <option value="">-- Chọn dịch vụ --</option>
                      <?php foreach ($danhSachDichVu as $dv): ?>
                        <option value="<?= $dv['id'] ?>">
                          <?= htmlspecialchars($dv['ten_dich_vu']) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <!-- Bác sĩ -->
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">Chọn bác sĩ<span class="text-danger">*</span></label>
                    <select id="doctor" name="doctor_id" class="form-select" required>
                      <option value="">-- Chọn bác sĩ --</option>
                      <?php foreach ($danhSachBacSi as $doctor): ?>
                        <option value="<?= $doctor['id'] ?>">
                          <?= htmlspecialchars($doctor['ten_bac_si']) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <!-- Ngày khám -->
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">Chọn ngày khám <span class="text-danger">*</span></label>
                    <input type="date" id="date" name="date" class="form-control" value="<?php echo date('Y-m-d'); ?>"
                      required>
                  </div>
                  <!-- Giờ Khám -->
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">
                      Chọn Giờ Khám <span class="text-danger">*</span>
                    </label>
                    <select name="exam_time" id="examTime" class="form-select" required>
                      <option value="">-- Chọn Giờ Khám --</option>
                    </select>
                  </div>

                  <!-- Ghi chú -->
                  <div class="col-12">
                    <label class="form-label fw-semibold">Mô tả triệu chứng (tùy chọn)</label>
                    <textarea class="form-control" name="message" rows="5"></textarea>
                  </div>

                  <!-- Thông báo -->
                  <div class="col-12">

                    <div class="error-message text-danger fw-semibold"
                      style="<?= isset($_SESSION['error']) ? 'display:block' : 'display:none' ?>">
                      Đặt lịch thất bại, vui lòng thử lại!
                    </div>

                    <div class="sent-message text-success fw-semibold"
                      style="<?= isset($_SESSION['success']) ? 'display:block' : 'display:none' ?>">
                      Yêu cầu đặt lịch khám của bạn đã được gửi thành công.
                    </div>

                    <?php
                    unset($_SESSION['success']);
                    unset($_SESSION['error']);
                    ?>

                    <button type="submit" class="btn btn-appointment w-100 mt-3">
                      <i class="bi bi-calendar-plus me-2"></i>Đặt Lịch Khám
                    </button>
                  </div>

                </div>
              </form>
            </div>
          </div><!-- End Appointment Form -->

        </div>

        <!-- Các Bước Quy Trình Đặt Lịch Khám -->
        <div class="process-steps mt-5" data-aos="fade-up" data-aos-delay="300">
          <div class="row text-center gy-4">
            <div class="col-lg-3 col-md-6">
              <div class="step-item">
                <div class="step-number">1</div>
                <div class="step-icon">
                  <i class="bi bi-person-fill"></i>
                </div>
                <h5>Điền Thông Tin</h5>
                <p>Cung cấp thông tin cá nhân của bạn và chọn khoa phòng ưa thích</p>
              </div>
            </div><!-- Kết Thúc Bước -->

            <div class="col-lg-3 col-md-6">
              <div class="step-item">
                <div class="step-number">2</div>
                <div class="step-icon">
                  <i class="bi bi-calendar-event"></i>
                </div>
                <h5>Chọn Ngày Giờ</h5>
                <p>Chọn ngày và khung giờ ưa thích từ các tùy chọn có sẵn</p>
              </div>
            </div><!-- Kết Thúc Bước -->

            <div class="col-lg-3 col-md-6">
              <div class="step-item">
                <div class="step-number">3</div>
                <div class="step-icon">
                  <i class="bi bi-check-circle"></i>
                </div>
                <h5>Xác Nhận</h5>
                <p>Nhận xác nhận tức thời và chi tiết lịch hẹn qua email hoặc tin nhắn</p>
              </div>
            </div><!-- Kết Thúc Bước -->

            <div class="col-lg-3 col-md-6">
              <div class="step-item">
                <div class="step-number">4</div>
                <div class="step-icon">
                  <i class="bi bi-heart-pulse"></i>
                </div>
                <h5>Nhận Điều Trị</h5>
                <p>Đến phòng khám của chúng tôi vào thời gian đã hẹn và nhận chăm sóc y tế chất lượng</p>
              </div>
            </div><!-- Kết Thúc Bước -->

          </div>
        </div><!-- Kết Thúc Các Bước Quy Trình -->

      </div>

    </section><!-- /Kết Thúc Phần Đặt Lịch Khám -->

  </main>


  <!-- Nút Cuộn Lên -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Thanh Tải Trước -->
  <div id="preloader"></div>

  <!-- Các Tệp JS Của Nhà Cung Cấp -->
  <script src="frontend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="frontend/assets/vendor/php-email-form/validate.js"></script>
  <script src="frontend/assets/vendor/aos/aos.js"></script>
  <script src="frontend/assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="frontend/assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="frontend/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="frontend/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="frontend/assets/vendor/glightbox/js/glightbox.min.js"></script>

  <!-- Tệp JS Chính -->
  <script src="frontend/assets/js/main.js"></script>
  <script>
    document.getElementById("doctor").addEventListener("change", loadTime);
    document.getElementById("date").addEventListener("change", loadTime);

    function loadTime() {

      let doctor = document.getElementById("doctor").value;
      let date = document.getElementById("date").value;

      let select = document.getElementById("examTime");

      if (!doctor || !date) {
        select.innerHTML = "<option value=''>-- Vui lòng chọn bác sĩ và ngày --</option>";
        return;
      }

      fetch("index.php?page=getAvailableTime", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "bac_si_id=" + doctor + "&ngay=" + date
      })
        .then(res => res.json())
        .then(data => {

          select.innerHTML = "";

          if (data.length === 0) {
            select.innerHTML = "<option value=''>Hết lịch</option>";
          } else {
            select.innerHTML = "<option value=''>-- Chọn giờ khám --</option>";
            data.forEach(time => {
              select.innerHTML += `<option value="${time}">${time}</option>`;
            });
          }
        })
        .catch(() => {
          select.innerHTML = "<option value=''>Lỗi tải dữ liệu</option>";
        });
    }
  </script>
  <script>
    document.querySelector(".appointment-form").addEventListener("submit", function (e) {

      let name = document.querySelector("input[name='name']").value.trim();
      let phone = document.querySelector("input[name='so_dien_thoai']").value.trim();
      let date = document.querySelector("input[name='date']").value;

      // ✅ Check tên (chỉ chữ và khoảng trắng, 2-100 ký tự)
      let nameRegex = /^[\p{L}\s]{2,100}$/u;
      if (!nameRegex.test(name)) {
        alert("Tên không hợp lệ (chỉ chứa chữ và khoảng trắng)");
        e.preventDefault();
        return;
      }

      // ✅ Check số điện thoại (bắt đầu bằng 0, 9-11 số)
      let phoneRegex = /^0[0-9]{8,10}$/;
      if (!phoneRegex.test(phone)) {
        alert("Số điện thoại không hợp lệ");
        e.preventDefault();
        return;
      }

      // ✅ Check ngày không nhỏ hơn hôm nay
      let today = new Date();
      today.setHours(0, 0, 0, 0);

      let selectedDate = new Date(date);

      if (selectedDate < today) {
        alert("Không thể đặt lịch trong quá khứ");
        e.preventDefault();
        return;
      }

    });
  </script>
</body>

</html>