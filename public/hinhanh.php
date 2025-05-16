<?php
// public/hinhanh.php

// Kiểm tra tham số 'file'
if (!isset($_GET['file']) || empty($_GET['file'])) {
    http_response_code(400);
    exit('Thiếu tên file ảnh.');
}

// Lấy tên file an toàn
$filename = basename($_GET['file']);

// Chỉ cho phép các định dạng ảnh cụ thể
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
$extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
if (!in_array($extension, $allowedExtensions)) {
    http_response_code(403);
    exit('Định dạng file không được phép.');
}

$relativePath = '../uploads/' . $filename;

// Kiểm tra file tồn tại
if (!file_exists($relativePath) || !is_file($relativePath)) {
    http_response_code(404);
    exit('Ảnh không tồn tại.');
}

// Thêm cache header để tối ưu
$lastModified = filemtime($relativePath);
$etag = md5_file($relativePath);
header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $lastModified) . ' GMT');
header('ETag: "' . $etag . '"');
header('Cache-Control: public, max-age=31536000'); // Cache 1 năm

// Kiểm tra if-modified-since để trả về 304 nếu không thay đổi
if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) || isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
    $ifModifiedSince = strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE'] ?? '');
    if ($ifModifiedSince >= $lastModified || (isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) === $etag)) {
        http_response_code(304);
        exit;
    }
}

// Xác định loại MIME
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $relativePath) ?: 'application/octet-stream';
finfo_close($finfo);

// Gửi file
header('Content-Type: ' . $mime);
header('Content-Length: ' . filesize($relativePath));
readfile($relativePath);