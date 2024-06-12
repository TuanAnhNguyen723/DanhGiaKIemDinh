<?php
// session_start();
include 'config.php';
// Khai báo biến lỗi
$oldPass_err = $newPass_err = $confirm_err = "";

// Kiểm tra xem form có được submit hay không
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $oldPass = $_POST['oldPass'];
    $newPass = $_POST['newPass'];
    $confirm = $_POST['confirm'];
    // Lấy username từ session
    $username = $_SESSION['username'];

    // Truy vấn lấy mật khẩu hiện tại từ database
    $sql = "SELECT password FROM users WHERE username = ?";
    $stmt = $link->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();

    // Kiểm tra mật khẩu cũ
    if (password_verify($oldPass, $hashed_password)) {
        // Kiểm tra mật khẩu mới và xác nhận mật khẩu
        if ($newPass === $confirm) {
            // Mã hóa mật khẩu mới
            $new_hashed_password = password_hash($newPass, PASSWORD_DEFAULT);

            // Cập nhật mật khẩu mới vào database
            $update_sql = "UPDATE users SET password = ? WHERE username = ?";
            $update_stmt = $link->prepare($update_sql);
            $update_stmt->bind_param("ss", $new_hashed_password, $username);

            if ($update_stmt->execute()) {
                echo "<script>alert('Đổi mật khẩu thành công');</script>";
            } else {
                echo "<script>alert('Đổi mật khẩu thất bại');</script>";
            }
        } else {
            $confirm_err = "Mật khẩu mới hoặc xác nhận mật khẩu không chính xác";
        }
    } else {
        $oldPass_err = "Mật khẩu cũ không đúng";
    }

    $stmt->close();
    $update_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .wrapper {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .error {
            color: #dc3545;
            margin-bottom: 10px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Đổi mật khẩu</h2>
        <form action="changepass.php" method="post">
            <div class="form-group">
                <label for="oldPass">Mật khẩu cũ</label>
                <input type="password" id="oldPass" name="oldPass" class="form-control" required>
                <div class="error"><?php echo $oldPass_err ?? ''; ?></div>
            </div>

            <div class="form-group">
                <label for="newPass">Mật khẩu mới</label>
                <input type="password" id="newPass" name="newPass" class="form-control" required>
                <div class="error"><?php echo $newPass_err ?? ''; ?></div>
            </div>

            <div class="form-group">
                <label for="confirm">Xác nhận mật khẩu mới</label>
                <input type="password" id="confirm" name="confirm" class="form-control" required>
                <div class="error"><?php echo $confirm_err ?? ''; ?></div>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary btn-block" value="Đổi mật khẩu">
            </div>
            <a href="index.php" class="btn btn-secondary btn-block">Quay về trang chủ</a>
        </form>
    </div>
</body>
</html>
