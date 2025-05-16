<?php
include 'includes/cauhinh.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Lấy thông tin giày
$sql = "SELECT g.*, th.ten_thuong_hieu, lg.ten_loai
        FROM giay g
        JOIN thuong_hieu th ON g.thuong_hieu_id = th.id
        JOIN loai_giay lg ON g.loai_giay_id = lg.id
        WHERE g.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$giay = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$giay) {
  echo "Không tìm thấy sản phẩm.";
  exit;
}

$giaSauGiam = $giay['ti_le_giam_gia'] > 0
    ? $giay['don_gia'] * (1 - $giay['ti_le_giam_gia'] / 100)
    : $giay['don_gia'];

// Lấy size còn hàng
$stmt = $conn->prepare("SELECT size, so_luong_ton FROM size_giay WHERE giay_id = ? ORDER BY size");
$stmt->bind_param("i", $id);
$stmt->execute();
$sizes = $stmt->get_result();
$stmt->close();

// Kiểm tra còn hàng không
$conHang = false;
$stmt = $conn->prepare("SELECT so_luong_ton FROM size_giay WHERE giay_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$sizesCheck = $stmt->get_result();
while ($row = $sizesCheck->fetch_assoc()) {
  if ($row['so_luong_ton'] > 0) {
    $conHang = true;
    break;
  }
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($giay['ten_giay']) ?> - Chi tiết</title>
  <link rel="icon" type="image/png" href="images/logo.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/dropdown-hover.js"></script>
  <script src="js/back-to-top.js"></script>
  <script src="js/image-zoom.js"></script>
</head>
<body class="bg-dark text-white">
<?php include 'includes/header.php'; ?>
<?php include 'includes/nav.php'; ?>

<div class="container my-5">
  <div class="row">
    <div class="col-md-6">
      <img src="../uploads/<?= htmlspecialchars($giay['hinh_anh']) ?>" class="img-fluid rounded shadow" data-zoom-image alt="<?= htmlspecialchars($giay['ten_giay']) ?>" style="cursor: pointer;">
    </div>
    <div class="col-md-6">
      <h2><?= htmlspecialchars($giay['ten_giay']) ?></h2>
      <p><strong>Thương hiệu:</strong> <?= htmlspecialchars($giay['ten_thuong_hieu']) ?></p>
      <p><strong>Loại:</strong> <?= htmlspecialchars($giay['ten_loai']) ?></p>

      <?php if ($giay['ti_le_giam_gia'] > 0): ?>
        <p>
          <span class="text-decoration-line-through text-secondary"><?= number_format($giay['don_gia'], 0, ',', '.') ?> đ</span>
          <span class="text-danger fw-bold ms-2"><?= number_format($giaSauGiam, 0, ',', '.') ?> đ</span>
        </p>
      <?php else: ?>
        <p class="text-danger fw-bold"><?= number_format($giaSauGiam, 0, ',', '.') ?> đ</p>
      <?php endif; ?>

      <div class="mt-3">
        <strong>Chọn size còn hàng:</strong><br>
        <?php if ($sizes->num_rows == 0): ?>
          <p class="text-danger mt-2">Sản phẩm hiện đang <strong>hết hàng</strong>.</p>
        <?php else: ?>
          <?php while($sz = $sizes->fetch_assoc()): ?>
            <?php if ($sz['so_luong_ton'] > 0): ?>
              <button type="button"
                      class="btn btn-outline-light btn-size me-1 mb-1"
                      data-size="<?= $sz['size'] ?>"
                      data-quantity="<?= $sz['so_luong_ton'] ?>">
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
              <div class="modal-header border-secondary">
                <h5 class="modal-title" id="modalLabel">🛒 Thêm vào giỏ hàng</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
              </div>
              <div class="modal-body">
                <form id="form-them-vao-gio" action="themvaogiohang.php" method="get">
                  <input type="hidden" name="id" value="<?= $giay['id'] ?>">
                  <input type="hidden" name="size" id="selected-size" value="">
                  <input type="hidden" name="gia" value="<?= $giaSauGiam ?>">
                  <div class="mb-3">
                    <label for="soluong" class="form-label">Số lượng:</label>
                    <input type="number" class="form-control bg-dark text-white border-secondary" name="soluong" id="input-soluong" value="1" min="1" required>
                  </div>
                  <button type="submit" class="btn btn-warning w-100">✔️ Xác nhận thêm vào giỏ</button>
                </form>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal Đặt hàng ngay -->
        <div class="modal fade" id="modalDatHangNgay" tabindex="-1" aria-labelledby="modalLabelDatHang" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-white bg-dark">
              <div class="modal-header border-secondary">
                <h5 class="modal-title" id="modalLabelDatHang">💳 Đặt hàng ngay</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
              </div>
              <div class="modal-body">
                <form id="form-dat-hang-ngay" action="dathangngay.php" method="get">
                  <input type="hidden" name="id" value="<?= $giay['id'] ?>">
                  <input type="hidden" name="size" id="selected-size-mua" value="">
                  <input type="hidden" name="gia" value="<?= $giaSauGiam ?>">
                  <div class="mb-3">
                    <label for="soluong-mua" class="form-label">Số lượng:</label>
                    <input type="number" class="form-control bg-dark text-white border-secondary" name="soluong" id="input-soluong-mua" value="1" min="1" required>
                  </div>
                  <button type="submit" class="btn btn-danger w-100">💳 Xác nhận đặt hàng</button>
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
            <button type="button"
                    class="btn btn-danger <?= $conHang ? '' : 'disabled opacity-50' ?>"
                    id="btn-open-modal-dathang"
                    <?= $conHang ? '' : 'disabled' ?>>
              💳 Đặt hàng
            </button>
          </form>
        <?php else: ?>
          <a href="dangnhap.php" class="btn btn-outline-light me-2 <?= $conHang ? '' : 'disabled opacity-50' ?>">🛒 Thêm vào giỏ</a>
          <a href="dangnhap.php" class="btn btn-outline-danger <?= $conHang ? '' : 'disabled opacity-50' ?>">💳 Đặt hàng</a>
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

<!-- Overlay phóng to ảnh -->
<div id="imageZoomOverlay" class="d-none position-fixed top-0 start-0 w-100 h-100" style="background: rgba(30, 30, 30, 0.95); z-index: 2000;">
  <div class="d-flex justify-content-center align-items-center h-100 position-relative">
    <div id="imageContainer" style="position: relative; display: inline-block; cursor: grab;">
      <img id="zoomedImage" src="" class="img-fluid" style="max-height: 90vh; transform: scale(1); transition: transform 0.3s ease; user-select: none;">
    </div>
    <!-- Nút đóng ở góc trên phải -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 2100;">
      <button type="button" class="btn btn-warning btn-lg rounded-circle" onclick="closeImageOverlay()" title="Đóng">
        <i class="bi bi-x-lg"></i>
      </button>
    </div>
    <!-- Nút phóng to/thu nhỏ ở giữa bên phải -->
    <div class="position-fixed end-0 top-50 translate-middle-y p-3 d-flex flex-column gap-2" style="z-index: 2100;">
      <button type="button" class="btn btn-warning btn-sm rounded-circle" onclick="zoomImage(0.1)" title="Phóng to">
        <i class="bi bi-zoom-in"></i>
      </button>
      <button type="button" class="btn btn-warning btn-sm rounded-circle" onclick="zoomImage(-0.1)" title="Thu nhỏ">
        <i class="bi bi-zoom-out"></i>
      </button>
    </div>
  </div>
</div>

<?php include 'giaylienquan.php'; ?>
<?php include 'includes/footer.php'; ?>

<!-- Nút quay lại đầu trang -->
<button type="button" class="btn btn-warning btn-lg rounded-circle shadow back-to-top" id="btn-back-to-top">
  <i class="bi bi-arrow-up"></i>
</button>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const sizeButtons = document.querySelectorAll('.btn-size');
  const inputSelectedSize = document.getElementById('selected-size');
  const muaNgaySizeInput = document.getElementById('selected-size-mua');
  const openModalBtn = document.getElementById('btn-open-modal');
  const datHangBtn = document.getElementById('btn-open-modal-dathang');
  const modalThemVaoGio = new bootstrap.Modal(document.getElementById('modalThemVaoGio'));
  const modalDatHangNgay = new bootstrap.Modal(document.getElementById('modalDatHangNgay'));
  const soluongInput = document.getElementById('input-soluong');
  const soluongMuaInput = document.getElementById('input-soluong-mua');

  let maxQuantity = 0;

  sizeButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      sizeButtons.forEach(b => b.classList.remove('btn-warning'));
      btn.classList.add('btn-warning');
      const selectedSize = btn.dataset.size;
      maxQuantity = parseInt(btn.dataset.quantity) || 0;
      inputSelectedSize.value = selectedSize;
      muaNgaySizeInput.value = selectedSize;
      soluongInput.max = maxQuantity;
      soluongMuaInput.max = maxQuantity;
    });
  });

  document.getElementById("form-them-vao-gio").addEventListener("submit", function (e) {
    const qty = parseInt(soluongInput.value);
    if (qty > maxQuantity) {
      e.preventDefault();
      alert(`⚠️ Chỉ còn ${maxQuantity} sản phẩm có sẵn cho size đã chọn.`);
    } else if (qty <= 0 || isNaN(qty)) {
      e.preventDefault();
      alert("⚠️ Vui lòng nhập số lượng hợp lệ.");
    }
  });

  document.getElementById("form-dat-hang-ngay").addEventListener("submit", function (e) {
    const qty = parseInt(soluongMuaInput.value);
    if (qty > maxQuantity) {
      e.preventDefault();
      alert(`⚠️ Chỉ còn ${maxQuantity} sản phẩm có sẵn cho size đã chọn.`);
    } else if (qty <= 0 || isNaN(qty)) {
      e.preventDefault();
      alert("⚠️ Vui lòng nhập số lượng hợp lệ.");
    }
  });

  openModalBtn.addEventListener('click', () => {
    if (!inputSelectedSize.value.trim()) {
      alert("⚠️ Vui lòng chọn size trước khi thêm vào giỏ hàng.");
      return;
    }
    modalThemVaoGio.show();
  });

  datHangBtn.addEventListener('click', () => {
    if (!muaNgaySizeInput.value.trim()) {
      alert("⚠️ Vui lòng chọn size trước khi đặt hàng.");
      return;
    }
    modalDatHangNgay.show();
  });
});
</script>
</body>
</html>