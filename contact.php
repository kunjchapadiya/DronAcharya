<?php
session_start();
require_once('connect.php');

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Array of customer care numbers (dummy)
$customer_care_numbers = [
    "+91 98765 43210",
    "+91 98765 43211",
    "+91 98765 43212",
    "+91 98765 43213",
    "+91 98765 43214"
];

// Get a random customer care number
$random_number = $customer_care_numbers[array_rand($customer_care_numbers)];

// Support email (dummy)
$support_email = "support@dronacharya.com";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - DronAcharya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="Style/home.css">
    <link rel="stylesheet" href="Style/contact.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="contact-section">
        <div class="container">
            <h1 class="page-title">Contact Us</h1>

            <div class="row">
                <div class="col-md-6">
                    <div class="contact-card">
                        <div class="contact-info text-center">
                            <i class="fas fa-phone-alt"></i>
                            <h3>Customer Support</h3>
                            <p>24/7 Customer Care Number</p>
                            <a href="tel:<?php echo str_replace(' ', '', $random_number); ?>">
                                <?php echo $random_number; ?>
                            </a>
                            <p class="support-hours">
                                Available 24 hours, 7 days a week
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="contact-card">
                        <div class="contact-info text-center">
                            <i class="fas fa-envelope"></i>
                            <h3>Email Support</h3>
                            <p>Send us your queries</p>
                            <a href="mailto:<?php echo $support_email; ?>">
                                <?php echo $support_email; ?>
                            </a>
                            <p class="support-hours">
                                We typically respond within 24 hours
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="contact-card mt-4">
                <div class="contact-info text-center">
                    <i class="fas fa-map-marker-alt"></i>
                    <h3>Head Office</h3>
                    <p>DronAcharya Drone Services</p>
                    <p>123, Tech Park, Sector 15</p>
                    <p>Gujarat, India - 380015</p>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="Scripts/home.js"></script>
    <script src="Scripts/contact.js"></script>
</body>
</html> 