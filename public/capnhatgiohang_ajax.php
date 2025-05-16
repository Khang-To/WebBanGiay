<?php
session_start();
include 'includes/cauhinh.php'; // Kết nối CSDL

header('Content-Type: application/json');

if (!isset($_SESSION['taikhoan'])) {
    echo json_encode(['error' => 'Bạn chưa đăng nhập']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Phương thức không hợp lệ']);
    exit();
}

$key = $_POST['key'] ?? '';
$soluong = (int)($_POST['soluong'] ?? 0);

if ($key === '' || $soluong < 1) {
    echo json_encode(['error' => 'Số lượng không hợp lệ']);
    exit();
}

if (!isset($_SESSION['giohang'][$key])) {
    echo json_encode(['error' => 'Sản phẩm không tồn tại trong giỏ hàng']);
    exit();
}

list($id, $size) = explode('_', $key);

// Kiểm tra tồn kho
$stmt = $conn->prepare("SELECT so_luong_ton FROM size_giay WHERE giay_id = ? AND size = ?");
$stmt->bind_param("ii", $id, $size);
$stmt->execute();
$stmt->bind_result($so_luong_ton);
if (!$stmt->fetch()) {
    $stmt->close();
    echo json_encode(['error' => 'Không tìm thấy sản phẩm hoặc size']);
    exit();
}
$stmt->close();

if ($soluong > $so_luong_ton) {
    echo json_encode(['error' => "Số lượng vượt quá tồn kho ($so_luong_ton)"]);
    exit();
}

// Cập nhật số lượng trong giỏ
$_SESSION['giohang'][$key]['so_luong'] = $soluong;

// Tính lại thành tiền và tổng tiền
// Lấy giá giày
$stmt2 = $conn->prepare("SELECT don_gia, ti_le_giam_gia FROM giay WHERE id = ?");
$stmt2->bind_param("i", $id);
$stmt2->execute();
$stmt2->bind_result($don_gia, $ti_le_giam_gia);
$stmt2->fetch();
$stmt2->close();

$gia_ap_dung = ($ti_le_giam_gia > 0) ? $don_gia * (1 - $ti_le_giam_gia / 100) : $don_gia;
$thanhtien = $gia_ap_dung * $soluong;

// Tính tổng tiền tất cả sản phẩm trong giỏ
$tongtien = 0;
foreach ($_SESSION['giohang'] as $k => $item) {
    list($pid, $psize) = explode('_', $k);
    $sl = (int)$item['so_luong'];

    $stmt3 = $conn->prepare("SELECT don_gia, ti_le_giam_gia FROM giay WHERE id = ?");
    $stmt3->bind_param("i", $pid);
    $stmt3->execute();
    $stmt3->bind_result($dg, $tlgg);
    $stmt3->fetch();
    $stmt3->close();

    $gia = ($tlgg > 0) ? $dg * (1 - $tlgg / 100) : $dg;
    $tongtien += $gia * $sl;
}

echo json_encode([
    'error' => '',
    'thanhtien' => $thanhtien,
    'tongtien' => $tongtien
]);
