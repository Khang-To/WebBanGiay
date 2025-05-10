<?php
session_start();

// Bật hiển thị lỗi (để dễ debug khi trắng trang)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'includes/cauhinh.php';

if (!isset($_SESSION['taikhoan'])) {
    header("Location: dangnhap.php");
    exit();
}

$taikhoan = $_SESSION['taikhoan'];

// Nếu là cập nhật thông tin cá nhân
if (isset($_POST['ho_ten']) && isset($_POST['dia_chi']) && !isset($_POST['mat_khau_cu'])) {
    $ho_ten = $_POST['ho_ten'];
    $dia_chi = $_POST['dia_chi'];

    $sql_update_info = "UPDATE khach_hang SET ho_ten = ?, dia_chi = ? WHERE tai_khoan = ?";
    $stmt_info = $conn->prepare($sql_update_info);
    $stmt_info->bind_param("sss", $ho_ten, $dia_chi, $taikhoan);
    $stmt_info->execute();

    $_SESSION['thongbao_thongtin'] = "Cập nhật thông tin thành công!";
    header("Location: hoso.php");
    exit();
}

// Nếu là đổi mật khẩu
if (isset($_POST['mat_khau_cu']) && isset($_POST['mat_khau_moi']) && isset($_POST['mat_khau_lai'])) {
    $mat_khau_cu = md5($_POST['mat_khau_cu']);
    $mat_khau_moi = $_POST['mat_khau_moi'];
    $mat_khau_lai = $_POST['mat_khau_lai'];

    if ($mat_khau_moi !== $mat_khau_lai) {
        $_SESSION['loi_mk'] = "Mật khẩu mới không khớp.";
        header("Location: hoso.php");
        exit();
    }

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

    $mat_khau_moi_mahoa = md5($mat_khau_moi);
    $sql_update = "UPDATE khach_hang SET mat_khau = ? WHERE tai_khoan = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ss", $mat_khau_moi_mahoa, $taikhoan);
    $stmt_update->execute();

    $_SESSION['thongbao_mk'] = "Đổi mật khẩu thành công!";
    header("Location: hoso.php");
    exit();
}

// Nếu không khớp dữ liệu gửi, quay lại trang hồ sơ
header("Location: hoso.php");
exit();
?>
