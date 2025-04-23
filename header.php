<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="header">
    <div class="container">
        <a href="home.php" class="logo">DronAcharya</a>
        <button class="menu-btn">
            <span class="line line-1"></span>
            <span class="line line-2"></span>
            <span class="line line-3"></span>
        </button>
        <nav class="menu">
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="Book-service.php">Book Service</a></li>
                <li><a href="Chemicals.php">Chemicals</a></li>
                <li><a href="about.php">About us</a></li>
                <?php if(isset($_SESSION['email'])): ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle">
                            <i class="fas fa-user-circle"></i> Profile
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="profile.php"><i class="fas fa-user"></i> My Profile</a></li>
                            <li><a href="security.php"><i class="fas fa-lock"></i> Security</a></li>
                            <li><a href="addresses.php"><i class="fas fa-map-marker-alt"></i> Addresses</a></li>
                            <li><a href="transactions.php"><i class="fas fa-history"></i> Transactions</a></li>
                            <li><a href="contact.php"><i class="fas fa-envelope"></i> Contact Us</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="signup.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>

<style>
.dropdown {
    position: relative;
}

.dropdown-toggle {
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
}

.dropdown-menu {
    display: none;
    position: absolute;
    right: 0;
    top: 100%;
    background: white;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    border-radius: 4px;
    min-width: 200px;
    z-index: 1000;
    padding: 8px 0;
}

.dropdown-menu li {
    display: block;
    margin: 0;
}

.dropdown-menu a {
    padding: 8px 16px;
    display: flex;
    align-items: center;
    gap: 10px;
    color: #333;
    text-decoration: none;
    transition: background-color 0.2s;
}

.dropdown-menu a:hover {
    background-color: #f8f9fa;
    color: #0056b3;
}

.dropdown:hover .dropdown-menu {
    display: block;
}

.dropdown-divider {
    margin: 8px 0;
    border-top: 1px solid #e9ecef;
}

/* Responsive styles */
@media (max-width: 768px) {
    .dropdown-menu {
        position: static;
        box-shadow: none;
        background: transparent;
        min-width: 100%;
        padding: 0;
    }

    .dropdown-menu a {
        padding: 8px 32px;
        color: inherit;
    }

    .dropdown:hover .dropdown-menu {
        display: none;
    }

    .dropdown.active .dropdown-menu {
        display: block;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile dropdown toggle
    const dropdowns = document.querySelectorAll('.dropdown-toggle');
    dropdowns.forEach(dropdown => {
        dropdown.addEventListener('click', function(e) {
            e.preventDefault();
            if (window.innerWidth <= 768) {
                this.parentElement.classList.toggle('active');
            }
        });
    });
});
</script> 