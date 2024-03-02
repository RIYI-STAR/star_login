<?php

session_start();
if (isset($_GET['test'])) {
    $aaa = $_GET['test'];
} else {
    $aaa = "info.php";
}
function generateUniqueString($length)
{
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

function stringExistsInPrevious($str)
{
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
    $nickname = $_POST['nickname'];
    $bio = $_POST['bio'];
    if (isset($bio)) {

        $sql = "UPDATE user SET bio='$bio' WHERE username='$username'";
        $result = $conn->query($sql);
    }
    if (isset($nickname)) {

        $sql = "UPDATE user SET nickname='$nickname' WHERE username='$username'";
        $result = $conn->query($sql);
    }
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


if (isset($_GET['test'])) {
    $aaa = $_GET['test'];
} else {
    $aaa = "info.php";
}
//include $aaa;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>个人主页</title>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var bioInput = document.getElementById("bioInput");
            var nicknameInput = document.getElementById("nicknameInput");
            var editNickname = document.getElementById("editNickname");
            var editBio = document.getElementById("editBio");
            editNickname.addEventListener("click", function() {
                nicknameInput.style.display = "inline-block";
                editNickname.style.display = "none";
            });
            editBio.addEventListener("click", function() {
                bioInput.style.display = "block";
                editBio.style.display = "none";
            });
        });
    </script>
    <!-- <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }
        .profile-container {
            display: flex;
            max-width: 1000px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .menu {
            background-color: #0078D4;
            padding: 50px;
            border-radius: 0 10px 10px 0;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: flex-start;
            width: 200px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            z-index: 1000;
            transition: transform 0.3s ease;
            transform: translateX(-100%);
        }
        .menu a {
            color: #fff;
            text-decoration: none;
            margin-bottom: 20px;
            font-size: 20px;
            padding: 15px;
            transition: all 0.3s ease;
        }
        .menu a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        #profile-content {
            padding: 50px;
            position: relative;
            width: calc(100% - 200px);
        }
        .profile-picture {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 20px;
        }
        .profile-picture img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        /* h2 {
            text-align: center;
            color: #0078D4;
            font-size: 32px;
            margin-bottom: 20px;
        } */
        p {
            color: #666;
            font-size: 18px;
            margin-bottom: 10px;
        }
        textarea {
            width: calc(100% - 22px);
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            resize: none;
            font-size: 16px;
            margin-bottom: 10px;
        }
        button[type="submit"] {
            position: relative;
            display: inline-block;
            vertical-align: top;
            background-color: #0078D4;
            color: #fff;
            cursor: pointer;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            font-size: 16px;
        }
        button[type="submit"]:hover {
            background-color: #005a9e;
        }
        .menu-toggle {
            display: block;
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 9999;
            cursor: pointer;
        }
        .menu-toggle .bar {
            width: 30px;
            height: 5px;
            background-color: #20A647;
            margin: 6px 0;
            transition: 0.4s;
        }
        .menu-open .bar:nth-child(1) {
            transform: rotate(-45deg) translate(-9px, 6px);
        }
        .menu-open .bar:nth-child(2) {
            opacity: 0;
        }
        .menu-open .bar:nth-child(3) {
            transform: rotate(45deg) translate(-8px, -8px);
        }
        @media only screen and (max-width: 768px) {
            #profile-content {
                width: 100%;
            }
        }
        .menu a.active {
            background-color: rgba(255, 255, 255, 0.1);
        }
        #editNickname {
            color: #20A647;
            cursor: pointer;
            font-size: 13px;
            margin-top: 10px;
            display: inline-block;
        }
        #nicknameInput {
            display: none;
            margin-top: 10px;
        }
    </style> -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var menuLinks = document.querySelectorAll(".menu a");
            var menuToggle = document.getElementById("menu-toggle");
            menuLinks.forEach(function(link) {
                link.addEventListener("click", function(event) {
                    event.preventDefault();
                    menuLinks.forEach(function(link) {
                        link.classList.remove("active");
                    });
                    this.classList.add("active");
                    var href = this.getAttribute("href");
                    window.location.href = href;
                });
            });
            document.addEventListener("click", function(event) {
                if (!event.target.closest(".menu") && !event.target.closest("#menu-toggle")) {
                    document.getElementById("menu").style.transform = "translateX(-100%)";
                    menuToggle.classList.remove("menu-open");
                    menuLinks.forEach(function(link) {
                        link.classList.remove("active");
                    });
                }
            });
        });
        function toggleMenu() {
            var menu = document.getElementById("menu");
            var toggle = document.getElementById("menu-toggle");
            if (menu.style.transform === "translateX(-100%)") {
                menu.style.transform = "translateX(0)";
                toggle.classList.add("menu-open");
            } else {
                menu.style.transform = "translateX(-100%)";
                toggle.classList.remove("menu-open");
            }
        }
        function chooseFile() {
            document.getElementById("fileInput").click();
        }
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById("profileImg").src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        function changeProfile(type) {
            //let w = document.getElementById("profile-content");
            let xhr = new XMLHttpRequest();
            xhr.open("GET", "#/test=" + type);
            xhr.send();
            //let window=document.getElementById("profile-content");
            //window.innerHTML="<" + "?php include" + type + "?>";
        }
    </script>
</head>
<body>
<? include $aaa; ?>
</body>
<!-- <body>
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
    </div>
</div>
</body> -->
</html>

