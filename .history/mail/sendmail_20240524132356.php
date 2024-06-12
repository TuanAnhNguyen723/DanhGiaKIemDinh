<?php

include"../mail/PHPMailer/src/PHPMailer.php";
include"../mail/PHPMailer/src/Exception.php";
include"../mail/PHPMailer/src/OAuth.php";
include"../mail/PHPMailer/src/POP3.php";
include"../mail/PHPMailer/src/SMTP.php";
 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
print_r($mail);

?>