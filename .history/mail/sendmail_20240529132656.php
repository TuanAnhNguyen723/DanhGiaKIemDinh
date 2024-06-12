<?php
// Include PHPMailer
require '../mail/PHPMailer/src/PHPMailer.php';
require '../mail/PHPMailer/src/Exception.php';
require '../mail/PHPMailer/src/OAuth.php';
require '../mail/PHPMailer/src/POP3.php';
require '../mail/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Kiểm tra nếu form đã được submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Tạo đối tượng PHPMailer
    $mail = new PHPMailer(true);
    try {
        // Cài đặt máy chủ
        $mail->SMTPDebug = 0; // Tắt debug để tránh hiện ra quá nhiều thông tin
        $mail->isSMTP();                                      
        $mail->Host = 'smtp.gmail.com';  
        $mail->SMTPAuth = true;                               
        $mail->Username = 'nguyentuananhlccl@gmail.com';                
        $mail->Password = 'byud iscy aszt pudc';                           
        $mail->SMTPSecure = 'tls';                            
        $mail->Port = 587;                                    

        // Đặt mã hóa
        $mail->CharSet = 'UTF-8';

        // Người gửi
        $mail->setFrom('nguyentuananhlccl@gmail.com', 'Mailer');

        // Lấy thông tin từ form
        $to_email = $_POST['to_email'];
        $to_name = $_POST['to_name'];
        $subject = $_POST['subject'];
        $body = $_POST['body'];

        // Người nhận
        $mail->addAddress($to_email, $to_name);     
        
        // Thêm CC nếu có
        if (!empty($_POST['cc_email'])) {
            $mail->addCC($_POST['cc_email']);
        }

        
        // Nội dung email
        $mail->isHTML(true);                                 
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        echo '<script>alert("Gửi Mail thành công")</script>';
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Send Email</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }
        h2 {
            margin-top: 0;
            font-size: 24px;
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        input[type="text"],
        input[type="email"],
        textarea {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        input[type="submit"],
        .back-button {
            background-color: #28a745;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            margin-bottom: 10px;
        }
        input[type="submit"]:hover,
        .back-button:hover {
            background-color: #218838;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .back-button {
            background-color: #007bff;
            margin-top: 10px;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Send Email</h2>
        <form method="post" action="">
            <div class="form-group">
                <label for="to_email">To Email:</label>
                <input type="email" id="to_email" name="to_email" required>
            </div>
            <div class="form-group">
                <label for="to_name">To Name:</label>
                <input type="text" id="to_name" name="to_name" required>
            </div>
            <div class="form-group">
                <label for="subject">Subject:</label>
                <input type="text" id="subject" name="subject" required>
            </div>
            <div class="form-group">
                <label for="body">Body:</label>
                <br>
                <textarea id="body" name="body" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="cc_email">CC Email:</label>
                <input type="email" id="cc_email" name="cc_email">
            </div>
            <input type="submit" value="Send Email">
        </form>
        <button class="back-button" onclick="window.location.href='/TESTER/index.php'">Quay lại trang chủ</button>
    </div>
</body>
</html>
