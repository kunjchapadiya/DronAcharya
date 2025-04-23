// Add animations to contact cards
document.addEventListener('DOMContentLoaded', function() {
    const contactCards = document.querySelectorAll('.contact-card');
    
    contactCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            const icon = this.querySelector('.contact-info i');
            if (icon) {
                icon.style.transform = 'scale(1.1)';
            }
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            const icon = this.querySelector('.contact-info i');
            if (icon) {
                icon.style.transform = 'scale(1)';
            }
        });
    });

    // Format phone number
    const phoneLinks = document.querySelectorAll('a[href^="tel:"]');
    phoneLinks.forEach(link => {
        const number = link.textContent.trim();
        link.setAttribute('href', 'tel:' + number.replace(/\s+/g, ''));
    });

    // Copy to clipboard functionality
    const contactInfo = document.querySelectorAll('.contact-info a');
    contactInfo.forEach(info => {
        info.addEventListener('click', function(e) {
            if (this.href.startsWith('mailto:') || this.href.startsWith('tel:')) {
                const text = this.textContent.trim();
                navigator.clipboard.writeText(text).then(() => {
                    const tooltip = document.createElement('div');
                    tooltip.className = 'tooltip';
                    tooltip.textContent = 'Copied!';
                    this.appendChild(tooltip);
                    setTimeout(() => tooltip.remove(), 2000);
                });
            }
        });
    });
}); 