const menuBtn = document.querySelector(".header .menu-btn");
const menu = document.querySelector(".header .menu");

function toggleMenu() {
    menuBtn.classList.toggle("active");
    menu.classList.toggle("open");
}
menuBtn.addEventListener("click", toggleMenu);

document.getElementById("bookingForm").addEventListener("submit", function() {
    let farmSize = document.getElementById("farmSizeInput").value; // Get farm size value
    this.action = "payment.php?farmSize=" + encodeURIComponent(farmSize); // Append to URL
});

$(document).ready(function () {
    let chemicals = [
        "Azoxystrobin", "Chlorpyrifos", "Fenitrothion", "Imidacloprid", "Propiconazole",
        "Tricyclazole", "Carbendazim", "Buprofezin", "Atrazine", "Lambda-Cyhalothrin",
        "Chlorantraniliprole", "Tebuconazole", "Metribuzin", "Fenpropidin", "Prothioconazole",
        "Chlorothalonil", "Cyproconazole", "Mancozeb", "Hexaconazole", "Dimethoate",
        "Thiophanate-methyl", "Spinosad", "Fenvalerate", "Copper Oxychloride",
        "Bordeaux Mixture", "Difenoconazole", "Emamectin Benzoate"
    ];

    $("#chemicalName").autocomplete({
        source: chemicals,
        minLength: 1 // Suggest after 1 character
    });
});

// Chemical mappings for different crops
const cropChemicals = {
    'Wheat - गेहूं': [
        'Azoxystrobin',
        'Chlorpyrifos',
        'Fenitrothion',
        'Imidacloprid',
        'Propiconazole'
    ],
    'Rice - चावल': [
        'Tricyclazole',
        'Carbendazim',
        'Buprofezin',
        'Imidacloprid',
        'Propiconazole'
    ],
    'Maize - मक्का': [
        'Atrazine',
        'Lambda-Cyhalothrin',
        'Chlorantraniliprole',
        'Propiconazole',
        'Tebuconazole'
    ],
    'Sugarcane - गन्ना': [
        'Atrazine',
        'Imidacloprid',
        'Chlorpyrifos',
        'Propiconazole',
        'Metribuzin'
    ],
    'Barley - जौ': [
        'Tebuconazole',
        'Fenpropidin',
        'Prothioconazole',
        'Chlorothalonil',
        'Cyproconazole'
    ],
    'Groundnut - मूंगफली': [
        'Mancozeb',
        'Hexaconazole',
        'Imidacloprid',
        'Chlorpyrifos',
        'Tebuconazole'
    ],
    'Pulses - दालें': [
        'Chlorothalonil',
        'Dimethoate',
        'Carbendazim',
        'Imidacloprid',
        'Thiophanate-methyl'
    ],
    'Cotton - कपास': [
        'Imidacloprid',
        'Chlorpyrifos',
        'Spinosad',
        'Fenvalerate',
        'Propiconazole'
    ],
    'Tea - चाय': [
        'Atrazine',
        'Imidacloprid',
        'Chlorpyrifos',
        'Propiconazole',
        'Metribuzin'
    ],
    'Coffee - कॉफी': [
        'Copper Oxychloride',
        'Hexaconazole',
        'Chlorpyrifos',
        'Propiconazole',
        'Bordeaux Mixture'
    ],
    'Spices - मसाले': [
        'Copper Oxychloride',
        'Difenoconazole',
        'Hexaconazole',
        'Emamectin Benzoate',
        'Mancozeb'
    ]
};

// Function to update chemical options based on selected crop
function updateChemicalOptions() {
    const cropSelect = document.getElementById('cropSelect');
    const chemicalSelect = document.getElementById('chemicalName');
    
    // Clear existing options except the first one
    while (chemicalSelect.options.length > 1) {
        chemicalSelect.remove(1);
    }
    
    // Get selected crop
    const selectedCrop = cropSelect.value;
    
    // If a crop is selected, populate chemical options
    if (selectedCrop && cropChemicals[selectedCrop]) {
        cropChemicals[selectedCrop].forEach(chemical => {
            const option = document.createElement('option');
            option.value = chemical;
            option.textContent = chemical;
            chemicalSelect.appendChild(option);
        });
    }
}

// Add event listener to crop select
document.addEventListener('DOMContentLoaded', function() {
    const cropSelect = document.getElementById('cropSelect');
    if (cropSelect) {
        cropSelect.addEventListener('change', updateChemicalOptions);
    }
});