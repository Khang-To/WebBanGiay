<?php
require_once 'includes/auth_admin.php';
require_once 'includes/db.php';

// Lấy tham số ngày từ URL
$from_input = $_GET['from'] ?? '';
$to_input = $_GET['to'] ?? '';

$from = strtotime($from_input);
$to = strtotime($to_input);

$errors = [];

if ($from_input && $to_input) {
    if ($from === false || $to === false) {
        $errors[] = "Ngày không hợp lệ.";
        $from = null;
        $to = null;
    } elseif ($from > $to) {
        $errors[] = "Từ ngày phải nhỏ hơn hoặc bằng đến ngày.";
        $from = null;
        $to = null;
    } else {
        $from = date('Y-m-d', $from);
        $to = date('Y-m-d', $to);
    }
} else {
    $from = null;
    $to = null;
}

// Hàm lọc dữ liệu để xuất Excel
function filterData(&$str) {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
}

// Tên tệp Excel
$fileName = "thong_ke_doanh_thu_" . date('Ymd') . ".xls";

// Thiết lập header để tải xuống file Excel
header("Content-Disposition: attachment; filename=\"$fileName\"");
header("Content-Type: application/vnd.ms-excel");

// Truy vấn dữ liệu từ cơ sở dữ liệu
$sql = "
    SELECT 
        DATE(dh.ngay_dat) as 'Ngay',
        SUM(ctdh.so_luong_ban) as 'Tong_so_luong_ban',
        SUM(ctdh.so_luong_ban * ctdh.don_gia_ban) as 'Tong_tien'
    FROM don_hang dh
    JOIN chi_tiet_don_hang ctdh ON dh.id = ctdh.don_hang_id
    WHERE dh.trang_thai = 'da_thanh_toan'
";


$params = [];
$types = '';

if ($from && $to) {
    $sql .= " AND DATE(dh.ngay_dat) BETWEEN ? AND ?";
    $params[] = $from;
    $params[] = $to;
    $types = 'ss';
}

$sql .= " GROUP BY DATE(dh.ngay_dat) ORDER BY DATE(dh.ngay_dat)";

$stmt = $conn->prepare($sql);
if ($types) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Xuất dữ liệu ra Excel
$flag = false;
while ($row = $result->fetch_assoc()) {
    if (!$flag) {
        // Xuất tiêu đề cột
        echo implode("\t", array_keys($row)) . "\n";
        $flag = true;
    }
    array_walk($row, 'filterData');
    echo implode("\t", array_values($row)) . "\n";
}
exit;
?>
