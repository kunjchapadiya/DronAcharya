// Confirm delete address
function confirmDelete(event) {
    if (!confirm('Are you sure you want to remove this address?')) {
        event.preventDefault();
    }
}

// Add event listeners to all delete buttons
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('[name="delete_address"]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', confirmDelete);
    });

    // Animation for address cards
    const addressCards = document.querySelectorAll('.address-card');
    addressCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
}); 