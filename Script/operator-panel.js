document.addEventListener('DOMContentLoaded', function() {
    // Toggle sidebar on mobile
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');

    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            mainContent.classList.toggle('active');
        });
    }

    // Handle booking actions
    const startJobButtons = document.querySelectorAll('.start-job');
    const viewDetailsButtons = document.querySelectorAll('.view-details');
    const updateStatusButtons = document.querySelectorAll('.update-status');

    // Start Job functionality
    startJobButtons.forEach(button => {
        button.addEventListener('click', function() {
            const bookingId = this.dataset.bookingId;
            if (confirm('Are you sure you want to start this job?')) {
                // Here you would typically make an AJAX call to update the booking status
                console.log('Starting job:', bookingId);
                // Update UI to show job in progress
                const card = this.closest('.booking-card');
                const statusBadge = card.querySelector('.status-badge');
                statusBadge.textContent = 'In Progress';
                statusBadge.classList.add('in-progress');
                this.disabled = true;
            }
        });
    });

    // View Details functionality
    viewDetailsButtons.forEach(button => {
        button.addEventListener('click', function() {
            const bookingId = this.dataset.bookingId;
            // Here you would typically show a modal with booking details
            console.log('Viewing details for booking:', bookingId);
        });
    });

    // Update Drone Status functionality
    updateStatusButtons.forEach(button => {
        button.addEventListener('click', function() {
            const droneId = this.dataset.droneId;
            // Here you would typically show a modal to update drone status
            console.log('Updating status for drone:', droneId);
        });
    });

    // Battery level animation
    const batteryLevels = document.querySelectorAll('.battery-fill');
    batteryLevels.forEach(battery => {
        const level = parseInt(battery.style.width);
        if (level < 20) {
            battery.style.backgroundColor = 'var(--danger-color)';
        } else if (level < 50) {
            battery.style.backgroundColor = 'var(--warning-color)';
        }
    });

    // Notification bell click handler
    const notificationBell = document.querySelector('.notification-bell');
    if (notificationBell) {
        notificationBell.addEventListener('click', function() {
            // Here you would typically show a dropdown with notifications
            console.log('Showing notifications');
        });
    }

    // Responsive adjustments
    function handleResize() {
        if (window.innerWidth > 992) {
            sidebar.classList.remove('active');
            mainContent.classList.remove('active');
        }
    }

    window.addEventListener('resize', handleResize);
    handleResize();
}); 