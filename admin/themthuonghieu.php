<?php
include 'includes/auth_admin.php';
include 'includes/db.php';
include 'includes/thongbao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten = trim($_POST['ten_thuong_hieu'] ?? '');

    if ($ten !== '') {
        $stmt = $conn->prepare("INSERT INTO thuong_hieu (ten_thuong_hieu) VALUES (?)");
        $stmt->bind_param("s", $ten);

        if ($stmt->execute()) {
            flashMessage('successs','Thêm thương hiệu thành công!');
        } else {
            flashMessage('error', 'Lỗi khi thêm: ' . $conn->error);
        }
    } else {
        flashMessage('warning', 'Vui lòng nhập tên thương hiệu');
    }

    //thông báo xóa thành công
    if (isset($_GET['msg']) && $_GET['msg'] === 'deleted') {
    flashMessage('success', '🗑️ Đã xóa thương hiệu!');
    }
}

include 'includes/header.php';
?>

<!-- Thêm thương hiệu và load danh sách -->
<div class="row">
    <div class="col-md-6">
        <h4 class="mb-3">Thêm thương hiệu mới</h4>
        <form method="POST" class="card card-body shadow-sm">
            <div class="mb-3">
                <label class="form-label">Tên thương hiệu</label>
                <input type="text" name="ten_thuong_hieu" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Thêm</button>
        </form>
    </div>

    <div class="col-md-6">
        <h4 class="mb-3">Danh sách thương hiệu</h4>
        <!-- Form tìm kiếm -->
        <form method="GET" class="d-flex mb-3" style="max-width: 350px;">
            <input type="text" name="keyword" class="form-control me-2" placeholder="Tìm thương hiệu..." value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>">
            <button type="submit" class="btn btn-outline-secondary">Tìm</button>
        </form>

        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên thương hiệu</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $keyword = trim($_GET['keyword'] ?? '');
                    if ($keyword !== '') {
                        $stmt = $conn->prepare("SELECT * FROM thuong_hieu WHERE ten_thuong_hieu LIKE CONCAT('%', ?, '%') ORDER BY id DESC");
                        $stmt->bind_param("s", $keyword);
                        $stmt->execute();
                        $result = $stmt->get_result();
                    } else {
                        $result = $conn->query("SELECT * FROM thuong_hieu ORDER BY id DESC");
                    }

                    while ($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td><?= $row["id"] ?></td>
                    <td><?= $row["ten_thuong_hieu"] ?></td>
                    <td>
                        <a href="suathuonghieu.php?id=<?= $row["id"] ?>" class="btn btn-sm btn-primary">Sửa</a>
                        <a href="xoathuonghieu.php?id=<?= $row["id"] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
