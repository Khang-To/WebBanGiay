<?php
session_start();
require_once 'includes/cauhinh.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $taikhoan = trim($_POST['taikhoan']);
    $matkhau = md5(trim($_POST['matkhau'])); // Mã hóa MD5

$stmt = $conn->prepare("SELECT id, ho_ten, email, dia_chi, so_dien_thoai FROM khach_hang WHERE tai_khoan = ? AND mat_khau = ?");
$stmt->bind_param("ss", $taikhoan, $matkhau);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows == 1) {
$stmt->bind_result($id, $ho_ten, $email, $dia_chi, $so_dien_thoai);
    $stmt->fetch();
    $_SESSION['taikhoan'] = [
    'id' => $id,
    'tai_khoan' => $taikhoan,
    'ho_ten' => $ho_ten,
    'email' => $email,
    'dia_chi' => $dia_chi,
    'so_dien_thoai' => $so_dien_thoai
];
    $_SESSION['khach_hang_id'] = $id; // Thêm dòng này


        // 🔁 Khôi phục giỏ hàng từ cookie nếu có
        if (isset($_COOKIE['cart_backup'])) {
            $_SESSION['cart'] = json_decode($_COOKIE['cart_backup'], true);
            setcookie("cart_backup", "", time() - 3600, "/"); // Xóa cookie
        }

        header("Location: index.php");
        exit();
    } else {
        header("Location: dangnhap.php?error=Tài khoản hoặc mật khẩu không đúng");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
