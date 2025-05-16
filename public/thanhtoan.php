<?php
session_start();
require 'includes/cauhinh.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['taikhoan']) || !is_array($_SESSION['taikhoan'])) {
    header("Location: dangnhap.php");
    exit;
}

// Lấy ID khách hàng từ session
$khach_hang_id = $_SESSION['taikhoan']['id'] ?? null;

// Kiểm tra ID đơn hàng
if (!isset($_GET['don_hang_id']) || !is_numeric($_GET['don_hang_id'])) {
    echo "Thiếu ID đơn hàng.";
    exit;
}
$don_hang_id = (int)$_GET['don_hang_id'];

// Cập nhật trạng thái đơn hàng
$stmt = $conn->prepare("UPDATE don_hang SET trang_thai = 'da_thanh_toan' WHERE id = ? AND khach_hang_id = ?");
$stmt->bind_param("ii", $don_hang_id, $khach_hang_id);
$stmt->execute();

// Lấy thông tin đơn hàng
$sql_don_hang = "SELECT dh.id, dh.ngay_dat, dh.ghi_chu, kh.ho_ten, kh.dia_chi, kh.so_dien_thoai
                 FROM don_hang dh
                 JOIN khach_hang kh ON dh.khach_hang_id = kh.id
                 WHERE dh.id = ? AND dh.khach_hang_id = ?";
$stmt_don_hang = $conn->prepare($sql_don_hang);
$stmt_don_hang->bind_param("ii", $don_hang_id, $khach_hang_id);
$stmt_don_hang->execute();
$don_hang = $stmt_don_hang->get_result()->fetch_assoc();
$stmt_don_hang->close();

// Lấy chi tiết đơn hàng
$sql_chi_tiet = "SELECT ct.size_giay_id, ct.so_luong_ban, ct.don_gia_ban, g.ten_giay, sg.size
                 FROM chi_tiet_don_hang ct
                 JOIN size_giay sg ON ct.size_giay_id = sg.id
                 JOIN giay g ON sg.giay_id = g.id
                 WHERE ct.don_hang_id = ?";
$stmt_chi_tiet = $conn->prepare($sql_chi_tiet);
$stmt_chi_tiet->bind_param("i", $don_hang_id);
$stmt_chi_tiet->execute();
$chi_tiets = $stmt_chi_tiet->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt_chi_tiet->close();

// Tính tổng tiền
$tong_tien = 0;
foreach ($chi_tiets as $ct) {
    $tong_tien += $ct['so_luong_ban'] * $ct['don_gia_ban'];
}

// Trừ số lượng tồn kho
foreach ($chi_tiets as $ct) {
    $stmt_kho = $conn->prepare("SELECT so_luong_ton FROM size_giay WHERE id = ?");
    $stmt_kho->bind_param("i", $ct['size_giay_id']);
    $stmt_kho->execute();
    $so_luong_ton = $stmt_kho->get_result()->fetch_assoc()['so_luong_ton'];
    $stmt_kho->close();

    $moi_so_luong = max(0, $so_luong_ton - $ct['so_luong_ban']);
    $stmt_update = $conn->prepare("UPDATE size_giay SET so_luong_ton = ? WHERE id = ?");
    $stmt_update->bind_param("ii", $moi_so_luong, $ct['size_giay_id']);
    $stmt_update->execute();
    $stmt_update->close();
}


// Xoá giỏ hàng sau khi thanh toán
unset($_SESSION['giohang']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán thành công</title>
    <link rel="icon" type="image/png" href="images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/dropdown-hover.js"></script>
</head>
<body class="bg-dark text-white">
<?php include 'includes/header.php'; ?>
<?php include 'includes/nav.php'; ?>

<div class="container py-5">
    <div class="text-center mb-5">
        <h2><i class="bi bi-check-circle-fill text-success"></i> Cảm ơn bạn đã mua hàng!</h2>
        <p class="fs-5">Đơn hàng <strong>#<?= $don_hang_id ?></strong> của bạn đã được thanh toán thành công. Bộ phận giao hàng sẽ vận chuyển giày đến cho bạn với thời gian sớm nhất.</p>
    </div>

    <!-- Thông tin hóa đơn -->
    <div class="card bg-dark text-white border-secondary shadow-sm">
        <div class="card-header">
            <h4 class="mb-0"><i class="bi bi-receipt"></i> Chi tiết hóa đơn #<?= $don_hang_id ?></h4>
        </div>
        <div class="card-body">
            <!-- Thông tin khách hàng -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Thông tin khách hàng</h5>
                    <p><strong>Họ tên:</strong> <?= htmlspecialchars($don_hang['ho_ten'] ?? 'N/A') ?></p>
                    <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($don_hang['dia_chi'] ?? 'N/A') ?></p>
                    <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($don_hang['so_dien_thoai'] ?? 'N/A') ?></p>
                </div>
                <div class="col-md-6">
                    <h5>Thông tin đơn hàng</h5>
                    <p><strong>Mã đơn hàng:</strong> #<?= $don_hang_id ?></p>
                    <p><strong>Ngày đặt:</strong> <?= date('d/m/Y H:i', strtotime($don_hang['ngay_dat'])) ?></p>
                    <p><strong>Ghi chú:</strong> <?= htmlspecialchars($don_hang['ghi_chu'] ?? 'Không có') ?></p>
                </div>
            </div>

            <!-- Danh sách sản phẩm -->
            <h5>Danh sách sản phẩm</h5>
            <div class="table-responsive">
                <table class="table table-dark table-bordered">
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
                        <?php foreach ($chi_tiets as $ct): ?>
                            <tr>
                                <td><?= htmlspecialchars($ct['ten_giay']) ?></td>
                                <td><?= $ct['size'] ?></td>
                                <td><?= $ct['so_luong_ban'] ?></td>
                                <td><?= number_format($ct['don_gia_ban']) ?>₫</td>
                                <td><?= number_format($ct['so_luong_ban'] * $ct['don_gia_ban']) ?>₫</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Tổng tiền:</strong></td>
                            <td><strong><?= number_format($tong_tien) ?>₫</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-outline-light"><i class="bi bi-house"></i> Về trang chủ</a>
        <a href="donhang.php" class="btn btn-outline-primary ms-2"><i class="bi bi-search"></i> Xem đơn hàng</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>