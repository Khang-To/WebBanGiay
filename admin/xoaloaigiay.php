<?php
include 'includes/auth_admin.php';
include 'includes/db.php';
include_once 'includes/thongbao.php';

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    // Kiểm tra xem loại giày có đang được sử dụng không
    $check = $conn->prepare("SELECT COUNT(*) FROM giay WHERE loai_giay_id = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $check->bind_result($count);
    $check->fetch();
    $check->close();

    if ($count > 0) {
        flashMessage('warning', 'Không thể xóa vì vẫn còn giày thuộc loại giày này!');
    } else {
        // Nếu không bị ràng buộc thì xóa
        $stmt = $conn->prepare("DELETE FROM loai_giay WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            flashMessage('success', 'Xóa loại giày thành công!');
        } else {
            flashMessage('error', 'Lỗi khi xóa loại giày!');
        }
    }
}

header("Location: themloaigiay.php");
exit;
