<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Chi tiết gói điều trị</title>
  <style>
    body {
      font-family: Arial;
      background: #f5f7fa;
      padding: 20px;
    }

    .detail-box {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    }

    .detail-box h2 {
      margin-top: 0;
    }

    .detail-box p {
      margin: 8px 0;
    }

    .btn {
      padding: 6px 14px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 14px;
      text-decoration: none;
    }

    .btn-back {
      background: #3b82f6;
      color: #fff;
    }
  </style>
</head>

<body>
  <div class="detail-box">
    <h2>Chi tiết gói điều trị</h2>
    <p><strong>ID:</strong> <?php echo $package['id']; ?></p>
    <p><strong>Mã gói:</strong> <?php echo $package['ma_goi']; ?></p>
    <p><strong>Tên gói:</strong> <?php echo $package['ten_goi']; ?></p>
    <p><strong>Loại:</strong> <?php echo $package['loai_id']; ?></p>
    <p><strong>Giá:</strong> <?php echo number_format($package['gia']); ?> VNĐ</p>
    <p><strong>Mô tả:</strong> <?php echo $package['mo_ta']; ?></p>
    <a href="admin.php?admin=treatmentPackageIndex" class="btn btn-back">← Quay lại danh sách</a>
  </div>
</body>

</html>