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
    w.innerHTML = ""
    w.src="window.php?test=" + type;
    setTimeout(function() {
        w.contentWindow.location.reload();
    }, 100);
    //let window=document.getElementById("profile-content");
    //window.innerHTML="<" + "?php include" + type + "?>";
}