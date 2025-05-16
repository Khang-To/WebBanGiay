<?php
include 'includes/auth_admin.php';
include 'includes/db.php';
include_once 'includes/thongbao.php';



$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    flashMessage('error', 'ID không hợp lệ!');
    header("Location: danhsachtonkho.php");
    exit;
}

// Lấy dữ liệu tồn kho cần sửa
$stmt = $conn->prepare("SELECT sg.*, g.ten_giay, th.ten_thuong_hieu, lg.ten_loai
                        FROM 
                        size_giay sg JOIN giay g ON sg.giay_id = g.id
                        JOIN thuong_hieu th ON g.thuong_hieu_id = th.id
                        JOIN loai_giay lg ON g.loai_giay_id = lg.id 
                        WHERE sg.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$tonkho = $result->fetch_assoc();

if (!$tonkho) {
    flashMessage('error', 'Không tìm thấy dữ liệu tồn kho!');
    header("Location: danhsachtonkho.php");
    exit;
}

// Xử lý khi submit cập nhật
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $so_luong_moi = intval($_POST['so_luong_ton']);
    if ($so_luong_moi >= 0) {
        $update = $conn->prepare("UPDATE size_giay SET so_luong_ton = ? WHERE id = ?");
        $update->bind_param("ii", $so_luong_moi, $id);
        if ($update->execute()) {
            flashMessage('success', 'Cập nhật số lượng thành công!');
        } else {
            flashMessage('error', 'Lỗi khi cập nhật dữ liệu!');
        }
        header("Location: danhsachtonkho.php");
        exit;
    } else {
        flashMessage('warning', 'Số lượng tồn không hợp lệ!');
    }
}

include 'includes/header.php';
?>

<h4 class="mb-3 text-center">Cập nhật tồn kho</h4>
<form method="POST" class="card card-body shadow-sm" style="max-width: 600px; margin: auto;">
    <p><strong>Giày:</strong> <?= htmlspecialchars($tonkho['ten_giay']) ?></p>
    <p><strong>Thương hiệu:</strong> <?= htmlspecialchars($tonkho['ten_thuong_hieu']) ?></p>
    <p><strong>Loại giày:</strong> <?= htmlspecialchars($tonkho['ten_loai']) ?></p>
    <p><strong>Size:</strong> <?= $tonkho['size'] ?></p>

    <label class="form-label">Số lượng tồn mới</label>
    <input type="number" name="so_luong_ton" class="form-control" min="0" value="<?= $tonkho['so_luong_ton'] ?>" required>

    <div class="mt-3 d-flex justify-content-between">
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="danhsachtonkho.php" class="btn btn-secondary">Quay lại</a>
    </div>
</form>

<?php include 'includes/footer.php'; ?>
