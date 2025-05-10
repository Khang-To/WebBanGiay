<?php
include 'includes/cauhinh.php';
?>
<nav class="main-menu py-2 sticky-top bg-dark shadow" style="z-index: 1020;">
    <div class="container d-flex">
        <a href="index.php">TRANG CHỦ</a>
        <a href="gioithieu.php">VỀ BLUE EAGLE</a>

        <!-- GIÀY BÓNG ĐÁ Dropdown -->
        <div class="dropdown me-2">
            <a class="dropdown-toggle text-white text-decoration-none" href="#" role="button" id="loaiGiay" data-bs-toggle="dropdown" aria-expanded="false">
                GIÀY BÓNG ĐÁ
            </a>
            <ul class="dropdown-menu">
                <?php
                $sql_loai = "SELECT * FROM loai_giay";
                $kq_loai = mysqli_query($conn, $sql_loai);
                while ($row_loai = mysqli_fetch_assoc($kq_loai)) {
                    echo '<li><a class="dropdown-item" href="giay.php?loai_giay=' . $row_loai['id'] . '">' . $row_loai['ten_loai'] . '</a></li>';
                }
                ?>
            </ul>
        </div>

        <!-- THƯƠNG HIỆU Dropdown -->
        <div class="dropdown me-2">
            <a class="dropdown-toggle text-white text-decoration-none" href="#" role="button" id="thuongHieu" data-bs-toggle="dropdown" aria-expanded="false">
                THƯƠNG HIỆU
            </a>
            <ul class="dropdown-menu">
                <?php
                $sql_th = "SELECT * FROM thuong_hieu";
                $kq_th = mysqli_query($conn, $sql_th);
                while ($row_th = mysqli_fetch_assoc($kq_th)) {
                    echo '<li><a class="dropdown-item" href="giay.php?thuong_hieu=' . $row_th['id'] . '">' . $row_th['ten_thuong_hieu'] . '</a></li>';
                }
                ?>
            </ul>
        </div>


        <a href="#">PHỤ KIỆN</a>
        <a href="#">HƯỚNG DẪN</a>
        <a href="#">TIN TỨC GIÀY</a>
        <a href="#">HỆ THỐNG CỬA HÀNG</a>
        <a href="#">TUYỂN DỤNG</a>
    </div>
</nav>
<script>
    document.getElementById('thuongHieu').addEventListener('click', function (e) {
        window.location.href = 'giay.php';
    });
    document.getElementById('loaiGiay').addEventListener('click', function (e) {
        window.location.href = 'giay.php';
    });
</script>

