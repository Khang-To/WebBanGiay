<?php
    include 'includes/db.php';
    include 'auth_admin.php';
    include_once 'includes/thongbao.php';
?>
<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <title>Quản trị | Blue Eagle Store</title>
        <link rel="icon" type="image/jpg" href="images\logo-shop.jpg">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <!--css riêng-->
        <link href="css/style.css" rel="stylesheet">
    </head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark sticky-top shadow">
        <div class="container">
            <a class="navbar-brand" href="index.php"><img src="images\logo-shop.jpg" width="100" height="auto"></a>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        Quản lý giày
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="themgiay.php">Thêm giày</a></li>
                        <li><a class="dropdown-item" href="themtonkho.php">Cập nhật tồn kho theo size</a></li>
                        <li><a class="dropdown-item" href="danhsachgiay.php">Danh sách mẫu giày</a></li>
                        <li><a class="dropdown-item" href="danhsachtonkho.php">Tồn kho giày</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button">
                        Quản lý thương hiệu & loại giày
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="themthuonghieu.php">Thêm thương hiệu</a></li>
                        <li><a class="dropdown-item" href="themloaigiay.php">Thêm loại giày</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="danhsach_khachhang.php">Quản lý khách hàng</a></li>
                <li class="nav-item"><a class="nav-link" href="danhsachdonhang.php">Quản lý đơn hàng</a></li>
                <li class="nav-item"><a class="nav-link" href="thongke.php">Thống kê</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button">
                        Quản lý hồ sơ
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="hosoadmin.php">Thông tin cá nhân</a></li>
                        <li><a class="dropdown-item" href="doimatkhauadmin.php">Đổi mật khẩu</a></li>
                    </ul>
                </li>
            </ul>
            <div class="ms-auto" style="padding-top: 3px;">
                <span class="text-white me-3">Xin chào <?= $_SESSION['admin_ten'] ?? 'Admin' ?></span>
                <a href="dangxuat.php" class="btn btn-outline-light btn-sm">Đăng xuất</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
    <?php flashMessage(); ?>

