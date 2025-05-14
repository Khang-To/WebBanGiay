<?php
include_once 'cauhinh.php';

$sql_moi = "SELECT * FROM giay ORDER BY id DESC LIMIT 10";
$kq_moi = mysqli_query($conn, $sql_moi);
?>

<section class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-white fw-bold">HÀNG MỚI VỀ</h2>
    <a href="giay.php" class="btn btn-outline-warning">Xem tất cả</a>
  </div>

  <div id="hangMoiCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <?php
      $count = 0;
      while ($row = mysqli_fetch_assoc($kq_moi)) {
          if ($count % 5 == 0) {
              if ($count > 0) echo '</div></div>'; // đóng slide cũ
              echo '<div class="carousel-item'.($count == 0 ? ' active' : '').'"><div class="row g-4">';
          }

          $gia_cu = $row['don_gia'];
          $giam = $row['ti_le_giam_gia'];
          $gia_moi = $gia_cu * (1 - $giam / 100);
      ?>
      <div class="col-6 col-md-4 col-lg-2">
        <a href="giaychitiet.php?id=<?= $row['id'] ?>" class="text-decoration-none text-white">
          <div class="card h-100 bg-dark text-white border-0 shadow-sm position-relative">
            <?php if ($giam > 0): ?>
              <div class="position-absolute top-0 start-0 bg-danger text-white px-2 py-1 small rounded-end">
                -<?= $giam ?>%
              </div>
            <?php endif; ?>
            <img src="hinhanh.php?file=<?= urlencode($row['hinh_anh']) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['ten_giay']) ?>">
            <div class="card-body">
              <h6 class="card-title text-truncate mb-2"><?= $row['ten_giay'] ?></h6>
              <?php if ($giam > 0): ?>
                <div class="text-muted small text-decoration-line-through" style="font-size: 0.75rem;">
                  <?= number_format($gia_cu, 0, ',', '.') ?>đ
                </div>
              <?php endif; ?>
              <div class="fw-bold" style="color: #ffc107; font-size: 1rem;">
                <?= number_format($gia_moi, 0, ',', '.') ?>đ
              </div>
            </div>
          </div>
        </a>
      </div>
      <?php
          $count++;
      }
      if ($count > 0) echo '</div></div>'; // đóng slide cuối
      ?>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#hangMoiCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#hangMoiCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
    </button>
  </div>
</section>
