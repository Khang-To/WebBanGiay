<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'includes/cauhinh.php';
$user = $_SESSION['taikhoan'] ?? null;
?>
<!-- Wrapper -->
<div>
    <!-- Thanh trên cùng -->
    <div class="top-bar text-white py-1" style="background-color: #1e1e1e; font-size: 0.9rem; position: relative; z-index: 1040;">
        <div class="container d-flex justify-content-between align-items-center">
            <span>
                <i class="bi bi-megaphone-fill text-warning"></i>
                HỆ THỐNG BÁN GIÀY CỦA CLB BÓNG ĐÁ BLUE EAGLE HÌNH THÀNH VÀ PHÁT TRIỂN
            </span>

            <!-- Dropdown tài khoản -->
            <div class="dropdown">
                <a class="dropdown-toggle text-white text-decoration-none" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person"></i>
                    <?php
                    if ($user && is_array($user)) {
                        $name = !empty($user['ho_ten']) ? $user['ho_ten'] : (!empty($user['tai_khoan']) ? $user['tai_khoan'] : 'Tài khoản');
                        echo "Xin chào: " . htmlspecialchars($name);
                    } else {
                        echo "Tài khoản";
                    }
                    ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <?php if (isset($_SESSION['taikhoan']) && is_array($_SESSION['taikhoan'])): ?>
                        <li><a class="dropdown-item" href="giohang.php"><i class="bi bi-cart-check me-1"></i> Giỏ hàng</a></li>
                        <li><a class="dropdown-item" href="tracuuthanhtoan.php"><i class="bi bi-credit-card me-1"></i> Thanh toán</a></li>
                        <li><a class="dropdown-item" href="donhang.php"><i class="bi bi-search me-1"></i> Tra cứu đơn hàng</a></li>
                        <li><a class="dropdown-item text-danger" href="dangxuat.php"><i class="bi bi-box-arrow-right me-1"></i> Đăng xuất</a></li>
                    <?php else: ?>
                        <li><a class="dropdown-item" href="dangky.php"><i class="bi bi-person-plus me-1"></i> Đăng ký</a></li>
                        <li><a class="dropdown-item" href="dangnhap.php"><i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <!-- Header chính -->
    <header class="main-header py-2" style="background-color: #2a2a2a; position: relative; z-index: 1030;">
        <div class="container d-flex align-items-center justify-content-between">
            <!-- Logo -->
            <a href="index.php" class="d-block me-3">
                <img src="images/logo.png" alt="Blue Eagle" style="height: 100px;">
            </a>

            <!-- Tìm kiếm -->
            <form action="giay.php" method="GET" class="w-50" id="form-timkiem">
    <div class="input-group">
        <input type="text" id="tu_khoa" name="tu_khoa" class="form-control" placeholder="Tìm kiếm..." autocomplete="off">
        <button class="btn btn-primary" type="submit" id="btn-timkiem">
            <i class="bi bi-search"></i>
        </button>
    </div>
    <div id="suggestion-box"></div>
</form>
            <script src="js/timkiem.js"></script>

            <!-- Tài khoản + Giỏ hàng -->
            <div class="d-flex align-items-center gap-3">
                <a href="<?php echo isset($_SESSION['taikhoan']) ? 'hoso.php' : 'dangnhap.php'; ?>" class="text-white text-decoration-none">
                    <i class="bi bi-person fs-5"></i>
                </a>
                <a href="giohang.php" class="text-white position-relative">
                    <i class="bi bi-cart fs-5"></i>
                    <?php
                    $tong_so_luong = 0;
                    if (!empty($_SESSION['giohang'])) {
                        foreach ($_SESSION['giohang'] as $sp) {
                            if (isset($sp['so_luong'])) {
                                $tong_so_luong += $sp['so_luong'];
                            }
                        }
                    }
                    ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?= $tong_so_luong ?>
                    </span>
                </a>
            </div>
        </div>
    </header>
</div>

<!-- CSS bổ sung -->
<style>
    .dropdown-menu a.dropdown-item:hover {
        background-color: #f57c00;
        color: #fff;
    }
    .badge.bg-danger {
        font-size: 0.75rem;
        padding: 4px 6px;
        font-weight: bold;
        box-shadow: 0 0 5px rgba(255, 0, 0, 0.6);
    }
</style>
