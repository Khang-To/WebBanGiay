<?php include 'cauhinh.php'; ?>

<section class="container py-5 text-light" style="background-color: #111;">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-danger mb-0">🔥 GIẢM GIÁ SỐC</h2>
    <a href="giay.php?giamgia=1" class="btn btn-outline-light">Xem tất cả</a>
  </div>

  <div id="giamGiaCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <?php
      $sql = "SELECT * FROM giay WHERE ti_le_giam_gia > 0 ORDER BY ti_le_giam_gia DESC";
      $result = mysqli_query($conn, $sql);
      $active = true;
      $count = 0;

      while ($row = mysqli_fetch_assoc($result)) {
          $gia_goc = $row['don_gia'];
          $giam = $row['ti_le_giam_gia'];
          $gia_giam = $gia_goc * (1 - $giam / 100);

          if ($count % 4 == 0) {
              if ($count > 0) echo '</div></div>'; // đóng item trước
              echo '<div class="carousel-item ' . ($active ? 'active' : '') . '"><div class="row g-4">';
              $active = false;
          }
      ?>
      <div class="col-md-3">
        <a href="giaychitiet.php?id=<?= $row['id'] ?>" class="text-decoration-none">
          <div class="card bg-dark text-white h-100 shadow-sm border-0 position-relative">
            <div class="position-absolute top-0 start-0 bg-danger px-2 py-1 text-white small rounded-end">
              -<?= $giam ?>%
            </div>
            <img src="hinhanh.php?file=<?php echo urlencode($row['hinh_anh']); ?>" class="card-img-top" alt="<?= $row['ten_giay'] ?>" style="height: 200px; object-fit: cover;">
            <div class="card-body">
              <h6 class="card-title text-truncate"><?= $row['ten_giay'] ?></h6>
              
              <div class="mb-1" style="font-size: 0.8rem;">
                <span class="text-muted text-decoration-line-through"><?= number_format($gia_goc, 0, ',', '.') ?>đ</span>
              </div>
              
              <div class="fw-bold" style="font-size: 1.1rem; color: #ffc107;">
                <?= number_format($gia_giam, 0, ',', '.') ?>đ
              </div>
            </div>
          </div>
        </a>
      </div>
      <?php
          $count++;
      }

      if ($count > 0) echo '</div></div>'; // đóng item cuối
      ?>
    </div>

    <!-- Carousel controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#giamGiaCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
      <span class="visually-hidden">Trước</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#giamGiaCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
      <span class="visually-hidden">Sau</span>
    </button>
  </div>
</section>
