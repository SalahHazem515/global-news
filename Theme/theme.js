document.addEventListener("DOMContentLoaded", function () {
  const themeSwitch = document.getElementById("themeToggle");
  const isDarkMode = localStorage.getItem("theme") === "dark";

  document.body.classList.toggle("dark", isDarkMode);
  themeSwitch.checked = isDarkMode;

  themeSwitch.addEventListener("change", function () {
    if (this.checked) {
      document.body.classList.add("dark");
      localStorage.setItem("theme", "dark");
    } else {
      document.body.classList.remove("dark");
      localStorage.setItem("theme", "light");
    }
  });
});