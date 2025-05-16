<?php
    include 'includes/auth_admin.php';
    include 'includes/db.php';

    // ===== PHÂN TRANG + TÌM KIẾM =====
    $limit = 8;
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $offset = ($page - 1) * $limit;

    $search = trim(preg_replace('/\s+/', ' ', $_GET['search'] ?? ''));

    $where = "WHERE 1=1";
    $params = [];
    $types = '';

    if ($search !== '') {
        $clean = strtolower($search);
        $where .= " AND (
            LOWER(tai_khoan) LIKE ? OR 
            LOWER(ho_ten) LIKE ? OR 
            LOWER(email) LIKE ? OR 
            so_dien_thoai LIKE ?
        )";
        for ($i = 0; $i < 4; $i++) {
            $params[] = "%$clean%";
            $types .= 's';
        }
    }

    // ===== ĐẾM TỔNG =====
    $sqlCount = "SELECT COUNT(*) FROM khach_hang $where";
    $stmt = $conn->prepare($sqlCount);
    if (!empty($params)) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $stmt->bind_result($total);
    $stmt->fetch();
    $stmt->close();

    $total_pages = ceil($total / $limit);

    // ===== LẤY DỮ LIỆU TRANG HIỆN TẠI =====
    $sql = "SELECT * FROM khach_hang kh
            $where
            ORDER BY kh.id DESC
            LIMIT ?, ?";
    $params[] = $offset;
    $params[] = $limit;
    $types .= 'ii';

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    include 'includes/header.php';
?>

<div class="container mt-4">
    <h4 class="mb-3">Danh sách khách hàng</h4>
    <form method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm khách hàng..." value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-primary" type="submit">Tìm kiếm</button>
            <a href="danhsach_khachhang.php" class="btn btn-secondary">Reset</a>
        </div>
    </form>
    <?php if ($result->num_rows === 0): ?>
        <div class="alert alert-info">Hiện tại không có khách hàng nào.</div>
    <?php else: ?>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Tài khoản</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Địa chỉ</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php while($kh = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($kh['tai_khoan']) ?></td>
                <td><?= htmlspecialchars($kh['ho_ten']) ?></td>
                <td><?= htmlspecialchars($kh['email']) ?></td>
                <td><?= htmlspecialchars($kh['so_dien_thoai']) ?></td>
                <td><?= htmlspecialchars($kh['dia_chi']) ?></td>
                <td>
                    <a href="suakhachhang.php?id=<?= $kh['id'] ?>" class="btn btn-sm btn-warning">Sửa</a>
                    <a href="xoakhachhang.php?id=<?= $kh['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa khách hàng này?')">Xóa</a>
                    <a href="donhang_khachhang.php?id=<?= $kh['id'] ?>" class="btn btn-sm btn-info">Đơn hàng</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
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

<?php include 'includes/footer.php'?>
