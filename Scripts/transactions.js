// Add animation to transaction cards
document.addEventListener('DOMContentLoaded', function() {
    const transactionCards = document.querySelectorAll('.transaction-card');
    
    transactionCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Format currency
    const amounts = document.querySelectorAll('.transaction-amount');
    amounts.forEach(amount => {
        const value = parseFloat(amount.textContent.replace('₹', ''));
        amount.textContent = '₹' + value.toLocaleString('en-IN');
    });

    // Format dates
    const dates = document.querySelectorAll('.transaction-date');
    dates.forEach(date => {
        const dateObj = new Date(date.textContent);
        date.textContent = dateObj.toLocaleDateString('en-IN', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    });
}); 