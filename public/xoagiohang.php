<?php
session_start();

// Xóa toàn bộ giỏ hàng
if (isset($_GET['all']) && $_GET['all'] == 1) {
    unset($_SESSION['giohang']);
} elseif (isset($_GET['key'])) {
    $key = $_GET['key'];
    if (isset($_SESSION['giohang'][$key])) {
        unset($_SESSION['giohang'][$key]);
    }
}

// Quay về trang giỏ hàng
header("Location: giohang.php");
exit();
