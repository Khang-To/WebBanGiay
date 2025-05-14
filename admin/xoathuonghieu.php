<?php
include 'includes/auth_admin.php';
include 'includes/db.php';

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM thuong_hieu WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Trở về trang chính sau khi xóa
header("Location: themthuonghieu.php?msg=deleted");
exit;
