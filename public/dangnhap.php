<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <link rel="icon" type="image/png" href="images/logo.png">
    <script src="js/dropdown-hover.js"></script>


    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Your custom CSS -->
    <link rel="stylesheet" href="css/style.css">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-gradient-gray-black">
    <div class="wrapper d-flex flex-column min-vh-100">
        <?php include 'includes/header.php'; ?>
        <?php include 'includes/nav.php'; ?>

        <div class="container mt-5 pb-5 flex-grow-1" style="max-width: 450px;">
            <h4 class="text-center mb-4">Đăng nhập</h4>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
            <?php elseif (isset($_GET['success'])): ?>
                <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
            <?php endif; ?>

            <form action="dangnhap_xuli.php" method="POST">
                <div class="mb-3">
                    <label for="taikhoan" class="form-label">Tên tài khoản</label>
                    <input type="text" name="taikhoan" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="matkhau" class="form-label">Mật khẩu</label>
                    <input type="password" name="matkhau" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-danger w-100">Đăng nhập</button>
                <div class="mt-3 text-center">
                    <a href="dangky.php">Chưa có tài khoản? Đăng ký</a>
                </div>
            </form>
        </div>

        <?php include 'includes/footer.php'; ?>
    </div>
</body>
</html>
