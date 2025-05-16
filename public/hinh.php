<?php
// hinh.php – lấy ảnh từ thư mục ../uploads/

if (!isset($_GET['file'])) {
    http_response_code(400);
    exit("Thiếu tham số file.");
}

$filename = basename($_GET['file']); // lọc tên file, tránh truy cập ../.. độc hại

// Đường dẫn tương đối đến thư mục uploads nằm ngoài public
$uploadPath = realpath(__DIR__ . '/../uploads/' . $filename);

if ($uploadPath && file_exists($uploadPath)) {
    $mime = mime_content_type($uploadPath); // xác định loại MIME (image/jpeg, image/webp, ...)
    header("Content-Type: $mime");
    readfile($uploadPath);
    exit;
} else {
    http_response_code(404);
    exit("Không tìm thấy hình ảnh.");
}
