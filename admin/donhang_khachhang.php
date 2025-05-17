<?php
    include 'includes/auth_admin.php';
    include 'includes/db.php';

    $khach_id = intval($_GET['id'] ?? 0);
    $ho_ten = 'Khách chưa xác định';

    // Lấy tên khách hàng
    $stmtTen = $conn->prepare("SELECT ho_ten FROM khach_hang WHERE id = ?");
    $stmtTen->bind_param("i", $khach_id);
    $stmtTen->execute();
    $stmtTen->bind_result($ho_ten);
    $stmtTen->fetch();
    $stmtTen->close();

    // ===== PHÂN TRANG + TÌM KIẾM =====
    $limit = 8;
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $offset = ($page - 1) * $limit;

    $statusFilter = $_GET['status'] ?? '';

    $where = "WHERE dh.khach_hang_id = ?";
    $params = [$khach_id];
    $types = 'i';

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
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $stmt->bind_result($total);
    $stmt->fetch();
    $stmt->close();

    $total_pages = ceil($total / $limit);

    // ===== LẤY DỮ LIỆU TRANG HIỆN TẠI =====
    $sql = "SELECT dh.* FROM don_hang dh
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

    // Mapping trạng thái
    $mapTrangThai = [
        'cho_xac_nhan' => 'Chờ xác nhận',
        'da_xac_nhan' => 'Đã xác nhận',
        'da_thanh_toan' => 'Đã thanh toán'
    ];
    $badgeMap = [
        'cho_xac_nhan' => 'warning',
        'da_xac_nhan' => 'info',
        'da_thanh_toan' => 'success'
    ];
?>

<div class="container mt-4">
    <h4 class="mb-3">Đơn hàng của khách hàng: <?= htmlspecialchars($ho_ten) ?></h4>
    
    <!-- Form lọc -->
    <form method="get" class="row g-3 mb-4">
        <input type="hidden" name="id" value="<?= $khach_id ?>">
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">-- Tất cả trạng thái --</option>
                <option value="cho_xac_nhan" <?= $statusFilter === 'cho_xac_nhan' ? 'selected' : '' ?>>Chờ xác nhận</option>
                <option value="da_xac_nhan" <?= $statusFilter === 'da_xac_nhan' ? 'selected' : '' ?>>Đã xác nhận</option>
                <option value="da_thanh_toan" <?= $statusFilter === 'da_thanh_toan' ? 'selected' : '' ?>>Đã thanh toán</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary">Lọc</button>
            <a href="donhang_khachhang.php?id=<?= $khach_id ?>" class="btn btn-secondary">Reset</a>
            <a href="danhsach_khachhang.php" class="btn btn-dark">Quay lại</a>
        </div>
    </form>

    <!-- Bảng đơn hàng -->
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Mã đơn hàng</th>
                <th>Ngày đặt</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['ngay_dat'] ?></td>
                    <td>
                        <span class="badge bg-<?= $badgeMap[$row['trang_thai']] ?? 'secondary' ?>">
                            <?= $mapTrangThai[$row['trang_thai']] ?? $row['trang_thai'] ?>
                        </span>
                    </td>
                    <td>
                        <a href="chitiet_donhang.php?id=<?= $row['id'] ?>&back=khach&id_khach=<?= $khach_id ?>" class="btn btn-sm btn-outline-primary">
                            Xem chi tiết
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Phân trang -->
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
