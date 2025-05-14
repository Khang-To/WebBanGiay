<?php
include 'includes/cauhinh.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Lấy thông tin giày
$sql = "SELECT g.*, th.ten_thuong_hieu, lg.ten_loai
        FROM giay g
        JOIN thuong_hieu th ON g.thuong_hieu_id = th.id
        JOIN loai_giay lg ON g.loai_giay_id = lg.id
        WHERE g.id = $id";
$giay = $conn->query($sql)->fetch_assoc();

if (!$giay) {
  echo "Không tìm thấy sản phẩm.";
  exit;
}

$giaSauGiam = $giay['ti_le_giam_gia'] > 0
    ? $giay['don_gia'] * (1 - $giay['ti_le_giam_gia'] / 100)
    : $giay['don_gia'];

// Lấy size còn hàng
$sizes = $conn->query("SELECT size, so_luong_ton FROM size_giay WHERE giay_id = $id ORDER BY size");

// Kiểm tra còn hàng không
$conHang = false;
$sizesCheck = $conn->query("SELECT so_luong_ton FROM size_giay WHERE giay_id = $id");
while ($row = $sizesCheck->fetch_assoc()) {
  if ($row['so_luong_ton'] > 0) {
    $conHang = true;
    break;
  }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($giay['ten_giay']) ?> - Chi tiết</title>
  <link rel="icon" type="image/png" href="images/logo.png">
  <script src="js/dropdown-hover.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/back-to-top.js"></script>
</head>
<body class="bg-gradient-gray-black text-white">

<?php include 'includes/header.php'; ?>
<?php include 'includes/nav.php'; ?>

<div class="container my-5">
  <div class="row">
    <div class="col-md-6">
<img src="../uploads/<?= htmlspecialchars($giay['hinh_anh']) ?>" class="img-fluid rounded shadow">
    </div>
    <div class="col-md-6">
      <h2><?= htmlspecialchars($giay['ten_giay']) ?></h2>
      <p><strong>Thương hiệu:</strong> <?= htmlspecialchars($giay['ten_thuong_hieu']) ?></p>
      <p><strong>Loại:</strong> <?= htmlspecialchars($giay['ten_loai']) ?></p>

      <?php if ($giay['ti_le_giam_gia'] > 0): ?>
        <p>
          <span class="text-decoration-line-through text-secondary"><?= number_format($giay['don_gia']) ?> đ</span>
          <span class="text-danger fw-bold ms-2"><?= number_format($giaSauGiam) ?> đ</span>
        </p>
      <?php else: ?>
        <p class="text-danger fw-bold"><?= number_format($giaSauGiam) ?> đ</p>
      <?php endif; ?>

      <div class="mt-3">
        <strong>Chọn size còn hàng:</strong><br>
        <?php if ($sizes->num_rows == 0): ?>
          <p class="text-danger mt-2">Sản phẩm hiện đang <strong>hết hàng</strong>.</p>
        <?php else: ?>
          <?php while($sz = $sizes->fetch_assoc()): ?>
            <?php if ($sz['so_luong_ton'] > 0): ?>
              <button type="button" class="btn btn-outline-light btn-size me-1 mb-1" data-size="<?= $sz['size'] ?>">
                <?= $sz['size'] ?>
              </button>
            <?php else: ?>
              <button type="button" class="btn btn-outline-secondary me-1 mb-1" disabled>
                <?= $sz['size'] ?> (Hết hàng)
              </button>
            <?php endif; ?>
          <?php endwhile; ?>
        <?php endif; ?>
      </div>

      <div class="mt-4">
        <!-- Modal thêm giỏ hàng -->
        <div class="modal fade" id="modalThemVaoGio" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-white bg-dark">
              <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">🛒 Thêm vào giỏ hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
              </div>
              <div class="modal-body">
                <form id="form-them-vao-gio" action="themvaogiohang.php" method="get">
                  <input type="hidden" name="id" value="<?= $giay['id'] ?>">
                  <input type="hidden" name="size" id="selected-size" value="">
                  <input type="hidden" name="gia" value="<?= $giaSauGiam ?>">
                  <div class="mb-3">
                    <label for="soluong" class="form-label">Số lượng:</label>
                    <input type="number" class="form-control" name="soluong" id="input-soluong" value="1" min="1" required>
                  </div>
                  <button type="submit" class="btn btn-warning w-100">✔️ Xác nhận thêm vào giỏ</button>
                </form>
              </div>
            </div>
          </div>
        </div>

        <!-- Nút thêm giỏ / mua ngay -->
        <?php if (isset($_SESSION['taikhoan'])): ?>
          <button type="button"
                  class="btn btn-warning me-2 <?= $conHang ? '' : 'disabled opacity-50' ?>"
                  id="btn-open-modal" <?= $conHang ? '' : 'disabled' ?>>
            🛒 Thêm vào giỏ
          </button>
          <form action="muahang.php" method="get" class="d-inline">
            <input type="hidden" name="id" value="<?= $giay['id'] ?>">
            <input type="hidden" name="size" id="mua-ngay-size" value="">
            <input type="hidden" name="soluong" value="1">
            <button type="submit"
                    class="btn btn-danger <?= $conHang ? '' : 'disabled opacity-50' ?>"
                    <?= $conHang ? '' : 'disabled' ?>>
              💳 Mua ngay
            </button>
          </form>
        <?php else: ?>
          <a href="dangnhap.php" class="btn btn-outline-light me-2 <?= $conHang ? '' : 'disabled opacity-50' ?>">🛒 Thêm vào giỏ</a>
          <a href="dangnhap.php" class="btn btn-outline-danger <?= $conHang ? '' : 'disabled opacity-50' ?>">💳 Mua ngay</a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Mô tả -->
  <div class="row mt-5">
    <div class="col-12">
      <hr class="border-secondary">
      <h5 class="mb-3">📝 Mô tả sản phẩm</h5>
      <p><?= nl2br(htmlspecialchars($giay['mo_ta'])) ?></p>
    </div>
  </div>
</div>

<?php include 'includes/giaylienquan.php'; ?>
<?php include 'includes/footer.php'; ?>

<!-- Nút quay lại đầu trang -->
<button type="button" class="btn btn-warning btn-lg rounded-circle shadow back-to-top" id="btn-back-to-top">
  <i class="bi bi-arrow-up"></i>
</button>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const sizeButtons = document.querySelectorAll('.btn-size');
  const inputSelectedSize = document.getElementById('selected-size');
  const muaNgaySizeInput = document.getElementById('mua-ngay-size');
  const openModalBtn = document.getElementById('btn-open-modal');
  const inputSoLuong = document.getElementById('input-soluong');
  const modal = new bootstrap.Modal(document.getElementById('modalThemVaoGio'));

  let selectedSize = '';

  // Khi chọn size
  sizeButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      sizeButtons.forEach(b => b.classList.remove('btn-warning'));
      btn.classList.add('btn-warning');

      selectedSize = btn.dataset.size;
      inputSelectedSize.value = selectedSize;
      muaNgaySizeInput.value = selectedSize;
    });
  });

  // Mở modal nếu đã chọn size
  openModalBtn.addEventListener('click', () => {
    if (!inputSelectedSize.value.trim()) {
      alert("⚠️ Vui lòng chọn size trước khi thêm vào giỏ hàng.");
      return;
    }
    modal.show();
  });
});
</script>
</body>
</html>
