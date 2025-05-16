<?php
include 'includes/auth_admin.php';
include 'includes/db.php';
include_once 'includes/thongbao.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten = trim($_POST['ten_loai'] ?? '');

    if ($ten !== '') {
        $stmt = $conn->prepare("INSERT INTO loai_giay (ten_loai) VALUES (?)");
        $stmt->bind_param("s", $ten);

        if ($stmt->execute()) {
            flashMessage('successs','Thêm loại giày thành công!');
        } else {
            flashMessage('error', 'Lỗi khi thêm: ' . $conn->error);
        }
    } else {
        flashMessage('warning', 'Vui lòng nhập tên loại');
    }
}

include 'includes/header.php';
?>

<!-- Thêm lọa giày và load danh sách -->
<div class="row">
    <div class="col-md-6">
        <h4 class="mb-3">Thêm loại giày mới</h4>
        <form method="POST" class="card card-body shadow-sm">
            <div class="mb-3">
                <label class="form-label">Tên loại giày</label>
                <input type="text" name="ten_loai" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Thêm</button>
        </form>
    </div>

    <div class="col-md-6">
        <h4 class="mb-3">Danh sách loại giày</h4>
        <!-- Form tìm kiếm -->
        <form method="GET" class="d-flex mb-3" style="max-width: 350px;">
            <input type="text" name="keyword" class="form-control me-2" placeholder="Tìm loại giày..." value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>">
            <button type="submit" class="btn btn-outline-secondary">Tìm</button>
        </form>

        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên loại giày</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $keyword = trim($_GET['keyword'] ?? '');
                    if ($keyword !== '') {
                        $stmt = $conn->prepare("SELECT * FROM loai_giay WHERE ten_loai LIKE CONCAT('%', ?, '%') ORDER BY id DESC");
                        $stmt->bind_param("s", $keyword);
                        $stmt->execute();
                        $result = $stmt->get_result();
                    } else {
                        $result = $conn->query("SELECT * FROM loai_giay ORDER BY id DESC");
                    }

                    while ($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td><?= $row["id"] ?></td>
                    <td><?= $row["ten_loai"] ?></td>
                    <td>
                        <a href="sualoaigiay.php?id=<?= $row["id"] ?>" class="btn btn-sm btn-primary">Sửa</a>
                        <a href="xoaloaigiay.php?id=<?= $row["id"] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
