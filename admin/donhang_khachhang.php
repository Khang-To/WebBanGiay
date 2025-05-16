<?php
include 'includes/auth_admin.php';
include 'includes/db.php';

$khach_id = intval($_GET['id'] ?? 0);
$ho_ten = 'Khách chưa xác định';

// Lấy tên khách hàng
$stmtTen = $conn->prepare("SELECT ho_ten FROM khach_hang WHERE id = ?");
$stmtTen->bind_param("i", $khach_id);
$stmtTen->execute();
$stmtTen->bind_result($ho_ten);
$stmtTen->fetch();
$stmtTen->close();

// Lấy đơn hàng
$stmt = $conn->prepare("SELECT dh.* FROM don_hang dh WHERE dh.khach_hang_id = ? ORDER BY dh.ngay_dat DESC");
$stmt->bind_param("i", $khach_id);
$stmt->execute();
$result = $stmt->get_result();

include 'includes/header.php';
?>


<?php include 'includes/footer.php'; ?>
