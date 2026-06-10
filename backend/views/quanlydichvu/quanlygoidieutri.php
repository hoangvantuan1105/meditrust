<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý gói điều trị</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>
<style>
  body {
    font-family: Arial, sans-serif;
    background: #f5f7fa;
    padding: 20px;
  }

  h2 {
    margin-top: 0;
  }

  .box {
    background: #ffffff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    margin-bottom: 30px;
  }

  .toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
  }

  .btn {
    padding: 6px 14px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
  }

  .btn-add {
    background: #22c55e;
    color: #fff;
  }

  .btn-edit {
    background: #facc15;
  }

  .btn-delete {
    background: #ef4444;
    color: #fff;
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }

  table th,
  table td {
    border-bottom: 1px solid #e5e7eb;
    padding: 10px;
    text-align: left;
  }

  table th {
    background: #f1f5f9;
  }

  .form-box {
    margin-top: 20px;
    padding: 15px;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
  }

  .form-box input,
  .form-box textarea {
    width: 100%;
    padding: 8px;
    margin: 6px 0;
    border: 1px solid #d1d5db;
    border-radius: 4px;
  }

  .form-box button {
    margin-top: 10px;
  }

  .btn-info {
    background: #0ea5e9;
    color: #fff;
  }
</style>

<body>

  <!-- QUẢN LÝ GÓI ĐIỀU TRỊ -->
  <div class="box">
    <div class="toolbar">
      <h2>Quản lý gói điều trị</h2>
      <a href="admin.php?admin=createTreatmentPackage" class="btn btn-add" title="Add package">
        <i class="fas fa-plus"></i>
      </a>


    </div>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Mã gói</th>
          <th>Tên gói</th>
          <th>Loại</th>
          <th>Tổng giá</th>
          <th>Mô tả</th>
          <th>Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if (!empty($treatmentPackages)) {
          foreach ($treatmentPackages as $goi) {
            echo "<tr>";
            echo "<td>" . ($goi['id'] ?? '') . "</td>";
            echo "<td>" . ($goi['ma_goi'] ?? '') . "</td>";
            echo "<td>" . ($goi['ten_goi'] ?? '') . "</td>";
            echo "<td>" . ($goi['loai_id'] ?? '') . "</td>";
            echo "<td>" . ($goi['gia'] ?? '') . "</td>";
            echo "<td>" . ($goi['mo_ta'] ?? '') . "</td>";
            echo "<td>";
            echo "<a href='admin.php?admin=showTreatmentPackage&idAdmin=" . $goi['id'] . "' 
                        class='btn btn-info' title='View'>
                        <i class='fas fa-eye'></i>
                      </a> ";

            echo "<a href='admin.php?admin=editTreatmentPackage&idAdmin=" . $goi['id'] . "' 
                        class='btn btn-edit' title='Edit'>
                        <i class='fas fa-edit'></i>
                      </a> ";

            echo "<a href='admin.php?admin=deleteTreatmentPackage&idAdmin=" . $goi['id'] . "' 
                        class='btn btn-delete'
                        title='Delete'
                        onclick='return confirm(\"Có muốn xóa không?\")'>
                        <i class='fas fa-trash'></i>
                      </a>";

            echo "</td>";
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='7'>Không có dữ liệu</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

</body>

</html>