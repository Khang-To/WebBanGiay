<?php
session_start();
include 'includes/db.php';

$thong_bao = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tk = $_POST['tai_khoan'] ?? '';
    $mk = $_POST['mat_khau'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM admin WHERE tai_khoan = ?");
    $stmt->bind_param("s", $tk);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && password_verify($mk, $admin['mat_khau'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_ten'] = $admin['ho_ten'];
        header("Location: index.php");
        exit;
    } else {
        $thong_bao = "Sai tài khoản hoặc mật khẩu!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .login-box {
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<div class="login-box">
    <h4 class="mb-3 text-center">🔐 Đăng nhập Quản trị</h4>
    <?php if ($thong_bao): ?>
        <div class="alert alert-danger"><?= $thong_bao ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label for="tai_khoan" class="form-label">Tài khoản</label>
            <input type="text" class="form-control" name="tai_khoan" required>
        </div>
        <div class="mb-3">
            <label for="mat_khau" class="form-label">Mật khẩu</label>
            <input type="password" class="form-control" name="mat_khau" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
    </form>
</div>
</body>
</html>
