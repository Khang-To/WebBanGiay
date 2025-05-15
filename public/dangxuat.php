<?php
session_start();

if (isset($_SESSION['cart'])) {
    setcookie("cart_backup", json_encode($_SESSION['cart']), time() + 3600, "/");
}

// Xóa toàn bộ thông tin đăng nhập
unset($_SESSION['khach_hang_id']);
unset($_SESSION['taikhoan']);

header("Location: index.php");
exit();
