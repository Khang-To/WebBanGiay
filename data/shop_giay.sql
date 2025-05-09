-- Tạo CSDL
CREATE DATABASE shop_giay CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE shop_giay;

-- Bảng Admin
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tai_khoan VARCHAR(100) NOT NULL,
    mat_khau VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    ho_ten VARCHAR(100),
    dia_chi VARCHAR(255)
);

-- Bảng Khách hàng
CREATE TABLE khach_hang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tai_khoan VARCHAR(100) NOT NULL,
    mat_khau VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    ho_ten VARCHAR(100),
    dia_chi VARCHAR(255)
);

-- Bảng Thương hiệu
CREATE TABLE thuong_hieu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten_thuong_hieu VARCHAR(100) NOT NULL
);

-- Bảng Loại giày
CREATE TABLE loai_giay (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten_loai VARCHAR(100) NOT NULL
);

-- Bảng Giày (KHÔNG chứa so_luong, vì đã tách theo size)
CREATE TABLE giay (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten_giay VARCHAR(255) NOT NULL,
    thuong_hieu_id INT,
    loai_giay_id INT,
    don_gia DECIMAL(10,2),
    hinh_anh VARCHAR(255),
    mo_ta TEXT,
    ti_le_giam_gia DECIMAL(5,2) DEFAULT 0,
    FOREIGN KEY (thuong_hieu_id) REFERENCES thuong_hieu(id),
    FOREIGN KEY (loai_giay_id) REFERENCES loai_giay(id)
);

-- Bảng Size Giày (quản lý kho theo size)
CREATE TABLE size_giay (
    id INT AUTO_INCREMENT PRIMARY KEY,
    giay_id INT NOT NULL,
    size VARCHAR(10),
    so_luong_ton INT,
    FOREIGN KEY (giay_id) REFERENCES giay(id) ON DELETE CASCADE
);

-- Bảng Đơn hàng (ĐÃ thêm ghi_chu)
CREATE TABLE don_hang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    khach_hang_id INT NOT NULL,
    ngay_dat DATETIME DEFAULT CURRENT_TIMESTAMP,
    trang_thai ENUM('cho_xac_nhan', 'da_xac_nhan', 'da_thanh_toan') DEFAULT 'cho_xac_nhan',
    ghi_chu TEXT,
    FOREIGN KEY (khach_hang_id) REFERENCES khach_hang(id)
);

-- Bảng Chi tiết đơn hàng
CREATE TABLE chi_tiet_don_hang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    don_hang_id INT NOT NULL,
    size_giay_id INT NOT NULL,
    so_luong_ban INT,
    don_gia_ban DECIMAL(10,2),
    FOREIGN KEY (don_hang_id) REFERENCES don_hang(id),
    FOREIGN KEY (size_giay_id) REFERENCES size_giay(id)
);

