<?php
    include 'includes/auth_admin.php';
    include 'includes/db.php';
    include_once 'includes/thongbao.php';


    //Xử lý form khi submit
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $giay_id = intval($_POST['giay_id']);
        $size = intval($_POST['size']);
        $so_luong_ton = intval($_POST['so_luong_ton']);

        if ($giay_id && $size !== '' && $so_luong_ton > 0) {
            // Kiểm tra nếu size đã tồn tại cho giày này
            $check = $conn->prepare("SELECT id FROM size_giay WHERE giay_id = ? AND size = ?");
            $check->bind_param("ii", $giay_id, $size);
            $check->execute();
            $check->store_result();

            if ($check->num_rows > 0) {
                // Nếu đã tồn tại ⇒ cộng thêm số lượng
                $update = $conn->prepare("UPDATE size_giay SET so_luong_ton = so_luong_ton + ? WHERE giay_id = ? AND size = ?");
                $update->bind_param("iii", $so_luong_ton, $giay_id, $size);
                $update->execute();
                flashMessage('success', 'Cập nhật số lượng tồn thành công!');
            } else {
                // Thêm mới size
                $insert = $conn->prepare("INSERT INTO size_giay (giay_id, size, so_luong_ton) VALUES (?, ?, ?)");
                $insert->bind_param("iii", $giay_id, $size, $so_luong_ton);
                $insert->execute();
                flashMessage('success', 'Thêm tồn kho thành công!');
            }
        } else {
            flashMessage('warning', 'Vui lòng nhập đầy đủ thông tin và số lượng > 0.');
        }
    }

    // Lấy danh sách giày cùng thương hiệu & loại giày
    $sql = "SELECT g.id, g.ten_giay, th.ten_thuong_hieu, lg.ten_loai
            FROM giay g
            JOIN thuong_hieu th ON g.thuong_hieu_id = th.id
            JOIN loai_giay lg ON g.loai_giay_id = lg.id";
    $giay = $conn->query($sql);
	include 'includes/header.php';
?>

<!-- form thêm tồn kho -->
<h4 class="mb-3 text-center">Thêm tồn kho theo size</h4>
<form method="POST" enctype="multipart/form-data" class="card card-body shadow-sm" style="max-width: 700px; margin: auto;">
	<label class="form-label">Giày</label>
	<select name="giay_id" class="form-select" id="giay_id" required>
    <option value="">-- Chọn giày --</option>
    <?php while($th = $giay->fetch_assoc()) :?>
        <option 
            value="<?= $th['id'] ?>" 
            data-thuong-hieu="<?= htmlspecialchars($th['ten_thuong_hieu']) ?>" 
            data-loai-giay="<?= htmlspecialchars($th['ten_loai']) ?>"
        >
            <?= $th['ten_giay'] ?>
        </option>
    <?php endwhile; ?>
    </select>

    <label class="form-label mt-3">Thương hiệu: <strong id="hien_thuong_hieu">--</strong></label>
    <label class="form-label mb-2">Loại giày: <strong id="hien_loai_giay">--</strong></label>

	<label class="form-label">Size</label>
	<input type="number" name="size" class="form-control" min="27" max="48" required>

	<label class="form-label">Số lượng</label>
	<input type="number" name="so_luong_ton" class="form-control" value="0">

	<button type="submit" class="btn btn-primary mt-3">Thêm tồn kho</button>
</form>
 <?php include 'includes/footer.php';?>
 <script>
document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('giay_id');
    const hienTH = document.getElementById('hien_thuong_hieu');
    const hienLoai = document.getElementById('hien_loai_giay');

    select.addEventListener('change', function () {
        const selected = select.options[select.selectedIndex];
        hienTH.innerText = selected.getAttribute('data-thuong-hieu') || '--';
        hienLoai.innerText = selected.getAttribute('data-loai-giay') || '--';
    });
});
</script>
