<?php
session_start();

// Bật hiển thị lỗi
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'includes/cauhinh.php';

// Kiểm tra phiên đăng nhập
if (!isset($_SESSION['taikhoan']) || !is_array($_SESSION['taikhoan'])) {
    header("Location: dangnhap.php");
    exit();
}

$taikhoan = $_SESSION['taikhoan']['tai_khoan'] ?? null;

if (!$taikhoan) {
    $_SESSION['loi_chung'] = "Lỗi hệ thống: Tài khoản không hợp lệ.";
    header("Location: hoso.php");
    exit();
}

// ==== CẬP NHẬT THÔNG TIN CÁ NHÂN ==== 
if (isset($_POST['ho_ten'], $_POST['dia_chi'], $_POST['so_dien_thoai']) && empty($_POST['mat_khau_cu'])) {
    $ho_ten = trim($_POST['ho_ten']);
    $dia_chi = trim($_POST['dia_chi']);
    $so_dien_thoai = trim($_POST['so_dien_thoai']);  // Lấy số điện thoại từ form

    $sql_update_info = "UPDATE khach_hang SET ho_ten = ?, dia_chi = ?, so_dien_thoai = ? WHERE tai_khoan = ?";
    $stmt_info = $conn->prepare($sql_update_info);
    $stmt_info->bind_param("ssss", $ho_ten, $dia_chi, $so_dien_thoai, $taikhoan);
    $stmt_info->execute();

    // Cập nhật session để hiển thị lại thông tin mới
    $_SESSION['taikhoan']['ho_ten'] = $ho_ten;
    $_SESSION['taikhoan']['dia_chi'] = $dia_chi;
    $_SESSION['taikhoan']['so_dien_thoai'] = $so_dien_thoai;  // Cập nhật số điện thoại trong session

    $_SESSION['thongbao_thongtin'] = "Cập nhật thông tin thành công!";

    // Nếu người dùng đang quay lại từ trang xác nhận, chuyển đến trang đặt hàng
    if (isset($_SESSION['quay_lai_xacnhan']) && $_SESSION['quay_lai_xacnhan'] === true) {
        unset($_SESSION['quay_lai_xacnhan']);
        header("Location: dathang.php");
    } else {
        header("Location: hoso.php");
    }
    exit();
}


// ==== ĐỔI MẬT KHẨU ====
if (isset($_POST['mat_khau_cu'], $_POST['mat_khau_moi'], $_POST['mat_khau_lai'])) {
    $mat_khau_cu = md5($_POST['mat_khau_cu']);
    $mat_khau_moi = $_POST['mat_khau_moi'];
    $mat_khau_lai = $_POST['mat_khau_lai'];

    // Kiểm tra mật khẩu mới nhập lại có khớp không
    if ($mat_khau_moi !== $mat_khau_lai) {
        $_SESSION['loi_mk'] = "Mật khẩu mới không khớp.";
        header("Location: hoso.php");
        exit();
    }

    // Kiểm tra mật khẩu cũ
    $sql_check = "SELECT * FROM khach_hang WHERE tai_khoan = ? AND mat_khau = ?";
    $stmt = $conn->prepare($sql_check);
    $stmt->bind_param("ss", $taikhoan, $mat_khau_cu);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['loi_mk'] = "Mật khẩu cũ không đúng.";
        header("Location: hoso.php");
        exit();
    }

    // Cập nhật mật khẩu mới
    $mat_khau_moi_mahoa = md5($mat_khau_moi);
    $sql_update = "UPDATE khach_hang SET mat_khau = ? WHERE tai_khoan = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ss", $mat_khau_moi_mahoa, $taikhoan);
    $stmt_update->execute();

    $_SESSION['thongbao_mk'] = "Đổi mật khẩu thành công!";
    header("Location: hoso.php");
    exit();
}

// Nếu không khớp dữ liệu nào hợp lệ, trở về hồ sơ
$_SESSION['loi_chung'] = "Dữ liệu không hợp lệ.";
header("Location: hoso.php");
exit();
?>
