<style>
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
        width: 65%;
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
    #nickname {
        display: inline-block;
        position: relative;
        font-size: 2.5em;
    }
    textarea[name="bio"] {
        width: 65%;
        display: inline-block;
        position: relative;
        height: 4em;
    }
    textarea[name="nickname"] {
        width: 200px;
        height: 1em;
    }
    @media only screen and (max-width: 1024px) {
        #nickname {
            display: inline-block;
            position: relative;
            font-size: 1em;
        }
        textarea[name="nickname"] {
            font-size: 1em;
            width: 60%;
        }
        button {
            position: relative;
            display: inline-block;
            vertical-align: top;
            background-color: #0078D4;
            color: #fff;
            cursor: pointer;
            padding: 0 0;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            font-size: 1em;
        }
    }
</style>
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
        let w = document.getElementById("window");
        w.src=type;
        setTimeout(function() {
            w.contentWindow.location.reload();
        }, 100);
        //let window=document.getElementById("profile-content");
        //window.innerHTML="<" + "?php include" + type + "?>";
    }
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
<form method="post" action="" enctype="multipart/form-data">
    <div class="profile-picture" onclick="chooseFile()">
        <img id="profileImg" src="<?php
        if (isset($user['url'])) {
            echo $user['url'];
        } else {
            echo "user.png";
        }
        ?>">
        <input type="file" id="fileInput" style="display: none;" accept="image/*" onchange="previewImage(this)" name="file">
    </div>
    <button type="submit">提交头像</button>
    <p style="color: red;">点击当前头像即可上传</p>
</form>
<div id="nickname"><?= $user['nickname']; ?></div>
<h4 id="editNickname" style="display: inline-block; position: relative; color: #20A647; cursor: pointer; font-size: 13px;">修改昵称</h4>
<form method="post" action="" style="display: none; position: relative;" id="nicknameInput">
    <textarea name="nickname" placeholder="此处填写修改后的昵称"></textarea>
    <button type="submit" name="save">保存</button>
</form>
<p>用户名: <?= $_SESSION['username']; ?></p>
<p>邮箱: <?= $user['email']; ?></p>
<p>我的简介: <?php if (isset($user['bio'])) {echo $user['bio'];} else {echo "[当前无简介]";} ?></p>
<p>修改我的信息：</p>
<h4 id="editBio" style="color: #20A647; cursor: pointer;">修改简介</h4>
<form method="post" action="" style="display: none;" id="bioInput">
    <textarea  name="bio" placeholder="此处填写修改后的简介"></textarea><br>
    <button type="submit" name="save" style="position: relative;">保存</button>
</form>