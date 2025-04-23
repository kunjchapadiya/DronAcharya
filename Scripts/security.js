// Toggle password visibility
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.querySelector(`#${inputId} + .password-toggle i`);
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const currentPassword = document.getElementById('current_password').value;
    const newPassword = document.getElementById('new_password').value;
    const email = document.getElementById('email').value;
    const username = document.getElementById('username').value;

    if (!email || !username || !currentPassword) {
        e.preventDefault();
        alert('Please fill in all required fields');
        return;
    }

    if (newPassword && newPassword.length < 6) {
        e.preventDefault();
        alert('New password must be at least 6 characters long');
        return;
    }
}); 