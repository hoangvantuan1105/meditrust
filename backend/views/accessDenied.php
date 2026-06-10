<!DOCTYPE html>
<html lang="vi">
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Inter', 'Segoe UI', sans-serif;
    }

    /* Background ảnh nha khoa */
    .dental-bg {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        /* Bạn có thể thay URL ảnh nha khoa thật ở đây */
        background: url('https://images.unsplash.com/photo-1629909608135-ca29ed974bb9?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80') no-repeat center center;
        background-size: cover;
        z-index: -2;
    }

    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 48, 73, 0.6);
        /* Phủ màu xanh đậm trong suốt */
        backdrop-filter: blur(5px);
        /* Làm mờ ảnh nền */
        z-index: -1;
    }

    body {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
        overflow: hidden;
    }

    .access-card {
        background: rgba(255, 255, 255, 0.9);
        padding: 50px 40px;
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        text-align: center;
        max-width: 500px;
        width: 90%;
        animation: fadeInUp 0.8s ease-out;
    }

    .dental-icon {
        position: relative;
        font-size: 70px;
        color: #0077b6;
        margin-bottom: 20px;
    }

    .lock-shield {
        position: absolute;
        top: 50%;
        left: 55%;
        font-size: 35px;
        color: #d62828;
        background: white;
        border-radius: 50%;
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    h1 {
        font-size: 80px;
        font-weight: 900;
        color: #003049;
        line-height: 1;
    }

    h2 {
        color: #d62828;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 15px;
    }

    .divider {
        width: 60px;
        height: 4px;
        background: #0077b6;
        margin: 15px auto;
        border-radius: 2px;
    }

    p {
        color: #555;
        margin-bottom: 30px;
        font-size: 1.1rem;
    }

    .button-group {
        display: flex;
        gap: 15px;
        justify-content: center;
    }

    .btn-back,
    .btn-home {
        padding: 12px 25px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
        border: none;
        font-size: 16px;
    }

    .btn-back {
        background: #e9ecef;
        color: #333;
    }

    .btn-home {
        background: #0077b6;
        color: white;
    }

    .btn-back:hover {
        background: #dee2e6;
    }

    .btn-home:hover {
        background: #005b8a;
        transform: translateY(-2px);
    }

    /* Animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Truy cập bị từ chối - Dental Management</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="dental-bg"></div>

    <div class="overlay"></div>

    <div class="container">
        <div class="access-card">
            <div class="dental-icon">
                <i class="fas fa-tooth"></i>
                <div class="lock-shield">
                    <i class="fas fa-ban"></i>
                </div>
            </div>

            <div class="content">
                <h1>403</h1>
                <h2>Quyền truy cập bị từ chối</h2>
                <div class="divider"></div>
                <p>Khu vực này chỉ dành cho nhân sự được cấp quyền. Vui lòng quay lại hoặc báo cáo nếu bạn cho rằng đây là lỗi hệ thống.</p>

                <div class="button-group">
                    <button onclick="window.history.back()" class="btn-back">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </button>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const icon = document.querySelector('.dental-icon');

            // Tạo hiệu ứng đập nhẹ như nhịp tim cho chiếc răng
            setInterval(() => {
                icon.style.transform = 'scale(1.05)';
                setTimeout(() => {
                    icon.style.transform = 'scale(1)';
                }, 500);
            }, 2000);
        });
    </script>
</body>

</html>