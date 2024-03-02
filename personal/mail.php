<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'D:\phpStudy\PHPTutorial\WWW\PHPMailer-master\src\Exception.php';
require 'D:\phpStudy\PHPTutorial\WWW\PHPMailer-master\src\PHPMailer.php';
require 'D:\phpStudy\PHPTutorial\WWW\PHPMailer-master\src\SMTP.php';
function sendMail($to, $sub, $content){
    $mailer = new PHPMailer(true);
    try {
        $mailer->isSMTP();
        $mailer->Host = 'smtp.126.com';
        $mailer->SMTPAuth = true;
        $mailer->Username = 'starbot@126.com';
        $mailer->Password = 'AKPBNOMLENRJTRAE';
        $mailer->SMTPSecure = 'tls';
        $mailer->Port = 25;
        $mailer->setFrom('starbot@126.com', 'STAR 验证码');
        $mailer->addAddress($to);
        $mailer->isHTML(true);
        $mailer->CharSet = 'UTF-8';
        $mailer->Subject = $sub;
        $mailer->Body    = $content;
        $mailer->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $verificationCode = rand(100000, 999999);
    $subject = "验证码";
    $content = "您的验证码是：$verificationCode";
    if (sendMail($email, $subject, $content)) {
        $_SESSION['verification_code'] = $verificationCode;
        echo "验证码已发送至您的邮箱，请查收。";
    } else {
        echo "验证码发送失败，请稍后再试。";
    }
}
?>