<?php
    include 'includes/auth_admin.php';
    include 'includes/db.php';
    include_once 'includes/thongbao.php';

    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM khach_hang WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $khach = $stmt->get_result()->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $ho_ten = trim($_POST['ho_ten']);
        $email = trim($_POST['email']);
        $so_dien_thoai = trim($_POST['so_dien_thoai']);
        $dia_chi = trim($_POST['dia_chi']);

        $update = $conn->prepare("UPDATE khach_hang SET ho_ten = ?, email = ?, so_dien_thoai = ?, dia_chi = ? WHERE id = ?");
        $update->bind_param("ssssi", $ho_ten, $email, $so_dien_thoai, $dia_chi, $id);

        if ($update->execute()) {
            flashMessage('success', 'Cập nhật thành công!');
            header("Location: danhsach_khachhang.php");
            exit;
        } else {
            flashMessage('error', 'Lỗi khi cập nhật!');
        }
    }

    include 'includes/header.php';
?>

<h4 class="mb-3 text-center">Sửa thông tin khách hàng</h4>

<form method="POST" enctype="multipart/form-data" class="card card-body shadow-sm" style="max-width: 700px; margin: auto;">
    <label class="form-label">Họ tên</label>
    <input type="text" name="ho_ten" class="form-control" value="<?= htmlspecialchars($khach['ho_ten']) ?>" required>

    <label class="form-label mt-3">Email</label>
    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($khach['email']) ?>" required>

    <label class="form-label mt-3">Số điện thoại</label>
    <input type="text" name="so_dien_thoai" class="form-control" value="<?= htmlspecialchars($khach['so_dien_thoai']) ?>" required>

    <label class="form-label mt-3">Địa chỉ</label>
    <input type="text" name="dia_chi" class="form-control" value="<?= htmlspecialchars($khach['dia_chi']) ?>" required>

    <button type="submit" class="btn btn-primary mt-3">Cập nhật</button>
    <a href="danhsach_khachhang.php" class="btn btn-secondary mt-3">Quay lại</a>
</form>

<?php include 'includes/footer.php'; ?>
