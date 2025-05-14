<?php
session_start();

// Bắt buộc đăng nhập
if (!isset($_SESSION['taikhoan']) || !is_array($_SESSION['taikhoan'])) {
    header("Location: dangnhap.php?redirect=giohang");
    exit();
}

// Nhận dữ liệu từ URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$size = isset($_GET['size']) ? trim($_GET['size']) : '';
$soluong = isset($_GET['soluong']) ? (int)$_GET['soluong'] : 1;
$gia = isset($_GET['gia']) ? (int)$_GET['gia'] : 0;

// Kiểm tra tính hợp lệ
if ($id <= 0 || $size == '' || $soluong <= 0 || $gia <= 0) {
    echo "<p style='color:red; padding:20px;'>❌ Thiếu thông tin sản phẩm (id: $id, size: '$size', số lượng: $soluong, giá: $gia).<br>
    <a href='javascript:history.back()'>⬅️ Quay lại</a></p>";
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
    $_SESSION['giohang'][$key] = [
        'so_luong' => $soluong,
        'gia' => $gia
    ];
}

// Chuyển hướng về giỏ hàng
header("Location: giohang.php");
exit();
