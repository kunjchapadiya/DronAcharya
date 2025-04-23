const menuBtn = document.querySelector(".header .menu-btn");
const menu = document.querySelector(".header .menu");

function toggleMenu()
{
    menuBtn.classList.toggle("active");
    menu.classList.toggle("open");
}
menuBtn.addEventListener("click", toggleMenu);