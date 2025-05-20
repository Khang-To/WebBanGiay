<?php
    include 'includes/auth_admin.php';
    include 'includes/db.php';
    include 'includes/header.php';

    $from_input = $_GET['from'] ?? '';
    $to_input = $_GET['to'] ?? '';
    $errors = [];

    $from = strtotime($from_input);
    $to = strtotime($to_input);

    if ($from_input && $to_input) {
        if ($from === false || $to === false) {
            $errors[] = "Ngày không hợp lệ.";
            $from = $to = null;
        } elseif ($from > $to) {
            $errors[] = "Từ ngày phải nhỏ hơn hoặc bằng đến ngày.";
            $from = $to = null;
        } else {
            $from = date('Y-m-d', $from);
            $to = date('Y-m-d', $to);
        }
    } else {
        $from = $to = null;
    }

    // 1. Tổng doanh thu
    $tong_doanh_thu_sql = "
        SELECT SUM(ctdh.so_luong_ban * ctdh.don_gia_ban) AS tong_doanh_thu
        FROM chi_tiet_don_hang ctdh
        JOIN don_hang dh ON ctdh.don_hang_id = dh.id
        WHERE dh.trang_thai = 'da_thanh_toan'
    ";
    $params = [];
    $types = '';
    if ($from && $to) {
        $tong_doanh_thu_sql .= " AND DATE(dh.ngay_dat) BETWEEN ? AND ?";
        $params = [$from, $to];
        $types = 'ss';
    }
    $stmt = $conn->prepare($tong_doanh_thu_sql);
    if ($types) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $tong_doanh_thu = $stmt->get_result()->fetch_assoc()['tong_doanh_thu'] ?? 0;
    $stmt->close();

    // 2. Tổng số giày bán
    $tong_so_luong_sql = "
        SELECT SUM(ctdh.so_luong_ban) AS tong_so_luong
        FROM chi_tiet_don_hang ctdh
        JOIN don_hang dh ON ctdh.don_hang_id = dh.id
        WHERE dh.trang_thai = 'da_thanh_toan'
    ";
    $params = [];
    $types = '';
    if ($from && $to) {
        $tong_so_luong_sql .= " AND DATE(dh.ngay_dat) BETWEEN ? AND ?";
        $params = [$from, $to];
        $types = 'ss';
    }
    $stmt = $conn->prepare($tong_so_luong_sql);
    if ($types) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $tong_so_luong_ban = $stmt->get_result()->fetch_assoc()['tong_so_luong'] ?? 0;
    $stmt->close();

    // 3. Doanh thu theo ngày (biểu đồ)
    $revenue_sql = "
        SELECT DATE(dh.ngay_dat) as ngay, SUM(ctdh.so_luong_ban * ctdh.don_gia_ban) as tong_tien
        FROM don_hang dh
        JOIN chi_tiet_don_hang ctdh ON dh.id = ctdh.don_hang_id
        WHERE dh.trang_thai = 'da_thanh_toan'
    ";
    $params = [];
    $types = '';
    if ($from && $to) {
        $revenue_sql .= " AND DATE(dh.ngay_dat) BETWEEN ? AND ?";
        $params = [$from, $to];
        $types = 'ss';
    }
    $revenue_sql .= " GROUP BY DATE(dh.ngay_dat) ORDER BY DATE(dh.ngay_dat)";
    $stmt = $conn->prepare($revenue_sql);
    if ($types) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $revenue_data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // 4. Top 5 sản phẩm bán chạy
    $top_sql = "
        SELECT g.ten_giay, th.ten_thuong_hieu, sg.size, lg.ten_loai, SUM(ctdh.so_luong_ban) AS tong_so_luong
        FROM chi_tiet_don_hang ctdh
        JOIN size_giay sg ON ctdh.size_giay_id = sg.id
        JOIN giay g ON sg.giay_id = g.id
        JOIN thuong_hieu th ON g.thuong_hieu_id = th.id
        JOIN loai_giay lg ON g.loai_giay_id = lg.id
        JOIN don_hang dh ON dh.id = ctdh.don_hang_id
        WHERE dh.trang_thai = 'da_thanh_toan'
    ";
    $params = [];
    $types = '';
    if ($from && $to) {
        $top_sql .= " AND DATE(dh.ngay_dat) BETWEEN ? AND ?";
        $params = [$from, $to];
        $types = 'ss';
    }
    $top_sql .= " GROUP BY g.id ORDER BY tong_so_luong DESC LIMIT 5";
    $stmt = $conn->prepare($top_sql);
    if ($types) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $top_products = $stmt->get_result();
?>

<div class="container mt-4">
    <h2 class="mb-4">Thống kê doanh thu và sản phẩm bán chạy</h2>

    <form class="row g-3 mb-4" method="get">
        <div class="col-md-2">
            <label class="form-label">Từ ngày</label>
            <input type="date" name="from" class="form-control" value="<?= htmlspecialchars($from_input) ?>">
        </div>
        <div class="col-md-2">
            <label class="form-label">Đến ngày</label>
            <input type="date" name="to" class="form-control" value="<?= htmlspecialchars($to_input) ?>">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button class="btn btn-primary me-2">Lọc</button>
            <a href="thongke.php" class="btn btn-secondary me-2">Reset</a>
            <button type="button" id="export-excel" class="btn btn-success">Xuất Excel</button>
        </div>
    </form>

    <?php if ($errors): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?= $e ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (!$errors): ?>
        <?php if ($tong_so_luong_ban == 0): ?>
            <div class="alert alert-warning">Không có giày nào được bán trong khoảng thời gian đã chọn.</div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6">
                <div class="card bg-info text-white mb-3 shadow" style="max-width: 400px;">
                    <div class="card-body">
                        <h5 class="card-title">Tổng doanh thu</h5>
                        <p class="card-text"><?= number_format($tong_doanh_thu, 0, ',', '.') ?> VND</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-warning text-white mb-3 shadow" style="max-width: 400px;">
                    <div class="card-body">
                        <h5 class="card-title">Tổng số giày đã bán</h5>
                        <p class="card-text"><?= $tong_so_luong_ban ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <canvas id="revenueChart"></canvas>
            </div>
            <div class="col-md-6">
                <h5>Top 5 sản phẩm bán chạy</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tên giày</th>
                            <th>Thương hiệu</th>
                            <th>Loại</th>
                            <th>Size</th>
                            <th>Số lượng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $top_products->fetch_assoc()): ?>
                            <tr>
                                <td style="max-width: 220px;"><?= htmlspecialchars($row['ten_giay']) ?></td>
                                <td><?= htmlspecialchars($row['ten_thuong_hieu']) ?></td>
                                <td><?= htmlspecialchars($row['ten_loai']) ?></td>
                                <td><?= htmlspecialchars($row['size'])?></td>
                                <td><?= $row['tong_so_luong'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function () {
    const data = <?= json_encode($revenue_data) ?>;
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(x => x.ngay),
            datasets: [{
                label: 'Doanh thu',
                data: data.map(x => x.tong_tien),
                backgroundColor: 'rgba(54,162,235,0.3)',
                borderColor: 'rgba(54,162,235,1)',
                borderWidth: 2,
                fill: true
            }]
        }
    });

    $('#export-excel').click(function() {
        const from = $('input[name="from"]').val();
        const to = $('input[name="to"]').val();
        let url = 'xuat_excel.php';
        if (from && to) url += `?from=${from}&to=${to}`;
        window.location.href = url;
    });
});
</script>

<?php include 'includes/footer.php'; ?>
