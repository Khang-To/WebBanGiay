<?php
// public/hinhanh.php

if (!isset($_GET['file'])) {
    http_response_code(400);
    exit('Thiếu tên file ảnh.');
}

$filename = basename($_GET['file']); // Ngăn truy cập ../
$relativePath = __DIR__ . '/../uploads/' . $filename;

// Kiểm tra file tồn tại và là file hợp lệ
if (!file_exists($relativePath) || !is_file($relativePath)) {
    http_response_code(404);
    exit('Ảnh không tồn tại.');
}

// Xác định loại MIME
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $relativePath);
finfo_close($finfo);

// Gửi header loại file và đọc nội dung file
header('Content-Type: ' . $mime);
header('Content-Length: ' . filesize($relativePath));
readfile($relativePath);
exit;
?>
