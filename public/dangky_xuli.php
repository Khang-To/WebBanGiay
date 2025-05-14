<?php
require_once 'includes/cauhinh.php';

$tai_khoan = trim($_POST['taikhoan']);
$email = trim($_POST['email']);
$matkhau = trim($_POST['matkhau']);
$nhaplaimatkhau = trim($_POST['nhaplaimatkhau']);

// Kiểm tra mật khẩu khớp
if ($matkhau !== $nhaplaimatkhau) {
    header("Location: dangky.php?error=Mật khẩu không khớp!");
    exit;
}

// Kiểm tra tài khoản hoặc email đã tồn tại
$sql_check = "SELECT * FROM khach_hang WHERE tai_khoan = ? OR email = ?";
$stmt = $conn->prepare($sql_check);
$stmt->bind_param("ss", $tai_khoan, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    header("Location: dangky.php?error=Tài khoản hoặc email đã tồn tại!");
    exit;
}

// Mã hóa mật khẩu bằng MD5
$mat_khau_mahoa = md5($matkhau);

// Lưu tài khoản
$sql_insert = "INSERT INTO khach_hang (tai_khoan, mat_khau, email) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql_insert);
$stmt->bind_param("sss", $tai_khoan, $mat_khau_mahoa, $email);

if ($stmt->execute()) {
    header("Location: dangnhap.php?success=Đăng ký thành công!");
} else {
    header("Location: dangky.php?error=Lỗi khi đăng ký!");
}

$conn->close();
?>
