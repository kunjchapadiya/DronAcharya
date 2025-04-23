// Date validation for booking
document.addEventListener('DOMContentLoaded', function() {
    // Set min date for booking
    const sprayingDateInput = document.getElementById('sprayingDate');
    if (sprayingDateInput) {
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0');
        var yyyy = today.getFullYear();

        today = yyyy + '-' + mm + '-' + dd;
        sprayingDateInput.setAttribute('min', today);
        
        // Add event listener to validate date on change
        sprayingDateInput.addEventListener('change', function() {
            var selectedDate = new Date(this.value);
            var currentDate = new Date();
            
            // Reset time part for accurate date comparison
            selectedDate.setHours(0,0,0,0);
            currentDate.setHours(0,0,0,0);
            
            if (selectedDate < currentDate) {
                alert('Please select a future date for booking.');
                this.value = '';
            }
        });
    }

    // Validate farm size input
    const farmSizeInput = document.getElementById('farmSize');
    if (farmSizeInput) {
        farmSizeInput.addEventListener('input', function() {
            const value = parseFloat(this.value);
            if (!Number.isInteger(value)) {
                alert('Farm size must be a whole number');
                this.value = Math.floor(value);
            } else if (value <= 0) {
                alert('Farm size must be greater than 0 acres');
                this.value = '';
            }
        });

        // Prevent invalid values on blur
        farmSizeInput.addEventListener('blur', function() {
            const value = parseFloat(this.value);
            if (value < 1) {
                alert('Farm size must be at least 1 acre');
                this.value = '';
            }
        });
    }

    // Validate chemical quantity input
    const chemicalQuantityInput = document.getElementById('chemicalQuantity');
    const chemicalNameSelect = document.getElementById('chemicalName');
    
    if (chemicalNameSelect) {
        // Fetch available chemicals when page loads
        fetch('get_chemicals.php')
            .then(response => response.json())
            .then(chemicals => {
                chemicals.forEach(chemical => {
                    const option = document.createElement('option');
                    option.value = chemical.name;
                    option.textContent = chemical.name;
                    chemicalNameSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching chemicals:', error));
    }

    if (chemicalQuantityInput && chemicalNameSelect) {
        chemicalQuantityInput.addEventListener('input', function() {
            const value = parseFloat(this.value);
            if (!Number.isInteger(value)) {
                alert('Chemical quantity must be a whole number');
                this.value = Math.floor(value);
            } else if (value <= 0) {
                alert('Chemical quantity must be greater than 0 liters');
                this.value = '';
            }
        });

        // Prevent invalid values on blur
        chemicalQuantityInput.addEventListener('blur', function() {
            const value = parseFloat(this.value);
            if (value < 1) {
                alert('Chemical quantity must be at least 1 liter');
                this.value = '';
            }
        });
    }
}); 