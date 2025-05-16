<?php
include 'includes/auth_admin.php';
include 'includes/db.php';

$sql = "SELECT dh.*, kh.ho_ten FROM don_hang dh
        JOIN khach_hang kh ON dh.khach_hang_id = kh.id
        ORDER BY dh.ngay_dat DESC";
$result = $conn->query($sql);

include 'includes/header.php';
?>

<div class="container mt-4">
    <h4 class="mb-3">Danh sách đơn hàng</h4>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
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
                        $badgeClass = 'secondary';
                        if ($status == 'cho_xac_nhan') $badgeClass = 'warning';
                        elseif ($status == 'da_xac_nhan') $badgeClass = 'info';
                        elseif ($status == 'da_thanh_toan') $badgeClass = 'success';
                        ?>
                        <span class="badge bg-<?= $badgeClass ?>">
                            <?= ucfirst(str_replace('_', ' ', $status)) ?>
                        </span>
                    </td>
                    <td>
                        <a href="chitiet_donhang.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Chi tiết</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
