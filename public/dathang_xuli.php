<?php
session_start();
include 'includes/cauhinh.php'; // Kết nối CSDL

// Kiểm tra đăng nhập
if (!isset($_SESSION['taikhoan']) || !is_array($_SESSION['taikhoan'])) {
    header("Location: dangnhap.php?redirect=dathang");
    exit;
}

// Lấy thông tin khách hàng
$khach_hang_id = $_SESSION['taikhoan']['id'];
$ghi_chu = trim($_POST['ghi_chu'] ?? '');

$ho_ten = $_SESSION['taikhoan']['ho_ten'] ?? '';
$dia_chi = $_SESSION['taikhoan']['dia_chi'] ?? '';
$so_dien_thoai = $_SESSION['taikhoan']['so_dien_thoai'] ?? '';

if (empty(trim($ho_ten)) || empty(trim($dia_chi)) || empty(trim($so_dien_thoai))) {
    $_SESSION['quay_lai_xacnhan'] = true;
    header("Location: hoso.php");
    exit;
}

// Kiểm tra: mua ngay hay từ giỏ hàng?
$is_mua_ngay = isset($_POST['id'], $_POST['size'], $_POST['soluong']);

$conn->begin_transaction();

try {
    // Tạo đơn hàng
    $stmt = $conn->prepare("INSERT INTO don_hang (khach_hang_id, ghi_chu) VALUES (?, ?)");
    $stmt->bind_param("is", $khach_hang_id, $ghi_chu);
    $stmt->execute();
    $don_hang_id = $stmt->insert_id;
    $stmt->close();

    if ($is_mua_ngay) {
        // ======= MUA NGAY 1 SẢN PHẨM =======
        $giay_id = (int)$_POST['id'];
        $size = (int)$_POST['size'];
        $so_luong = (int)$_POST['soluong'];

        if ($so_luong <= 0 || $size <= 0 || $giay_id <= 0) {
            throw new Exception("Dữ liệu đầu vào không hợp lệ.");
        }

        $stmt1 = $conn->prepare("SELECT don_gia, ti_le_giam_gia FROM giay WHERE id = ?");
        $stmt1->bind_param("i", $giay_id);
        $stmt1->execute();
        $stmt1->bind_result($don_gia, $giam);
        if (!$stmt1->fetch()) {
            throw new Exception("Không tìm thấy giày với ID $giay_id");
        }
        $stmt1->close();

        $gia_ban = ($giam > 0) ? $don_gia * (1 - $giam / 100) : $don_gia;

        $stmt2 = $conn->prepare("SELECT id FROM size_giay WHERE giay_id = ? AND size = ?");
        $stmt2->bind_param("ii", $giay_id, $size);
        $stmt2->execute();
        $stmt2->bind_result($size_giay_id);
        if ($stmt2->fetch()) {
            $stmt2->close();

            $stmt3 = $conn->prepare("INSERT INTO chi_tiet_don_hang (don_hang_id, size_giay_id, so_luong_ban, don_gia_ban) VALUES (?, ?, ?, ?)");
            $stmt3->bind_param("iiid", $don_hang_id, $size_giay_id, $so_luong, $gia_ban);
            $stmt3->execute();
            $stmt3->close();
        } else {
            throw new Exception("Không tìm thấy size giày cho sản phẩm $giay_id size $size");
        }
    } else {
        // ======= ĐẶT TỪ GIỎ HÀNG =======
        if (!isset($_SESSION['giohang']) || count($_SESSION['giohang']) === 0) {
            throw new Exception("Giỏ hàng rỗng.");
        }

        foreach ($_SESSION['giohang'] as $key => $item) {
            list($giay_id, $size) = explode('_', $key);
            $so_luong = (int)$item['so_luong'];

            $stmt1 = $conn->prepare("SELECT don_gia, ti_le_giam_gia FROM giay WHERE id = ?");
            $stmt1->bind_param("i", $giay_id);
            $stmt1->execute();
            $stmt1->bind_result($don_gia, $giam);
            if (!$stmt1->fetch()) {
                throw new Exception("Không tìm thấy giày với ID $giay_id");
            }
            $stmt1->close();

            $gia_ban = ($giam > 0) ? $don_gia * (1 - $giam / 100) : $don_gia;

            $stmt2 = $conn->prepare("SELECT id FROM size_giay WHERE giay_id = ? AND size = ?");
            $stmt2->bind_param("ii", $giay_id, $size);
            $stmt2->execute();
            $stmt2->bind_result($size_giay_id);
            if ($stmt2->fetch()) {
                $stmt2->close();

                $stmt3 = $conn->prepare("INSERT INTO chi_tiet_don_hang (don_hang_id, size_giay_id, so_luong_ban, don_gia_ban) VALUES (?, ?, ?, ?)");
                $stmt3->bind_param("iiid", $don_hang_id, $size_giay_id, $so_luong, $gia_ban);
                $stmt3->execute();
                $stmt3->close();
            } else {
                throw new Exception("Không tìm thấy size giày cho sản phẩm $giay_id size $size");
            }
        }

        // Xoá giỏ hàng sau khi đặt xong
        unset($_SESSION['giohang']);
    }

    $conn->commit();
    header("Location: thongbaodathang.php?msg=DatHangThanhCong");
    exit;
} catch (Exception $e) {
    $conn->rollback();
    echo "Lỗi: " . $e->getMessage();
}
?>
