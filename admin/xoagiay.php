<?php
include 'includes/auth_admin.php';
include 'includes/db.php';
include_once 'includes/thongbao.php';


$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    // Kiểm tra giày đã xuất hiện trong đơn hàng chưa
    $stmt = $conn->prepare("SELECT COUNT(*) 
                            FROM chi_tiet_don_hang ctdh 
                            JOIN size_giay sg ON ctdh.size_giay_id = sg.id 
                            WHERE sg.giay_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        flashMessage('warning', 'Không thể xóa vì giày đã có trong đơn hàng.');
    } else {
        // Xóa tất cả size trước khi xóa giày
        $conn->query("DELETE FROM size_giay WHERE giay_id = $id");

        // Xóa giày
        $stmt = $conn->prepare("DELETE FROM giay WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            flashMessage('success', 'Xóa giày thành công.');
        } else {
            flashMessage('error', 'Lỗi khi xóa giày.');
        }
    }
} else {
    flashMessage('error', 'ID không hợp lệ.');
}

// Trở về danh sách
header("Location: danhsachgiay.php");
exit;
