<?php
require_once "config.php";
include 'csdl.php';

function registerUser($username, $email, $password) {
    global $link;
    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    $sql = "INSERT INTO users (username, email, pass) VALUES (?, ?, ?)";
    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_email, $param_password);
        $param_username = $username;
        $param_email = $email;
        $param_password = $password_hash;
        if(mysqli_stmt_execute($stmt)){
            return true;
        }
    }
    return false;
}

function loginUser($email, $password) {
    global $link;
    $sql = "SELECT id, username, pass FROM users WHERE email = ?";
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

function recordWaterUsage($user_id, $water_amount) {
    global $link;
    $sql = "INSERT INTO water_usages (user_id, water_amount) VALUES (?, ?)";
    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "id", $user_id, $water_amount);
        if(mysqli_stmt_execute($stmt)){
            return true;
        }
    }
    return false;
}

function getUserWaterUsage($user_id) {
    global $link;
    $sql = "SELECT water_amount, date FROM water_usages WHERE user_id = ?";
    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            return mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
    }
    return [];
}
?>
