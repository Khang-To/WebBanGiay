<?php
include 'includes/auth_admin.php';
include 'includes/db.php';
include_once 'includes/thongbao.php';

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    // Kiểm tra xem loại giày có đang được sử dụng không
    $check = $conn->prepare("SELECT COUNT(*) FROM don_hang WHERE khach_hang_id = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $check->bind_result($count);
    $check->fetch();
    $check->close();

    if ($count > 0) {
        flashMessage('warning', 'Không thể xóa vì khách vẫn còn các đơn hàng!');
    } else {
        // Nếu không bị ràng buộc thì xóa
        $stmt = $conn->prepare("DELETE FROM thuong_hieu WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            flashMessage('success', 'Xóa khách hàng thành công!');
        } else {
            flashMessage('error', 'Lỗi khi xóa khách hàng!');
        }
    }
}

header("Location: danhsach_khachhang.php");
exit;
