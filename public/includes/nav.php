<?php include 'includes/cauhinh.php'; ?>
<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow">
  <div class="container d-flex justify-content-center">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-center" id="mainNav">
      <ul class="navbar-nav text-center">
        <li class="nav-item px-2">
          <a class="nav-link" href="index.php"><i class="bi bi-house-door"></i> TRANG CHỦ</a>
        </li>
        <li class="nav-item px-2">
          <a class="nav-link" href="gioithieu.php"><i class="bi bi-info-circle"></i> VỀ BLUE EAGLE</a>
        </li>
        <li class="nav-item px-2">
          <a class="nav-link" href="giay.php"><i class="bi bi-bag"></i> TẤT CẢ SẢN PHẨM</a>
        </li>

        <!-- GIÀY BÓNG ĐÁ -->
        <li class="nav-item dropdown px-2">
          <a class="nav-link dropdown-toggle" href="#" id="loaiGiayDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-joystick"></i> LOẠI GIÀY
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
        </li>

        <!-- THƯƠNG HIỆU -->
        <li class="nav-item dropdown px-2">
          <a class="nav-link dropdown-toggle" href="#" id="thuongHieuDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-award"></i> THƯƠNG HIỆU
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
        </li>

        <li class="nav-item px-2">
          <a class="nav-link" href="donhang.php"><i class="bi bi-box-seam"></i> TRA CỨU ĐƠN HÀNG</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- CSS tùy chỉnh -->
<style>
  .navbar-nav .nav-link {
    white-space: nowrap;
    font-weight: 500;
  }

  .dropdown-menu {
    background-color: #343a40;
    border: none;
    min-width: 180px;
    padding: 0;
  }

  .dropdown-item {
    color: #fff;
    padding: 10px 20px;
  }

  .dropdown-item:hover,
  .dropdown-item:focus {
    background-color: #f57c00;
    color: #fff;
  }

  .dropdown-item.active {
    background-color: #e67e22;
    color: #fff;
  }
</style>
