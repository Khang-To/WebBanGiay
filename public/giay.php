<?php
include 'includes/cauhinh.php';
session_start();

// Lấy các tham số
$thuong_hieu = $_GET['thuong_hieu'] ?? '';
$loai_giay   = $_GET['loai_giay']   ?? '';
$size        = $_GET['size']        ?? '';
$gia         = $_GET['gia']         ?? '';
$tu_khoa     = $_GET['tu_khoa']     ?? '';
$giamgia     = $_GET['giamgia']     ?? '';

// Số sản phẩm mỗi trang
$products_per_page = 15;

// Lấy trang hiện tại từ URL, mặc định là trang 1
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $products_per_page;

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
} elseif ($thuong_hieu) {
    $r = $conn->query("SELECT ten_thuong_hieu FROM thuong_hieu WHERE id = '" . $conn->real_escape_string($thuong_hieu) . "'")->fetch_assoc();
    $title = $r ? "Thương hiệu: " . htmlspecialchars($r['ten_thuong_hieu']) : $title;
} elseif ($loai_giay) {
    $r = $conn->query("SELECT ten_loai FROM loai_giay WHERE id = '" . $conn->real_escape_string($loai_giay) . "'")->fetch_assoc();
    $title = $r ? "Loại giày: " . htmlspecialchars($r['ten_loai']) : $title;
} elseif ($size) {
    $title = "Size: " . htmlspecialchars($size);
} elseif ($giamgia) {
    $title = "Sản phẩm đang giảm giá";
} elseif ($gia) {
    if ($gia == '1')      $title = "Giá: Dưới 500.000₫";
    elseif ($gia == '2')  $title = "Giá: 500.000₫ – 1.000.000₫";
    elseif ($gia == '3')  $title = "Giá: Trên 1.000.000₫";
    elseif ($gia == '4')  $title = "Giày cao cấp";
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

// Lấy tổng số sản phẩm để tính số trang
$total_products_query = $conn->query($sql);
$total_products = $total_products_query->num_rows;
$total_pages = ceil($total_products / $products_per_page);

// Thêm giới hạn và offset vào câu SQL
$sql .= " LIMIT $products_per_page OFFSET $offset";

$giay = $conn->query($sql);
$ds_th = $conn->query("SELECT * FROM thuong_hieu");
$ds_loai = $conn->query("SELECT * FROM loai_giay");
$ds_size = $conn->query("SELECT DISTINCT size FROM size_giay ORDER BY size");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Blue Eagle Store - Giày là đam mê</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/logo.png" type="image/png">
    <script src="js/dropdown-hover.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/back-to-top.js"></script>
    <style>
        /* Tùy chỉnh giao diện phân trang */
        .pagination .page-item .page-link {
            background-color: #343a40; /* Màu nền tối */
            color: #ffffff; /* Màu chữ trắng */
            border: 1px solid #6c757d; /* Viền xám */
            transition: all 0.3s ease; /* Hiệu ứng chuyển mượt */
        }

        .pagination .page-item.active .page-link {
            background-color: #ffc107; /* Màu vàng sáng cho trang hiện tại */
            color: #000000; /* Màu chữ đen */
            border-color: #ffc107;
            box-shadow: 0 0 10px rgba(255, 193, 7, 0.7); /* Hiệu ứng phát sáng */
            font-weight: bold; /* Chữ đậm */
        }

        .pagination .page-item:not(.active) .page-link:hover {
            background-color: #495057; /* Màu xám sáng khi hover */
            color: #ffc107; /* Màu chữ vàng khi hover */
            border-color: #ffc107;
        }

        .pagination .page-item.disabled .page-link {
            background-color: #343a40;
            color: #6c757d;
            border-color: #6c757d;
            cursor: not-allowed;
        }
        .bg-dark {
  background-color: #1e1e1e !important;
  transition: all 0.3s ease;
}

.form-label {
  font-size: 0.95rem;
  color: #e0e0e0;
}

.form-select, .form-check-input {
  background-color: #2a2a2a;
  border-color: #444;
  color: #fff;
  transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.form-select:focus, .form-check-input:focus {
  border-color: #ffc107;
  box-shadow: 0 0 8px rgba(255, 193, 7, 0.3);
  outline: none;
}

.form-select option {
  background-color: #2a2a2a;
  color: #fff;
}

.form-check-input:checked {
  background-color: #ffc107;
  border-color: #ffc107;
}

.form-check-label {
  color: #e0e0e0;
  font-size: 0.9rem;
}

.filter-input:hover, .form-check-input:hover {
  cursor: pointer;
  border-color: #ffc107;
}

.shadow-sm {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4) !important;
}

.spinner-border {
  width: 2rem;
  height: 2rem;
}
    </style>
</head>
<body class="bg-gradient-gray-black text-white">

<?php include 'includes/header.php'; ?>
<?php include 'includes/nav.php'; ?>

<div class="container mt-5 mb-5">
  <div class="row">
   <!-- Bộ lọc -->
<div class="col-md-3">
  <div class="bg-dark p-4 rounded shadow-sm text-white position-relative">
    <h5 class="fw-bold mb-4"><i class="bi bi-funnel-fill me-2"></i> Lọc sản phẩm</h5>
    <form id="filterForm" method="GET">
      <div class="mb-4">
        <label class="form-label fw-medium">Thương hiệu</label>
        <select name="thuong_hieu" class="form-select bg-dark text-white border-secondary filter-input" onchange="submitFilter()">
          <option value="">Tất cả</option>
          <?php while($th = $ds_th->fetch_assoc()): ?>
            <option value="<?= $th['id'] ?>" <?= $thuong_hieu == $th['id'] ? 'selected' : '' ?>><?= htmlspecialchars($th['ten_thuong_hieu']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="mb-4">
        <label class="form-label fw-medium">Loại giày</label>
        <select name="loai_giay" class="form-select bg-dark text-white border-secondary filter-input" onchange="submitFilter()">
          <option value="">Tất cả</option>
          <?php while($lg = $ds_loai->fetch_assoc()): ?>
            <option value="<?= $lg['id'] ?>" <?= $loai_giay == $lg['id'] ? 'selected' : '' ?>><?= htmlspecialchars($lg['ten_loai']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="mb-4">
        <label class="form-label fw-medium">Size</label>
        <select name="size" class="form-select bg-dark text-white border-secondary filter-input" onchange="submitFilter()">
          <option value="">Tất cả</option>
          <?php while($s = $ds_size->fetch_assoc()): ?>
            <option value="<?= $s['size'] ?>" <?= $size == $s['size'] ? 'selected' : '' ?>><?= htmlspecialchars($s['size']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="mb-4">
        <label class="form-label fw-medium">Giá</label>
        <select name="gia" class="form-select bg-dark text-white border-secondary filter-input" onchange="submitFilter()">
          <option value="">Tất cả</option>
          <option value="1" <?= $gia == '1' ? 'selected' : '' ?>>Dưới 500.000đ</option>
          <option value="2" <?= $gia == '2' ? 'selected' : '' ?>>500.000đ - 1.000.000đ</option>
          <option value="3" <?= $gia == '3' ? 'selected' : '' ?>>Trên 1.000.000đ</option>
          <option value="4" <?= $gia == '4' ? 'selected' : '' ?>>Trên 2.000.000đ</option>
        </select>
      </div>
      <div class="form-check mb-4">
        <input class="form-check-input" type="checkbox" value="1" id="giamgia" name="giamgia" <?= isset($_GET['giamgia']) ? 'checked' : '' ?> onclick="submitFilter()">
        <label class="form-check-label" for="giamgia">
          Sản phẩm đang giảm giá
        </label>
      </div>
    </form>
    <!-- Spinner loading -->
    <div id="filterLoading" class="position-absolute top-50 start-50 translate-middle" style="display: none;">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Đang tải...</span>
      </div>
    </div>
  </div>
</div>

<!-- JavaScript cho lọc tự động -->
<script>
function submitFilter() {
  const form = document.getElementById('filterForm');
  const loading = document.getElementById('filterLoading');
  
  // Hiển thị spinner
  loading.style.display = 'block';
  
  // Gửi form
  setTimeout(() => {
    form.submit();
  }, 300); // Delay nhẹ để spinner hiển thị
}
</script>

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
                    <?php endif; ?>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-warning text-center">Không tìm thấy sản phẩm phù hợp.</div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Phân trang -->
        <?php if ($total_pages > 1): ?>
            <nav aria-label="Page navigation" class="mt-4">
                <ul class="pagination justify-content-center">
                    <!-- Về đầu -->
                    <li class="page-item <?= $current_page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?<?php
                            $params = $_GET;
                            $params['page'] = 1;
                            echo http_build_query($params);
                        ?>" title="Về đầu"><i class="bi bi-chevron-double-left"></i></a>
                    </li>
                    <!-- Trang trước -->
                    <li class="page-item <?= $current_page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?<?php
                            $params = $_GET;
                            $params['page'] = $current_page - 1;
                            echo http_build_query($params);
                        ?>" title="Trang trước"><i class="bi bi-chevron-left"></i></a>
                    </li>
                    <!-- Các số trang -->
                    <?php
                    $start_page = max(1, $current_page - 2);
                    $end_page = min($total_pages, $current_page + 2);
                    if ($start_page > 1) {
                        echo '<li class="page-item"><a class="page-link" href="?' . http_build_query(array_merge($_GET, ['page' => 1])) . '">1</a></li>';
                        if ($start_page > 2) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                    }
                    for ($i = $start_page; $i <= $end_page; $i++):
                    ?>
                        <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
                            <a class="page-link" href="?<?php
                                $params = $_GET;
                                $params['page'] = $i;
                                echo http_build_query($params);
                            ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <?php
                    if ($end_page < $total_pages) {
                        if ($end_page < $total_pages - 1) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                        echo '<li class="page-item"><a class="page-link" href="?' . http_build_query(array_merge($_GET, ['page' => $total_pages])) . '">' . $total_pages . '</a></li>';
                    }
                    ?>
                    <!-- Trang sau -->
                    <li class="page-item <?= $current_page >= $total_pages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?<?php
                            $params = $_GET;
                            $params['page'] = $current_page + 1;
                            echo http_build_query($params);
                        ?>" title="Trang sau"><i class="bi bi-chevron-right"></i></a>
                    </li>
                    <!-- Về cuối -->
                    <li class="page-item <?= $current_page >= $total_pages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?<?php
                            $params = $_GET;
                            $params['page'] = $total_pages;
                            echo http_build_query($params);
                        ?>" title="Về cuối"><i class="bi bi-chevron-double-right"></i></a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
<button type="button" class="btn btn-warning btn-lg rounded-circle shadow back-to-top" id="btn-back-to-top">
    <i class="bi bi-arrow-up"></i>
</button>
</body>
</html>