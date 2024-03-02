<?php
function showError($message) {
    ?><div style='position: fixed; top: 0; left: 0; width: 100%; background-color: #ff3333; color: #fff; text-align: center; padding: 10px; font-weight: bold;'><?=$message?></div><?php
}
function showTip($message){
    ?><div style='position: fixed; top: 0; left: 0; width: 100%; background-color: #20A647; color: #fff; text-align: center; padding: 10px; font-weight: bold;'><?=$message?></div><?php
}
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'D:\phpStudy\PHPTutorial\WWW\PHPMailer-master\src\Exception.php';
require 'D:\phpStudy\PHPTutorial\WWW\PHPMailer-master\src\PHPMailer.php';
require 'D:\phpStudy\PHPTutorial\WWW\PHPMailer-master\src\SMTP.php';
$servername = "localhost";
$username = "root";
$password = "Aeyi852022";
$database = "www";
$conn = new mysqli($servername, $username, $password, $database);
$conn->set_charset("utf8mb4");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->query("SET NAMES 'utf8mb4'");
if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] < 3 * 60 * 60)) {
    if (isset($_GET['dnext'])){
        showTip("检测到您已经登录。5秒后跳转... &nbsp;&nbsp; <a href='logout.php' style='color:white;'>切换账号</a>");
        ?> <meta http-equiv="refresh" content="5; <?=$_GET['dnext']?>" /> <?php
    } else {
        showTip("检测到您已经登录。5秒后跳转... &nbsp;&nbsp; <a href='logout.php' style='color:white;'>切换账号</a>");
        ?> <meta http-equiv="refresh" content="5; index.php" /> <?php
    }
} else if (isset($_GET['dnext'])) showError("请先登录。");
if (isset($_POST['login'])) {
    $loginUsername = $_POST['loginUsername'];
    $loginPassword = $_POST['loginPassword'];
    $checkUser = "SELECT * FROM user WHERE username=? OR email=?";
    $stmt = $conn->prepare($checkUser);
    $stmt->bind_param("ss", $loginUsername, $loginUsername);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($loginPassword, $row['password'])) {
            $_SESSION['username'] = $loginUsername;
            $_SESSION['login_time'] = time();
            showTip("登录成功。3秒后跳转...");
            if (isset($_GET['dnext'])) echo "<meta http-equiv='refresh' content='3; {$_GET['dnext']}' />";
            else echo "<meta http-equiv='refresh' content='3; profile.php' />";
        } else {
            showError("无效的密码或用户名/电子邮件");
        }
    } else {
        showError("无效的密码或用户名/电子邮件");
    }
}
if (isset($_POST['register'])) {
    if (!isset($_POST['capt'])) {
        showError("请输入验证码");
    } else if ($_SESSION['verification_code'] != $_POST['capt']) {
        showError("验证码错误");
    } else {
        $username = $_POST['username'];
        $nickname = $_POST['nickname'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $email = $_POST['email'];
        $checkDuplicate = "SELECT * FROM user WHERE username=? OR email=?";
        $stmtDuplicate = $conn->prepare($checkDuplicate);
        $stmtDuplicate->bind_param("sss", $username, $email, $nickname);
        $stmtDuplicate->execute();
        $duplicateResult = $stmtDuplicate->get_result();
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.126.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'starbot@126.com';
            $mail->Password   = 'AKPBNOMLENRJTRAE';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 25;
            $mail->setFrom('starbot@126.com', 'STAR 工作室');
            $mail->addAddress($_POST['email']);
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'STAR-账号注册';
            $mail->Body    = '您已成功使用当前邮箱注册STAR账号！';
            $mail->send();
        } catch (Exception $e) {
            if ($mail->ErrorInfo == 'SMTP Error: Could not authenticate.SMTP server error: QUIT command failed') {
                $mail->ErrorInfo = "";
            } else {
                echo "<div style='position: fixed; top: 37px; left: 0; width: 100%; background-color: #20A647; color: #fff; text-align: center; padding: 10px; font-weight: bold;'>{$mail->ErrorInfo}</div>";
            }
        }
    }
    if ($duplicateResult->num_rows > 0) {
        showError("用户名或邮箱已被占用。请重新再试。");
    } else {
        $sql = "INSERT INTO user (username, password, email, nickname)
                    VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $username, $password, $email, $nickname);
        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cab 博客-注册/登录 </title>
    <link rel="stylesheet" href="/css/login.css" />
</head>
<body>
<div id="overlay" style="position:fixed;width:100% !important;height:100% !important;background-color:rgba(0,0,0,0.3);display:none;"></div>
<div class="container">
    <div class="tabs">
        <div class="tab active" data-tab="login" onclick="showTab('login')">登录</div>
        <div class="tab" data-tab="register" onclick="showTab('register')">注册</div>
    </div>
    <form method="post" action="<?php
    echo "login.php";
    if (isset($_GET['dnext']))
        echo "?dnext=" . $_GET['dnext'];
    ?>">
        <div class="form-container" id="loginForm">
            <div class="input-group">
                <label for="loginUsername">用户名或电子邮箱：</label><br>
                <input type="text" name="loginUsername" id="loginUsername" placeholder="在此输入你的用户名或电子邮箱" required>
            </div>
            <div class="input-group">
                <label for="loginPassword">密码：</label><br>
                <input type="password" name="loginPassword" id="loginPassword" placeholder="请在此输入您的密码" required>
            </div>
            <input type="checkbox" name="ensure" id="ensure" required /> 同意并遵守 <a target="_blank" href="../CommunityNorms.php" style="text-align:center; margin:0 auto; text-decoration:none; color:blue;" onmouseover="this.style.color='#020262'" onmouseout="this.style.color='#0000FF'"><strong>社区规范</strong></a>
            <div class="input-group">
                <button type="submit" name="login">登录</button><br><br>
                <a href="forgot.php" target="_blank" style="text-align:center; margin:0 auto; text-decoration:none; color:blue;">忘记密码？</a>
            </div>
        </div>
    </form>
    <form method="post" action="<?php
    echo "login.php";
    if (isset($_GET['dnext']))
        echo "?dnext=" . $_GET['dnext'];
    ?>">
        <div class="form-container" id="registerForm" style="display: none;">
            <div class="input-group">
                <label for="username">用户名<span style="color: red;">*</span>: </label>
                <input type="text" name="username" id="username" placeholder="请在此输入您的用户名" required>
            </div>
            <div class="input-group">
                <label for="nickname">昵称<span style="color: red;">*</span>: </label>
                <input type="text" name="nickname" id="nickname" placeholder="请在此输入您的昵称" required>
            </div>
            <div class="input-group">
                <label for="password">密码<span style="color: red;">*</span>: </label>
                <input type="password" name="password" id="password" placeholder="请在此输入您的密码" required>
            </div>
            <div class="input-group">
                <label for="email">邮箱<span style="color: red;">*</span>: </label>
                <input type="email" name="email" id="email" placeholder="输入邮箱" required>
            </div>
            <div class="input-group">
                <button type="button" id="sendVerificationButton" onclick="sendVerification()">发送验证码</button>
            </div>
            <div class="input-group">
                <label for="capt">验证码<span style="color: red;">*</span>: </label>
                <input type="text" name="capt" id="capt" placeholder="输入获取的验证码" required>
            </div>
            <input type="checkbox" name="ensure" id="ensure" required /> 同意并遵守 <a href="../CommunityNorms.php" style="color:blue; text-align:center; margin:0 auto; text-decoration:none;" onmouseover="this.style.color='#020262'" onmouseout="this.style.color='#0000FF'"><strong>社区规范</strong></a>
            <div class="input-group">
                <button type="submit" name="register">注册</button><br><br><br><br>
            </div>
        </div>
    </form>
</div>
<script>
    function sendVerification() {
        var email = document.getElementById('email').value;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'mail.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var response = xhr.responseText;
                if (response != '') {
                    alert(response);
                }
            }
        };
        xhr.send('email=' + email);
    }
</script>
<script>
    function showTab(tabName) {
        var loginForm = document.getElementById('loginForm');
        var registerForm = document.getElementById('registerForm');
        var loginTab = document.querySelector('.tab[data-tab="login"]');
        var registerTab = document.querySelector('.tab[data-tab="register"]');
        loginForm.style.display = 'none';
        registerForm.style.display = 'none';
        document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
        if (tabName === 'login') {
            loginForm.style.display = 'block';
            loginTab.classList.add('active');
        } else if (tabName === 'register') {
            registerForm.style.display = 'block';
            registerTab.classList.add('active');
        }
    }
    document.querySelector('.tab:nth-child(1)').addEventListener('touchstart', function() {
        showTab('login');
    });
    document.querySelector('.tab:nth-child(2)').addEventListener('touchstart', function() {
        showTab('register');
    });
</script>
<footer class="footer-container">
    <p>&copy; 2024 <a href="http://www.2b2t.cab/" target="_blank" style="text-decoration: none; color: blue;">Cab</a> All Rights Reserved.</p>
</footer>
</body>
</html>
