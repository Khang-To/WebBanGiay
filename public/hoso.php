<?php
session_start();
include 'includes/cauhinh.php';

if (!isset($_SESSION['taikhoan']) || !is_array($_SESSION['taikhoan'])) {
    header("Location: dangnhap.php");
    exit();
}

// Lấy thông tin người dùng từ DB
$taikhoan = $_SESSION['taikhoan']['tai_khoan'];
$sql = "SELECT * FROM khach_hang WHERE tai_khoan = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $taikhoan);
$stmt->execute();
$result = $stmt->get_result();
$khach_hang = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hồ sơ người dùng</title>
    <link rel="icon" type="image/png" href="images/logo.png">
    <script src="js/dropdown-hover.js"></script>

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-gradient-gray-black text-white">
<div class="wrapper d-flex flex-column min-vh-100">
<?php include 'includes/header.php'; ?>
<?php include 'includes/nav.php'; ?>

<div class="container py-5">

    <?php if (isset($_SESSION['loi_mk'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['loi_mk'] ?></div>
        <?php unset($_SESSION['loi_mk']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['thongbao_mk'])): ?>
        <div class="alert alert-success"><?= $_SESSION['thongbao_mk'] ?></div>
        <?php unset($_SESSION['thongbao_mk']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['thongbao_thongtin'])): ?>
        <div class="alert alert-success"><?= $_SESSION['thongbao_thongtin'] ?></div>
        <?php unset($_SESSION['thongbao_thongtin']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['loi_chung'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['loi_chung'] ?></div>
        <?php unset($_SESSION['loi_chung']); ?>
    <?php endif; ?>

    <h2>Hồ sơ cá nhân</h2>
    <p><strong>Họ tên:</strong> <?= htmlspecialchars($khach_hang['ho_ten'] ?? 'Chưa có') ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($khach_hang['email'] ?? 'Chưa có') ?></p>
    <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($khach_hang['dia_chi'] ?? 'Chưa có') ?></p>

    <a href="?edit=true" class="btn btn-warning me-2">Sửa thông tin</a>
    <a href="?doimatkhau=true" class="btn btn-warning me-2">Đổi mật khẩu</a>

    <?php if (isset($_GET['edit']) && $_GET['edit'] === 'true'): ?>
        <hr>
        <form method="post" action="hoso_xuli.php" class="row g-3">
            <div class="col-md-6">
                <label for="ho_ten" class="form-label">Họ tên</label>
                <input type="text" name="ho_ten" id="ho_ten" class="form-control"
                       value="<?= htmlspecialchars($khach_hang['ho_ten'] ?? '') ?>" required>
            </div>
            <div class="col-md-6">
                <label for="dia_chi" class="form-label">Địa chỉ</label>
                <input type="text" name="dia_chi" id="dia_chi" class="form-control"
                       value="<?= htmlspecialchars($khach_hang['dia_chi'] ?? '') ?>" required>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-success">Lưu thông tin</button>
                <a href="hoso.php" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    <?php endif; ?>

    <?php if (isset($_GET['doimatkhau']) && $_GET['doimatkhau'] === 'true'): ?>
        <hr>
        <form method="post" action="hoso_xuli.php" class="row g-3">
            <h5>Đổi mật khẩu</h5>
            <div class="col-md-4">
                <label for="mat_khau_cu" class="form-label">Mật khẩu cũ</label>
                <input type="password" name="mat_khau_cu" id="mat_khau_cu" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label for="mat_khau_moi" class="form-label">Mật khẩu mới</label>
                <input type="password" name="mat_khau_moi" id="mat_khau_moi" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label for="mat_khau_lai" class="form-label">Nhập lại mật khẩu mới</label>
                <input type="password" name="mat_khau_lai" id="mat_khau_lai" class="form-control" required>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-warning">Đổi mật khẩu</button>
                <a href="hoso.php" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    <?php endif; ?>

</div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
