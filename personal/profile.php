<?php
session_start();
if (isset($_GET['test'])) {
    $aaa = $_GET['test'];
} else {
    $aaa = "info.php";
}
function generateUniqueString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $uniqueString = '';
    do {
        $uniqueString = '';
        for ($i = 0; $i < $length; $i++) {
            $uniqueString .= $characters[rand(0, $charactersLength - 1)];
        }
    } while (stringExistsInPrevious($uniqueString));

    return $uniqueString;
}

function stringExistsInPrevious($str) {
    global $previousStrings;
    return in_array($str, $previousStrings);
}
$previousStrings = array();
$newString = generateUniqueString(10);
$previousStrings[] = $newString;
error_reporting(E_ALL);
ini_set('display_errors', 1);
$servername = "localhost";
$username = "root";
$password = "Aeyi852022";
$dbname = "www";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
$conn->query("SET NAMES 'utf8mb4'");

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
$username = $_SESSION['username'];
$sql = "SELECT * FROM user WHERE username='$username'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
}
if (isset($_POST['save'])) {
    $bio = $_POST['bio'];
    $sql = "UPDATE user SET bio='$bio' WHERE username='$username'";
    $result = $conn->query($sql);
    $nickname = $_POST['nickname'];
    $sql = "UPDATE user SET nickname='$nickname' WHERE username='$username'";
    $result = $conn->query($sql);
    header("Refresh: 0");
}
if (isset($_FILES['file']) && isset($_FILES['file']['name'])) {
    if ($_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $file_name = $_FILES['file']['name'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_ext = strtolower(end(explode('.', $file_name)));
        $extensions = array("jpeg", "jpg", "png");
        if (in_array($file_ext, $extensions)) {
            $new_file_name = uniqid('', true) . '.' . $file_ext;
            $upload_path = "image/" . $new_file_name;
            if (move_uploaded_file($file_tmp, $upload_path)) {
                $imagePath = mysqli_real_escape_string($conn, $upload_path);
                $sql = "UPDATE user SET url='$imagePath' WHERE username='$username'";
                $conn->query($sql);
                header("Refresh: 0");
            } else {
                echo 'File upload failed.<br>';
            }
        } else {
            echo "Invalid file type.";
        }
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STARBOT</title>
    <script src="js/profile.js"></script>
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>
<div class="profile-container">
    <div id="menu" class="menu">
        <a href="#index.php/html">主页</a>
        <a href="javascript:changeProfile('info.php');">资料</a>
        <a href="javascript:changeProfile('settings.php');">设置</a>
        <a href="javascript:changeProfile('products.php');">产品</a>
        <a href="logout.php">登出</a>
    </div>
    <div id="profile-content">
        <div class="menu-toggle" id="menu-toggle" onclick="toggleMenu()">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>
        <br><br>
        <iframe src="window.php" id="window"></iframe>

    </div>
</div>
</body>
</html>
