<?php
    include 'includes/auth_admin.php';
    include 'includes/db.php';

    // ===== PHÂN TRANG + TÌM KIẾM =====
    $limit = 8;
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $offset = ($page - 1) * $limit;

    $search = trim(preg_replace('/\s+/', ' ', $_GET['search'] ?? ''));
    $statusFilter = $_GET['status'] ?? '';

    $where = "WHERE 1=1";
    $params = [];
    $types = '';

    if ($search !== '') {
        $where .= " AND LOWER(kh.ho_ten) LIKE ?";
        $params[] = '%' . strtolower($search) . '%';
        $types .= 's';
    }

    if ($statusFilter !== '') {
        $where .= " AND dh.trang_thai = ?";
        $params[] = $statusFilter;
        $types .= 's';
    }

    // ===== ĐẾM TỔNG =====
    $sqlCount = "SELECT COUNT(*) FROM don_hang dh 
                JOIN khach_hang kh ON dh.khach_hang_id = kh.id 
                $where";
    $stmt = $conn->prepare($sqlCount);
    if (!$stmt) die("Lỗi SQL đếm: " . $conn->error);
    if (!empty($params)) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $stmt->bind_result($total);
    $stmt->fetch();
    $stmt->close();

    $total_pages = ceil($total / $limit);

    // ===== LẤY DỮ LIỆU TRANG HIỆN TẠI =====
    $sql = "SELECT dh.*, kh.ho_ten FROM don_hang dh
            JOIN khach_hang kh ON dh.khach_hang_id = kh.id
            $where
            ORDER BY dh.ngay_dat DESC
            LIMIT ?, ?";
    $params[] = $offset;
    $params[] = $limit;
    $types .= 'ii';

    $stmt = $conn->prepare($sql);
    if (!$stmt) die("Lỗi SQL lấy dữ liệu: " . $conn->error);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    include 'includes/header.php';
?>

<div class="container mt-4">
    <h4 class="mb-3">Danh sách đơn hàng</h4>
    <form method="get" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Tìm theo tên khách hàng" value="<?= htmlspecialchars($search) ?>">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">-- Tất cả trạng thái --</option>
                <option value="cho_xac_nhan" <?= $statusFilter === 'cho_xac_nhan' ? 'selected' : '' ?>>Chờ xác nhận</option>
                <option value="da_xac_nhan" <?= $statusFilter === 'da_xac_nhan' ? 'selected' : '' ?>>Đã xác nhận</option>
                <option value="da_thanh_toan" <?= $statusFilter === 'da_thanh_toan' ? 'selected' : '' ?>>Đã thanh toán</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Lọc</button>
            <a href="danhsachdonhang.php" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Mã đơn hàng</th>
                <th>Khách hàng</th>
                <th>Ngày đặt</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['ho_ten']) ?></td>
                    <td><?= $row['ngay_dat'] ?></td>
                    <td>
                        <?php
                        $status = $row['trang_thai'];
                        $badgeClass = [
                            'cho_xac_nhan' => 'warning',
                            'da_xac_nhan' => 'info',
                            'da_thanh_toan' => 'success'
                        ][$status] ?? 'secondary';

                        $mapTrangThai = [
                            'cho_xac_nhan' => 'Chờ xác nhận',
                            'da_xac_nhan' => 'Đã xác nhận',
                            'da_thanh_toan' => 'Đã thanh toán'
                        ];
                        ?>
                        <span class="badge bg-<?= $badgeClass ?>">
                            <?= $mapTrangThai[$status] ?? $status ?>
                        </span>
                    </td>
                    <td>
                        <a href="chitiet_donhang.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary">Xem chi tiết</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <?php if ($total_pages > 1): ?>
        <nav>
            <ul class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
