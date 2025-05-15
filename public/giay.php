<?php
include 'includes/cauhinh.php';
session_start();

// Lấy các tham số
$thuong_hieu = $_GET['thuong_hieu'] ?? '';
$loai_giay    = $_GET['loai_giay']   ?? '';
$size         = $_GET['size']        ?? '';
$gia          = $_GET['gia']         ?? '';
$tu_khoa      = $_GET['tu_khoa']     ?? '';
$giamgia = $_GET['giamgia'] ?? '';


$sql = "SELECT g.*, th.ten_thuong_hieu, lg.ten_loai 
        FROM giay g
        JOIN thuong_hieu th ON g.thuong_hieu_id = th.id
        JOIN loai_giay lg ON g.loai_giay_id = lg.id
        WHERE 1=1";

// Xây dựng tiêu đề động
$title = "Tất cả sản phẩm";
if ($tu_khoa !== '') {
    $tk = htmlspecialchars($tu_khoa);
    $title = "Kết quả tìm kiếm cho “{$tk}”";
}
elseif ($thuong_hieu) {
    $r = $conn->query("SELECT ten_thuong_hieu FROM thuong_hieu WHERE id = '". $conn->real_escape_string($thuong_hieu) ."'")->fetch_assoc();
    $title = $r ? "Thương hiệu: " . htmlspecialchars($r['ten_thuong_hieu']) : $title;
}
elseif ($loai_giay) {
    $r = $conn->query("SELECT ten_loai FROM loai_giay WHERE id = '". $conn->real_escape_string($loai_giay) ."'")->fetch_assoc();
    $title = $r ? "Loại giày: " . htmlspecialchars($r['ten_loai']) : $title;
}
elseif ($size) {
    $title = "Size: " . htmlspecialchars($size);
}

elseif ($giamgia) {
    $title = "Sản phẩm đang giảm giá";
}

elseif ($gia) {
    if ($gia == '1')      $title = "Giá: Dưới 500.000₫";
    elseif ($gia == '2')  $title = "Giá: 500.000₫ – 1.000.000₫";
    elseif ($gia == '3')  $title = "Giá: Trên 1.000.000₫";
    elseif ($gia == '4') $title = "Giày cao cấp";

}

if ($thuong_hieu) $sql .= " AND g.thuong_hieu_id = '$thuong_hieu'";
if ($loai_giay)   $sql .= " AND g.loai_giay_id = '$loai_giay'";
if ($gia) {
    if ($gia == '1') $sql .= " AND don_gia < 500000";
    elseif ($gia == '2') $sql .= " AND don_gia BETWEEN 500000 AND 1000000";
    elseif ($gia == '3') $sql .= " AND don_gia > 1000000";
    elseif ($gia == '4') $sql .= " AND don_gia > 2000000";

}

if ($giamgia) {
    $sql .= " AND g.ti_le_giam_gia > 0";
}

if ($size) {
    $sql .= " AND g.id IN (
        SELECT giay_id FROM size_giay WHERE size = '$size' AND so_luong_ton > 0
    )";
}

if ($tu_khoa !== '') {
    $sql .= " AND g.ten_giay LIKE '%" . $conn->real_escape_string($tu_khoa) . "%'";
}

$giay = $conn->query($sql);
$ds_th = $conn->query("SELECT * FROM thuong_hieu");
$ds_loai = $conn->query("SELECT * FROM loai_giay");
$ds_size = $conn->query("SELECT DISTINCT size FROM size_giay ORDER BY size");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Blue Eagle Shoes - Giày là đam mê</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/logo.png" type="image/png">
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

<div class="container mt-5 mb-5">
  <div class="row">
    <!-- Bộ lọc -->
    <div class="col-md-3">
      <div class="bg-dark p-4 rounded shadow-sm text-white">
        <h5 class="fw-bold mb-3">🔍 Lọc sản phẩm</h5>
        <form method="GET">
          <div class="mb-3">
            <label class="form-label">Thương hiệu</label>
            <select name="thuong_hieu" class="form-select bg-dark text-white border-secondary">
              <option value="">Tất cả</option>
              <?php while($th = $ds_th->fetch_assoc()): ?>
                <option value="<?= $th['id'] ?>" <?= $thuong_hieu==$th['id']?'selected':'' ?>><?= $th['ten_thuong_hieu'] ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Loại giày</label>
            <select name="loai_giay" class="form-select bg-dark text-white border-secondary">
              <option value="">Tất cả</option>
              <?php while($lg = $ds_loai->fetch_assoc()): ?>
                <option value="<?= $lg['id'] ?>" <?= $loai_giay==$lg['id']?'selected':'' ?>><?= $lg['ten_loai'] ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Size</label>
            <select name="size" class="form-select bg-dark text-white border-secondary">
              <option value="">Tất cả</option>
              <?php while($s = $ds_size->fetch_assoc()): ?>
                <option value="<?= $s['size'] ?>" <?= $size==$s['size']?'selected':'' ?>><?= $s['size'] ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Giá</label>
            <select name="gia" class="form-select bg-dark text-white border-secondary">
              <option value="">Tất cả</option>
              <option value="1" <?= $gia=='1'?'selected':'' ?>>Dưới 500.000đ</option>
              <option value="2" <?= $gia=='2'?'selected':'' ?>>500.000đ - 1.000.000đ</option>
              <option value="3" <?= $gia=='3'?'selected':'' ?>>Trên 1.000.000đ</option>
              <option value="4" <?= $gia=='4'?'selected':'' ?>>Trên 2.000.000đ</option>
            </select>
          </div>
          <div class="form-check mb-3">
  <input class="form-check-input" type="checkbox" value="1" id="giamgia" name="giamgia" <?= isset($_GET['giamgia']) ? 'checked' : '' ?>>
  <label class="form-check-label" for="giamgia">
    Sản phẩm đang giảm giá
  </label>
</div>

          <button class="btn btn-primary w-100"><i class="bi bi-funnel-fill me-1"></i> Lọc</button>
        </form>
      </div>
    </div>

    <!-- Danh sách sản phẩm -->
<div class="col-md-9">
          <h4 class="text-white mb-4"><?= $title ?></h4>
  <div class="row">
    <?php if ($giay->num_rows > 0): ?>
      <?php while($sp = $giay->fetch_assoc()): ?>
        <?php
          $giay_id = $sp['id'];
          $sl_row = $conn->query("SELECT SUM(so_luong_ton) AS tong_ton FROM size_giay WHERE giay_id = $giay_id")->fetch_assoc();
          $so_luong_ton = $sl_row['tong_ton'] ?? 0;
          $giam = $sp['ti_le_giam_gia'];
          $gia_goc = $sp['don_gia'];
          $gia_moi = $gia_goc * (1 - $giam / 100);
          $is_out_of_stock = $so_luong_ton <= 0;
        ?>
        
<div class="col-md-4 mb-4">
    <div class="card h-100 shadow-sm border-0 bg-dark text-white brand-card card-link">
        <a href="giaychitiet.php?id=<?= $sp['id'] ?>">
            <img src="../uploads/<?= $sp['hinh_anh'] ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
        </a>
        <div class="card-body d-flex flex-column justify-content-between">
            <div>
                <h6 class="text-primary fw-bold mb-1"><?= $sp['ten_thuong_hieu'] ?></h6>
                <h6 class="card-title text-truncate"><?= $sp['ten_giay'] ?></h6>

                <div class="mb-2" style="min-height: 45px;">
                    <?php if ($giam > 0): ?>
                        <small class="text-muted text-decoration-line-through"><?= number_format($gia_goc) ?>đ</small>
                        <span class="badge bg-danger ms-2">-<?= $giam ?>%</span><br>
                        <span class="text-warning fw-bold fs-5"><?= number_format($gia_moi) ?>đ</span>
                    <?php else: ?>
                        <span class="text-warning fw-bold fs-5"><?= number_format($gia_goc) ?>đ</span>
                    <?php endif; ?>
                </div>

                <p class="text-muted small mb-1"><?= $sp['ten_loai'] ?></p>

                <?php if ($is_out_of_stock): ?>
                    <p class="text-danger small mb-1"><strong>HẾT HÀNG</strong></p>
                <?php else: ?>
                    <p class="small mb-2">Còn lại: <strong><?= $so_luong_ton ?></strong></p>
                <?php endif; ?>
            </div>

            <?php if (isset($_SESSION['taikhoan'])): ?>
                <div class="d-flex gap-2">
                    <!-- If the product is out of stock, disable buttons -->
                    <button type="button" class="btn btn-warning w-50" data-bs-toggle="modal" data-bs-target="#modal<?= $sp['id'] ?>" <?= $is_out_of_stock ? 'disabled' : '' ?>>
                        Thêm vào giỏ
                    </button>
                    <button type="button" class="btn btn-success w-50" data-bs-toggle="modal" data-bs-target="#muangay<?= $sp['id'] ?>" <?= $is_out_of_stock ? 'disabled' : '' ?>>
                        Đặt hàng
                    </button>
                </div>
            <?php else: ?>
                <a href="dangnhap.php" class="btn btn-outline-light w-100">
                    <i class="bi bi-box-arrow-in-right"></i> Đăng nhập để mua
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

        <!-- Modal Thêm vào giỏ -->
<?php if (isset($_SESSION['taikhoan'])): ?>
  <div class="modal fade" id="modal<?= $sp['id'] ?>" tabindex="-1" aria-labelledby="modalLabel<?= $sp['id'] ?>" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white border-secondary">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel<?= $sp['id'] ?>">Chọn size & số lượng</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>

      <form action="themvaogiohang.php" method="get">
        <div class="modal-body">
          <input type="hidden" name="id" value="<?= $sp['id'] ?>">
          <input type="hidden" name="gia" value="<?= $sp['ti_le_giam_gia'] > 0 ? $sp['don_gia'] * (1 - $sp['ti_le_giam_gia'] / 100) : $sp['don_gia'] ?>">

          <!-- Chọn size -->
          <div class="mb-3">
            <label class="form-label">Size</label>
            <select name="size" class="form-select bg-dark text-white border-secondary" required>
              <option value="">Chọn size</option>
              <?php
              $sizes = $conn->query("SELECT size, so_luong_ton FROM size_giay WHERE giay_id = {$sp['id']} AND so_luong_ton > 0 ORDER BY size");
              while ($sz = $sizes->fetch_assoc()):
              ?>
                <option value="<?= $sz['size'] ?>" data-soluongton="<?= $sz['so_luong_ton'] ?>">
                  <?= $sz['size'] ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <!-- Nhập số lượng -->
          <div class="mb-3">
            <label class="form-label">Số lượng</label>
            <input type="number" name="soluong" value="1" min="1" class="form-control bg-dark text-white border-secondary" required>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-warning">🛒 Thêm vào giỏ</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Script kiểm tra số lượng vượt quá tồn kho -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  const modal = document.getElementById('modal<?= $sp['id'] ?>');
  if (!modal) return;

  const sizeSelect = modal.querySelector('select[name="size"]');
  const quantityInput = modal.querySelector('input[name="soluong"]');

  sizeSelect.addEventListener('change', function () {
    const selected = sizeSelect.options[sizeSelect.selectedIndex];
    const maxQty = parseInt(selected.dataset.soluongton) || 1;
    quantityInput.max = maxQty;
    if (parseInt(quantityInput.value) > maxQty) {
      quantityInput.value = maxQty;
    }
  });

  quantityInput.addEventListener('input', function () {
    const selected = sizeSelect.options[sizeSelect.selectedIndex];
    const maxQty = parseInt(selected.dataset.soluongton) || 1;
    if (parseInt(quantityInput.value) > maxQty) {
      alert("Số lượng vượt quá tồn kho!");
      quantityInput.value = maxQty;
    }
  });
});
</script>

<?php endif; ?>
        <!-- Modal Đặt hàng -->
<div class="modal fade" id="muangay<?= $sp['id'] ?>" tabindex="-1" aria-labelledby="muangayLabel<?= $sp['id'] ?>" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white border-secondary">
      <div class="modal-header">
        <h5 class="modal-title" id="muangayLabel<?= $sp['id'] ?>">Đặt hàng - chọn size & số lượng</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>
      <form action="dathangngay.php" method="get">
        <div class="modal-body">
          <input type="hidden" name="id" value="<?= $sp['id'] ?>">
          
          <!-- Chọn size -->
          <div class="mb-3">
            <label class="form-label">Size</label>
            <select name="size" class="form-select bg-dark text-white border-secondary" required>
              <option value="">Chọn size</option>
              <?php
              $sizes = $conn->query("SELECT size, so_luong_ton FROM size_giay WHERE giay_id = {$sp['id']} AND so_luong_ton > 0 ORDER BY size");
              while($sz = $sizes->fetch_assoc()):
              ?>
                <option value="<?= $sz['size'] ?>" data-soluongton="<?= $sz['so_luong_ton'] ?>">
                  <?= $sz['size'] ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <!-- Nhập số lượng -->
          <div class="mb-3">
            <label class="form-label">Số lượng</label>
            <input type="number" name="soluong" value="1" min="1" class="form-control bg-dark text-white border-secondary" required>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-success">Đặt hàng</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Script kiểm tra số lượng vượt tồn kho -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  const modal = document.getElementById('muangay<?= $sp['id'] ?>');
  if (!modal) return;

  const sizeSelect = modal.querySelector('select[name="size"]');
  const quantityInput = modal.querySelector('input[name="soluong"]');

  sizeSelect.addEventListener('change', function () {
    const selected = sizeSelect.options[sizeSelect.selectedIndex];
    const maxQty = parseInt(selected.dataset.soluongton) || 1;
    quantityInput.max = maxQty;
    if (parseInt(quantityInput.value) > maxQty) {
      quantityInput.value = maxQty;
    }
  });

  quantityInput.addEventListener('input', function () {
    const selected = sizeSelect.options[sizeSelect.selectedIndex];
    const maxQty = parseInt(selected.dataset.soluongton) || 1;
    if (parseInt(quantityInput.value) > maxQty) {
      alert("Số lượng vượt quá tồn kho!");
      quantityInput.value = maxQty;
    }
  });
});
</script>

      <?php endwhile; ?>
    <?php else: ?>
      <div class="col-12">
        <div class="alert alert-warning text-center">Không tìm thấy sản phẩm phù hợp.</div>
      </div>
    <?php endif; ?>
  </div>
</div>
  </div>
</div>
</div>
<?php include 'includes/footer.php'; ?>
    <button type="button" class="btn btn-warning btn-lg rounded-circle shadow back-to-top" id="btn-back-to-top">
  <i class="bi bi-arrow-up"></i>
</button>
</body>
</html>


