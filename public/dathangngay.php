<?php
session_start();
include 'includes/cauhinh.php'; // kết nối CSDL ($conn)

// Kiểm tra đăng nhập
if (!isset($_SESSION['taikhoan']) || !is_array($_SESSION['taikhoan'])) {
    header("Location: dangnhap.php?redirect=dathangngay");
    exit;
}

// Kiểm tra dữ liệu đầu vào
if (!isset($_GET['id'], $_GET['size'], $_GET['soluong'])) {
    die("Thiếu thông tin sản phẩm cần đặt.");
}

$id = (int)$_GET['id'];
$size = (int)$_GET['size'];
$soluong = (int)$_GET['soluong'];

// Lấy thông tin khách hàng
$ho_ten = $_SESSION['taikhoan']['ho_ten'] ?? '';
$dia_chi = $_SESSION['taikhoan']['dia_chi'] ?? '';
$email = $_SESSION['taikhoan']['email'] ?? '';
$so_dien_thoai = $_SESSION['taikhoan']['so_dien_thoai'] ?? '';

// Yêu cầu cập nhật hồ sơ nếu thiếu
if (empty(trim($ho_ten)) || empty(trim($dia_chi)) || empty(trim($so_dien_thoai))) {
    $_SESSION['quay_lai_xacnhan'] = true;
    echo '<script>alert("Vui lòng cập nhật hồ sơ trước khi đặt hàng.");window.location.href="hoso.php";</script>';
    exit;
}

// Lấy thông tin sản phẩm
$stmt = $conn->prepare("SELECT ten_giay, don_gia, ti_le_giam_gia, hinh_anh FROM giay WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($ten_giay, $don_gia, $ti_le_giam_gia, $hinh_anh);
if (!$stmt->fetch()) {
    die("Sản phẩm không tồn tại.");
}
$stmt->close();

$gia_ap_dung = ($ti_le_giam_gia > 0) ? $don_gia * (1 - $ti_le_giam_gia/100) : $don_gia;
$thanhtien = $gia_ap_dung * $soluong;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác nhận đặt hàng</title>
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
    <h2><i class="bi bi-receipt"></i> Xác nhận đơn hàng</h2>

    <h4>Thông tin khách hàng</h4>
    <table class="table table-bordered table-dark text-white w-50">
        <tr><th>Họ tên</th><td><?= htmlspecialchars($ho_ten) ?></td></tr>
        <tr><th>Địa chỉ</th><td><?= htmlspecialchars($dia_chi) ?></td></tr>
        <tr><th>Email</th><td><?= htmlspecialchars($email) ?></td></tr>
        <tr><th>Số điện thoại</th><td><?= htmlspecialchars($so_dien_thoai) ?></td></tr>
    </table>

    <h4>Sản phẩm đặt mua</h4>
    <table class="table table-bordered table-dark text-white">
        <thead>
            <tr>
                <th>Ảnh</th>
                <th>Tên giày</th>
                <th>Size</th>
                <th>Đơn giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><img src="../uploads/<?= htmlspecialchars($hinh_anh) ?>" width="80" alt=""></td>
                <td><?= htmlspecialchars($ten_giay) ?></td>
                <td><?= htmlspecialchars($size) ?></td>
                <td><?= number_format($gia_ap_dung,0,',','.') ?> đ</td>
                <td><?= $soluong ?></td>
                <td><?= number_format($thanhtien,0,',','.') ?> đ</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="text-end">Tổng cộng:</th>
                <th><?= number_format($thanhtien,0,',','.') ?> đ</th>
            </tr>
        </tfoot>
    </table>

    <form action="dathang_xuli.php" method="post">
        <!-- Truyền dữ liệu sang xử lý -->
        <input type="hidden" name="id" value="<?= $id ?>">
        <input type="hidden" name="size" value="<?= $size ?>">
        <input type="hidden" name="soluong" value="<?= $soluong ?>">

        <div class="mb-3">
            <label for="ghi_chu" class="form-label">Ghi chú cho đơn hàng</label>
            <textarea name="ghi_chu" id="ghi_chu" class="form-control" rows="3" placeholder="Ví dụ: Giao giờ hành chính, gọi trước khi giao..."></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Xác nhận đặt hàng</button>
        <a href="index.php" class="btn btn-secondary">Huỷ</a>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
