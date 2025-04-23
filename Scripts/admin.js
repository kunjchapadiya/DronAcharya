function toggleMenu() {
    const navLinks = document.getElementById("navLinks");
    navLinks.classList.toggle("active");
}
document.addEventListener("DOMContentLoaded", function () {
    // Revenue Chart Data
    const revenueLabels = revenueData.map(item => item.month);
    const revenueValues = revenueData.map(item => parseFloat(item.total_payment));

    // Crop Chart Data
    const cropLabels = cropData.map(item => item.crop);
    const cropPercentages = cropData.map(item => parseFloat(item.percentage));

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: revenueLabels,
            datasets: [{
                label: 'Total Revenue (₹)',
                data: revenueValues,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => '₹' + value.toLocaleString()
                    }
                }
            }
        }
    });


// Pie Chart of Crop used by farmers in 2024-2025 Year 

const cropCtx = document.getElementById('cropChart').getContext('2d');

// Generate unique colors dynamically
const generateColors = (numColors) => {
    const colors = [];
    for (let i = 0; i < numColors; i++) {
        colors.push(`hsl(${Math.floor(Math.random() * 360)}, 70%, 60%)`); // Random HSL colors
    }
    return colors;
};

const cropColors = generateColors(cropLabels.length); // Generate a unique color for each crop

new Chart(cropCtx, {
    type: 'pie',
    data: {
        labels: cropLabels,
        datasets: [{
            data: cropPercentages,
            backgroundColor: cropColors, // Use dynamically generated colors
            hoverOffset: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: true }
        }
    }
});

});
