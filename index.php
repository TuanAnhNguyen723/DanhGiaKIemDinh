<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Water Billing System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f4f4;
        }
        .wrapper {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .wrapper h2 {
            margin-bottom: 20px;
        }
        .welcome-message {
            font-size: 1.2em;
            margin-bottom: 20px;
        }
        .btn-custom {
            background-color: #007bff;
            border-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            font-size: 1em;
        }
    </style>
</head>
<body>
    <?php include('header.php'); ?>
    <div class="wrapper">
        <h2>Welcome</h2>
        <?php
        if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
            echo "<p class='welcome-message'>Xin chào, " . htmlspecialchars($_SESSION["username"]) . ". Chào mừng bạn tới trang web.</p>";
            echo "<a href='logout.php' class='btn btn-custom'>Đăng xuất</a>";
        } else {
            echo "<p class='welcome-message'>Xin vui lòng<a href='login.php'> Đăng nhập</a> để truy cập vào hệ thống.</p>";
        }
        ?>
    </div>
    <?php include('footer.php'); ?>
</body>
</html>
