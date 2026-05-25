<script>

const themeToggle = document.getElementById("themeToggle");

/* LOAD SAVED THEME */
if (localStorage.getItem("theme") === "dark") {
    document.body.classList.add("dark");

    if (themeToggle) {
        themeToggle.innerHTML = "☀️ Light Mode";
    }
}

/* TOGGLE */
themeToggle.addEventListener("click", () => {

    document.body.classList.toggle("dark");

    if (document.body.classList.contains("dark")) {

        localStorage.setItem("theme", "dark");

        themeToggle.innerHTML = "☀️ Light Mode";

    } else {

        localStorage.setItem("theme", "light");

        themeToggle.innerHTML = "🌙 Dark Mode";
    }

});

</script>