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
    border-radius: 5px;
    text-decoration: none;
    color: white;
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
    <label for="current_password">Current Password</label>
    <input type="password" id="current_password" name="current_password" required>

    <label for="new_password">New Password</label>
    <input type="password" id="new_password" name="new_password" required>

    <label for="confirm_new_password">Confirm New Password</label>
    <input type="password" id="confirm_new_password" name="confirm_new_password" required>

    <input type="submit" value="Change Password">
    <a href="index.php" class="btn btn-secondary btn-block">Quay về trang chủ</a>
</form>

</body>
</html>
