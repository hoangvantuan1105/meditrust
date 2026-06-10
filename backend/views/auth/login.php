<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - MediTrust</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif
        }

        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: url('https://images.unsplash.com/photo-1629909613654-28e377c37b09?auto=format&fit=crop&w=1350&q=80') center/cover no-repeat;
        }

        .bg-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(78, 115, 223, .85), rgba(34, 74, 190, .95));
        }

        .login-box {
            position: relative;
            z-index: 2;
            width: 420px;
            background: #fff;
            padding: 45px 35px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0, 0, 0, .35)
        }

        .logo-icon {
            font-size: 48px;
            color: #4e73df;
            margin-bottom: 10px
        }

        h2 {
            color: #224abe;
            letter-spacing: 1px
        }

        p {
            font-size: 14px;
            color: #777;
            margin: 8px 0 30px
        }

        .input-group {
            position: relative;
            margin-bottom: 22px
        }

        .input-group input {
            width: 100%;
            padding: 14px 45px;
            border-radius: 30px;
            border: 1px solid #ddd;
            font-size: 15px;
            transition: .3s
        }

        .input-group input:focus {
            border-color: #4e73df;
            box-shadow: 0 0 10px rgba(78, 115, 223, .25)
        }

        .input-group i {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            color: #4e73df
        }

        .input-group .left {
            left: 18px
        }

        .input-group .toggle {
            right: 18px;
            cursor: pointer;
            color: #999
        }

        .input-group .toggle:hover {
            color: #4e73df
        }

        .error {
            color: #e74a3b;
            font-size: 13px;
            margin-top: 6px;
            text-align: left;
            padding-left: 15px
        }

        .actions {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            padding: 0 10px;
            margin-bottom: 25px;
            color: #555
        }

        .actions a {
            text-decoration: none;
            color: #555
        }

        .actions a:hover {
            color: #4e73df
        }

        .login-btn {
            width: 100%;
            padding: 14px;
            border-radius: 30px;
            border: none;
            background: linear-gradient(180deg, #4e73df, #224abe);
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 6px 18px rgba(34, 74, 190, .35);
            transition: .3s
        }

        .login-btn:hover {
            transform: translateY(-2px);
            filter: brightness(1.1)
        }

        .footer-text {
            margin-top: 25px;
            font-size: 12px;
            color: #aaa
        }
    </style>
</head>

<body>
    <?php if (!empty($_GET['msg']) && $_GET['msg'] == 'ip_changed'): ?>
        <p style="color:red;text-align:center;">
            Phiên đăng nhập không hợp lệ, vui lòng đăng nhập lại.
        </p>
    <?php endif; ?>

    <div class="bg-overlay"></div>

    <div class="login-box">
        <i class="fas fa-hand-holding-medical logo-icon"></i>
        <h2>ADMIN LOGIN</h2>
        <p>Nha Khoa MediTrust - Hệ Thống Quản Trị</p>

        <form method="post">
            <div class="input-group">
                <i class="fa-solid fa-phone left"></i>
                <input type="text" name="sdt" placeholder="Số điện thoại" required>
            </div>

            <div class="input-group">
                <i class="fas fa-lock left"></i>
                <input type="password" name="password" id="password" placeholder="Mật khẩu" required>
                <i class="fas fa-eye toggle" id="togglePassword"></i>
            </div>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="error"><?= $_SESSION['error'];
                                    unset($_SESSION['error']); ?></div>
            <?php endif; ?>



            <button class="login-btn">ĐĂNG NHẬP</button>
        </form>

        <div class="footer-text">© 2026 MediTrust Admin</div>
    </div>

    <script>
        const toggle = document.getElementById("togglePassword");
        const pass = document.getElementById("password");

        toggle.onclick = () => {
            const type = pass.type === "password" ? "text" : "password";
            pass.type = type;
            toggle.classList.toggle("fa-eye");
            toggle.classList.toggle("fa-eye-slash");
        };
    </script>

</body>

</html>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (!empty($_SESSION['error'])): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Thất bại',
            text: '<?= addslashes($_SESSION['error']) ?>',
            confirmButtonText: 'OK'
        });
    </script>
<?php unset($_SESSION['error']);
endif; ?>

<?php if (!empty($_SESSION['success'])): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Thành công',
            text: '<?= addslashes($_SESSION['success']) ?>',
            confirmButtonText: 'OK'
        });
    </script>
<?php unset($_SESSION['success']);
endif; ?>