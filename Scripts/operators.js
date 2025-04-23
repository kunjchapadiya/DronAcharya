// Form visibility functions
function showAddOperatorForm() {
    document.getElementById('addOperatorForm').style.display = 'block';
    document.getElementById('editOperatorForm').style.display = 'none';
}

function hideAddOperatorForm() {
    document.getElementById('addOperatorForm').style.display = 'none';
}

function showEditOperatorForm(operator) {
    document.getElementById('editOperatorForm').style.display = 'block';
    document.getElementById('addOperatorForm').style.display = 'none';
    
    // Fill the form with operator data
    document.getElementById('editOperatorId').value = operator.id;
    document.getElementById('editOperatorName').value = operator.name;
    document.getElementById('editOperatorEmail').value = operator.email;
    document.getElementById('editOperatorPhone').value = operator.phone;
    document.getElementById('editOperatorLicense').value = operator.license_number;
    document.getElementById('editOperatorStatus').value = operator.status;
}

function hideEditOperatorForm() {
    document.getElementById('editOperatorForm').style.display = 'none';
}

function deleteOperator(id) {
    if (confirm('Are you sure you want to delete this operator?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="${id}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

// Form validation
document.querySelectorAll('.operator-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        const email = this.querySelector('input[name="email"]').value;
        const phone = this.querySelector('input[name="phone"]').value;
        const license = this.querySelector('input[name="license"]').value;

        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert('Please enter a valid email address');
            e.preventDefault();
            return;
        }

        // Phone validation (basic format)
        const phoneRegex = /^\+?[\d\s-]{10,}$/;
        if (!phoneRegex.test(phone)) {
            alert('Please enter a valid phone number');
            e.preventDefault();
            return;
        }

        // License number validation (alphanumeric with minimum length)
        const licenseRegex = /^[A-Za-z0-9]{5,}$/;
        if (!licenseRegex.test(license)) {
            alert('License number must be at least 5 characters long and contain only letters and numbers');
            e.preventDefault();
            return;
        }
    });
});

// Add smooth transitions
document.addEventListener('DOMContentLoaded', function() {
    // Add fade-in effect to table rows
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.animation = `fadeIn 0.5s ease forwards ${index * 0.1}s`;
    });

    // Add fade-in effect to forms
    const forms = document.querySelectorAll('.form-section');
    forms.forEach(form => {
        form.style.opacity = '0';
        form.style.animation = 'fadeIn 0.3s ease forwards';
    });
});

// Add CSS animation
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
`;
document.head.appendChild(style); 