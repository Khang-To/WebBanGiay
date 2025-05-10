<?php
include 'includes/cauhinh.php';

$thuong_hieu = $_GET['thuong_hieu'] ?? '';
$loai_giay = $_GET['loai_giay'] ?? '';
$size = $_GET['size'] ?? '';
$gia = $_GET['gia'] ?? '';

// Câu truy vấn sản phẩm
$sql = "SELECT g.*, th.ten_thuong_hieu, lg.ten_loai 
        FROM giay g
        JOIN thuong_hieu th ON g.thuong_hieu_id = th.id
        JOIN loai_giay lg ON g.loai_giay_id = lg.id
        WHERE 1=1";

if ($thuong_hieu) $sql .= " AND g.thuong_hieu_id = '$thuong_hieu'";
if ($loai_giay)   $sql .= " AND g.loai_giay_id = '$loai_giay'";
if ($gia) {
    if ($gia == '1') $sql .= " AND don_gia < 500000";
    elseif ($gia == '2') $sql .= " AND don_gia BETWEEN 500000 AND 1000000";
    elseif ($gia == '3') $sql .= " AND don_gia > 1000000";
}
if ($size) {
    $sql .= " AND g.id IN (
        SELECT giay_id FROM size_giay WHERE size = '$size' AND so_luong_ton > 0
    )";
}

$giay = $conn->query($sql);

// Lấy danh sách filter
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

    <link rel="icon" type="image/png" href="images/logo.png">
    <script src="js/dropdown-hover.js"></script>


    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Your custom CSS -->
    <link rel="stylesheet" href="css/style.css">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/nav.php'; ?>
   <!-- GIỮ LẠI PHẦN PHP NHƯ BẠN ĐÃ VIẾT (ở trên) -->

<!-- HTML phía dưới -->
<div class="container mt-5 mb-5">
  <div class="row">
    <!-- Bộ lọc -->
    <div class="col-md-3">
      <div class="bg-light p-4 rounded shadow-sm">
        <h5 class="fw-bold mb-3">🔍 Lọc sản phẩm</h5>
        <form method="GET">
          <!-- Thương hiệu -->
          <div class="mb-3">
            <label class="form-label">Thương hiệu</label>
            <select name="thuong_hieu" class="form-select">
              <option value="">Tất cả</option>
              <?php while($th = $ds_th->fetch_assoc()): ?>
                <option value="<?= $th['id'] ?>" <?= $thuong_hieu==$th['id']?'selected':'' ?>>
                  <?= $th['ten_thuong_hieu'] ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <!-- Loại giày -->
          <div class="mb-3">
            <label class="form-label">Loại giày</label>
            <select name="loai_giay" class="form-select">
              <option value="">Tất cả</option>
              <?php while($lg = $ds_loai->fetch_assoc()): ?>
                <option value="<?= $lg['id'] ?>" <?= $loai_giay==$lg['id']?'selected':'' ?>>
                  <?= $lg['ten_loai'] ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <!-- Size -->
          <div class="mb-3">
            <label class="form-label">Size</label>
            <select name="size" class="form-select">
              <option value="">Tất cả</option>
              <?php while($s = $ds_size->fetch_assoc()): ?>
                <option value="<?= $s['size'] ?>" <?= $size==$s['size']?'selected':'' ?>>
                  <?= $s['size'] ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <!-- Giá -->
          <div class="mb-3">
            <label class="form-label">Giá</label>
            <select name="gia" class="form-select">
              <option value="">Tất cả</option>
              <option value="1" <?= $gia=='1'?'selected':'' ?>>Dưới 500.000đ</option>
              <option value="2" <?= $gia=='2'?'selected':'' ?>>500.000đ - 1.000.000đ</option>
              <option value="3" <?= $gia=='3'?'selected':'' ?>>Trên 1.000.000đ</option>
            </select>
          </div>

          <button class="btn btn-primary w-100"><i class="bi bi-funnel-fill me-1"></i> Lọc</button>
        </form>
      </div>
    </div>

    <!-- Sản phẩm -->
    <div class="col-md-9">
      <div class="row">
        <?php if ($giay->num_rows > 0): ?>
          <?php while($sp = $giay->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
              <div class="card h-100 shadow-sm border-0">
                <img src="images/<?= $sp['hinh_anh'] ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                  <h6 class="card-title text-truncate"><?= $sp['ten_giay'] ?></h6>
                  <p class="text-danger fw-bold mb-1"><?= number_format($sp['don_gia']) ?> đ</p>
                  <p class="text-muted small mb-0"><?= $sp['ten_thuong_hieu'] ?> | <?= $sp['ten_loai'] ?></p>
                </div>
              </div>
            </div>
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
<?php include 'includes/footer.php'; ?>
</body>
</html>
