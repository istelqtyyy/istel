<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if (isset($_POST["send"])) {
    $mail = new PHPMailer(true);

    // Check for required POST fields
    if (empty($_POST["recipient"]) || empty($_POST["subject"]) || empty($_POST["message"])) {
        echo "<script>alert('Please fill in all fields.'); document.location.href = 'send.php';</script>";
        exit;
    }

    try {
        // Enable debugging
        $mail->SMTPDebug = 2; // 0 = off (for production use), 1 = client messages, 2 = client and server messages
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'istelqtyyy@gmail.com'; // Your Gmail address
        $mail->Password = 'rotqqwlftnhhkuhr'; // Your Gmail app password
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        // Recipients
        $mail->setFrom('istelqtyyy@gmail.com', 'Your Name'); // Your name (optional)
        $mail->addAddress($_POST["recipient"]); // Recipient address
        $mail->Subject = $_POST["subject"]; // Subject
        $mail->Body = $_POST["message"]; // Message body

        // Send email
        $mail->send();
        echo "
        <script>
        alert('Sent Successfully');
        document.location.href = 'send.php';
        </script>
        ";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
