<?php
session_start();
include 'includes/cauhinh.php'; // Kết nối CSDL

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
    if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])) {
        echo '<div class="alert alert-danger">';
        foreach ($_SESSION['errors'] as $error) {
            echo "<p>$error</p>";
        }
        echo '</div>';
        unset($_SESSION['errors']);
    }

    $tong_tien = 0;

    if (!isset($_SESSION['giohang']) || count($_SESSION['giohang']) == 0) {
        echo "<p class='text-warning'>Giỏ hàng trống.</p>";
    } else {
        $co_san_pham = false;
        echo '<form id="form-giohang">';  // bỏ action để không submit cứng
        echo '<table class="table table-bordered table-dark text-white">';
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

            $stmt = $conn->prepare("SELECT ten_giay, don_gia, ti_le_giam_gia, hinh_anh FROM giay WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($ten_giay, $don_gia, $ti_le_giam_gia, $hinh_anh);

            if (!$stmt->fetch()) {
                $stmt->close();
                continue;
            }
            $stmt->close();

            $co_san_pham = true;
            $gia_ap_dung = ($ti_le_giam_gia > 0) ? $don_gia * (1 - $ti_le_giam_gia / 100) : $don_gia;
            $thanhtien = $gia_ap_dung * $soluong;
            $tong_tien += $thanhtien;

            echo "<tr data-key='{$key}'>
                    <td><img src='../uploads/{$hinh_anh}' width='80'></td>
                    <td>{$ten_giay}</td>
                    <td>{$size}</td>
                    <td class='text-price'>" . number_format($gia_ap_dung) . " đ</td>
                    <td>
                        <input type='number' name='soluong[{$key}]' value='{$soluong}' min='1' style='width: 60px' class='input-soluong'>
                        <div class='text-warning warning-msg'></div>
                    </td>
                    <td class='text-price thanhtien'>" . number_format($thanhtien) . " đ</td>
                    <td><a href='xoagiohang.php?key={$key}' class='btn btn-danger btn-sm'>X</a></td>
                  </tr>";
        }

        echo "</tbody></table>";

        if (!$co_san_pham) {
            echo "<p class='text-warning'>Giỏ hàng trống hoặc sản phẩm không còn tồn tại.</p>";
        } else {
            echo "<h4 class='text-price' id='tong-tien'>Tổng tiền: " . number_format($tong_tien) . " đ</h4>";
            echo '<a href="index.php" class="btn btn-outline-light">⬅ Tiếp tục mua</a> ';
            echo '<button type="button" id="btn-dathang" class="btn btn-primary" disabled>💳 Đặt hàng</button> ';
            echo '<a href="xoagiohang.php?all=1" class="btn btn-outline-danger">🗑 Xóa tất cả</a>';
        }

        echo '</form>';
    }
    ?>
</div>

<?php include 'includes/footer.php'; ?>

<script>
const btnOrder = document.getElementById('btn-dathang');
const warningElements = document.querySelectorAll('.warning-msg');

function checkWarnings() {
    // Nếu có ít nhất 1 warning khác rỗng thì disable btn đặt hàng
    for (let we of warningElements) {
        if (we.textContent.trim() !== '') {
            btnOrder.disabled = true;
            btnOrder.title = we.textContent.trim();
            return false;
        }
    }
    btnOrder.disabled = false;
    btnOrder.title = '';
    return true;
}

function updateCart(key, soluong) {
    return fetch('capnhatgiohang_ajax.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: new URLSearchParams({
            'key': key,
            'soluong': soluong
        })
    })
    .then(res => res.json());
}

document.querySelectorAll('.input-soluong').forEach(input => {
    input.addEventListener('input', () => {
        const tr = input.closest('tr');
        const key = tr.dataset.key;
        let soluong = input.value;

        // Bỏ trống hoặc số âm thì thôi không gửi request
        if (soluong === '' || soluong < 1) {
            input.nextElementSibling.textContent = 'Số lượng phải là số nguyên lớn hơn 0';
            btnOrder.disabled = true;
            return;
        } else {
            input.nextElementSibling.textContent = '';
        }

        updateCart(key, soluong).then(data => {
            if (data.error) {
                input.nextElementSibling.textContent = data.error;
            } else {
                input.nextElementSibling.textContent = '';
                // Cập nhật thành tiền và tổng tiền
                tr.querySelector('.thanhtien').textContent = new Intl.NumberFormat().format(data.thanhtien) + ' đ';
                document.getElementById('tong-tien').textContent = 'Tổng tiền: ' + new Intl.NumberFormat().format(data.tongtien) + ' đ';
            }
            checkWarnings();
        });
    });
});

// Xử lý nút Đặt hàng
btnOrder.addEventListener('click', () => {
    if (!btnOrder.disabled) {
        window.location.href = 'dathang.php';
    }
});

// Khi trang load xong, kiểm tra lỗi tồn kho ban đầu, bật/tắt nút đặt hàng phù hợp
window.addEventListener('DOMContentLoaded', () => {
    checkWarnings();
});
</script>
</body>
</html>
