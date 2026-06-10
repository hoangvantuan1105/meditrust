<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Chi Tiết Dịch Vụ - MediTrust Bootstrap Template</title>
  <!-- Trang chi tiết về các dịch vụ y tế -->
  <meta name="description" content="Xem chi tiết về các dịch vụ y tế chuyên nghiệp của chúng tôi">
  <meta name="keywords" content="chi tiết dịch vụ, y tế, bệnh viện">

  <!-- Favicons -->
  <link href="frontend/assets/img/favicon.png" rel="icon">
  <link href="frontend/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

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
  * Template Name: MediTrust
  * Template URL: https://bootstrapmade.com/meditrust-bootstrap-hospital-website-template/
  * Updated: Jul 04 2025 with Bootstrap v5.3.7
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="service-details-page">
  <!-- Trang chi tiết dịch vụ y tế -->



  <main class="main">

    <!-- Tiêu đề trang -->
    <div class="page-title">
      <div class="breadcrumbs">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="bi bi-house"></i> Trang Chủ</a></li>
            <li class="breadcrumb-item"><a href="#">Danh Mục</a></li>
            <li class="breadcrumb-item active current">Chi Tiết Dịch Vụ</li>
          </ol>
        </nav>
      </div>

      <div class="title-wrapper">
        <h1>Chi Tiết Dịch Vụ Y Tế</h1>
        <p>Khám phá chi tiết về các dịch vụ y tế chuyên nghiệp của chúng tôi, được thiết kế để đáp ứng nhu cầu sức khỏe toàn diện của bạn với chất lượng cao nhất.</p>
      </div>
    </div><!-- Kết thúc Tiêu đề trang -->

    <!-- Phần Chi Tiết Dịch Vụ -->
    <section id="service-details-2" class="service-details-2 section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-5">

          <!-- Hình ảnh dịch vụ -->
          <div class="col-lg-6" data-aos="zoom-in" data-aos-delay="200">
            <div class="service-image">
              <img src="backend/uploads/services/<?= $servicesDetail['image'] ?>" alt="<?= $servicesDetail['ten_dich_vu'] ?>" class="img-fluid">
              <div class="service-tag">
                <span>Chăm Sóc Chuyên Khoa</span>
              </div>
            </div>
          </div>

          <!-- Nội dung chi tiết dịch vụ -->
          <div class="col-lg-6" data-aos="fade-left" data-aos-delay="300">
            <div class="service-content">

              <h2><?= $servicesDetail['ten_dich_vu'] ?></h2>
              <p class="service-tagline">Chăm sóc <?= $servicesDetail['ten_dich_vu'] ?> với công nghệ hiện đại và chuyên gia giàu kinh nghiệm</p>

              <!-- Danh sách dịch vụ -->
              <!-- <div class="service-features">
                <h4>Các Dịch Vụ Của Chúng Tôi Bao Gồm:</h4>
                <ul>
                  <li><i class="bi bi-check-circle"></i> Khám sức khỏe tim mạch toàn diện</li>
                  <li><i class="bi bi-check-circle"></i> Chẩn đoán hình ảnh y tế nâng cao</li>
                  <li><i class="bi bi-check-circle"></i> Chương trình sàng lọc tim mạch định kỳ</li>
                  <li><i class="bi bi-check-circle"></i> Liệu pháp phục hồi chức năng tim</li>
                  <li><i class="bi bi-check-circle"></i> Can thiệp tim mạch khẩn cấp</li>
                  <li><i class="bi bi-check-circle"></i> Theo dõi và chăm sóc sau phẫu thuật</li>
                </ul>
              </div> -->

              <div class="service-actions">
                <!-- <a href="#" class="btn-primary">Đặt Lịch Tư Vấn</a> -->
                <a href="#" class="btn-secondary">Tìm Hiểu Thêm</a>
              </div>
            </div>
          </div>

        </div>

        <!-- Phần thẻ dịch vụ -->
        <div class="row mt-5">

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="service-card">
              <div class="card-icon">
                <i class="bi bi-heart-pulse"></i>
              </div>
              <h4>Xét Nghiệm Chẩn Đoán</h4>
              <p>Chúng tôi sử dụng các thiết bị xét nghiệm hiện đại nhất để chẩn đoán chính xác tình trạng sức khỏe tim mạch của bạn.</p>
              <a href="#" class="card-link">
                <span>Đặt Ngay</span>
                <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div>

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
            <div class="service-card">
              <div class="card-icon">
                <i class="bi bi-hospital"></i>
              </div>
              <h4>Các Thủ Thuật Phẫu Thuật</h4>
              <p>Đội ngũ phẫu thuật viên của chúng tôi có kinh nghiệm trong các thủ thuật phẫu thuật tim mạch tiên tiến.</p>
              <a href="#" class="card-link">
                <span>Đặt Lịch Phẫu Thuật</span>
                <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div>

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
            <div class="service-card">
              <div class="card-icon">
                <i class="bi bi-shield-check"></i>
              </div>
              <h4>Chăm Sóc Phòng Ngừa</h4>
              <p>Chúng tôi cung cấp các chương trình sàng lọc và phòng ngừa bệnh tim mạch định kỳ để bảo vệ sức khỏe của bạn.</p>
              <a href="#" class="card-link">
                <span>Sàng Lọc Ngay</span>
                <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div>

        </div>

        <!-- Phần đặt lịch hẹn -->
        <div class="row mt-5">

          <div class="col-lg-8" data-aos="fade-right" data-aos-delay="100">
            <div class="booking-section">
              <h3>Sẵn Sàng Đặt Lịch Khám Bệnh?</h3>
              <p>Các bác sĩ chuyên khoa tim mạch của chúng tôi có sẵn để tư vấn từ Thứ Hai đến Thứ Sáu. Có sẵn các lịch hẹn cùng ngày cho các trường hợp khẩn cấp.</p>

              <!-- Thông tin sẵn có -->
              <div class="availability-info">
                <div class="info-item">
                  <i class="bi bi-clock"></i>
                  <div>
                    <strong>Giờ Làm Việc</strong>
                    <span>Thứ Hai - Thứ Sáu: 8:00 AM - 6:00 PM</span>
                  </div>
                </div>
                <div class="info-item">
                  <i class="bi bi-telephone"></i>
                  <div>
                    <strong>Đường Dây Cấp Cứu</strong>
                    <span>+84 (28) 3333-4567</span>
                  </div>
                </div>
                <div class="info-item">
                  <i class="bi bi-geo-alt"></i>
                  <div>
                    <strong>Địa Chỉ</strong>
                    <span>123 Trung Tâm Y Tế, Thành Phố Hồ Chí Minh</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Thẻ đặt lịch hẹn -->
          <div class="col-lg-4" data-aos="fade-left" data-aos-delay="200">
            <div class="appointment-card">
              <h4>Đặt Lịch Khám</h4>
              <p>Đặt lịch trực tuyến nhanh chóng và dễ dàng</p>
              <a href="appointment.html" class="btn-appointment">Đặt Lịch Hẹn</a>
              <div class="contact-alternative">
                <span>Hoặc gọi cho chúng tôi</span>
                <a href="tel:+84283333456">+84 (28) 3333-4567</a>
              </div>
            </div>
          </div>

        </div>

      </div>

    </section><!-- Kết Thúc Phần Chi Tiết Dịch Vụ -->

  </main>



  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="frontend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="frontend/assets/vendor/php-email-form/validate.js"></script>
  <script src="frontend/assets/vendor/aos/aos.js"></script>
  <script src="frontend/assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="frontend/assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="frontend/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="frontend/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="frontend/assets/vendor/glightbox/js/glightbox.min.js"></script>

  <!-- Main JS File -->
  <script src="frontend/assets/js/main.js"></script>

</body>

</html>