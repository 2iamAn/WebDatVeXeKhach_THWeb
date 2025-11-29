-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th11 27, 2025
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `datvexekhach`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chuyenxe`
--

CREATE TABLE `chuyenxe` (
  `MaChuyenXe` int(11) NOT NULL,
  `MaNhaXe` int(11) NOT NULL,
  `MaTuyen` int(11) NOT NULL,
  `DiemLenXe` varchar(255) DEFAULT NULL COMMENT 'Điểm lên xe cụ thể',
  `DiemXuongXe` varchar(255) DEFAULT NULL COMMENT 'Điểm xuống xe cụ thể',
  `MaXe` int(11) DEFAULT NULL,
  `GioKhoiHanh` datetime NOT NULL,
  `GioDen` datetime DEFAULT NULL,
  `GiaVe` decimal(10,2) NOT NULL,
  `TrangThai` varchar(20) DEFAULT 'Còn chỗ',
  `LyDoTuChoi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chuyenxe`
--

INSERT INTO `chuyenxe` (`MaChuyenXe`, `MaNhaXe`, `MaTuyen`, `DiemLenXe`, `DiemXuongXe`, `MaXe`, `GioKhoiHanh`, `GioDen`, `GiaVe`, `TrangThai`, `LyDoTuChoi`) VALUES
-- Ngày 27/11/2025
(1, 1, 1, 'Bến Xe Miền Đông', 'Bến Xe Gia Lai', 1, '2025-11-27 17:31:00', '2025-11-28 03:31:00', 500000.00, 'Còn chỗ', NULL),
(2, 2, 2, 'Bến Xe Miền Đông', 'Bến Xe Đắk Nông', 2, '2025-11-27 21:00:00', '2025-11-28 03:00:00', 350000.00, 'Còn chỗ', NULL),
(3, 1, 3, 'Bến Xe Miền Đông', 'Bến Xe Buôn Ma Thuột', 5, '2025-11-27 19:30:00', '2025-11-28 03:30:00', 400000.00, 'Còn chỗ', NULL),
(4, 3, 4, 'Bến Xe Miền Đông', 'Gia Nghĩa (Dọc Quốc lộ 14)', 3, '2025-11-27 19:30:00', '2025-11-28 05:30:00', 200000.00, 'Còn chỗ', NULL),
(5, 4, 5, 'Bến Xe Miền Đông', 'Bến Xe Buôn Ma Thuột', 4, '2025-11-27 21:30:00', '2025-11-28 05:30:00', 380000.00, 'Còn chỗ', NULL),
(6, 2, 6, 'Bến Xe Miền Đông', 'Bến Xe Đà Lạt', 6, '2025-11-27 22:00:00', '2025-11-28 05:00:00', 320000.00, 'Còn chỗ', NULL),
(7, 1, 7, 'Bến Xe Miền Đông', 'Bến Xe Lâm Đồng', 5, '2025-11-27 20:00:00', '2025-11-28 03:00:00', 300000.00, 'Còn chỗ', NULL),
(8, 2, 8, 'Bến Xe Miền Tây', 'Bến Xe Cần Thơ', 6, '2025-11-27 06:00:00', '2025-11-27 10:00:00', 150000.00, 'Còn chỗ', NULL),
(9, 3, 9, 'Bến Xe Miền Đông', 'Bến Xe Đà Lạt', 7, '2025-11-27 22:30:00', '2025-11-28 05:30:00', 450000.00, 'Còn chỗ', NULL),
(10, 4, 10, 'Bến Xe Miền Đông', 'Bến Xe Nha Trang', 8, '2025-11-27 19:00:00', '2025-11-28 04:00:00', 380000.00, 'Còn chỗ', NULL),
(11, 3, 11, 'Bến Xe Miền Đông', 'Bến Xe Đà Nẵng', 7, '2025-11-27 15:00:00', '2025-11-28 09:00:00', 550000.00, 'Còn chỗ', NULL),
(12, 4, 12, 'Bến Xe Miền Đông', 'Bến Xe Quy Nhơn', 8, '2025-11-27 18:00:00', '2025-11-28 06:00:00', 420000.00, 'Còn chỗ', NULL),
-- Ngày 28/11/2025
(13, 1, 1, 'Bến Xe Miền Đông', 'Bến Xe Gia Lai', 1, '2025-11-28 06:00:00', '2025-11-28 16:00:00', 500000.00, 'Còn chỗ', NULL),
(14, 2, 2, 'Bến Xe Miền Đông', 'Bến Xe Đắk Nông', 2, '2025-11-28 07:00:00', '2025-11-28 13:00:00', 350000.00, 'Còn chỗ', NULL),
(15, 1, 7, 'Bến Xe Miền Đông', 'Bến Xe Lâm Đồng', 5, '2025-11-28 08:00:00', '2025-11-28 15:00:00', 300000.00, 'Còn chỗ', NULL),
(16, 3, 1, 'Bến Xe Miền Đông', 'Bến Xe Gia Lai', 3, '2025-11-28 20:00:00', '2025-11-29 06:00:00', 480000.00, 'Còn chỗ', NULL),
(17, 4, 3, 'Bến Xe Miền Đông', 'Bến Xe Buôn Ma Thuột', 8, '2025-11-28 21:00:00', '2025-11-29 05:00:00', 390000.00, 'Còn chỗ', NULL),
(18, 2, 6, 'Bến Xe Miền Đông', 'Bến Xe Đà Lạt', 6, '2025-11-28 23:00:00', '2025-11-29 06:00:00', 320000.00, 'Còn chỗ', NULL),
-- Ngày 29/11/2025
(19, 1, 1, 'Bến Xe Miền Đông', 'Bến Xe Gia Lai', 5, '2025-11-29 17:00:00', '2025-11-30 03:00:00', 500000.00, 'Còn chỗ', NULL),
(20, 2, 2, 'Bến Xe Miền Đông', 'Bến Xe Đắk Nông', 6, '2025-11-29 20:00:00', '2025-11-30 02:00:00', 350000.00, 'Còn chỗ', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danhgia`
--

CREATE TABLE `danhgia` (
  `MaDanhGia` int(11) NOT NULL,
  `MaNguoiDung` int(11) NOT NULL,
  `MaNhaXe` int(11) NOT NULL,
  `MaVeXe` int(11) DEFAULT NULL,
  `SoSao` tinyint(4) NOT NULL,
  `NoiDung` text DEFAULT NULL,
  `DaMuaQua` tinyint(1) DEFAULT 0,
  `NgayDanhGia` timestamp NOT NULL DEFAULT current_timestamp(),
  `HienThi` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `danhgia`
--

INSERT INTO `danhgia` (`MaDanhGia`, `MaNguoiDung`, `MaNhaXe`, `MaVeXe`, `SoSao`, `NoiDung`, `DaMuaQua`, `NgayDanhGia`, `HienThi`) VALUES
-- Nhà xe 1: Hoàng Long (7x5sao + 2x4.5sao + 1x4sao = 4.8/5)
(1, 4, 1, 1, 5, 'Limousine sang trọng, ghế nằm êm ái. Rất đáng giá!', 1, '2025-11-25 09:00:00', 1),
(2, 8, 1, NULL, 5, 'Xe chạy đúng giờ, nhân viên phục vụ nhiệt tình. Sẽ đi tiếp lần sau!', 1, '2025-11-24 15:20:00', 1),
(3, 10, 1, NULL, 5, 'Rất hài lòng với chất lượng dịch vụ. Xe mới và sạch sẽ.', 1, '2025-11-23 14:30:00', 1),
(4, 11, 1, NULL, 5, 'Xe sạch sẽ, ghế ngồi thoải mái. Tài xế lái xe rất an toàn.', 1, '2025-11-22 11:00:00', 1),
(5, 12, 1, NULL, 5, 'Dịch vụ tuyệt vời! Limousine đẳng cấp 5 sao', 1, '2025-11-21 16:45:00', 1),
(6, 4, 1, NULL, 5, 'Xe mới, tiện nghi đầy đủ. Rất hài lòng!', 1, '2025-11-20 13:20:00', 1),
(7, 8, 1, NULL, 5, 'Chuyến đi tuyệt vời, sẽ giới thiệu cho bạn bè', 1, '2025-11-19 10:30:00', 1),
(8, 10, 1, NULL, 4.5, 'Xe đẹp, sạch sẽ. Nhân viên thân thiện. Chỉ tiếc là hơi đắt một chút', 1, '2025-11-18 14:00:00', 1),
(9, 11, 1, NULL, 4.5, 'Dịch vụ tốt, xe đúng giờ. Ghế ngồi thoải mái nhưng wifi hơi yếu', 1, '2025-11-17 09:15:00', 1),
(10, 5, 1, NULL, 4, 'Tổng thể tốt, chỉ có điều giá hơi cao so với các nhà xe khác', 0, '2025-11-16 15:40:00', 1),

-- Nhà xe 2: Phương Trang (7x5sao + 2x4.5sao + 1x4sao = 4.8/5)
(11, 4, 2, 2, 5, 'Phương Trang luôn là sự lựa chọn hàng đầu của tôi!', 1, '2025-11-25 10:30:00', 1),
(12, 9, 2, NULL, 5, 'Xe mới, sạch sẽ. Tài xế lái xe rất cẩn thận', 1, '2025-11-24 11:00:00', 1),
(13, 10, 2, NULL, 5, 'Rất hài lòng! Nhân viên nhiệt tình, xe chạy êm', 1, '2025-11-23 08:15:00', 1),
(14, 11, 2, NULL, 5, 'Chất lượng dịch vụ xuất sắc. Đúng là thương hiệu uy tín!', 1, '2025-11-22 16:30:00', 1),
(15, 12, 2, NULL, 5, 'Xe đi êm, ghế ngồi thoải mái. Giá cả hợp lý', 1, '2025-11-21 12:45:00', 1),
(16, 8, 2, NULL, 5, 'Dịch vụ 5 sao! Xe đúng giờ, nhân viên chuyên nghiệp', 1, '2025-11-20 09:00:00', 1),
(17, 9, 2, NULL, 5, 'Rất hài lòng với chuyến đi. Sẽ quay lại!', 1, '2025-11-19 14:20:00', 1),
(18, 10, 2, NULL, 4.5, 'Xe sạch, nhân viên nhiệt tình. Điều hòa mát lạnh', 1, '2025-11-18 10:50:00', 1),
(19, 11, 2, NULL, 4.5, 'Dịch vụ tốt, xe đi đúng giờ. Điều hòa mát nhưng hơi ồn', 0, '2025-11-17 13:30:00', 1),
(20, 5, 2, NULL, 4, 'Xe khá ổn, tài xế lái xe an toàn. Có thể cải thiện thêm', 0, '2025-11-16 08:40:00', 1),

-- Nhà xe 3: Hải Âu (7x5sao + 2x4.5sao + 1x4sao = 4.8/5)
(21, 4, 3, NULL, 5, 'Limousine VIP tuyệt vời! Ghế massage rất thoải mái', 1, '2025-11-25 12:00:00', 1),
(22, 10, 3, NULL, 5, 'Dịch vụ 5 sao! Sẽ quay lại', 1, '2025-11-24 11:00:00', 1),
(23, 11, 3, NULL, 5, 'Xe sang trọng, tiện nghi đầy đủ. Rất đáng tiền!', 1, '2025-11-23 15:30:00', 1),
(24, 12, 3, NULL, 5, 'Chất lượng dịch vụ xuất sắc. Limousine đẳng cấp!', 1, '2025-11-22 09:45:00', 1),
(25, 8, 3, NULL, 5, 'Rất hài lòng với chuyến đi. Nhân viên chuyên nghiệp', 1, '2025-11-21 14:15:00', 1),
(26, 9, 3, NULL, 5, 'Xe mới tinh, ghế nằm êm ái. Sẽ giới thiệu cho bạn bè', 1, '2025-11-20 10:20:00', 1),
(27, 10, 3, NULL, 5, 'Tuyệt vời! Xứng đáng với mức giá', 1, '2025-11-19 16:00:00', 1),
(28, 11, 3, NULL, 4.5, 'Giá cả hợp lý, dịch vụ tốt. Xe sạch sẽ', 1, '2025-11-18 11:30:00', 1),
(29, 12, 3, NULL, 4.5, 'Xe đẹp, nhân viên thân thiện. Wifi hơi yếu', 1, '2025-11-17 08:50:00', 1),
(30, 4, 3, NULL, 4, 'Tổng thể khá tốt. Có thể cải thiện thêm về wifi', 0, '2025-11-16 13:10:00', 1),

-- Nhà xe 4: Mai Linh Express (7x5sao + 2x4.5sao + 1x4sao = 4.8/5)
(31, 4, 4, NULL, 5, 'Xe mới, sạch sẽ. Tài xế lái xe rất cẩn thận', 1, '2025-11-25 08:30:00', 1),
(32, 11, 4, NULL, 5, 'Rất hài lòng! Dịch vụ chuyên nghiệp', 1, '2025-11-24 12:00:00', 1),
(33, 12, 4, NULL, 5, 'Xe chạy êm, an toàn. Nhân viên nhiệt tình', 1, '2025-11-23 10:45:00', 1),
(34, 8, 4, NULL, 5, 'Chất lượng dịch vụ tốt. Xe đúng giờ', 1, '2025-11-22 15:20:00', 1),
(35, 9, 4, NULL, 5, 'Tuyệt vời! Sẽ sử dụng dịch vụ lần sau', 1, '2025-11-21 09:30:00', 1),
(36, 10, 4, NULL, 5, 'Xe sạch, ghế ngồi thoải mái. Rất hài lòng', 1, '2025-11-20 14:00:00', 1),
(37, 11, 4, NULL, 5, 'Dịch vụ xuất sắc! Đúng là thương hiệu uy tín', 1, '2025-11-19 11:15:00', 1),
(38, 12, 4, NULL, 4.5, 'Xe chạy êm, an toàn. Giá hợp lý', 1, '2025-11-18 13:40:00', 1),
(39, 8, 4, NULL, 4.5, 'Tổng thể khá tốt. Nhân viên thân thiện', 1, '2025-11-17 10:00:00', 1),
(40, 5, 4, NULL, 4, 'Xe ổn, tài xế lái cẩn thận. Có thể cải thiện thêm', 0, '2025-11-16 12:30:00', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ghe`
--

CREATE TABLE `ghe` (
  `MaGhe` int(11) NOT NULL,
  `MaChuyenXe` int(11) NOT NULL,
  `SoGhe` varchar(10) NOT NULL,
  `TrangThai` varchar(20) DEFAULT 'Trống'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `ghe`
--

INSERT INTO `ghe` (`MaGhe`, `MaChuyenXe`, `SoGhe`, `TrangThai`) VALUES
(1, 1, 'A01', 'Trống'),
(2, 1, 'A02', 'Trống'),
(3, 1, 'A03', 'Trống'),
(4, 1, 'B01', 'Trống'),
(5, 1, 'B02', 'Trống'),
(6, 1, 'B03', 'Trống'),
(7, 2, 'A01', 'Trống'),
(8, 2, 'A02', 'Trống'),
(9, 2, 'A03', 'Trống'),
(10, 2, 'B01', 'Trống'),
(11, 3, 'A01', 'Trống'),
(12, 3, 'A02', 'Trống'),
(13, 3, 'A03', 'Trống'),
(14, 4, 'A01', 'Trống'),
(15, 4, 'A02', 'Trống'),
(16, 4, 'A03', 'Trống');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoidung`
--

CREATE TABLE `nguoidung` (
  `MaNguoiDung` int(11) NOT NULL,
  `HoTen` varchar(100) NOT NULL,
  `TenDangNhap` varchar(50) NOT NULL,
  `MatKhau` varchar(255) NOT NULL,
  `LoaiNguoiDung` tinyint(4) NOT NULL COMMENT '1=KhachHang, 2=NhaXe, 3=Admin',
  `SDT` varchar(15) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `TrangThai` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoidung`
--

INSERT INTO `nguoidung` (`MaNguoiDung`, `HoTen`, `TenDangNhap`, `MatKhau`, `LoaiNguoiDung`, `SDT`, `Email`, `TrangThai`) VALUES
(1, 'Admin System', 'admin', '123', 3, '0901234567', 'admin@datvexe.com', 1),
(2, 'Nguyễn Văn A', 'nhaxe1', '123', 2, '0912345678', 'nhaxe1@gmail.com', 1),
(3, 'Trần Thị B', 'nhaxe2', '123', 2, '0923456789', 'nhaxe2@gmail.com', 1),
(4, 'Nguyễn công tài', 'khach1', '123', 1, '0123456789', 'khach1@gmail.com', 1),
(5, 'Lê Văn Đăng', 'khach2', '123', 1, '0934567890', 'khach2@gmail.com', 1),
(6, 'Phạm Thị C', 'nhaxe3', '123', 2, '0945678901', 'nhaxe3@gmail.com', 1),
(7, 'Hoàng Văn D', 'nhaxe4', '123', 2, '0956789012', 'nhaxe4@gmail.com', 1),
(8, 'Nguyễn duy báo', 'khach3', '123', 1, '0967890123', 'khach3@gmail.com', 1),
(9, 'Lâm Phú', 'khach4', '123', 1, '0978901234', 'khach4@gmail.com', 1),
(10, 'Vũ Thị E', 'khach5', '123', 1, '0989012345', 'khach5@gmail.com', 1),
(11, 'Đặng Văn F', 'khach6', '123', 1, '0990123456', 'khach6@gmail.com', 1),
(12, 'Bùi Thị G', 'khach7', '123', 1, '0901234568', 'khach7@gmail.com', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhaxe`
--

CREATE TABLE `nhaxe` (
  `MaNhaXe` int(11) NOT NULL,
  `MaNguoiDung` int(11) DEFAULT NULL,
  `TenNhaXe` varchar(100) NOT NULL,
  `MoTa` varchar(255) DEFAULT NULL,
  `TrangThai` varchar(20) DEFAULT 'ChoDuyet',
  `LyDoTuChoi` text DEFAULT NULL,
  `ChinhSachHuyVe` text DEFAULT NULL,
  `QuyDinhNhaXe` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nhaxe`
--

INSERT INTO `nhaxe` (`MaNhaXe`, `MaNguoiDung`, `TenNhaXe`, `MoTa`, `TrangThai`, `LyDoTuChoi`, `ChinhSachHuyVe`, `QuyDinhNhaXe`) VALUES
(1, 2, 'Viet Tan Phat', 'Chuyên tuyến Sài Gòn - Gia Lai, xe limousine cao cấp 36 phòng', 'DaDuyet', NULL, 
'Từ ngày mua đến trước 13:30 27/11/2025: Miễn phí|Từ 13:30 27/11/2025 đến khởi hành: Không hoàn tiền',
'Có mặt tại văn phòng/quầy vé/bến xe trước 30 phút để làm thủ tục lên xe|Đổi vé giấy trước khi lên xe|Xuất trình SMS/Email đặt vé trước khi lên xe|Không mang đồ ăn, thức ăn có mùi lên xe|Không hút thuốc, uống rượu, sử dụng chất kích thích trên xe|Không mang các vật dễ cháy nổ lên xe|Không vứt rác trên xe|Không làm ồn, gây mất trật tự trên xe|Không mang giày, dép trên xe|Tổng trọng lượng hành lý không vượt quá 20 kg|Không vận chuyển hàng hóa cồng kềnh|Không hoàn tiền trong trường hợp hủy đơn hàng do vi phạm các quy định về hành lý'),
(2, 3, 'Phuong Trang', 'Nhà xe lớn nhất Việt Nam, dịch vụ 5 sao, chất lượng đảm bảo', 'DaDuyet', NULL,
'Hủy vé trước 24 giờ: Hoàn 90% giá vé|Hủy vé trước 12 giờ: Hoàn 70% giá vé|Hủy vé trước 6 giờ: Hoàn 50% giá vé|Hủy vé trong vòng 6 giờ trước khởi hành: Không hoàn tiền',
'Có mặt tại bến xe trước 15 phút để làm thủ tục|Xuất trình vé điện tử hoặc giấy tờ tùy thân|Hành lý miễn phí tối đa 20kg|Không mang đồ ăn có mùi lên xe|Không hút thuốc trên xe|Giữ trật tự và vệ sinh chung'),
(3, 6, 'Phuong Hong Linh', 'Nhà xe uy tín với hơn 20 năm kinh nghiệm phục vụ khách hàng', 'DaDuyet', NULL,
'Hủy vé trước 48 giờ: Hoàn 100% giá vé|Hủy vé trước 24 giờ: Hoàn 80% giá vé|Hủy vé trước 12 giờ: Hoàn 60% giá vé|Hủy trong 12 giờ: Không hoàn tiền',
'Đến bến trước 20 phút để làm thủ tục|Xuất trình giấy tờ khi lên xe|Hành lý tối đa 25kg|Không mang vật dễ cháy nổ|Không gây ồn ào|Giữ vệ sinh xe sạch sẽ'),
(4, 7, 'Tien Oanh', 'Chuyên các tuyến đường dài, xe giường nằm cao cấp', 'DaDuyet', NULL,
'Hủy trước 24 giờ: Hoàn 85% giá vé|Hủy trước 12 giờ: Hoàn 65% giá vé|Hủy trước 6 giờ: Hoàn 40% giá vé|Hủy trong 6 giờ: Không hoàn tiền',
'Có mặt trước 25 phút để check-in|Mang theo CMND/CCCD|Hành lý tối đa 20kg/khách|Không mang đồ ăn có mùi|Không sử dụng chất kích thích|Giữ trật tự trên xe');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `xe`
--

CREATE TABLE `xe` (
  `MaXe` int(11) NOT NULL,
  `MaNhaXe` int(11) NOT NULL,
  `TenXe` varchar(150) NOT NULL,
  `LoaiXe` varchar(100) DEFAULT NULL,
  `SoGhe` int(11) DEFAULT NULL,
  `SoGiuong` int(11) DEFAULT NULL,
  `TienNghi` text DEFAULT NULL,
  `HinhAnh1` varchar(255) DEFAULT NULL,
  `HinhAnh2` varchar(255) DEFAULT NULL,
  `HinhAnh3` varchar(255) DEFAULT NULL,
  `BienSoXe` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `xe`
--

INSERT INTO `xe` (`MaXe`, `MaNhaXe`, `TenXe`, `LoaiXe`, `SoGhe`, `SoGiuong`, `TienNghi`, `HinhAnh1`, `HinhAnh2`, `HinhAnh3`, `BienSoXe`) VALUES
(1, 1, 'Viet Tan Phat 03', 'Limousine 36 phòng', 0, 36, 'Tivi LED|Đèn đọc sách: Hỗ trợ hành khách đọc sách dễ dàng và an toàn khi ngồi trên xe|Rèm cửa|Chăn đắp|Nước uống: Nhà xe có phục vụ nước cho hành khách|Khăn lạnh|Búa phá kính: Dụng để phá kính ô tô thoát hiểm trong trường hợp khẩn cấp|Sạc điện thoại|Dép: Khi dừng ở trạm dừng chân sẽ có dép của nhà xe cho hành khách xuống xe|Dây đai an toàn: Trên xe có trang bị dây đai an toàn cho hành khách khi ngồi trên xe|Wifi|Điều hòa|Gối nằm: Trên xe có trang bị gối nằm', NULL, NULL, NULL, '51H-12345'),
(2, 2, 'Phuong Trang 4', 'Limousine', 0, 40, 'Tivi LED|Đèn đọc sách: Hỗ trợ hành khách đọc sách dễ dàng và an toàn khi ngồi trên xe|Chăn đắp|Nước uống: Nhà xe có phục vụ nước cho hành khách|Sạc điện thoại|Dây đai an toàn: Trên xe có trang bị dây đai an toàn cho hành khách khi ngồi trên xe|Wifi|Điều hòa|Gối nằm: Trên xe có trang bị gối nằm', NULL, NULL, NULL, '51H-67890'),
(3, 3, 'Phuong Hong Linh 01', 'Limousine 36 phòng', 0, 36, 'Tivi LED|Rèm cửa|Chăn đắp|Nước uống: Nhà xe có phục vụ nước cho hành khách|Khăn lạnh|Búa phá kính: Dụng để phá kính ô tô thoát hiểm trong trường hợp khẩn cấp|Wifi|Điều hòa|Gối nằm: Trên xe có trang bị gối nằm', NULL, NULL, NULL, '51H-11111'),
(4, 4, 'Tien Oanh 05', 'Giường nằm', 0, 36, 'Đèn đọc sách: Hỗ trợ hành khách đọc sách dễ dàng và an toàn khi ngồi trên xe|Chăn đắp|Nước uống: Nhà xe có phục vụ nước cho hành khách|Dây đai an toàn: Trên xe có trang bị dây đai an toàn cho hành khách khi ngồi trên xe|Điều hòa|Gối nằm: Trên xe có trang bị gối nằm', NULL, NULL, NULL, '51H-22222'),
(5, 1, 'Viet Tan Phat 07', 'Limousine 36 phòng', 0, 36, 'Tivi LED|Đèn đọc sách: Hỗ trợ hành khách đọc sách dễ dàng và an toàn khi ngồi trên xe|Rèm cửa|Chăn đắp|Nước uống: Nhà xe có phục vụ nước cho hành khách|Khăn lạnh|Búa phá kính: Dụng để phá kính ô tô thoát hiểm trong trường hợp khẩn cấp|Sạc điện thoại|Dép: Khi dừng ở trạm dừng chân sẽ có dép của nhà xe cho hành khách xuống xe|Dây đai an toàn: Trên xe có trang bị dây đai an toàn cho hành khách khi ngồi trên xe|Wifi|Điều hòa|Gối nằm: Trên xe có trang bị gối nằm', NULL, NULL, NULL, '51H-77777'),
(6, 2, 'Phuong Trang 15', 'Limousine', 0, 40, 'Tivi LED|Chăn đắp|Nước uống: Nhà xe có phục vụ nước cho hành khách|Sạc điện thoại|Wifi|Điều hòa|Gối nằm: Trên xe có trang bị gối nằm', NULL, NULL, NULL, '51H-88888'),
(7, 3, 'Phuong Hong Linh 09', 'Ghế ngồi', 45, 0, 'Nước uống: Nhà xe có phục vụ nước cho hành khách|Wifi|Điều hòa', NULL, NULL, NULL, '51H-99999'),
(8, 4, 'Tien Oanh 12', 'Giường nằm', 0, 36, 'Chăn đắp|Nước uống: Nhà xe có phục vụ nước cho hành khách|Điều hòa|Gối nằm: Trên xe có trang bị gối nằm', NULL, NULL, NULL, '51H-10101');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thanhtoan`
--

CREATE TABLE `thanhtoan` (
  `MaThanhToan` int(11) NOT NULL,
  `MaVe` int(11) NOT NULL,
  `PhuongThuc` varchar(50) DEFAULT NULL,
  `SoTien` decimal(10,2) DEFAULT NULL,
  `TrangThai` varchar(20) DEFAULT 'Success',
  `NgayThanhToan` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `thanhtoan`
--

INSERT INTO `thanhtoan` (`MaThanhToan`, `MaVe`, `PhuongThuc`, `SoTien`, `TrangThai`, `NgayThanhToan`) VALUES
(1, 1, 'MoMo', 450000.00, 'Success', '2025-11-26 10:48:57');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tuyenduong`
--

CREATE TABLE `tuyenduong` (
  `MaTuyen` int(11) NOT NULL,
  `MaNhaXe` int(11) DEFAULT NULL,
  `DiemDi` varchar(100) DEFAULT NULL,
  `DiemDen` varchar(100) DEFAULT NULL,
  `KhoangCach` int(11) DEFAULT NULL,
  `ThoiGianHanhTrinh` varchar(50) DEFAULT NULL,
  `TrangThai` varchar(20) DEFAULT 'DaDuyet',
  `LyDoTuChoi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tuyenduong`
--

INSERT INTO `tuyenduong` (`MaTuyen`, `MaNhaXe`, `DiemDi`, `DiemDen`, `KhoangCach`, `ThoiGianHanhTrinh`, `TrangThai`, `LyDoTuChoi`) VALUES
(1, 1, 'Sài Gòn', 'Gia Lai', 500, '10 giờ', 'DaDuyet', NULL),
(2, 2, 'Sài Gòn', 'Đắk Nông', 250, '6 giờ', 'DaDuyet', NULL),
(3, 1, 'Sài Gòn', 'Đắk Lắk', 350, '8 giờ', 'DaDuyet', NULL),
(4, 3, 'Sài Gòn', 'Gia Lai', 500, '10 giờ', 'DaDuyet', NULL),
(5, 4, 'Sài Gòn', 'Đắk Lắk', 350, '8 giờ', 'DaDuyet', NULL),
(6, 2, 'Sài Gòn', 'Lâm Đồng', 300, '7 giờ', 'DaDuyet', NULL),
(7, 1, 'Sài Gòn', 'Lâm Đồng', 300, '7 giờ', 'DaDuyet', NULL),
(8, 2, 'Sài Gòn', 'Cần Thơ', 170, '4 giờ', 'DaDuyet', NULL),
(9, 3, 'Sài Gòn', 'Đà Lạt', 308, '7 giờ', 'DaDuyet', NULL),
(10, 4, 'Sài Gòn', 'Nha Trang', 450, '9 giờ', 'DaDuyet', NULL),
(11, 3, 'Sài Gòn', 'Đà Nẵng', 950, '18 giờ', 'DaDuyet', NULL),
(12, 4, 'Sài Gòn', 'Quy Nhơn', 650, '12 giờ', 'DaDuyet', NULL),
(13, 1, 'Sài Gòn', 'Phú Yên', 570, '11 giờ', 'DaDuyet', NULL),
(14, 2, 'Sài Gòn', 'An Giang', 220, '5 giờ', 'DaDuyet', NULL),
(15, 3, 'Sài Gòn', 'Vũng Tàu', 125, '2.5 giờ', 'DaDuyet', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vexe`
--

CREATE TABLE `vexe` (
  `MaVe` int(11) NOT NULL,
  `MaNguoiDung` int(11) NOT NULL,
  `MaChuyenXe` int(11) NOT NULL,
  `MaGhe` int(11) NOT NULL,
  `NgayDat` datetime DEFAULT current_timestamp(),
  `GiaTaiThoiDiemDat` decimal(10,2) DEFAULT NULL,
  `TrangThai` varchar(20) DEFAULT 'Chưa thanh toán'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `vexe`
--

INSERT INTO `vexe` (`MaVe`, `MaNguoiDung`, `MaChuyenXe`, `MaGhe`, `NgayDat`, `GiaTaiThoiDiemDat`, `TrangThai`) VALUES
(1, 4, 1, 1, '2025-11-26 10:48:57', 450000.00, 'Đã thanh toán'),
(2, 5, 2, 5, '2025-11-26 10:48:57', 350000.00, 'Chưa thanh toán');

-- --------------------------------------------------------

--
-- Cấu trúc cho view `baocaodoanhthu`
--

CREATE OR REPLACE VIEW `baocaodoanhthu` AS 
SELECT 
  `nx`.`MaNhaXe` AS `MaNhaXe`, 
  `nx`.`TenNhaXe` AS `TenNhaXe`, 
  count(`vx`.`MaVe`) AS `TongSoVe`, 
  sum(`tt`.`SoTien`) AS `TongDoanhThu`, 
  count(distinct `cx`.`MaChuyenXe`) AS `TongChuyen`, 
  avg(`cx`.`GiaVe`) AS `GiaVeTrungBinh`, 
  max(`tt`.`NgayThanhToan`) AS `LanThanhToanCuoi` 
FROM (((`nhaxe` `nx` 
  left join `chuyenxe` `cx` on(`cx`.`MaNhaXe` = `nx`.`MaNhaXe`)) 
  left join `vexe` `vx` on(`vx`.`MaChuyenXe` = `cx`.`MaChuyenXe`)) 
  left join `thanhtoan` `tt` on(`tt`.`MaVe` = `vx`.`MaVe`)) 
GROUP BY `nx`.`MaNhaXe`, `nx`.`TenNhaXe`;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `chuyenxe`
--
ALTER TABLE `chuyenxe`
  ADD PRIMARY KEY (`MaChuyenXe`),
  ADD KEY `MaNhaXe` (`MaNhaXe`),
  ADD KEY `MaTuyen` (`MaTuyen`),
  ADD KEY `MaXe` (`MaXe`);

--
-- Chỉ mục cho bảng `danhgia`
--
ALTER TABLE `danhgia`
  ADD PRIMARY KEY (`MaDanhGia`),
  ADD KEY `MaNguoiDung` (`MaNguoiDung`),
  ADD KEY `MaNhaXe` (`MaNhaXe`),
  ADD KEY `MaVeXe` (`MaVeXe`);

--
-- Chỉ mục cho bảng `ghe`
--
ALTER TABLE `ghe`
  ADD PRIMARY KEY (`MaGhe`),
  ADD KEY `MaChuyenXe` (`MaChuyenXe`);

--
-- Chỉ mục cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD PRIMARY KEY (`MaNguoiDung`),
  ADD UNIQUE KEY `TenDangNhap` (`TenDangNhap`);

--
-- Chỉ mục cho bảng `nhaxe`
--
ALTER TABLE `nhaxe`
  ADD PRIMARY KEY (`MaNhaXe`),
  ADD UNIQUE KEY `MaNguoiDung` (`MaNguoiDung`),
  ADD KEY `MaNguoiDung_2` (`MaNguoiDung`);

--
-- Chỉ mục cho bảng `thanhtoan`
--
ALTER TABLE `thanhtoan`
  ADD PRIMARY KEY (`MaThanhToan`),
  ADD KEY `MaVe` (`MaVe`);

--
-- Chỉ mục cho bảng `tuyenduong`
--
ALTER TABLE `tuyenduong`
  ADD PRIMARY KEY (`MaTuyen`),
  ADD KEY `MaNhaXe` (`MaNhaXe`),
  ADD KEY `TrangThai` (`TrangThai`);

--
-- Chỉ mục cho bảng `xe`
--
ALTER TABLE `xe`
  ADD PRIMARY KEY (`MaXe`),
  ADD KEY `MaNhaXe` (`MaNhaXe`);

--
-- Chỉ mục cho bảng `vexe`
--
ALTER TABLE `vexe`
  ADD PRIMARY KEY (`MaVe`),
  ADD KEY `MaNguoiDung` (`MaNguoiDung`),
  ADD KEY `MaChuyenXe` (`MaChuyenXe`),
  ADD KEY `MaGhe` (`MaGhe`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `chuyenxe`
--
ALTER TABLE `chuyenxe`
  MODIFY `MaChuyenXe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT cho bảng `danhgia`
--
ALTER TABLE `danhgia`
  MODIFY `MaDanhGia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT cho bảng `ghe`
--
ALTER TABLE `ghe`
  MODIFY `MaGhe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  MODIFY `MaNguoiDung` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `nhaxe`
--
ALTER TABLE `nhaxe`
  MODIFY `MaNhaXe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `thanhtoan`
--
ALTER TABLE `thanhtoan`
  MODIFY `MaThanhToan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `tuyenduong`
--
ALTER TABLE `tuyenduong`
  MODIFY `MaTuyen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `xe`
--
ALTER TABLE `xe`
  MODIFY `MaXe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `vexe`
--
ALTER TABLE `vexe`
  MODIFY `MaVe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `chuyenxe`
--
ALTER TABLE `chuyenxe`
  ADD CONSTRAINT `chuyenxe_ibfk_1` FOREIGN KEY (`MaNhaXe`) REFERENCES `nhaxe` (`MaNhaXe`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `chuyenxe_ibfk_2` FOREIGN KEY (`MaTuyen`) REFERENCES `tuyenduong` (`MaTuyen`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `chuyenxe_ibfk_3` FOREIGN KEY (`MaXe`) REFERENCES `xe` (`MaXe`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `danhgia`
--
ALTER TABLE `danhgia`
  ADD CONSTRAINT `danhgia_ibfk_1` FOREIGN KEY (`MaNguoiDung`) REFERENCES `nguoidung` (`MaNguoiDung`) ON DELETE CASCADE,
  ADD CONSTRAINT `danhgia_ibfk_2` FOREIGN KEY (`MaNhaXe`) REFERENCES `nhaxe` (`MaNhaXe`) ON DELETE CASCADE,
  ADD CONSTRAINT `danhgia_ibfk_3` FOREIGN KEY (`MaVeXe`) REFERENCES `vexe` (`MaVe`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `ghe`
--
ALTER TABLE `ghe`
  ADD CONSTRAINT `ghe_ibfk_1` FOREIGN KEY (`MaChuyenXe`) REFERENCES `chuyenxe` (`MaChuyenXe`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `nhaxe`
--
ALTER TABLE `nhaxe`
  ADD CONSTRAINT `nhaxe_ibfk_1` FOREIGN KEY (`MaNguoiDung`) REFERENCES `nguoidung` (`MaNguoiDung`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `thanhtoan`
--
ALTER TABLE `thanhtoan`
  ADD CONSTRAINT `thanhtoan_ibfk_1` FOREIGN KEY (`MaVe`) REFERENCES `vexe` (`MaVe`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `vexe`
--
ALTER TABLE `vexe`
  ADD CONSTRAINT `vexe_ibfk_1` FOREIGN KEY (`MaNguoiDung`) REFERENCES `nguoidung` (`MaNguoiDung`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vexe_ibfk_2` FOREIGN KEY (`MaChuyenXe`) REFERENCES `chuyenxe` (`MaChuyenXe`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vexe_ibfk_3` FOREIGN KEY (`MaGhe`) REFERENCES `ghe` (`MaGhe`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `xe`
--
ALTER TABLE `xe`
  ADD CONSTRAINT `xe_ibfk_1` FOREIGN KEY (`MaNhaXe`) REFERENCES `nhaxe` (`MaNhaXe`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

