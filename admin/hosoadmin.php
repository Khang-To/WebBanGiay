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
        $ho_ten = trim($_POST['ho_ten']);
        $email = trim($_POST['email']);
        $dia_chi = trim($_POST['dia_chi']);

        $stmt = $conn->prepare("UPDATE admin SET ho_ten = ?, email = ?, dia_chi = ? WHERE id = ?");
        $stmt->bind_param('sssi', $ho_ten, $email, $dia_chi, $admin_id);
        $stmt->execute();

        $_SESSION['admin_ten'] = $ho_ten;
        
        flashMessage('success', 'Cập nhật thông tin thành công!');
        header('Location: hosoadmin.php');
        exit;
    }

    $stmt = $conn->prepare("SELECT ho_ten, email, dia_chi FROM admin WHERE id = ?");
    $stmt->bind_param('i', $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();
    $stmt->close();

    include 'includes/header.php';
?>

<div class="container mt-4">
    <h4 class="mb-3">Hồ sơ cá nhân</h4>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Họ tên</label>
            <input type="text" class="form-control" name="ho_ten" value="<?= htmlspecialchars($admin['ho_ten']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($admin['email']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Địa chỉ</label>
            <input type="text" class="form-control" name="dia_chi" value="<?= htmlspecialchars($admin['dia_chi']) ?>">
        </div>
        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
