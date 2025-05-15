<?php
session_start();
$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông báo đặt hàng</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="js/dropdown-hover.js"></script>


    <style>
        body {
            background: linear-gradient(to right, #1f1f1f, #2c3e50);
            color: #fff;
        }

        .notification-card {
            background-color: #2d2d2d;
            border: none;
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
        }

        .btn-success {
            background-color: #28a745;
            border: none;
        }

        .btn-outline-light:hover {
            background-color: #f8f9fa;
            color: #000;
        }

        .alert {
            background-color: #e9f7fe;
            color: #0c5460;
        }

        h2 i {
            color: #28a745;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <?php include 'includes/header.php'; ?>
    <?php include 'includes/nav.php'; ?>

    <!-- Main Content -->
    <main class="flex-grow-1 d-flex justify-content-center align-items-center p-4">
        <div class="notification-card text-center p-5 rounded" style="max-width: 500px; width: 100%;">
            <h2 class="mb-3"><i class="fas fa-check-circle"></i> Đặt hàng thành công!</h2>
            <p class="mb-4">Cảm ơn bạn đã đặt hàng, vui lòng đợi khi duyệt xong vào mục Thanh toán bạn nhé.</p>
            <div class="d-grid gap-2 d-md-flex justify-content-center mt-3">
                <a href="index.php" class="btn btn-success">
                    <i class="fas fa-shopping-bag me-1"></i> Tiếp tục mua sắm
                </a>
                <a href="donhang.php" class="btn btn-outline-light">
                    <i class="fas fa-box-open me-1"></i> Xem đơn hàng
                </a>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <!-- Ngăn quay lại trang giỏ hàng -->
    <script>
        window.history.pushState(null, "", window.location.href);
        window.onpopstate = function () {
            window.location.href = "index.php";
        };
    </script>

    <!-- Bootstrap JS + Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
