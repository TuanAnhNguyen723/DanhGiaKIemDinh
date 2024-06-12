<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Water Billing System</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        nav {
            background-color: #333;
        }
        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        nav ul li {
            float: left;
        }
        nav ul li a {
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }
        nav ul li a:hover {
            background-color: #ddd;
            color: #333;
        }
    </style>
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                

                <?php
                // Include database connection
                include 'config.php';

                // Query database to get user role
                $sql = "SELECT role FROM users WHERE id = ?";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $role);
                    mysqli_stmt_fetch($stmt);

                    // If user role is admin, display "Quản lý người dùng" link
                    if ($role === "admin") {
                        echo '<li><a href="invoice.php">In hóa đơn</a></li>';
                        echo '<li><a href="quanly.php">Quản lý người dùng</a></li>';
                    }else if ($role === "user") {
                        echo '<li><a href="update_water.php">Cập nhật số nước</a></li>';
                    }

                    mysqli_stmt_close($stmt);
                }
                ?>
                <li><a href="changepass.php">Đổi mật khẩu</a></li>
                <li><a href="logout.php">Đăng xuất</a></li>
            <?php else: ?>
                <li><a href="login.php">Đăng nhập</a></li>
                <li><a href="register.php">Đăng ký</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</body>
</html>
