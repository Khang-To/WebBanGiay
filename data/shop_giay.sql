-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 17, 2025 lúc 06:55 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `shop_giay`
--
CREATE DATABASE IF NOT EXISTS `shop_giay` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `shop_giay`;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `tai_khoan` varchar(100) NOT NULL,
  `mat_khau` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `ho_ten` varchar(100) DEFAULT NULL,
  `dia_chi` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `admin`
--

INSERT INTO `admin` (`id`, `tai_khoan`, `mat_khau`, `email`, `ho_ten`, `dia_chi`) VALUES
(1, 'admin', '$2y$10$6SF9737XG11e0P9GhYDGQ.H3WCxYfcomT.KGuYCLAE.xDwENBkcV2', 'admin@shop.com', 'Quản trị viên', 'Hà Nội');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chi_tiet_don_hang`
--

CREATE TABLE `chi_tiet_don_hang` (
  `id` int(11) NOT NULL,
  `don_hang_id` int(11) NOT NULL,
  `size_giay_id` int(11) NOT NULL,
  `so_luong_ban` int(11) DEFAULT NULL,
  `don_gia_ban` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `don_hang`
--

CREATE TABLE `don_hang` (
  `id` int(11) NOT NULL,
  `khach_hang_id` int(11) NOT NULL,
  `ngay_dat` datetime DEFAULT current_timestamp(),
  `trang_thai` enum('cho_xac_nhan','da_xac_nhan','da_thanh_toan') DEFAULT 'cho_xac_nhan',
  `ghi_chu` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `giay`
--

CREATE TABLE `giay` (
  `id` int(11) NOT NULL,
  `ten_giay` varchar(255) NOT NULL,
  `thuong_hieu_id` int(11) DEFAULT NULL,
  `loai_giay_id` int(11) DEFAULT NULL,
  `don_gia` decimal(10,2) DEFAULT NULL,
  `hinh_anh` varchar(255) DEFAULT NULL,
  `mo_ta` text DEFAULT NULL,
  `ti_le_giam_gia` decimal(5,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `giay`
--

INSERT INTO `giay` (`id`, `ten_giay`, `thuong_hieu_id`, `loai_giay_id`, `don_gia`, `hinh_anh`, `mo_ta`, `ti_le_giam_gia`) VALUES
(7, 'Giày bóng đá Nike Phantom Luna II Elite FG Prism - Crimson Tint/Black/Pink Blast FJ2572-800', 2, 2, 1890000.00, '1747473387_anh_sp_add-01-01-01-04-013-3_faf51ff610e342f0b7b114d9987c78cb_1024x1024.jpg', '', 10.00),
(9, 'Giày đá bóng Nike Air Zoom Mercurial Vapor 16 Elite FG Prism - Ocean Cube/Pink Blast FQ1457-301', 2, 3, 7902000.00, '1747490943_5dd0cfce4948437eb71a8aa889984a78_ecc971c9a3f445d088b92fd1c0969602_1024x1024.webp', 'Giày đá bóng Nike Air Zoom Mercurial Vapor 16 Elite FG Prism - Ocean Cube/Pink Blast FQ1457-301', 29.00),
(10, 'Giày đá bóng Nike Air Zoom Mercurial Vapor 16 Pro AG-PRO Mad Energy - Ember Glow/Aurora Green FQ8684-800', 2, 3, 4490000.00, '1747491011_anh_sp_add_web_ballak02-01-01-01-01-2_c330437b069042c983662dcaf3431bb1_1024x1024.webp', 'Giày đá bóng Nike Air Zoom Mercurial Vapor 16 Pro AG-PRO Mad Energy - Ember Glow/Aurora Green FQ8684-800', 22.00),
(11, 'Giày đá bóng NMS FLASH Kids - Grey/Orange', 7, 4, 350000.00, '1747491095_anh_sp_add_web_3-02-02-01-01-0-01-01-011-01-01-2_f7df70b23c344dae95683cef91a95e3a_1024x1024.webp', 'Giày đá bóng NMS FLASH Kids - Grey/Orange', 0.00),
(12, 'Giày đá bóng JOMA Top Flex Rebound 2476 Light Up - White/Red TOPW2476IN', 10, 5, 2650000.00, '1747491178_741cb8ac611ad3448a0b_bfddb61fc77048759a4ca29c4896d22e_1024x1024.webp', 'Joma được thành lập vào năm 1965 tại Tây Ban Nha, bởi Fructuoso López, một cựu vận động viên điền kinh. Với mong muốn mang đến cho người dùng những sản phẩm chất lượng và phù hợp với môn thể thao mà họ yêu thích, Joma không ngừng phát triển và đưa thương hiệu nhanh chóng trở thành một trong những tên tuổi hàng đầu trong ngành thời trang thể thao. Hiện tại, Joma đã có mặt tại hơn 110 quốc gia trên toàn thế giới và là nhà sản xuất trang phục thể thao và giày đá banh cho nhiều đội bóng danh tiếng. Đặc biệt,Joma đã thành công trở thành một trong những thương hiệu hàng đầu cho ra nhiều mẫu giày đá banh tốt nhất dành riêng cho những tuyển thủ Futsal nổi tiếng trên khắp thế giới như Ferrao, Taynan, Brandi, Pito, Pany,...\r\n\r\nGiày đá bóng JOMA Top Flex Rebound 2476 Light Up - White/Red TOPW2476IN là mẫu giày phổ thông cho mặt sân futsal.\r\n\r\nCông nghệ trên đôi Giày đá bóng JOMA Top Flex Rebound 2476 Light Up - White/Red TOPW2476IN:\r\n\r\nVTS (Ventilation): Công nghệ thoáng khí với các lỗ thông hơi giúp tăng cường sự lưu thông không khí, giữ cho bàn chân luôn khô ráo và thoải mái.\r\n\r\nDurability: Sử dụng cao su có độ bền cao, giúp chống mài mòn và kéo dài tuổi thọ của giày ngay cả khi sử dụng trên bề mặt cứng. \r\n\r\nReactiveBall (Reactivity): Công nghệ hỗ trợ khả năng đàn hồi và phản ứng nhanh, giúp tối ưu hóa khả năng di chuyển và độ bật nảy trong các tình huống thi đấu.\r\n\r\nFlexo (Flexibility): Hệ thống các rãnh uốn trên đế giày giúp giày thích ứng linh hoạt với chuyển động của bàn chân, mang lại cảm giác tự nhiên khi di chuyển.\r\n\r\nProtection: Gia cố các khu vực quan trọng trên giày để tăng độ bền và bảo vệ bàn chân khỏi va đập trong suốt quá trình thi đấu.', 34.00),
(13, 'Giày đá bóng Nike Phantom GX II Pro TF Prism - Crimson Tint/Black/Pink Blast FJ2583-800', 2, 2, 4109000.00, '1747491277_93611796de3641b9bba898d436a5b628_db77e1a7911740c6b0536790880be444_1024x1024.webp', 'Nike Prism Pack, bộ quần áo bóng đá mới nhất cho mùa xuân 2025, đã có biểu tượng chính thức. Lấy cảm hứng từ lăng kính đa sắc, Prism Pack mang đến những gam màu pastel tươi trẻ, rực rỡ, tạo nên vẻ ngoài trẻ trung, năng động và đầy sức sống. Từ sắc xanh biển nhạt dịu mát của Mercurial, sắc hồng đào ngọt ngào của Phantom đến sắc xanh ngọc lam thanh lịch của Tiempo, mỗi dòng giày đều xịt lên một cá tính đặc biệt, đáp ứng mọi phong cách và vị trí thi đấu trên sân cỏ. Sự kết hợp hài hòa giữa các gam màu pastel không chỉ tạo nên vẻ đẹp thẩm mỹ mà còn mang đến cảm giác nhẹ nhàng, thoải mái, giúp cầu thủ tự tin thể hiện bản thân.\r\n\r\n \r\n\r\nVề công nghệ cuả phiên bản Giày đá bóng Nike Phantom Luna II Elite FG:\r\n\r\nGiày đá bóng Nike Phantom GX II Pro TF Prism - Crimson Tint/Black/Pink Blast FJ2583-800 là mẫu giày phân khúc cao cấp dành cho mặt sân cỏ nhân tạo 5-7 người. Dòng giày được thiết kế dành cho sự chính xác và kiểm soát bóng tuyệt đối, xuất hiện trong Prism Pack với gam màu \"Crimson Tint/Black/Pink Blast\" đầy quyến rũ và nổi bật. Sắc hồng đào ngọt ngào bao phủ phần upper knit, tạo nên vẻ ngoài trẻ trung, năng động và không kém phần mạnh mẽ.\r\n\r\nPhần upper là điểm cải tiến lớn nhất ở phiên bản Phantom GX II này, nó được làm từ chất liệu sợi dệt Flyknit siêu mỏng và nhẹ, nhưng vẫn giữ được độ bền và đàn hồi cần thiết để tối ưu cho những pha di chuyển linh hoạt của người chơi trên sân cỏ. Phủ lên trên upper đó là công nghệ ACC (All Conditions Control) giúp người chơi có thể kiểm soát bóng tốt nhất trong mọi điều kiện thời tiết.\r\n\r\nPhần mũi giày có các vân vòng tròn được in dập nổi ở những vùng tiếp xúc bóng chủ yếu giúp tăng thêm độ xoáy cho những cú phất bóng và cứa lòng được Nike gọi là Công nghệ Strike Zone.\r\n\r\nDây giày đặc trưng của dòng Phantom tiếp tục được duy trì với thiết kế lệch về phía má ngoài nhằm mở rộng diện tích sút và chuyền bóng.\r\n\r\nLưỡi gà và cổ giày ở phiên bản GX II này cũng được nâng cấp thành sợi dệt Flyknit với độ co giãn cao, mang đến sự ôm chân chắc chắn nhưng không kém phần thoải mái. Với chất liệu đặc biệt này, người chơi có thể xỏ chân vào giày dễ dàng hơn trước. \r\n\r\nĐệm gót giày với chất liệu vải mềm mịn, tạo cảm giác ôm chân vừa vặn và dễ chịu giúp hỗ trợ tốt hơn cho quá trình break-in. \r\n\r\nGiày đá banh Nike Phantom GX II Pro TF vẫn giữ nguyên bộ đệm React xịn xò, giúp hỗ trợ người chơi tối đa trong việc thi đấu trên mặt sân cỏ nhân tạo. \r\n\r\nĐế giày làm từ chất liệu cao su cao cấp với các đinh dạng Elip lớn nhỏ khác nhau, không chỉ tăng độ bám mà còn giảm áp lực lên các khớp, tạo điều kiện thuận lợi cho những bước chuyển động nhanh chóng\r\n\r\n \r\n\r\nTHƯƠNG HIỆU\r\n\r\nNIKE - Thương hiệu cung cấp giày bóng đá chính hãng và các phụ kiện bóng đá lớn nhất thế giới, được hình thành vào năm 1964 tại Hoa Kỳ. Các đại diện nổi tiếng của NIKE là Halland, Mbappe, CR7…\r\n\r\nNIKE MERCURIAL: Dòng giày tốc độ (SPEED) với thiết kế mỏng, nhẹ hỗ trợ tăng tốc.NIKE TIEMPO: dòng giày cảm giác (TOUCH) mang lại cảm giác êm ái trong mỗi pha chạm bóng.NIKE PHANTOM: dòng giày kỹ thuật (SKILL) hỗ trợ kiểm soát bóng và dứt điểm.\r\n \r\n\r\nTất cả sản phẩm Giày đá banh Nike đều được hỗ trợ trả góp 0% lãi suất thông qua Fundiin.', 31.00),
(14, 'Asics Destaque FF 2 IC - Classic Red/Beet Juice', 6, 2, 3490000.00, '1747491363_anh_sp_add_w735eb_2-01-02-03737-02-02-02-01_bbefd6599d384e9680a50d0c833e3d07_1024x1024.webp', '', 37.00),
(15, 'Asics Calcetto WD 9 IC - White/Black', 6, 3, 2295000.00, '1747491408_anh_sp_add_web_joma-02-02-02-01-02-01-01-01-01-01-02-02-02-02-02-01-02_fd4889970f8c45ca87da7aa009f9b0df_1024x1024.webp', '', 35.00),
(16, 'Asics Calcetto WD 9 IC - Black/Black', 6, 3, 2295000.00, '1747491464_2-02-02-02-01-01-01-01-01-02-01-01-01-01-02-01-01-01-01-01-01-01-01-01_95fb9848fac94ae0b4ad6fd0167e777b_1024x1024.webp', '', 35.00),
(17, 'Giày đá bóng Mizuno Monarcida Neo lll Select TF Mugen - Laser Blue/White P1GD242527', 5, 3, 1599000.00, '1747491512_anh_sp_add_web_3-02-02-01-01-01245241_526f1eb0ea874a549bb4c4b498e25795_1024x1024.jpg', '', 0.00),
(18, 'Giày đá bóng Mizuno Monarcida Neo III Select AS TF - Aqua Blue P1GD242513', 5, 2, 1599000.00, '1747491656_1-01-01-01-01-01-01-01-01-01-01-01-01-01-01-01-01-01-01-04-03-03-03-02_ec9ea0188fb84421ac94a64854ddb105_1024x1024.webp', 'Giày đá bóng Mizuno Monarcida Neo III Select AS TF - Aqua Blue P1GD242513', 0.00),
(19, 'Giày đá bóng Kamito Velocidad Fire Back TF - Mint/White KMTF240120', 8, 4, 499000.00, '1747491697_2-02-01-01-01-01-01-01-01-01-01-01-01-01-01-01-01-01-01-01-01-01-04-01_97ad169f1c91489084763da9a4bd383f_1024x1024.webp', '', 0.00),
(20, 'KAMITO TA11 TOUCH OF MAGIC - RED/GOLD', 8, 4, 690000.00, '1747491736_2-02-02-02-02-02-02-01-02-01-02-02-02-02-02-02-02-02-02-02-02-02-02-02_1ae1b2d105634299b4247d53a113857a_1024x1024.webp', '', 10.00),
(21, 'Giày đá bóng KAMITO TA11 Woncup - Mint Green/White', 8, 4, 690000.00, '1747491802_01-02-4-01-01-01-3-02-02-02-01-01-01-2-02-2-01-01-01-02-02-01-01-01-01_bbf40646ba35403690547f16fce5a232_1024x1024.webp', '', 13.00),
(22, 'Giày đá bóng X MUNICH PRISMA 26 BLANCO - White/Black 3116026', 11, 5, 2929000.00, '1747491858_anh_sp_add_w735eb_2-01-01-02-02-02_109e363cc7b6433abaaa327625bf0455_1024x1024.webp', '', 59.00),
(23, 'Giày đá bóng X MUNICH ONE indoor 52 - Royal Blue/White 3071052', 11, 5, 2692000.00, '1747491904_23f1fadd27c44038a66795d5f862971f_c5265c23d25945439f0acec033bf97b9_1024x1024.webp', '', 62.00),
(24, 'Giày đá bóng X MUNICH CONTINENTAL 945 - Royal Blue/White 4100945', 11, 5, 3600000.00, '1747491940_fcbe011ef2f24c44aea7c51d73a9189d_566eecda247f45e3af9f1c56da1ac278_1024x1024.webp', '', 59.00),
(25, 'Giày đá bóng Desporte Tessa Light TF Pro II Limited Edition - Silver/Blue DS-2042', 13, 5, 3790000.00, '1747492003__sp_add_web_3-02-02-01-01-01-01-01-01-01-01-01-01-01-01-01-01-01-01-01_9be438b22de0478a8e6044a21858e474_1024x1024.webp', '', 15.00),
(26, 'Giày đá banh Desporte Boa Vista KI PRO2 IC DS1933 - White/Green Camo', 13, 5, 3100000.00, '1747492156_cc708b939ebf4813984c3d32998af17f_d7e8d99fd1f9499893409c1cc82e64a6_1024x1024.webp', '', 29.00),
(27, 'Giày đá banh Desporte Campinas JTF6 TF DS2040 - White/Black/Gold', 13, 5, 2950000.00, '1747492238_anh_sp_add_web_ballak02-01-01-01-01-02-02-02_a3aa540c021549b6b92f3de6589362ee_1024x1024.jpg', '', 22.00),
(28, 'Giày đá bóng Zocker Winner Energy - Blue/White SNS-008-Blue', 9, 4, 650000.00, '1747492373_anh_sp_add-01-01-5773_7d674feb7726443db6373c3586bda970_1024x1024.webp', '', 0.00),
(29, 'Giày đá bóng Zocker Winner Energy - Red/Yellow SNS-008-Red', 9, 4, 650000.00, '1747492402_anh_sp_add-01-01-57873_fa9ce0f6e05744ee8c828437e58c4d3c_1024x1024.webp', '', 0.00),
(30, 'Giày đá bóng Zocker Winner Energy - White/Black SNS-008-White', 9, 4, 659000.00, '1747492434_anh_sp_add-017701-573_c1178662d34b4af4a24c73cbdc7643fd_1024x1024.webp', '', 0.00),
(31, 'Giày đá bóng Zocker Inspire Pro TF - Neon Green', 9, 4, 729000.00, '1747492463_anh_sp_add_w735eb_2-01-02-03737-02-01_792b14c9f16d41159b5cce6ac3dc72bf_1024x1024.webp', '', 0.00),
(32, 'Giày đá bóng NMS XABI TF - White/Red', 7, 4, 339000.00, '1747492580_2-01-01-01-01-01-01-01-01-01-01-01-01-01-01-01-01-01-01-01-01-04-03-01_d1ba233546bc458aa34a51243fdf49c4_1024x1024.webp', '', 18.00),
(33, 'Giày đá bóng adidas F50 Pro TF Celestial Victory - Fusion Pink/Lucid Lemon/Lucid Pink IE1219', 3, 2, 3500000.00, '1747493289_anh_sp_add_web_3-1701-01-01-04-01783-2-2_fa21387092c64367bbfe43303e6f023c_1024x1024.webp', '', 20.00),
(34, 'Giày đá bóng adidas Predator League FG/AG Celestial Victory - Footwear White/Lucid Pink/Lucid Lemon ID1330', 3, 3, 2400000.00, '1747493327_anh_sp_add_web_3-1701-01-01-04-019879983-2_c318d2de5cc94a86a2cca4075141fb20_1024x1024.webp', '', 19.00),
(35, 'Giày đá bóng Mizuno Morelia Neo IV Pro FG Platinum Silver - Galaxy Silver/Cool Gray P1GA253404', 5, 3, 2800000.00, '1747493378_anh_sp_ad-01-01-01-01-01-01-019-01-2_e69f173ecf8341498621b2d8e26c5b0c_1024x1024.webp', '', 10.00),
(36, 'Giày đá bóng Nike Air Zoom Mercurial Vapor 16 Pro TF Refresh - Ocean Cube/Pink Blast FQ8687-301', 2, 2, 3799000.00, '1747493440_anh_sp_add-01-01-01-04-07173-w3-2_330d9d2f836b4856b547b891608ef500_1024x1024.webp', '', 25.00),
(37, 'Giày đá bóng Nike Tiempo Legend 10 Elite FG LV8 Limited Edition - White/Multi Color HV4889-100', 2, 3, 7598000.00, '1747493482_anh_sp_add_web-1_c592324dd73a4bdcb669b9c9405ca03f_1024x1024.webp', '', 0.00),
(38, 'Giày đá bóng Nike Phantom GX II Elite FG LV8 Limited Edition - White/Multi Color HV4890-100', 2, 3, 8239000.00, '1747493531_anh_sp_add_web_0773dc2b033e403684ecdaef2141e27b_1024x1024.webp', '', 0.00),
(39, 'Giày đá bóng PUMA Future 8 Pro Cage TT Audacity - Yellow Alert/PUMA Black/Sun Struck 108366-03', 4, 2, 2999000.00, '1747493641_anh_sp_add_web_3-02-02-01-01-01-01-0081-01-01-2_84f5045140704ac792bcf9e62d492c3d_1024x1024.webp', '', 10.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khach_hang`
--

CREATE TABLE `khach_hang` (
  `id` int(11) NOT NULL,
  `tai_khoan` varchar(100) NOT NULL,
  `mat_khau` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `ho_ten` varchar(100) DEFAULT NULL,
  `dia_chi` varchar(255) DEFAULT NULL,
  `so_dien_thoai` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `loai_giay`
--

CREATE TABLE `loai_giay` (
  `id` int(11) NOT NULL,
  `ten_loai` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `loai_giay`
--

INSERT INTO `loai_giay` (`id`, `ten_loai`) VALUES
(2, 'Giày cỏ nhân tạo'),
(3, 'Giày cỏ tự nhiên'),
(4, 'Giày đá bóng giá rẻ'),
(5, 'Giày Futsal');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `size_giay`
--

CREATE TABLE `size_giay` (
  `id` int(11) NOT NULL,
  `giay_id` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `so_luong_ton` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `size_giay`
--

INSERT INTO `size_giay` (`id`, `giay_id`, `size`, `so_luong_ton`) VALUES
(5, 7, 27, 100),
(6, 7, 28, 96),
(7, 7, 29, 100),
(8, 13, 29, 100),
(9, 15, 36, 100);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thuong_hieu`
--

CREATE TABLE `thuong_hieu` (
  `id` int(11) NOT NULL,
  `ten_thuong_hieu` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `thuong_hieu`
--

INSERT INTO `thuong_hieu` (`id`, `ten_thuong_hieu`) VALUES
(2, 'NIKE'),
(3, 'ADIDAS'),
(4, 'PUMA'),
(5, 'MIZUNO'),
(6, 'ASICS'),
(7, 'NMS'),
(8, 'KAMITO'),
(9, 'ZOCKER'),
(10, 'JOMA'),
(11, 'X MUNICH'),
(12, 'GRANDSPORT'),
(13, 'DESPORTE');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `don_hang_id` (`don_hang_id`),
  ADD KEY `size_giay_id` (`size_giay_id`);

--
-- Chỉ mục cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `khach_hang_id` (`khach_hang_id`);

--
-- Chỉ mục cho bảng `giay`
--
ALTER TABLE `giay`
  ADD PRIMARY KEY (`id`),
  ADD KEY `thuong_hieu_id` (`thuong_hieu_id`),
  ADD KEY `loai_giay_id` (`loai_giay_id`);

--
-- Chỉ mục cho bảng `khach_hang`
--
ALTER TABLE `khach_hang`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `loai_giay`
--
ALTER TABLE `loai_giay`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `size_giay`
--
ALTER TABLE `size_giay`
  ADD PRIMARY KEY (`id`),
  ADD KEY `giay_id` (`giay_id`);

--
-- Chỉ mục cho bảng `thuong_hieu`
--
ALTER TABLE `thuong_hieu`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `giay`
--
ALTER TABLE `giay`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT cho bảng `khach_hang`
--
ALTER TABLE `khach_hang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `loai_giay`
--
ALTER TABLE `loai_giay`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `size_giay`
--
ALTER TABLE `size_giay`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `thuong_hieu`
--
ALTER TABLE `thuong_hieu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD CONSTRAINT `chi_tiet_don_hang_ibfk_1` FOREIGN KEY (`don_hang_id`) REFERENCES `don_hang` (`id`),
  ADD CONSTRAINT `chi_tiet_don_hang_ibfk_2` FOREIGN KEY (`size_giay_id`) REFERENCES `size_giay` (`id`);

--
-- Các ràng buộc cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  ADD CONSTRAINT `don_hang_ibfk_1` FOREIGN KEY (`khach_hang_id`) REFERENCES `khach_hang` (`id`);

--
-- Các ràng buộc cho bảng `giay`
--
ALTER TABLE `giay`
  ADD CONSTRAINT `giay_ibfk_1` FOREIGN KEY (`thuong_hieu_id`) REFERENCES `thuong_hieu` (`id`),
  ADD CONSTRAINT `giay_ibfk_2` FOREIGN KEY (`loai_giay_id`) REFERENCES `loai_giay` (`id`);

--
-- Các ràng buộc cho bảng `size_giay`
--
ALTER TABLE `size_giay`
  ADD CONSTRAINT `size_giay_ibfk_1` FOREIGN KEY (`giay_id`) REFERENCES `giay` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
