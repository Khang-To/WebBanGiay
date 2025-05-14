<?php
	include 'includes/db.php';				//kết nối database
	include 'includes/auth_admin.php';      // Kiểm tra đăng nhập
	include 'includes/thongbao.php';              // thông báo

	//xử lý khi submit form
	if($_SERVER['REQUEST_METHOD'] === 'POST'){
		$ten_giay = trim($_POST['ten_giay']);
		$thuong_hieu_id = intval($_POST['thuong_hieu_id']);
		$loai_giay_id = intval($_POST['loai_giay_id']);
		$don_gia = floatval($_POST['don_gia']);
		$ti_le_giam_gia = floatval($_POST['ti_le_giam_gia']);
		$mo_ta = trim($_POST['mo_ta']);
		$hinh_anh = '';

		//upload hình ảnh lên
		if(!empty($_FILES['hinh_anh']['name'])){
			$target_dir = "../uploads/"; // từ admin bước ra ngoài
			$ten_file = time() . '_' . basename($_FILES['hinh_anh']['name']);
			$target_file = $target_dir . $ten_file;

			if (move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $target_file)) {
				$hinh_anh = $ten_file; // chỉ lưu tên file vào CSDL
			}
			else{
				flashMessage('error','Không thể tải ảnh lên');
			}
		}

		//Nếu thêm ảnh không bị lỗi thì đổ vào database
		if($ten_giay && $thuong_hieu_id && $loai_giay_id && $don_gia && $hinh_anh){
			$stmt = $conn->prepare("INSERT INTO giay (ten_giay, thuong_hieu_id, loai_giay_id, don_gia, hinh_anh, mo_ta, ti_le_giam_gia)
									VALUES(?,?,?,?,?,?,?)");
			 $stmt->bind_param("siidssd", $ten_giay, $thuong_hieu_id, $loai_giay_id, $don_gia, $hinh_anh, $mo_ta, $ti_le_giam_gia);
        	if ($stmt->execute()){
				flashMessage('success','Thêm giày thành công!');
			}else{
				flashMessage('error','Lỗi khi thêm giày');
			}
		}else{
			flashMessage('warning', 'Vui lòng điền đầy đủ thông tin và tải ảnh!');
		}
	}

	//Lấy danh sách thương hiệu + loại giày
	$thuong_hieu = $conn->query("SELECT * FROM thuong_hieu");
	$loai_giay = $conn->query("SELECT * FROM loai_giay");

	include 'includes/header.php';
?>

<h4 class="mb-3">Thêm giày mới</h4>
<div class="row">
	<div class="col-md-6">
		<form method="POST" enctype="multipart/form-data" class="card card-body shadow-sm" style="max-width: 700px;">
			<div class="mb-3">
				<label class="form-label">Tên giày</label>
				<input type="text" name="ten_giay" class="form-control" required>
			</div>

			<div class="mb-3 row">
				<div class="col">
					<label class="form-label">Thương hiệu</label>
					<select name="thuong_hieu_id" class="form-select" required>
						<option value="">-- Chọn thương hiệu --</option>
						<?php while($th = $thuong_hieu->fetch_assoc()) :?>
							<option value="<?= $th['id'] ?>"><?= $th['ten_thuong_hieu'] ?></option>
						<?php endwhile; ?>
					</select>
				</div>
				<div class="col">
					<label class="form-label">Loại giày</label>
					<select name="loai_giay_id" class="form-select" required>
						<option value="">-- Chọn loại giày --</option>
						<?php while($lg = $loai_giay->fetch_assoc()) :?>
							<option value="<?= $lg['id'] ?>"><?= $lg['ten_loai'] ?></option>
						<?php endwhile; ?>
					</select>
				</div>
			</div>

			<div class="mb-3 row">
				<div class="col">
					<label class="form-label">Đơn giá (VNĐ)</label>
					<input type="number" name="don_gia" class="form-control" required>
				</div>
				<div class="col">
					<label class="form-label">Tỉ lệ giảm giá (%)</label>
					<input type="number" name="ti_le_giam_gia" class="form-control" value="0">
				</div>
			</div>

			<div class="mb-3">
				<label class="form-label">Hình ảnh</label>
				<input type="file" name="hinh_anh" class="form-control" required>
			</div>

			<div class="mb-3">
				<label class="form-label">Mô tả</label>
				<textarea name="mo_ta" rows="4" class="form-control"></textarea>
			</div>

			<button type="submit" class="btn btn-primary">Thêm giày</button>
		</form>
	</div>

	<!-- Đây là phần hiển thị demo -->
  	<div class="col-md-3">
		<div class="card shadow-sm mx-auto" style="max-width: 100%; min-height: 300px;">
		<img id="preview-img" class="img-fluid rounded-top bg-light" style="height: 200px; object-fit: contain;">
			<div class="card-body">
				<h6 class="card-title fw-bold" id="preview-name">Tên giày</h6>
				<div class="mb-1">
					<span class="text-muted text-decoration-line-through me-2" id="preview-original-price">0 đ</span>
					<span class="text-danger fw-bold" id="preview-discounted-price">0 đ</span>
				</div>
				<small class="text-muted" id="preview-extra">-- | --</small>
			</div>
		</div>
		<div class="text-center mt-2">Demo</div>
	</div>
</div>

<?php include 'includes/footer.php'; ?>
