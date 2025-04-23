const menuBtn = document.querySelector(".header .menu-btn");
const menu = document.querySelector(".header .menu");

function toggleMenu() {
    menuBtn.classList.toggle("active");
    menu.classList.toggle("open");
}
menuBtn.addEventListener("click", toggleMenu);

// Function to get values from localStorage and display them
function displayPaymentDetails() {
    let farmSize = localStorage.getItem("farmSize");
    let baseAmount = localStorage.getItem("baseAmount");
    let taxAmount = localStorage.getItem("taxAmount");
    let serviceCharge = localStorage.getItem("serviceCharge");
    let extraCharge = localStorage.getItem("extraCharge");
    let totalAmount = localStorage.getItem("totalAmount");

    document.getElementById("farmSize").innerText = farmSize + " acres";
    document.getElementById("baseAmount").innerText = "₹" + baseAmount;
    document.getElementById("taxAmount").innerText = "₹" + taxAmount;
    document.getElementById("serviceCharge").innerText = "₹" + serviceCharge;

    // Check if extra charge exists and display it
    if (extraCharge > 0) {
        document.getElementById("waterTransportFee").innerText = "₹" + extraCharge;
        document.getElementById("waterChargeRow").style.display = "table-row"; // Show row
    }

    document.getElementById("totalAmount").innerText = "₹" + totalAmount;
}

displayPaymentDetails();
