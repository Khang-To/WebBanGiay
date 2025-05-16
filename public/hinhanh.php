<?php
// public/hinhanh.php

if (!isset($_GET['file'])) {
    http_response_code(400);
    exit('Thiếu tên file ảnh.');
}

$filename = basename($_GET['file']); // Ngăn truy cập ../
$relativePath = '../uploads/' . $filename;

// Kiểm tra file tồn tại và nằm đúng thư mục
if (!file_exists($relativePath) || !is_file($relativePath)) {
    http_response_code(404);
    exit('Ảnh không tồn tại.');
}

// Xác định loại file để trình duyệt hiểu
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $relativePath);
finfo_close($finfo);

header('Content-Type: ' . $mime);
readfile($relativePath);
