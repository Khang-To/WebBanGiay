<?php
$servername = "localhost";     
$username = "root";           
$password = "vertrigo";               
$dbname = "shop_giay";         

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Đặt charset UTF-8 để hỗ trợ tiếng Việt
$conn->set_charset("utf8");
?>