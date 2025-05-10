<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'includes/cauhinh.php';
?>
<!-- Wrapper -->
<div>
    <!-- Thanh trên cùng -->
    <div class="top-bar text-white py-1" style="background-color: #1e1e1e; font-size: 0.9rem; position: relative; z-index: 1040;">
        <div class="container d-flex justify-content-between align-items-center">
            <span>HỆ THỐNG BÁN GIÀY CỦA CLB BÓNG ĐÁ BLUE EAGLE HÌNH THÀNH VÀ PHÁT TRIỂN</span>

            <!-- Dropdown tài khoản -->
            <div class="dropdown">
                <a class="dropdown-toggle text-white text-decoration-none" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person"></i>
                    <?php
                    if (isset($_SESSION['taikhoan'])) {
                        echo "Xin chào: " . (!empty($_SESSION['ho_ten']) ? htmlspecialchars($_SESSION['ho_ten']) : htmlspecialchars($_SESSION['taikhoan']));
                    } else {
                        echo "Tài khoản";
                    }
                    ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <?php if (isset($_SESSION['taikhoan'])): ?>
                        <li><a class="dropdown-item text-danger" href="dangxuat.php">Đăng xuất</a></li>
                        <li><a class="dropdown-item" href="giohang.php">Giỏ hàng</a></li>
                        <li><a class="dropdown-item" href="thanhtoan.php">Thanh toán</a></li>
                        <li><a class="dropdown-item" href="tracuu.php">Tra cứu đơn hàng</a></li>
                    <?php else: ?>
                        <li><a class="dropdown-item" href="dangky.php">Đăng ký</a></li>
                        <li><a class="dropdown-item" href="dangnhap.php">Đăng nhập</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <!-- Header chính -->
    <header class="main-header py-2" style="background-color: #2a2a2a; position: relative; z-index: 1030;">
        <div class="container d-flex align-items-center justify-content-between">
            <!-- Logo -->
            <div class="d-flex align-items-center">
                <a href="index.php" class="d-block me-2">
                    <img src="images/logo.png" alt="Blue Eagle" style="height: 100px;">
                </a>
            </div>

            <!-- Tìm kiếm -->
            <form action="tim_kiem.php" method="GET" class="d-flex w-50">
                <input type="text" name="tu_khoa" class="form-control me-2 bg-dark text-white border-0" placeholder="Tìm kiếm...">
                <button class="btn btn-outline-light" type="submit"><i class="bi bi-search"></i></button>
            </form>

            <!-- Tài khoản + Giỏ hàng -->
            <div class="d-flex align-items-center gap-3">
                <a href="<?php echo isset($_SESSION['taikhoan']) ? 'hoso.php' : 'dangnhap.php'; ?>" class="text-white text-decoration-none">
                    <i class="bi bi-person"></i>
                </a>
                <a href="gio_hang.php" class="text-white position-relative">
                    <i class="bi bi-cart fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        0
                    </span>
                </a>
            </div>
        </div>
    </header>
</div>
