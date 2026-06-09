<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Dịch Vụ - MediTrust Bootstrap Template</title>
  <!-- Trang liệt kê các dịch vụ y tế -->
  <meta name="description" content="Xem các dịch vụ y tế chuyên nghiệp của chúng tôi">
  <meta name="keywords" content="dịch vụ, y tế, bệnh viện">

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

<body class="services-page">
  <!-- Trang danh sách dịch vụ y tế -->



  <main class="main">

    <!-- Tiêu đề trang -->
    <div class="page-title">
      <div class="breadcrumbs">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="bi bi-house"></i> Trang Chủ</a></li>
            <li class="breadcrumb-item"><a href="#">Danh Mục</a></li>
            <li class="breadcrumb-item active current">Dịch Vụ</li>
          </ol>
        </nav>
      </div>

      <div class="title-wrapper">
        <h1>Dịch Vụ Y Tế</h1>
        <p>Chúng tôi cung cấp các dịch vụ y tế toàn diện và chất lượng cao với đội ngũ chuyên gia giàu kinh nghiệm, nhằm đáp ứng nhu cầu sức khỏe của bạn và gia đình.</p>
      </div>
    </div><!-- Kết thúc Tiêu đề trang -->

    <!-- Phần Dịch Vụ Y Tế -->
    <section id="services" class="services section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="services-tabs">
          <!-- <ul class="nav nav-tabs" role="tablist" data-aos="fade-up" data-aos-delay="200">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="services-primary-tab" data-bs-toggle="tab" data-bs-target="#services-primary" type="button" role="tab">Chăm Sóc Cơ Bản</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="services-specialty-tab" data-bs-toggle="tab" data-bs-target="#services-specialty" type="button" role="tab">Chăm Sóc Chuyên Khoa</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="services-diagnostics-tab" data-bs-toggle="tab" data-bs-target="#services-diagnostics" type="button" role="tab">Chẩn Đoán</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="services-emergency-tab" data-bs-toggle="tab" data-bs-target="#services-emergency" type="button" role="tab">Cấp Cứu</button>
            </li>
          </ul> -->

          <!-- Nội dung các tab dịch vụ -->
          <div class="tab-content" data-aos="fade-up" data-aos-delay="300">

            <!-- Tab 1: Dịch vụ chăm sóc cơ bản -->
            <div class="tab-pane fade show active" id="services-primary" role="tabpanel">
              <div class="row g-4">
                <?php foreach ($listServices as $list) { ?>
                  <div class="col-lg-6">
                    <div class="service-item">
                      <div class="service-icon-wrapper">
                        <i class="fa fa-stethoscope"></i>
                      </div>
                      <div class="service-details">
                        <h5><?= $list['ten_dich_vu'] ?></h5>
                        <p><?= $list['mo_ta'] ?></p>
                        <!-- <ul class="service-benefits">
                          <li><i class="fa fa-check-circle"></i>Đánh Giá Sức Khỏe Toàn Diện</li>
                          <li><i class="fa fa-check-circle"></i>Lập Kế Hoạch Chăm Sóc Phòng Ngừa</li>
                          <li><i class="fa fa-check-circle"></i>Theo Dõi Sức Khỏe Định Kỳ</li>
                        </ul> -->
                        <a href="index.php?page=serviceDetails&id=<?= $list['id'] ?>" class="service-link">
                          <span>Tìm Hiểu Thêm</span>
                          <i class="fa fa-arrow-right"></i>
                        </a>
                      </div>
                    </div>
                  </div>
                <?php } ?>

                <!-- <div class="col-lg-6">
                  <div class="service-item">
                    <div class="service-icon-wrapper">
                      <i class="fa fa-syringe"></i>
                    </div>
                    <div class="service-details">
                      <h5>Dịch Vụ Tiêm Chủng</h5>
                      <p>Tiêm chủng an toàn và hiệu quả để bảo vệ bạn và gia đình khỏi các bệnh truyền nhiễm.</p>
                      <ul class="service-benefits">
                        <li><i class="fa fa-check-circle"></i>Tiêm Chủng Người Lớn</li>
                        <li><i class="fa fa-check-circle"></i>Tiêm Vắcxin Du Lịch</li>
                        <li><i class="fa fa-check-circle"></i>Tiêm Ngừa Cúm Hàng Năm</li>
                      </ul>
                      <a href="index.php?page=serviceDetails" class="service-link">
                        <span>Tìm Hiểu Thêm</span>
                        <i class="fa fa-arrow-right"></i>
                      </a>
                    </div>
                  </div>
                </div> -->

                <!-- <div class="col-lg-6">
                  <div class="service-item">
                    <div class="service-icon-wrapper">
                      <i class="fa fa-baby"></i>
                    </div>
                    <div class="service-details">
                      <h5>Chăm Sóc Sức Khỏe Mẹ và Thai</h5>
                      <p>Hỗ trợ toàn diện từ giai đoạn mang thai cho đến sau sinh với chăm sóc y tế chất lượng cao.</p>
                      <ul class="service-benefits">
                        <li><i class="fa fa-check-circle"></i>Khám Trước Sinh</li>
                        <li><i class="fa fa-check-circle"></i>Hỗ Trợ Sinh Nở</li>
                        <li><i class="fa fa-check-circle"></i>Chăm Sóc Sau Sinh</li>
                      </ul>
                      <a href="index.php?page=serviceDetails" class="service-link">
                        <span>Tìm Hiểu Thêm</span>
                        <i class="fa fa-arrow-right"></i>
                      </a>
                    </div>
                  </div>
                </div> -->

                <!-- <div class="col-lg-6">
                  <div class="service-item">
                    <div class="service-icon-wrapper">
                      <i class="fa fa-user-md"></i>
                    </div>
                    <div class="service-details">
                      <h5>Y Học Gia Đình</h5>
                      <p>Cung cấp dịch vụ y tế cho toàn gia đình từ trẻ sơ sinh đến người cao tuổi.</p>
                      <ul class="service-benefits">
                        <li><i class="fa fa-check-circle"></i>Chăm Sóc Mọi Lứa Tuổi</li>
                        <li><i class="fa fa-check-circle"></i>Quản Lý Bệnh Mãn Tính</li>
                        <li><i class="fa fa-check-circle"></i>Chương Trình Wellness</li>
                      </ul>
                      <a href="index.php?page=serviceDetails" class="service-link">
                        <span>Tìm Hiểu Thêm</span>
                        <i class="fa fa-arrow-right"></i>
                      </a>
                    </div>
                  </div>
                </div> -->
              </div>
            </div>

            <!-- Tab 2: Dịch vụ chăm sóc chuyên khoa -->
            <!-- <div class="tab-pane fade" id="services-specialty" role="tabpanel">
              <div class="row g-4">
                <div class="col-lg-6">
                  <div class="service-item featured">
                    <div class="service-icon-wrapper">
                      <i class="fa fa-heartbeat"></i>
                    </div>
                    <div class="service-details">
                      <h5>Tim Mạch Học</h5>
                      <p>Chuyên khoa chẩn đoán và điều trị các bệnh tim mạch với công nghệ hiện đại nhất.</p>
                      <ul class="service-benefits">
                        <li><i class="fa fa-check-circle"></i>Điều Trị Bệnh Tim</li>
                        <li><i class="fa fa-check-circle"></i>Phẫu Thuật Tim</li>
                        <li><i class="fa fa-check-circle"></i>Chương Trình Phục Hồi Chức Năng</li>
                      </ul>
                      <a href="index.php?page=serviceDetails" class="service-link">
                        <span>Tìm Hiểu Thêm</span>
                        <i class="fa fa-arrow-right"></i>
                      </a>
                    </div>
                  </div>
                </div>

                <div class="col-lg-6">
                  <div class="service-item">
                    <div class="service-icon-wrapper">
                      <i class="fa fa-brain"></i>
                    </div>
                    <div class="service-details">
                      <h5>Thần Kinh Học</h5>
                      <p>Chuyên khoa chẩn đoán và điều trị các bệnh thần kinh với đội bác sĩ chuyên gia.</p>
                      <ul class="service-benefits">
                        <li><i class="fa fa-check-circle"></i>Đánh Giá Thần Kinh</li>
                        <li><i class="fa fa-check-circle"></i>Điều Trị Đột Quỵ</li>
                        <li><i class="fa fa-check-circle"></i>Chăm Sóc Trí Nhớ</li>
                      </ul>
                      <a href="index.php?page=serviceDetails" class="service-link">
                        <span>Tìm Hiểu Thêm</span>
                        <i class="fa fa-arrow-right"></i>
                      </a>
                    </div>
                  </div>
                </div>

                <div class="col-lg-6">
                  <div class="service-item">
                    <div class="service-icon-wrapper">
                      <i class="fa fa-bone"></i>
                    </div>
                    <div class="service-details">
                      <h5>Chỉnh Hình</h5>
                      <p>Chuyên khoa chẩn đoán và điều trị các bệnh xương khớp, chấn thương thể thao.</p>
                      <ul class="service-benefits">
                        <li><i class="fa fa-check-circle"></i>Thay Thế Khớp</li>
                        <li><i class="fa fa-check-circle"></i>Y Học Thể Thao</li>
                        <li><i class="fa fa-check-circle"></i>Quản Lý Đau</li>
                      </ul>
                      <a href="index.php?page=serviceDetails" class="service-link">
                        <span>Tìm Hiểu Thêm</span>
                        <i class="fa fa-arrow-right"></i>
                      </a>
                    </div>
                  </div>
                </div>

                <div class="col-lg-6">
                  <div class="service-item">
                    <div class="service-icon-wrapper">
                      <i class="fa fa-user-nurse"></i>
                    </div>
                    <div class="service-details">
                      <h5>Ung Thư Học</h5>
                      <p>Chuyên khoa điều trị ung thư với phương pháp hiện đại và chăm sóc toàn diện.</p>
                      <ul class="service-benefits">
                        <li><i class="fa fa-check-circle"></i>Điều Trị Ung Thư</li>
                        <li><i class="fa fa-check-circle"></i>Hóa Trị</li>
                        <li><i class="fa fa-check-circle"></i>Dịch Vụ Hỗ Trợ</li>
                      </ul>
                      <a href="index.php?page=serviceDetails" class="service-link">
                        <span>Tìm Hiểu Thêm</span>
                        <i class="fa fa-arrow-right"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div> -->

            <!-- Tab 3: Dịch vụ chẩn đoán -->
            <!-- <div class="tab-pane fade" id="services-diagnostics" role="tabpanel">
              <div class="row g-4">
                <div class="col-lg-6">
                  <div class="service-item">
                    <div class="service-icon-wrapper">
                      <i class="fa fa-vial"></i>
                    </div>
                    <div class="service-details">
                      <h5>Xét Nghiệm Phòng Thí Nghiệm</h5>
                      <p>Xét nghiệm máu và các xét nghiệm chẩn đoán hiện đại với kết quả nhanh và chính xác.</p>
                      <ul class="service-benefits">
                        <li><i class="fa fa-check-circle"></i>Phân Tích Máu</li>
                        <li><i class="fa fa-check-circle"></i>Dịch Vụ Mô Bệnh Học</li>
                        <li><i class="fa fa-check-circle"></i>Kết Quả Nhanh Chóng</li>
                      </ul>
                      <a href="index.php?page=serviceDetails" class="service-link">
                        <span>Tìm Hiểu Thêm</span>
                        <i class="fa fa-arrow-right"></i>
                      </a>
                    </div>
                  </div>
                </div>

                <div class="col-lg-6">
                  <div class="service-item">
                    <div class="service-icon-wrapper">
                      <i class="fa fa-x-ray"></i>
                    </div>
                    <div class="service-details">
                      <h5>Chẩn Đoán Hình Ảnh</h5>
                      <p>Cung cấp dịch vụ chẩn đoán hình ảnh với công nghệ y tế tiên tiến nhất.</p>
                      <ul class="service-benefits">
                        <li><i class="fa fa-check-circle"></i>Chụp MRI</li>
                        <li><i class="fa fa-check-circle"></i>Chụp CT</li>
                        <li><i class="fa fa-check-circle"></i>Siêu Âm</li>
                      </ul>
                      <a href="index.php?page=serviceDetails" class="service-link">
                        <span>Tìm Hiểu Thêm</span>
                        <i class="fa fa-arrow-right"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div> -->

            <!-- Tab 4: Dịch vụ cấp cứu -->
            <!-- <div class="tab-pane fade" id="services-emergency" role="tabpanel">
              <div class="row g-4">
                <div class="col-lg-12">
                  <div class="service-item emergency-highlight">
                    <div class="service-icon-wrapper">
                      <i class="fa fa-ambulance"></i>
                    </div>
                    <div class="service-details">
                      <h5>Dịch Vụ Cấp Cứu 24/7</h5>
                      <p>Cung cấp dịch vụ cấp cứu y tế 24 giờ mỗi ngày, 7 ngày trong tuần với đội ngũ chuyên gia y tế giàu kinh nghiệm sẵn sàng hỗ trợ khẩn cấp cho bạn.</p>
                      <ul class="service-benefits">
                        <li><i class="fa fa-check-circle"></i>Hoạt Động Liên Tục 24/7</li>
                        <li><i class="fa fa-check-circle"></i>Trung Tâm Chấn Thương</li>
                        <li><i class="fa fa-check-circle"></i>Phòng Chăm Sóc Đặc Biệt</li>
                        <li><i class="fa fa-check-circle"></i>Phẫu Thuật Cấp Cứu</li>
                      </ul>
                      <div class="emergency-actions">
                        <a href="tel:911" class="btn-emergency">
                          <i class="fa fa-phone"></i>
                          <span>Gọi Cấp Cứu</span>
                        </a>
                        <a href="directions.html" class="btn-directions">
                          <i class="fa fa-map-marker-alt"></i>
                          <span>Chỉ Đường</span>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div> -->

          </div>
        </div>

        <!-- Phần Lời Kêu Gọi Hành Động -->
        <div class="services-cta" data-aos="fade-up" data-aos-delay="400">
          <div class="row">
            <div class="col-lg-8 mx-auto text-center">
              <div class="cta-content">
                <i class="fa fa-calendar-check"></i>
                <h3>Sẵn Sàng Đặt Lịch Hẹn Của Bạn?</h3>
                <p>Hãy liên hệ với chúng tôi ngay hôm nay để đặt lịch khám bệnh hoặc tư vấn với các chuyên gia y tế của chúng tôi. Chúng tôi cam k承 sẽ cung cấp dịch vụ chất lượng cao.</p>
                <div class="cta-buttons">
                  <a href="index.php?page=appointment" class="btn-book">Đặt Lịch Ngay</a>
                  <a href="index.php?page=contact" class="btn-contact">Liên Hệ Chúng Tôi</a>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- Kết Thúc Phần Dịch Vụ Y Tế -->

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