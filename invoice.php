<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "database.php";

$user_id = $_SESSION["id"];
$water_usages = getUserWaterUsage($user_id);
$total_water = 0;
$total_amount = 0;
foreach ($water_usages as $usage) {
    $total_water += $usage['water_amount'];
    $total_amount += $usage['total_amount'];
}

// Lấy thông tin người dùng đã thanh toán
$sql = "
    SELECT users.username, SUM(water_usages.water_amount) AS total_water, SUM(water_usages.total_amount) AS total_amount
    FROM users
    JOIN water_usages ON users.id = water_usages.user_id
    WHERE water_usages.payment_status = 'paid'
    GROUP BY users.id, users.username
";
$result = mysqli_query($link, $sql);

$paid_users = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $paid_users[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - Water Billing System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .wrapper {
            max-width: 600px;
            width: 100%;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .invoice-header h2 {
            margin: 0;
            font-size: 28px;
            color: #333;
        }
        .invoice-body p {
            font-size: 18px;
            margin: 10px 0;
            color: #555;
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
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 10px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
    <script>
        function showInvoiceDetails() {
            var modal = document.getElementById('invoiceModal');
            var span = document.getElementsByClassName('close')[0];
            modal.style.display = 'block';

            span.onclick = function() {
                modal.style.display = 'none';
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            }
        }
    </script>
</head>
<body>
    <div class="wrapper">
        <div class="invoice-header">
            <h2>Invoice</h2>
        </div>
        <div class="invoice-body">
            <p><strong>Tổng số nước tiêu thụ:</strong> <?php echo $total_water; ?> m³</p>
            <p><strong>Tổng số tiền phải trả:</strong> <?php echo number_format($total_amount, 0, ',', '.'); ?> VND</p>
        </div>
        <div class="form-group text-center">
            <a href="index.php" class="btn btn-secondary">Quay về trang chủ</a>
            <button onclick="showInvoiceDetails()" class="btn btn-primary">In hóa đơn</button>
        </div>
    </div>

    <!-- Invoice Modal -->
    <div id="invoiceModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Chi tiết hóa đơn</h2>
            <p><strong>Tổng số nước tiêu thụ:</strong> <?php echo $total_water; ?> m³</p>
            <p><strong>Tổng số tiền phải trả:</strong> <?php echo number_format($total_amount, 0, ',', '.'); ?> VND</p>

            <h3>Thông tin người dùng đã thanh toán</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Tổng số nước (m³)</th>
                        <th>Tổng tiền (VND)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($paid_users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['total_water']); ?></td>
                            <td><?php echo number_format($user['total_amount'], 0, ',', '.'); ?> VND</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
