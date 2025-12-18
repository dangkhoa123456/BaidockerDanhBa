# 📋 Quản lý Danh bạ - Hướng dẫn Cài đặt

## 1. Chuẩn bị Môi trường

- **PHP**: Phiên bản 7.0+ (khuyên 7.4+)
- **MySQL**: Phiên bản 5.7+
- **Web Server**: Apache hoặc Nginx (khuyên dùng XAMPP/WAMP/MAMP)

## 2. Tạo Database

### Cách 1: Sử dụng phpMyAdmin

1. Mở `http://localhost/phpmyadmin`
2. Nhấp vào **"New"** để tạo database mới
3. Nhập tên: `contact_db`
4. Chọn **"Create"**
5. Chọn vào database `contact_db` vừa tạo
6. Vào tab **"SQL"** và chạy SQL sau:

```sql
CREATE TABLE contacts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL
);
```

### Cách 2: Sử dụng MySQL Command Line

Mở Terminal/Command Prompt và chạy:

```bash
mysql -u root -p
```

Sau đó nhập password (mặc định không có mật khẩu cho root):

```sql
CREATE DATABASE contact_db;
USE contact_db;

CREATE TABLE contacts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL
);
```

Sau khi chạy xong, dùng `exit` để thoát.

## 3. Cấu hình File

### Backend (backend/api.php)

Mở file `backend/api.php` và kiểm tra thông tin kết nối database:

```php
$host = 'localhost';     // Máy chủ MySQL
$db = 'contact_db';      // Tên database
$user = 'root';          // Tên user MySQL
$pass = '';              // Mật khẩu (mặc định rỗng)
```

**Điều chỉnh nếu cần** dựa trên cấu hình của bạn.

### Frontend (frontend/script.js)

Kiểm tra đường dẫn API:

```javascript
const API_URL = 'http://localhost/Project1MNM/backend/api.php';
```

**Lưu ý**: Thay đổi theo đường dẫn thực tế nếu project ở vị trí khác.

## 4. Chạy Ứng dụng

### Nếu dùng XAMPP:

1. Đặt folder `Project1MNM` vào `htdocs` của XAMPP
   ```
   C:\xampp\htdocs\Project1MNM\
   ```

2. Khởi động Apache và MySQL từ XAMPP Control Panel

3. Mở trình duyệt:
   ```
   http://localhost/Project1MNM/index.html
   ```

### Nếu dùng PHP built-in server:

```bash
cd d:\Project1MNM
php -S localhost:8000
```

Sau đó mở:
```
http://localhost:8000/frontend/index.html
```

## 5. Kiểm tra Hoạt động

- ✅ Tìm kiếm: Nhập tên để lọc danh bạ
- ✅ Thêm: Nhập tên + SĐT, nhấp "Lưu"
- ✅ Sửa: Nhấp nút "Sửa", chỉnh sửa thông tin
- ✅ Xóa: Nhấp nút "Xóa", xác nhận xóa

## 6. Troubleshooting

| Vấn đề | Giải pháp |
|--------|----------|
| **CORS Error** | Kiểm tra `Access-Control-Allow-Origin` trong api.php |
| **Database Connection Failed** | Kiểm tra thông tin `$host`, `$db`, `$user`, `$pass` |
| **404 Not Found** | Kiểm tra đường dẫn file và URL trong script.js |
| **Không có dữ liệu** | Kiểm tra xem bảng `contacts` đã được tạo chưa |

## 7. Cấu trúc Thư mục

```
Project1MNM/
├── backend/
│   └── api.php           (API xử lý CRUD)
├── frontend/
│   ├── index.html        (Giao diện)
│   └── script.js         (JavaScript logic)
└── DATABASE.md           (File hướng dẫn này)
```

## 8. Tài liệu API

### GET - Lấy danh sách
```
GET /api.php
GET /api.php?q=Nguyễn    (Tìm theo tên)
```

### POST - Thêm mới
```
POST /api.php
Body: {"name": "Nguyễn Văn A", "phone": "0123456789"}
```

### PUT - Cập nhật
```
PUT /api.php
Body: {"id": 1, "name": "Nguyễn Văn B", "phone": "0987654321"}
```

### DELETE - Xóa
```
DELETE /api.php?id=1
```

---

**Chúc bạn thành công! 🎉**
