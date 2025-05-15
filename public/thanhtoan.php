<?php
session_start();
require 'includes/cauhinh.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['taikhoan']) || !is_array($_SESSION['taikhoan'])) {
    header("Location: dangnhap.php");
    exit;
}

// Lấy ID khách hàng từ session
$khach_hang_id = $_SESSION['taikhoan']['id'] ?? null;

// Kiểm tra ID đơn hàng
if (!isset($_GET['don_hang_id']) || !is_numeric($_GET['don_hang_id'])) {
    echo "Thiếu ID đơn hàng.";
    exit;
}
$don_hang_id = (int)$_GET['don_hang_id'];

// Cập nhật trạng thái đơn hàng
$stmt = $conn->prepare("UPDATE don_hang SET trang_thai = 'da_thanh_toan' WHERE id = ? AND khach_hang_id = ?");
$stmt->bind_param("ii", $don_hang_id, $khach_hang_id);
$stmt->execute();

// Lấy chi tiết đơn hàng
$sql = "SELECT ct.*, sg.so_luong_ton 
        FROM chi_tiet_don_hang ct
        JOIN size_giay sg ON ct.size_giay_id = sg.id
        WHERE ct.don_hang_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $don_hang_id);
$stmt->execute();
$result = $stmt->get_result();
$chi_tiets = $result->fetch_all(MYSQLI_ASSOC);

// Trừ số lượng tồn kho
foreach ($chi_tiets as $ct) {
    $moi_so_luong = max(0, $ct['so_luong_ton'] - $ct['so_luong_ban']);
    $stmt_update = $conn->prepare("UPDATE size_giay SET so_luong_ton = ? WHERE id = ?");
    $stmt_update->bind_param("ii", $moi_so_luong, $ct['size_giay_id']);
    $stmt_update->execute();
}

// Xoá giỏ hàng sau khi thanh toán
unset($_SESSION['giohang']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán thành công</title>
    <link rel="icon" type="image/png" href="images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/dropdown-hover.js"></script>
</head>
<body class="bg-dark text-white">
<?php include 'includes/header.php'; ?>
<?php include 'includes/nav.php'; ?>

<div class="container py-5 text-center">
    <h2><i class="bi bi-check-circle-fill text-success"></i> Cảm ơn bạn đã mua hàng!</h2>
    <p class="fs-5">Đơn hàng <strong>#<?= $don_hang_id ?></strong> của bạn đã được thanh toán thành công, bộ phận giao hàng sẽ vận chuyển giày đến cho bạn với thời gian sớm nhất.</p>
    <a href="index.php" class="btn btn-outline-light mt-3"><i class="bi bi-house"></i> Về trang chủ</a>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
