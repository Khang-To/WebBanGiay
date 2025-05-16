<?php
// Lấy các sản phẩm liên quan cùng thương hiệu (ngoại trừ chính sản phẩm này)
$thuonghieu_id = $giay['thuong_hieu_id'];
$giay_lien_quan_sql = "SELECT id, ten_giay, hinh_anh, don_gia, ti_le_giam_gia 
                       FROM giay 
                       WHERE thuong_hieu_id = $thuonghieu_id AND id != $id 
                       LIMIT 6"; // Lấy 6 để kiểm tra có hơn 5 không

$giay_lien_quan = $conn->query($giay_lien_quan_sql);
?>

<?php if ($giay_lien_quan->num_rows > 0): ?>
  <div class="container mt-5 bg-dark p-4 rounded">
    <h4 class="text-white mb-3">Sản phẩm cùng thương hiệu</h4>
    <div class="row">
      <?php
      $dem = 0;
      while ($sp = $giay_lien_quan->fetch_assoc()):
        if ($dem >= 5) break; // Hiển thị tối đa 5
        $giaGiam = $sp['ti_le_giam_gia'] > 0 ? $sp['don_gia'] * (1 - $sp['ti_le_giam_gia'] / 100) : $sp['don_gia'];
        ?>
        <div class="col-md-2 col-6 mb-4">
  <a href="giaychitiet.php?id=<?= $sp['id'] ?>" class="text-decoration-none text-white">
    <div class="card bg-dark text-white h-100 shadow-sm border-light">
      <img src="hinh.php?file=<?= urlencode(htmlspecialchars($sp['hinh_anh'])) ?>" 
     class="card-img-top" 
     style="height: 160px; object-fit: cover;">
      <div class="card-body p-2">
        <h6 class="card-title mb-1" style="font-size: 14px;"><?= htmlspecialchars($sp['ten_giay']) ?></h6>
        <?php if ($sp['ti_le_giam_gia'] > 0): ?>
          <small class="text-decoration-line-through text-secondary"><?= number_format($sp['don_gia'], 0, ',', '.') ?> đ</small><br>
          <strong class="text-danger"><?= number_format($giaGiam, 0, ',', '.') ?> đ</strong>
        <?php else: ?>
          <strong class="text-danger"><?= number_format($sp['don_gia'], 0, ',', '.') ?> đ</strong>
        <?php endif; ?>
      </div>
    </div>
  </a>
</div>
        <?php $dem++; endwhile; ?>
    </div>

    <?php if ($giay_lien_quan->num_rows > 5): ?>
      <div class="text-end">
        <a href="giay.php?thuonghieu_id=<?= $thuonghieu_id ?>" class="btn btn-outline-light">Xem thêm →</a>
      </div>
    <?php endif; ?>
  </div>
<?php endif; ?>
