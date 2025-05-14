<?php
include 'includes/auth_admin.php';
include 'includes/db.php';
include 'includes/thongbao.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    flashMessage('error', 'ID không hợp lệ!');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten = trim($_POST['ten_thuong_hieu'] ?? '');
    if ($ten !== '') {
        $stmt = $conn->prepare("UPDATE thuong_hieu SET ten_thuong_hieu = ? WHERE id = ?");
        $stmt->bind_param("si", $ten, $id);
        if ($stmt->execute()) {
            flashMessage('success', 'Đã cập nhật thương hiệu!');
        } else {
            flashMessage('error', 'Lỗi: ' . $conn->error);
        }
    } else {
        flashMessage('warning', 'Không được để trống tên!');
    }
}

// Lấy dữ liệu hiện tại
$stmt = $conn->prepare("SELECT ten_thuong_hieu FROM thuong_hieu WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($ten_hien_tai);
$stmt->fetch();
$stmt->close();

include 'includes/header.php';
?>

<div class="container">
  <h4 class="mt-3">Sửa thương hiệu</h4>
  <form method="POST" class="card card-body shadow-sm mt-3" style="max-width:500px">
    <div class="mb-3">
      <label class="form-label">Tên thương hiệu</label>
      <input type="text" name="ten_thuong_hieu" class="form-control" value="<?= htmlspecialchars($ten_hien_tai) ?>" required>
    </div>
    <button type="submit" class="btn btn-success mb-2">Cập nhật</button>
    <a href="themthuonghieu.php" class="btn btn-secondary">Quay lại</a>
  </form>
</div>

<?php include 'includes/footer.php'; ?>
