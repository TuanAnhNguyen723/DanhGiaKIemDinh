<?php
// Thông tin kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Tạo cơ sở dữ liệu
$sql = "CREATE DATABASE IF NOT EXISTS water_billing";
if ($conn->query($sql) === TRUE) {
    // echo "Tạo cơ sở dữ liệu thành công<br>";
} else {
    echo "Lỗi khi tạo cơ sở dữ liệu: " . $conn->error;
}

// Sử dụng cơ sở dữ liệu vừa tạo
$conn->select_db("water_billing");

// Tạo bảng users
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($sql) === TRUE) {
    // echo "Tạo bảng users thành công<br>";
} else {
    echo "Lỗi khi tạo bảng users: " . $conn->error;
}

// Tạo bảng water_usages
$sql = "CREATE TABLE IF NOT EXISTS water_usages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    water_amount FLOAT NOT NULL,
    total_amount FLOAT NOT NULL, -- Thêm cột total_amount ở đây
    payment_status ENUM('unpaid', 'paid') DEFAULT 'unpaid', -- Thêm cột payment_status ở đây
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";
if ($conn->query($sql) === TRUE) {
    // echo "Tạo bảng water_usages thành công<br>";
} else {
    echo "Lỗi khi tạo bảng water_usages: " . $conn->error;
}

// Kiểm tra và thêm cột total_amount nếu chưa tồn tại
$columns = $conn->query("SHOW COLUMNS FROM water_usages LIKE 'total_amount'");
if ($columns->num_rows == 0) {
    $alter_sql = "ALTER TABLE water_usages ADD total_amount FLOAT NOT NULL";
    if ($conn->query($alter_sql) === TRUE) {
        // echo "Thêm cột total_amount thành công<br>";
    } else {
        echo "Lỗi khi thêm cột total_amount: " . $conn->error;
    }
}

// Kiểm tra và thêm cột payment_status nếu chưa tồn tại
$columns = $conn->query("SHOW COLUMNS FROM water_usages LIKE 'payment_status'");
if ($columns->num_rows == 0) {
    $alter_sql = "ALTER TABLE water_usages ADD payment_status ENUM('unpaid', 'paid') DEFAULT 'unpaid'";
    if ($conn->query($alter_sql) === TRUE) {
        // echo "Thêm cột payment_status thành công<br>";
    } else {
        echo "Lỗi khi thêm cột payment_status: " . $conn->error;
    }
}

// Đóng kết nối
$conn->close();
?>
