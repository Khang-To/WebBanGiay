<?php
session_start();

// Bắt buộc đăng nhập
if (!isset($_SESSION['taikhoan']) || !is_array($_SESSION['taikhoan'])) {
    header("Location: dangnhap.php?redirect=giohang");
    exit();
}

// Nhận dữ liệu
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$size = isset($_GET['size']) ? trim($_GET['size']) : '';
$soluong = isset($_GET['soluong']) ? (int)$_GET['soluong'] : 1;

if ($id <= 0 || $size == '' || $soluong <= 0) {
    header("Location: index.php");
    exit();
}

// Tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['giohang'])) {
    $_SESSION['giohang'] = [];
}

// Tạo key: id_size
$key = $id . '_' . $size;

// Cộng dồn nếu đã có
if (isset($_SESSION['giohang'][$key])) {
    $_SESSION['giohang'][$key]['so_luong'] += $soluong;
} else {
    $_SESSION['giohang'][$key] = ['so_luong' => $soluong];
}

// Chuyển hướng về giỏ hàng
header("Location: giohang.php");
exit();
