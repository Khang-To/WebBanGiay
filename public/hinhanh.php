<?php
// public/hinhanh.php

if (!isset($_GET['file'])) {
    http_response_code(400);
    exit('Thiếu tên file ảnh.');
}

$filename = basename($_GET['file']); // bảo vệ không cho truy cập ../
$path = realpath(__DIR__ . '/../uploads/' . $filename);

// Kiểm tra file có tồn tại và thuộc đúng thư mục uploads
if (!$path || strpos($path, realpath(__DIR__ . '/../uploads')) !== 0 || !file_exists($path)) {
    http_response_code(404);
    exit('Ảnh không tồn tại.');
}

// Xác định loại file để trình duyệt hiểu (jpg, png,...)
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $path);
finfo_close($finfo);

header('Content-Type: ' . $mime);
readfile($path);
