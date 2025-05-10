<?php
session_start();
include 'includes/cauhinh.php'; // Kết nối CSDL

// Kiểm tra đăng nhập
if (!isset($_SESSION['taikhoan'])) {
    header("Location: dangnhap.php?redirect=giohang");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng</title>
    <link rel="icon" type="image/png" href="images/logo.png">
    <script src="js/dropdown-hover.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-dark text-white">
<?php include 'includes/header.php'; ?>
<?php include 'includes/nav.php'; ?>

<div class="container py-5">
    <h2><i class="bi bi-cart"></i> Giỏ hàng của bạn</h2>
    <?php
    $tong_tien = 0;

    if (!isset($_SESSION['giohang']) || count($_SESSION['giohang']) == 0) {
        echo "<p class='text-warning'>Giỏ hàng trống.</p>";
    } else {
        $co_san_pham = false;
        echo '<form action="capnhatgiohang.php" method="post">';
        echo '<table class="table table-bordered text-white">';
        echo '<thead><tr>
                <th>Hình ảnh</th>
                <th>Tên giày</th>
                <th>Size</th>
                <th>Đơn giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
                <th>Xóa</th>
              </tr></thead><tbody>';

        foreach ($_SESSION['giohang'] as $key => $item) {
            if (strpos($key, '_') === false) continue;
            [$id, $size] = explode('_', $key);
            $soluong = (int)$item['so_luong'];

            $stmt = $conn->prepare("SELECT ten_giay, don_gia, hinh_anh FROM giay WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($ten_giay, $don_gia, $hinh_anh);

            if (!$stmt->fetch()) {
                $stmt->close();
                continue;
            }
            $stmt->close();

            $co_san_pham = true;
            $thanhtien = $don_gia * $soluong;
            $tong_tien += $thanhtien;

            echo "<tr>
                    <td><img src='uploads/{$hinh_anh}' width='80'></td>
                    <td>{$ten_giay}</td>
                    <td>{$size}</td>
                    <td>" . number_format($don_gia) . " đ</td>
                    <td><input type='number' name='soluong[{$key}]' value='{$soluong}' min='1' style='width: 60px'></td>
                    <td>" . number_format($thanhtien) . " đ</td>
                    <td><a href='xoagiohang.php?key={$key}' class='btn btn-danger btn-sm'>X</a></td>
                  </tr>";
        }

        echo "</tbody></table>";

        if (!$co_san_pham) {
            echo "<p class='text-warning'>Giỏ hàng trống hoặc sản phẩm không còn tồn tại.</p>";
        } else {
            echo "<h4 class='text-warning'>Tổng tiền: " . number_format($tong_tien) . " đ</h4>";
            echo '<a href="index.php" class="btn btn-outline-light">⬅ Tiếp tục mua</a> ';
            echo '<button type="submit" class="btn btn-success">🔄 Cập nhật giỏ hàng</button> ';
            echo '<a href="thanhtoan.php" class="btn btn-primary">💳 Thanh toán</a> ';
            echo '<a href="xoagiohang.php?all=1" class="btn btn-outline-danger">🗑 Xóa tất cả</a>';
        }

        echo '</form>';
    }
    ?>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
