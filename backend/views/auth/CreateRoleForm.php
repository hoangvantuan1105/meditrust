<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thêm tài khoản</title>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            background: #f4f6fb;
            font-family: "Segoe UI", Tahoma, sans-serif;
        }

        .card {
            width: 420px;
            background: #ffffff;
            border-radius: 20px;
            padding: 30px 28px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.08);
            animation: fadeIn .5s ease;
            border: 1px solid #eef2ff;
            margin-left: 35%;
            margin-top: 5%;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(25px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card h4 {
            text-align: center;
            color: #224abe;
            margin-bottom: 24px;
            font-weight: 600;
        }

        .form-group {
            position: relative;
            margin-bottom: 18px;
        }

        .form-group i {
            position: absolute;
            top: 50%;
            left: 14px;
            transform: translateY(-50%);
            color: #4e73df;
            font-size: 15px;
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            padding: 13px 14px 13px 42px;
            border-radius: 12px;
            border: 1.5px solid #e0e6ff;
            outline: none;
            transition: all .25s ease;
            font-size: 14px;
            background: #fafbff;
        }

        .form-control::placeholder {
            color: #9aa4c7;
        }

        .form-control:hover {
            border-color: #b8c2ff;
            background: #fff;
        }

        .form-control:focus {
            border-color: #4e73df;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.15);
        }

        select.form-control {
            cursor: pointer;
            appearance: none;
        }

        .btn {
            width: 100%;
            margin-top: 16px;
            padding: 13px;
            border: none;
            border-radius: 999px;
            background: linear-gradient(135deg, #4e73df, #224abe);
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all .25s ease;
            letter-spacing: .3px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(78, 115, 223, .35);
        }

        .btn:active {
            transform: translateY(0);
            box-shadow: none;
        }

        .btn i {
            margin-right: 6px;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .card {
                width: 100%;
                padding: 24px 18px;
                border-radius: 16px;
            }
        }

        select.form-control {
            cursor: pointer;
            appearance: none;
            padding-left: 42px;

        }

        select.form-control:invalid {
            color: #9aa4c7;
        }

        select.form-control option {
            color: #333;
        }
    </style>
</head>

<body>
    <form method="POST" action="admin.php?admin=addAccount" class="card p-4">
        <h4><i class="fa-solid fa-user-plus"></i> Thêm tài khoản</h4>

        <div class="form-group">
            <i class="fa-solid fa-phone"></i>
            <input type="text" name="sdt" class="form-control" placeholder="Số điện thoại" required>
        </div>

        <div class="form-group">
            <i class="fa-solid fa-user"></i>
            <input type="text" name="ten_nguoi_su_dung" class="form-control" placeholder="Tên người dùng" required>
        </div>

        <div class="form-group">
            <i class="fa-solid fa-lock"></i>
            <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required>
        </div>

        <div class="form-group">
            <i class="fa-solid fa-user-shield"></i>
            <select name="role" id="roleSelect" class="form-control" required>
                <option value="" selected disabled hidden>-- Chọn quyền --</option>
                <option value="bac_si">Bác sĩ</option>
                <option value="le_tan">Lễ tân</option>
            </select>
        </div>

        <!-- BÁC SĨ -->
        <div class="form-group" id="bacSiBox" style="display:none;">
            <i class="fa-solid fa-user-doctor"></i>
            <select name="bac_si_id" class="form-control">
                <option value="">-- Chọn bác sĩ --</option>
                <?php foreach ($listBacSi as $bs): ?>
                    <option value="<?= $bs['id'] ?>">
                        <?= $bs['ten_bac_si'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- LỄ TÂN -->
        <div class="form-group" id="leTanBox" style="display:none;">
            <i class="fa-solid fa-user-tie"></i>
            <input type="text" name="le_tan_name" class="form-control" placeholder="Tên lễ tân">
        </div>

        <button class="btn" type="submit">
            <i class="fa-solid fa-circle-plus"></i> Tạo tài khoản
        </button>
    </form>


</body>
<script>
    const roleSelect = document.getElementById('roleSelect');
    const bacSiBox = document.getElementById('bacSiBox');
    const leTanBox = document.getElementById('leTanBox');

    roleSelect.addEventListener('change', function() {
        bacSiBox.style.display = 'none';
        leTanBox.style.display = 'none';

        if (this.value === 'bac_si') {
            bacSiBox.style.display = 'block';
        }

        if (this.value === 'le_tan') {
            leTanBox.style.display = 'block';
        }
    });
</script>

</html>