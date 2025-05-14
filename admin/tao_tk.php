<?php
// Chạy 1 lần để thêm tài khoản admin, sau đó hãy xoá file này ❗

include 'includes/db.php';

$tk = 'admin';                  // tài khoản
$mk = '123456';                 // mật khẩu (sẽ được mã hoá)
$email = 'admin@shop.com';      // email admin
$ten = 'Quản trị viên';         // họ tên
$dia_chi = 'An Giang';            // địa chỉ

$mk_hash = password_hash($mk, PASSWORD_DEFAULT);

$sql = "INSERT INTO admin (tai_khoan, mat_khau, email, ho_ten, dia_chi)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $tk, $mk_hash, $email, $ten, $dia_chi);

if ($stmt->execute()) {
    echo "✅ Đã tạo tài khoản admin thành công!<br>";
    echo "🧑 Tài khoản: <strong>$tk</strong><br>";
    echo "🔐 Mật khẩu: <strong>$mk</strong><br>";
    echo "📧 Email: $email";
} else {
    echo "❌ Lỗi khi thêm admin: " . $stmt->error;
}
