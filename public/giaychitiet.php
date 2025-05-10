<?php
include 'includes/cauhinh.php';

$id = $_GET['id'] ?? 0;

// Lấy thông tin giày
$sql = "SELECT g.*, th.ten_thuong_hieu, lg.ten_loai
        FROM giay g
        JOIN thuong_hieu th ON g.thuong_hieu_id = th.id
        JOIN loai_giay lg ON g.loai_giay_id = lg.id
        WHERE g.id = $id";
$giay = $conn->query($sql)->fetch_assoc();

// Lấy size còn hàng
$sizes = $conn->query("SELECT size, so_luong_ton FROM size_giay WHERE giay_id = $id AND so_luong_ton > 0 ORDER BY size");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title><?= $giay['ten_giay'] ?> - Chi tiết</title>
  <link rel="icon" type="image/png" href="images/logo.png">
  <script src="js/dropdown-hover.js"></script>

  <!-- Bootstrap CSS & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Your custom CSS -->
  <link rel="stylesheet" href="css/style.css">

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/back-to-top.js"></script>
</head>
<body class="bg-gradient-gray-black text-white">
<?php include 'includes/header.php'; ?>
<?php include 'includes/nav.php'; ?>

<div class="container my-5">
  <div class="row">
    <div class="col-md-6">
      <img src="images/<?= $giay['hinh_anh'] ?>" class="img-fluid rounded shadow">
    </div>
    <div class="col-md-6">
      <h2><?= $giay['ten_giay'] ?></h2>
      <p><strong>Thương hiệu:</strong> <?= $giay['ten_thuong_hieu'] ?></p>
      <p><strong>Loại:</strong> <?= $giay['ten_loai'] ?></p>

      <?php if ($giay['ti_le_giam_gia'] > 0): ?>
        <p>
          <span class="text-decoration-line-through text-secondary">
            <?= number_format($giay['don_gia']) ?> đ
          </span>
          <span class="text-danger fw-bold ms-2">
            <?= number_format($giay['don_gia'] * (1 - $giay['ti_le_giam_gia'])) ?> đ
          </span>
        </p>
      <?php else: ?>
        <p class="text-danger fw-bold"><?= number_format($giay['don_gia']) ?> đ</p>
      <?php endif; ?>

      <p><?= nl2br($giay['mo_ta']) ?></p>

      <div class="mt-3">
        <strong>Chọn size còn hàng:</strong><br>
        <?php while($sz = $sizes->fetch_assoc()): ?>
          <button type="button" class="btn btn-outline-light btn-size me-1 mb-1" data-size="<?= $sz['size'] ?>">
            <?= $sz['size'] ?>
          </button>
        <?php endwhile; ?>
      </div>

      <div class="mt-4">
  <?php if (isset($_SESSION['taikhoan'])): ?>
    <!-- Đã đăng nhập: hiện nút thao tác -->
    <button class="btn btn-warning me-2" data-bs-toggle="modal" data-bs-target="#modalThemVaoGio">
      🛒 Thêm vào giỏ
    </button>

    <form action="muahang.php" method="get" class="d-inline">
      <input type="hidden" name="id" value="<?= $giay['id'] ?>">
      <input type="hidden" name="size" id="mua-ngay-size" value="">
      <input type="hidden" name="soluong" value="1">
      <button type="submit" class="btn btn-danger">💳 Mua ngay</button>
    </form>
  <?php else: ?>
    <!-- Chưa đăng nhập: chuyển đến trang đăng nhập -->
    <a href="dangnhap.php" class="btn btn-outline-light me-2">🛒 Thêm vào giỏ</a>
    <a href="dangnhap.php" class="btn btn-outline-danger">💳 Mua ngay</a>
  <?php endif; ?>
</div>

    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>

<!-- Nút quay lại đầu trang -->
<button type="button" class="btn btn-warning btn-lg rounded-circle shadow back-to-top" id="btn-back-to-top">
  <i class="bi bi-arrow-up"></i>
</button>

<!-- Modal nhập số lượng -->
<div class="modal fade" id="modalThemVaoGio" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="themvaogiohang.php" method="get" class="modal-content bg-dark text-white">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="modalLabel">Chọn số lượng</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" value="<?= $giay['id'] ?>">
        <input type="hidden" name="size" id="modal-size" value="">
        <div class="mb-3">
          <label for="soluong" class="form-label">Số lượng</label>
          <input type="number" name="soluong" id="soluong" class="form-control bg-dark text-white border-secondary" value="1" min="1" required>
        </div>
      </div>
      <div class="modal-footer border-0">
        <button type="submit" class="btn btn-success">🛒 Thêm vào giỏ</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
      </div>
    </form>
  </div>
</div>

<!-- JS xử lý chọn size -->
<script>
  const sizeButtons = document.querySelectorAll('.btn-size');
  let selectedSize = null;

  sizeButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      // Bỏ class active ở tất cả
      sizeButtons.forEach(b => b.classList.remove('btn-warning'));
      btn.classList.add('btn-warning');

      selectedSize = btn.dataset.size;

      // Gán giá trị cho cả 2 form
      document.getElementById('modal-size').value = selectedSize;
      document.getElementById('mua-ngay-size').value = selectedSize;
    });
  });
</script>

</body>
</html>
