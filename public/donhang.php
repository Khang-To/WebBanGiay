<?php
session_start();
require 'includes/cauhinh.php';

if (!isset($_SESSION['khach_hang_id'])) {
    header("Location: dangnhap.php");
    exit;
}

$khach_hang_id = $_SESSION['khach_hang_id'];

$sql = "SELECT * FROM don_hang WHERE khach_hang_id = ? ORDER BY ngay_dat DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $khach_hang_id);
$stmt->execute();
$result = $stmt->get_result();
$don_hangs = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đơn hàng của bạn</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="images/logo.png">
    <script src="js/dropdown-hover.js"></script>
    <link href="css/style.css" rel="stylesheet">
    <style>
        body {
            background-color:rgb(61, 60, 60);
            color: #f5f5f5;
        }
        .card {
            background-color: #1e1e1e;
            color: #fff;
        }
        .table {
            color: #fff;
        }
        .table th {
            background-color: #2c2c2c;
            color: #fff;
        }
        .table td {
            background-color: #1e1e1e;
            color: #fff;
        }
        .badge {
            font-size: 0.9rem;
        }
        .btn-success {
            background-color: #28a745;
            border: none;
        }      
    </style>
</head>
<body>

<?php include 'includes/header.php'; ?>
<?php include 'includes/nav.php'; ?>

<div class="container py-5">
    <h2 class="mb-4 text-info"><i class="bi bi-receipt-cutoff me-2"></i>Đơn hàng của bạn</h2>

    <?php if (count($don_hangs) === 0): ?>
        <div class="alert alert-secondary text-white bg-dark"><i class="bi bi-info-circle-fill me-2"></i>Bạn chưa có đơn hàng nào.</div>
    <?php else: ?>
        <?php foreach ($don_hangs as $don): ?>
            <div class="card shadow mb-4 border-0">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center bg-dark">
                    <div>
                        <h5 class="mb-0 text-light"><i class="bi bi-box-seam me-2"></i>Đơn hàng #<?= $don['id'] ?></h5>
                        <small class="text-muted">🕒 <?= date('d/m/Y H:i', strtotime($don['ngay_dat'])) ?></small>
                    </div>
                    <div>
                        <?php if ($don['trang_thai'] === 'cho_xac_nhan'): ?>
                            <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Chờ xác nhận</span>
                        <?php elseif ($don['trang_thai'] === 'da_xac_nhan'): ?>
                            <span class="badge bg-primary"><i class="bi bi-check-circle-fill me-1"></i>Đã xác nhận</span>
                        <?php elseif ($don['trang_thai'] === 'da_thanh_toan'): ?>
                            <span class="badge bg-success"><i class="bi bi-cash-coin me-1"></i>Đã thanh toán</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <?php
                    $sql_ct = "SELECT ct.*, g.ten_giay, sg.size 
                               FROM chi_tiet_don_hang ct
                               JOIN size_giay sg ON ct.size_giay_id = sg.id
                               JOIN giay g ON sg.giay_id = g.id
                               WHERE ct.don_hang_id = ?";
                    $stmt_ct = $conn->prepare($sql_ct);
                    $stmt_ct->bind_param("i", $don['id']);
                    $stmt_ct->execute();
                    $result_ct = $stmt_ct->get_result();
                    $chi_tiets = $result_ct->fetch_all(MYSQLI_ASSOC);
                    ?>

                    <?php if (!empty($chi_tiets)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle table-hover rounded text-white">
                                <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Size</th>
                                        <th>Số lượng</th>
                                        <th>Đơn giá</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $tong = 0;
                                    foreach ($chi_tiets as $ct):
                                        $thanh_tien = $ct['so_luong_ban'] * $ct['don_gia_ban'];
                                        $tong += $thanh_tien;
                                    ?>
                                        <tr>
                                            <td><?= htmlspecialchars($ct['ten_giay']) ?></td>
                                            <td><?= $ct['size'] ?></td>
                                            <td><?= $ct['so_luong_ban'] ?></td>
                                            <td><?= number_format($ct['don_gia_ban'], 0, ',', '.') ?>₫</td>
                                            <td><?= number_format($thanh_tien, 0, ',', '.') ?>₫</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="fw-bold">
                                        <td colspan="4" class="text-end">Tổng cộng:</td>
                                        <td><?= number_format($tong, 0, ',', '.') ?>₫</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php endif; ?>

                    <?php if ($don['trang_thai'] === 'da_xac_nhan'): ?>
                        <a href="thanhtoan.php?don_hang_id=<?= $don['id'] ?>" class="btn btn-success mt-3">
                            <i class="bi bi-credit-card-2-front-fill me-1"></i>Thanh toán
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
