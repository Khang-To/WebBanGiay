<?php
include_once 'includes/cauhinh.php';

$sql_moi = "SELECT * FROM giay ORDER BY id DESC LIMIT 18";
$kq_moi = mysqli_query($conn, $sql_moi);

// Lưu kết quả vào mảng
$ds_giay = [];
while ($row = mysqli_fetch_assoc($kq_moi)) {
    $ds_giay[] = $row;
}
?>

<style>
/* Ẩn thanh cuộn trên mọi trình duyệt */
.hide-scrollbar {
  scrollbar-width: none; /* Firefox */
}
.hide-scrollbar::-webkit-scrollbar {
  display: none; /* Chrome, Safari */
}

/* Nút điều hướng */
.custom-carousel-btn {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  z-index: 10;
  width: 45px;
  height: 45px;
  background-color: #ffc107;
  border: none;
  border-radius: 50%;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
  opacity: 0.9;
  transition: all 0.3s ease;
}
.custom-carousel-btn:hover {
  opacity: 1;
  transform: translateY(-50%) scale(1.1);
}
.left-btn { left: -20px; }
.right-btn { right: -20px; }
</style>

<section class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-white fw-bold">HÀNG MỚI VỀ</h2>
    <a href="giay.php" class="btn btn-outline-warning">Xem tất cả</a>
  </div>

  <div class="position-relative">
    <!-- Nút điều hướng -->
    <button class="custom-carousel-btn left-btn" onclick="scrollHangMoi(-1)">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    </button>
    <button class="custom-carousel-btn right-btn" onclick="scrollHangMoi(1)">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
    </button>

    <!-- Danh sách giày dạng ngang -->
    <div id="hangMoiList" class="d-flex overflow-auto scroll-smooth hide-scrollbar">
      <?php foreach ($ds_giay as $row): 
        $gia_cu = $row['don_gia'];
        $giam = $row['ti_le_giam_gia'];
        $gia_moi = $gia_cu * (1 - $giam / 100);
      ?>
      <div class="me-3" style="width: 170px; flex-shrink: 0;">
        <a href="giaychitiet.php?id=<?= $row['id'] ?>" class="text-decoration-none text-white">
          <div class="card h-100 bg-dark text-white border-0 shadow-sm position-relative">
            <?php if ($giam > 0): ?>
              <div class="position-absolute top-0 start-0 bg-danger text-white px-2 py-1 small rounded-end">
                -<?= $giam ?>%
              </div>
            <?php endif; ?>
          <img src="../uploads/<?= htmlspecialchars($row['hinh_anh']) ?>"
            class="card-img-top" 
            alt="<?= htmlspecialchars($row['ten_giay']) ?>" 
            style="height: 200px; object-fit: contain;">
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
      <?php endforeach; ?>
    </div>
  </div>
</section>
<script>
function scrollHangMoi(direction) {
  const container = document.getElementById('hangMoiList');
  const scrollAmount = 180; // chiều rộng mỗi item + margin

  container.scrollBy({
    left: direction * scrollAmount,
    behavior: 'smooth'
  });
}
</script>

