-- ===== TẠO DATABASE =====
CREATE DATABASE IF NOT EXISTS contact_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ===== SỬ DỤNG DATABASE =====
USE contact_db;

-- ===== TẠO BẢNG CONTACTS =====
CREATE TABLE IF NOT EXISTS contacts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ===== THÊM DỮ LIỆU MẪU (Tùy chọn) =====
INSERT INTO contacts (name, phone) VALUES 
('Nguyễn Văn A', '0123456789'),
('Trần Thị B', '0987654321'),
('Phạm Minh C', '0912345678');
