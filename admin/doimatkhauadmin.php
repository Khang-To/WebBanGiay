<?php
include 'includes/auth_admin.php';
include 'includes/db.php';
include 'includes/thongbao.php';

$admin_id = $_SESSION['admin_id'] ?? null;

if (!$admin_id) {
    header('Location: dangnhap.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mat_khau_cu = $_POST['mat_khau_cu'];
    $mat_khau_moi = $_POST['mat_khau_moi'];
    $xac_nhan = $_POST['xac_nhan'];

    $stmt = $conn->prepare("SELECT mat_khau FROM admin WHERE id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $stmt->bind_result($mat_khau_db);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($mat_khau_cu, $mat_khau_db)) {
        flashMessage('danger', 'Mật khẩu cũ không chính xác!');
    } elseif ($mat_khau_moi !== $xac_nhan) {
        flashMessage('warning', 'Mật khẩu xác nhận không khớp!');
    } else {
        $new_hash = password_hash($mat_khau_moi, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE admin SET mat_khau = ? WHERE id = ?");
        $stmt->bind_param("si", $new_hash, $admin_id);
        $stmt->execute();
        flashMessage('success', 'Đổi mật khẩu thành công!');
        header("Location: doimatkhauadmin.php");
        exit;
    }
}

include 'includes/header.php';
?>

<div class="container mt-4">
    <h4 class="mb-3">Đổi mật khẩu</h4>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Mật khẩu hiện tại</label>
            <input type="password" class="form-control" name="mat_khau_cu" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Mật khẩu mới</label>
            <input type="password" class="form-control" name="mat_khau_moi" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Xác nhận mật khẩu mới</label>
            <input type="password" class="form-control" name="xac_nhan" required>
        </div>
        <button type="submit" class="btn btn-primary">Lưu mật khẩu</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
