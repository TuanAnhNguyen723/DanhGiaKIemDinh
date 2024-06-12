<?php
require_once "config.php";
include 'csdl.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['updateProduct'])) {
        // Cập nhật sản phẩm
        $id = $_POST['userId'];
        $username = $_POST['userName'];
        $useremail = $_POST['userEmail'];

        $sql = "UPDATE users SET username='$username', email='$useremail' WHERE id='$id'";

        if ($link->query($sql) === TRUE) {
            echo "Product updated successfully";
        } else {
            echo "Error updating product: " . $link->error;
        }
    } elseif (isset($_POST['deleteProduct'])) {
        // Xóa sản phẩm
        $id = $_POST['userId'];
        $sql = "DELETE FROM users WHERE id = '$id'";
        if ($conn->query($sql) === TRUE) {
            echo "Xóa thành công";
            http_response_code(200);
        } else {
            http_response_code(500);
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE ID='$id'";
    $result = $link->query($sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        echo json_encode($product);
    } else {
        echo json_encode(['error' => 'Product not found']);
    }
    exit;
}

function registerUser($username, $email, $password) {
    global $link;
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Check if username contains "admin" to determine the role
    $role = (strpos($username, 'admin') !== false) ? 'admin' : 'user';

    $sql = "INSERT INTO users (username, email, password    , role) VALUES (?, ?, ?, ?)";
    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "ssss", $param_username, $param_email, $param_password, $param_role);
        $param_username = $username;
        $param_email = $email;
        $param_password = $password_hash;
        $param_role = $role;
        if(mysqli_stmt_execute($stmt)){
            return true;
        }
    }
    return false;
}

function emailExists($email) {
    global $link;

    $stmt = $link->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    return $stmt->num_rows > 0;
}

function loginUser($email, $password) {
    global $link;
    $sql = "SELECT id, username, password FROM users WHERE email = ?";
    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "s", $param_email);
        $param_email = $email;
        if(mysqli_stmt_execute($stmt)){
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_num_rows($stmt) == 1){
                mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                if(mysqli_stmt_fetch($stmt)){
                    if(password_verify($password, $hashed_password)){
                        session_start();
                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $id;
                        $_SESSION["username"] = $username;
                        return true;
                    }
                }
            }
        }
    }
    return false;
}

// Hàm để ghi lại việc sử dụng nước
function recordWaterUsage($user_id, $water_amount, $total_amount){
    global $link;
    $sql = "INSERT INTO water_usages (user_id, water_amount, total_amount) VALUES (?, ?, ?)";
    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "idd", $user_id, $water_amount, $total_amount);
        if(mysqli_stmt_execute($stmt)){
            return true;
        }
    }
    return false;
}

function getUserWaterUsage($user_id) {
    global $link;
    $sql = "SELECT water_amount, total_amount FROM water_usages WHERE user_id = ?";
    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $water_usages = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $water_usages[] = $row;
        }
        mysqli_stmt_close($stmt);
        return $water_usages;
    }
    return [];
}

