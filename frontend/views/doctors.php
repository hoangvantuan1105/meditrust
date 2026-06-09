<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Các Bác Sĩ - Mẫu Trang Web MediTrust</title>
  <meta name="description" content="Gặp gỡ các bác sĩ chuyên khoa giàu kinh nghiệm tại MediTrust Healthcare">
  <meta name="keywords" content="bác sĩ, chuyên gia y tế, đội ngũ bác sĩ, y tế">

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
  * Tên Mẫu: MediTrust
  * URL Mẫu: https://bootstrapmade.com/meditrust-bootstrap-hospital-website-template/
  * Cập Nhật: 04 tháng 7 năm 2025 với Bootstrap v5.3.7
  * Tác Giả: BootstrapMade.com
  * Giấy Phép: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="doctors-page">
  <!-- Trang Danh Sách Các Bác Sĩ - Hiển thị thông tin chi tiết về đội ngũ bác sĩ -->

  <main class="main">

    <!-- Tiêu Đề Trang -->
    <div class="page-title">
      <div class="breadcrumbs">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="bi bi-house"></i> Trang Chủ</a></li>
            <li class="breadcrumb-item"><a href="#">Danh Mục</a></li>
            <li class="breadcrumb-item active current">Các Bác Sĩ</li>
          </ol>
        </nav>
      </div>

      <div class="title-wrapper">
        <h1>Đội Ngũ Bác Sĩ Nha Khoa</h1>
        <p>Đội ngũ bác sĩ nha khoa <b>MediTrust</b> là những chuyên gia giàu kinh nghiệm, tận tâm và luôn cập nhật kiến thức mới trong lĩnh vực răng – hàm – mặt. Với sự am hiểu sâu rộng về các kỹ thuật nha khoa hiện đại như chỉnh nha, implant, phục hình thẩm mỹ và điều trị tổng quát, các bác sĩ của chúng tôi không chỉ mang đến nụ cười khỏe mạnh mà còn đem lại sự tự tin và hạnh phúc cho mỗi bệnh nhân. MediTrust cam kết cung cấp dịch vụ nha khoa chất lượng cao, an toàn và thân thiện, góp phần nâng cao sức khỏe răng miệng cho cộng đồng.
        </p>
      </div>
    </div><!-- Kết Thúc Tiêu Đề Trang -->

    <!-- Phần Các Bác Sĩ - Danh sách đối ngũ bác sĩ chuyên khoa -->
    <section id="doctors" class="doctors section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">

          <?php foreach ($listBacSi as $bs): ?>
            <div class="col-xl-3 col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
              <div class="doctor-card">
                <div class="doctor-image">
                  <img src="<?= htmlspecialchars($bs['photo_url'] ?? 'frontend/assets/img/default-doctor.png') ?>"
                    alt="<?= htmlspecialchars($bs['ten_bac_si']) ?>" class="img-fluid">
                </div>
                <div class="doctor-content">
                  <h4 class="doctor-name"><?= htmlspecialchars($bs['ten_bac_si']) ?></h4>
                  <span class="doctor-specialty"><?= htmlspecialchars($bs['chuyen_mon'] ?? '---') ?></span>
                  <p class="doctor-bio">Email: <?= htmlspecialchars($bs['email'] ?? '---') ?></p>
                  <div class="doctor-experience">
                    <span class="experience-badge">Ca làm: <?= htmlspecialchars($bs['ca_lam'] ?? '---') ?></span>
                  </div>
                  <a href="index.php?page=appointment" class="btn-appointment">Đặt Lịch Khám</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

      </div>

    </section><!-- /Kết Thúc Phần Các Bác Sĩ -->

  </main>



  <!-- Nút Cuộn Lên -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

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

</body>

</html>