<?php
function calculateTotalAmount($water_amount) {
    $rate_per_m3 = 3000; // Giá 10 đơn vị/m³
    return $water_amount * $rate_per_m3;
}

session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "database.php";

$water_amount = "";
$water_amount_err = "";
$total_amount = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["water_amount"]))){
        $water_amount_err = "Vui lòng nhập số nước.";
    } else {
        $water_amount = trim($_POST["water_amount"]);
    }
    
    if(empty($water_amount_err) && isset($_POST['calculate'])){
        // Sử dụng hàm calculateTotalAmount để tính tổng tiền
        $total_amount = calculateTotalAmount($water_amount);
    }

    if(empty($water_amount_err) && isset($_POST['submit'])){
        $total_amount = $water_amount * 3000; // Giá 10 đơn vị/m³
        if(recordWaterUsage($_SESSION["id"], $water_amount, $total_amount)){
            header("location: index.php");
        } else {
            echo "Something went wrong. Please try again later.";
        }
    }

    if(empty($water_amount_err) && isset($_POST['pay'])){
        $total_amount = $water_amount * 3000; // Giá 10 đơn vị/m³
        if(recordWaterUsage($_SESSION["id"], $water_amount, $total_amount)){
            header("location: invoice.php"); // Chuyển hướng đến trang in hóa đơn sau khi thanh toán
        } else {
            echo "Something went wrong. Please try again later.";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Water Usage - Water Billing System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
        }
        .wrapper {
            max-width: 500px;
            width: 100%;
            padding: 30px;
            background: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .has-error .form-control {
            border-color: #dc3545;
        }
        .help-block {
            color: #dc3545;
        }
        .wrapper h2 {
            margin-bottom: 20px;
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
    <div class="wrapper">
        <h2 class="text-center">Cập nhật số nước</h2>
        <p class="text-center">Giá: 3000 VND / 1 m³</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($water_amount_err)) ? 'has-error' : ''; ?>">
                <label>Số nước sử dụng (m³)</label>
                <input type="text" name="water_amount" class="form-control" value="<?php echo $water_amount; ?>">
                <span class="help-block"><?php echo $water_amount_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" name="calculate" class="btn btn-info btn-block" value="Tính tiền">
            </div>
            <?php if(!empty($total_amount)): ?>
                <div class="form-group">
                    <p class="text-center">Tổng Tiền: <?php echo $total_amount; ?> VND</p>
                </div>
            <?php endif; ?>
            <div class="form-group">
                <input type="submit" name="submit" class="btn btn-primary btn-block" value="Xác nhận">
            </div>
            <div class="form-group">
                <input type="submit" name="pay" class="btn btn-success btn-block" value="Thanh toán">
            </div>
            <div class="form-group">
                <a href="index.php" class="btn btn-secondary btn-block">Quay về trang chủ</a>
            </div>
        </form>
    </div>    
</body>
</html>
