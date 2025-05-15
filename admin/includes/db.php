<?php
$host = 'localhost';
$username = 'root'; 
$password = ''; 
$database = 'shop_giay';

$conn = new mysqli($host, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối CSDL thất bại: " . $conn->connect_error);
}

// Thiết lập charset utf8
$conn->set_charset("utf8mb4");
?>