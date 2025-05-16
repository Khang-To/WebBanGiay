<?php
    include 'includes/auth_admin.php';
    include 'includes/db.php';
    include_once 'includes/thongbao.php';

    $id = intval($_GET['id'] ?? 0);
    if ($id <= 0) {
        flashMessage('error', 'ID không hợp lệ!');
        exit;
    }
    // ======= Lấy thông tin giày hiện tại ========
    $stmt = $conn->prepare("SELECT * FROM giay WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $giay = $result->fetch_assoc();
    $stmt->close();

    if (!$giay) {
        flashMessage('error', 'Không tìm thấy sản phẩm!');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //xử lý khi submit form
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $ten_giay = trim($_POST['ten_giay']);
            $thuong_hieu_id = intval($_POST['thuong_hieu_id']);
            $loai_giay_id = intval($_POST['loai_giay_id']);
            $don_gia = floatval($_POST['don_gia']);
            $ti_le_giam_gia = floatval($_POST['ti_le_giam_gia']);
            $mo_ta = trim($_POST['mo_ta']);
            $hinh_anh = $giay['hinh_anh']; // Giữ ảnh cũ ban đầu

            // Nếu có upload ảnh mới thì thay thế
            if (!empty($_FILES['hinh_anh']['name'])) {
                $target_dir = "../uploads/";
                $ten_file = time() . '_' . basename($_FILES['hinh_anh']['name']);
                $target_file = $target_dir . $ten_file;

                if (move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $target_file)) {
                    $hinh_anh = $ten_file;
                } else {
                    flashMessage('error','Không thể tải ảnh lên');
                }
            }


            //Nếu thêm ảnh không bị lỗi thì đổ vào database
            if ($ten_giay && $thuong_hieu_id && $loai_giay_id && $don_gia) {
                $stmt = $conn->prepare("UPDATE giay
                                        SET ten_giay=?, 
                                            thuong_hieu_id=?, 
                                            loai_giay_id=?, 
                                            don_gia=?, 
                                            hinh_anh=?, 
                                            mo_ta=?, 
                                            ti_le_giam_gia=? 
                                        WHERE id=?");
                $stmt->bind_param("siidssdi", $ten_giay, $thuong_hieu_id, $loai_giay_id, $don_gia, $hinh_anh, $mo_ta, $ti_le_giam_gia, $id);

                if ($stmt->execute()) {
                    flashMessage('success', 'Cập nhật thành công!');
                    header("Location: danhsachgiay.php");
                    exit;
                } else {
                    flashMessage('error', 'Lỗi khi cập nhật!');
                }
            } else {
                flashMessage('warning', 'Vui lòng nhập đầy đủ thông tin!');
            }
        }

    }

	//Lấy danh sách thương hiệu + loại giày
	$thuong_hieu = $conn->query("SELECT * FROM thuong_hieu");
	$loai_giay = $conn->query("SELECT * FROM loai_giay");

include 'includes/header.php';
?>

<!-- form sửa giày -->
<h4 class="mb-3">Chỉnh sửa thông tin giày</h4>
<div class="row">
	<div class="col-md-6">
		<form method="POST" enctype="multipart/form-data" class="card card-body shadow-sm">
            <div class="mb-3">
                <label class="form-label">Tên giày</label>
                <input type="text" name="ten_giay" class="form-control" required value="<?= htmlspecialchars($giay['ten_giay']) ?>">
            </div>

            <div class="mb-3 row">
                <div class="col">
                    <label class="form-label">Thương hiệu</label>
                    <select name="thuong_hieu_id" class="form-select" required>
                        <?php while ($th = $thuong_hieu->fetch_assoc()): ?>
                            <option value="<?= $th['id'] ?>" <?= ($th['id'] == $giay['thuong_hieu_id']) ? 'selected' : '' ?>>
                                <?= $th['ten_thuong_hieu'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col">
                    <label class="form-label">Loại giày</label>
                    <select name="loai_giay_id" class="form-select" required>
                        <?php while ($lg = $loai_giay->fetch_assoc()): ?>
                            <option value="<?= $lg['id'] ?>" <?= ($lg['id'] == $giay['loai_giay_id']) ? 'selected' : '' ?>>
                                <?= $lg['ten_loai'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div class="mb-3 row">
                <div class="col">
                    <label class="form-label">Đơn giá (VNĐ)</label>
                    <input type="number" name="don_gia" class="form-control" required value="<?= $giay['don_gia'] ?>">
                </div>
                <div class="col">
                    <label class="form-label">Tỉ lệ giảm giá (%)</label>
                    <input type="number" name="ti_le_giam_gia" class="form-control" value="<?= $giay['ti_le_giam_gia'] ?>">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Hình ảnh (để trống nếu không thay)</label>
                <input type="file" name="hinh_anh" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea name="mo_ta" rows="4" class="form-control"><?= htmlspecialchars($giay['mo_ta']) ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary mb-2">Cập nhật</button>
            <a href="danhsachgiay.php" class="btn btn-secondary">Quay lại</a>
        </form>
	</div>

	<!-- Đây là phần hiển thị demo -->
  	<div class="col-md-4">
        <div class="card shadow-sm mx-auto">
        <img id="preview-img" 
            class="img-fluid rounded-top bg-light" 
            style="height: 200px; object-fit: contain;" 
            src="../uploads/<?= htmlspecialchars($giay['hinh_anh'] ?? '') ?>" 
            alt="Ảnh hiện tại">          
            <div class="card-body">
                <h6 class="card-title fw-bold"><?= htmlspecialchars($giay['ten_giay']) ?></h6>
                <p class="text-danger fw-bold mb-1"><?= number_format($giay['don_gia'], 0, ',', '.') ?> đ</p>
                <small class="text-muted">Tỉ lệ giảm giá: <?= $giay['ti_le_giam_gia'] ?>% | Mô tả: <?= $giay['mo_ta'] ?></small>
            </div>
        </div>
        <div class="text-center mt-2">Ảnh hiện tại</div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>