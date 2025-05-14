<?php
    include 'includes/db.php';
    include 'includes/auth_admin.php';
    include 'includes/thongbao.php';

    // ===== PHÂN TRANG + TÌM KIẾM =====
    $limit = 4;
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $offset = ($page - 1) * $limit;

    $search = $_GET['search'] ?? '';
    $thuong_hieu_id = $_GET['thuong_hieu_id'] ?? '';
    $loai_giay_id = $_GET['loai_giay_id'] ?? '';

    $where = "WHERE 1=1";
    $params = [];
    $types = '';

    if ($search !== '') {
        $where .= " AND g.ten_giay LIKE ?";
        $params[] = "%$search%";
        $types .= 's';
    }
    if ($thuong_hieu_id !== '') {
        $where .= " AND g.thuong_hieu_id = ?";
        $params[] = $thuong_hieu_id;
        $types .= 'i';
    }
    if ($loai_giay_id !== '') {
        $where .= " AND g.loai_giay_id = ?";
        $params[] = $loai_giay_id;
        $types .= 'i';
    }

    // ===== ĐẾM TỔNG =====
    $sqlCount = "SELECT COUNT(*) FROM giay g $where";
    $stmt = $conn->prepare($sqlCount);
    if (!empty($params)) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $stmt->bind_result($total);
    $stmt->fetch();
    $stmt->close();

    $total_pages = ceil($total / $limit);

    // ===== LẤY DỮ LIỆU TRANG HIỆN TẠI =====
    $sql = "SELECT g.*, th.ten_thuong_hieu, lg.ten_loai 
            FROM giay g
            JOIN thuong_hieu th ON g.thuong_hieu_id = th.id
            JOIN loai_giay lg ON g.loai_giay_id = lg.id
            $where
            ORDER BY g.id DESC
            LIMIT ?, ?";
    $params[] = $offset;
    $params[] = $limit;
    $types .= 'ii';

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    $thuong_hieu_rs = $conn->query("SELECT * FROM thuong_hieu");
    $loai_giay_rs = $conn->query("SELECT * FROM loai_giay");

    include 'includes/header.php';
?>

<h4 class="mb-3">Danh sách mẫu giày</h4>

<form method="GET" class="mb-3">
    <div class="row g-2">
        <div class="col-md-4">
            <select name="thuong_hieu_id" class="form-select">
                <option value="">-- Chọn thương hiệu --</option>
                <?php
                    $thuong_hieu = $conn->query("SELECT * FROM thuong_hieu");
                    while($th = $thuong_hieu->fetch_assoc()):
                ?>
                    <option value="<?= $th['id'] ?>" <?= isset($_GET['thuong_hieu_id']) && $_GET['thuong_hieu_id'] == $th['id'] ? 'selected' : '' ?>>
                        <?= $th['ten_thuong_hieu'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-4">
            <select name="loai_giay_id" class="form-select">
                <option value="">-- Chọn loại giày --</option>
                <?php
                    $loai_giay = $conn->query("SELECT * FROM loai_giay");
                    while($lg = $loai_giay->fetch_assoc()):
                ?>
                    <option value="<?= $lg['id'] ?>" <?= isset($_GET['loai_giay_id']) && $_GET['loai_giay_id'] == $lg['id'] ? 'selected' : '' ?>>
                        <?= $lg['ten_loai'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên giày..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
        </div>
    </div>
    <div class="mt-2">
        <button class="btn btn-primary" type="submit">Lọc</button>
        <a href="danhsachgiay.php" class="btn btn-secondary">Reset</a>
    </div>
</form>


<table class="table table-bordered table-hover">
    <thead class="table-light">
        <tr>
            <th>Hình ảnh</th>
            <th class="w-40">Tên giày</th>
            <th>Thương hiệu</th>
            <th>Loại giày</th>
            <th>Đơn giá</th>
            <th>Giảm giá (%)</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td>
                    <img src="../uploads/<?= htmlspecialchars($row['hinh_anh']) ?>" alt="Hình ảnh" width="80" height="80" style="object-fit: cover;">
                </td>
                <td style="width: 400px; word-break: break-word;" ><?= htmlspecialchars($row['ten_giay']) ?></td>
                <td><?= htmlspecialchars($row['ten_thuong_hieu']) ?></td>
                <td><?= htmlspecialchars($row['ten_loai']) ?></td>
                <td><?= number_format($row['don_gia'], 0, ',', '.') ?> đ</td>
                <td><?= $row['ti_le_giam_gia'] ?>%</td>
                <td>
                    <div class="d-flex gap-2">
                        <a href="suagiay.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                        <a href="xoagiay.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
                    </div>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php if ($total_pages > 1): ?>
<nav>
    <ul class="pagination text-center">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
