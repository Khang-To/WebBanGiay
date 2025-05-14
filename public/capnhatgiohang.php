<?php
session_start();

// Kiểm tra dữ liệu đầu vào
if (!isset($_POST['soluong']) || !is_array($_POST['soluong'])) {
    header("Location: giohang.php");
    exit();
}

foreach ($_POST['soluong'] as $key => $soluong) {
    if (!isset($_SESSION['giohang'][$key])) continue;

    $soluong = (int)$soluong;

    if ($soluong <= 0) {
        // Xóa sản phẩm nếu số lượng <= 0
        unset($_SESSION['giohang'][$key]);
    } else {
        // Cập nhật số lượng hợp lệ
        $_SESSION['giohang'][$key]['so_luong'] = $soluong;
    }
}

header("Location: giohang.php");
exit();
