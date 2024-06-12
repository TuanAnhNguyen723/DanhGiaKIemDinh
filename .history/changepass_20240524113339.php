<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Change Password</title>
<style>
    body {
        font-family: Arial, sans-serif;
    }
    form {
        width: 300px;
        margin: auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    input[type="password"],
    input[type="submit"] {
        display: block;
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
    input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        cursor: pointer;
    }
    input[type="submit"]:hover {
        background-color: #45a049;
    }
    .btn-block {
    padding: 5px;
    border-radius: 5px;
    text-decoration: none;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    }

    .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            margin-top: 10px;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
</style>
</head>
<body>

<h2>Change Password</h2>

<form action="changepass.php" method="post">
    <label for="oldPass">Current Password</label>
    <input type="password" id="oldPass" name="oldPass" required>

    <label for="newPass">New Password</label>
    <input type="password" id="newPass" name="newPass" required>

    <label for="confirm">Confirm New Password</label>
    <input type="password" id="confirm" name="confirm" required>

    <input style="font-size: 15px;" type="submit" value="Change Password">
    <a href="index.php" class="btn btn-secondary btn-block">Quay về trang chủ</a>
</form>

</body>

<?php
// Kiểm tra xem form có được submit hay không
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $oldPass = $_POST['oldPass'];
    $newPass = $_POST['newPass'];
    $confirm = $_POST['confirm'];
    // Lấy username từ session
    $username = $_SESSION['username'];

    // Truy vấn lấy mật khẩu hiện tại từ database
    $sql = "SELECT Pass FROM account WHERE Username = ?";
    $stmt = $conn->prepare($sql);
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
            $update_sql = "UPDATE account SET Pass = ? WHERE Username = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ss", $new_hashed_password, $username);

            if ($update_stmt->execute()) {
                echo "<script>alert('Password changed successfully');</script>";
            } else {
                echo "<script>alert('Password change failed');</script>";
            }
        } else {
            echo "<script>alert('New password and confirm password do not match');</script>";
        }
    } else {
        echo "<script>alert('Old password is incorrect');</script>";
    }

    $stmt->close();
    $update_stmt->close();
}

?>
</html>
