<?php
include 'includes/cauhinh.php';

if (isset($_GET['id']) && isset($_GET['size']) && isset($_GET['soluong'])) {
    $id = (int)$_GET['id'];
    $size = (int)$_GET['size'];
    $soluong = (int)$_GET['soluong'];

    $stmt = $conn->prepare("SELECT so_luong_ton FROM size_giay WHERE giay_id = ? AND size = ?");
    $stmt->bind_param("ii", $id, $size);
    $stmt->execute();
    $stmt->bind_result($so_luong_ton);
    if ($stmt->fetch()) {
        if ($soluong > $so_luong_ton) {
            echo "Số lượng vượt quá tồn kho (chỉ còn $so_luong_ton đôi)";
        } else {
            echo ""; // Không có lỗi
        }
    } else {
        echo "Không tìm thấy thông tin size.";
    }
    $stmt->close();
}
?>
