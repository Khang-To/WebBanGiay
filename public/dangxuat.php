<?php
session_start();

// Sao lưu giỏ hàng vào cookie trước khi xóa session
if (isset($_SESSION['cart'])) {
    setcookie("cart_backup", json_encode($_SESSION['cart']), time() + 3600, "/");
}

// Chỉ xóa tài khoản, giữ các phần khác
unset($_SESSION['taikhoan']);

header("Location: index.php");
exit();
