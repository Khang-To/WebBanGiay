<?php
session_start();
require_once 'includes/cauhinh.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $taikhoan = trim($_POST['taikhoan']);
    $matkhau = md5(trim($_POST['matkhau'])); // Mã hóa MD5

    $stmt = $conn->prepare("SELECT id, ho_ten FROM khach_hang WHERE tai_khoan = ? AND mat_khau = ?");
    $stmt->bind_param("ss", $taikhoan, $matkhau);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $ho_ten);
        $stmt->fetch();
        $_SESSION['khachhang_id'] = $id;
        $_SESSION['taikhoan'] = $taikhoan;
        $_SESSION['ho_ten'] = $ho_ten;
        header("Location: index.php");
    } else {
        header("Location: dangnhap.php?error=Tài khoản hoặc mật khẩu không đúng");
    }

    $stmt->close();
    $conn->close();
}
?>
