<?php
    include 'includes/auth_admin.php';      // Kiểm tra đăng nhập
    include 'includes/db.php';              // Kết nối CSDL
    include 'includes/header.php';          // Giao diện đầu trang

    // Truy vấn thống kê
    $tong_don_hang = $conn->query("SELECT COUNT(*) FROM don_hang")->fetch_row()[0];
    $tong_khach = $conn->query("SELECT COUNT(*) FROM khach_hang")->fetch_row()[0];
    $tong_san_pham = $conn->query("SELECT COUNT(*) FROM giay")->fetch_row()[0];
?>

<h2 class="mb-4"> 
    <img src="images/chart-png.webp" width="30" class="me-2">
    Tổng quan hệ thống
</h2>
<div class="row">
    <!-- card đơn hàng -->
    <div class="col-md-4">
        <div class="card bg-primary text-white mb-3 shadow">
            <div class="card-body">
                <h5 class="card-title">Tổng đơn hàng (<?= $tong_don_hang ?>)</h5>
                <hr class="my-2 border-light">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="danhsachdonhang.php" class="text-white text-decoration-none">Chi tiết</a>
                    <span class="text-white fw-bold">&raquo;</span>
                </div>
            </div>
        </div>
    </div>
     <!-- card khách hàng -->
    <div class="col-md-4">
        <div class="card bg-success text-white mb-3 shadow">
            <div class="card-body">
                <h5 class="card-title">Tổng khách hàng (<?= $tong_khach ?>)</h5>
                <hr class="my-2 border-light">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="danhsach_khachhang.php" class="text-white text-decoration-none">Chi tiết</a>
                    <span class="text-white fw-bold">&raquo;</span>
                </div>
            </div>
        </div>
    </div>
     <!-- card sản phẩm -->
    <div class="col-md-4">
        <div class="card bg-warning text-white mb-3 shadow">
            <div class="card-body">
                <h5 class="card-title">Tổng sản phẩm (<?= $tong_san_pham ?>)</h5>
                <hr class="my-2 border-light">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="danhsachgiay.php" class="text-white text-decoration-none">Chi tiết</a>
                    <span class="text-white fw-bold">&raquo;</span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
