<script>

/* =========================
   SIDEBAR TOGGLE
========================= */

const toggleBtn = document.getElementById("menu-toggle");
const sidebar = document.getElementById("sidebar");
const main = document.getElementById("main");
const overlay = document.getElementById("overlay");

function closeSidebar(){

    sidebar.classList.remove("active");
    sidebar.classList.add("close");

    main.classList.remove("full");

    toggleBtn.classList.remove("active");
    overlay.classList.remove("show");
}

toggleBtn.addEventListener("click", () => {

    if(window.innerWidth <= 768){

        sidebar.classList.toggle("active");
        toggleBtn.classList.toggle("active");
        overlay.classList.toggle("show");

    }else{

        sidebar.classList.toggle("close");
        main.classList.toggle("full");
        toggleBtn.classList.toggle("active");
    }

});

/* click overlay to close */
overlay.addEventListener("click", closeSidebar);


/* =========================
   AUTO HIDE HAMBURGER
========================= */

let lastScrollTop = 0;

window.addEventListener("scroll", function(){

    let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

    if(scrollTop > lastScrollTop){

        toggleBtn.classList.add("hide");

    } else {

        toggleBtn.classList.remove("hide");
    }

    lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;

});


/* =========================
   DARK MODE TOGGLE
========================= */

const darkToggle = document.getElementById("dark-toggle");
const themeIcon = document.getElementById("theme-icon");
const themeText = document.getElementById("theme-text");

/* load saved theme */
if(localStorage.getItem("darkMode") === "enabled"){

    document.body.classList.add("dark");

    themeIcon.innerHTML = "☀";
    themeText.innerHTML = "Light Mode";
}

/* toggle theme */
darkToggle.addEventListener("click", () => {

    document.body.classList.toggle("dark");

    /* DARK MODE ACTIVE */
    if(document.body.classList.contains("dark")){

        localStorage.setItem("darkMode", "enabled");

        themeIcon.innerHTML = "☀";
        themeText.innerHTML = "Light Mode";

    }

    /* LIGHT MODE ACTIVE */
    else{

        localStorage.setItem("darkMode", "disabled");

        themeIcon.innerHTML = "🌙";
        themeText.innerHTML = "Dark Mode";
    }

});

</script>