<?php
// Database connection
$servername = "127.0.0.1";
$username = "root";
$password = "";
$database = "project";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

// If user is not logged in, redirect to login page
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php?redirect=past-bookings.php");
    exit();
}

// Get user's email from session
$user_email = $_SESSION["email"];

// Fetch past bookings for the user
$sql = "SELECT * FROM bookingdata WHERE email = ? ORDER BY bookingDate DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./Image/drone.png" type="image/x-icon">
    <title>Past Bookings - DronAcharya</title>
    <link rel="stylesheet" href="./Style/past-bookings.css">
    <link rel="stylesheet" href="./Style/Common.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php
    $isLoggedIn = isset($_SESSION["user_id"]);
    ?>

<header class="header">
    <div class="container">
    <div class="logo">DronAcharya</div>
        <button type="button" class="menu-btn">
            <span class="line line-1"></span>
            <span class="line line-2"></span>
            <span class="line line-3"></span>
        </button>
        <nav class="menu">
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="Book-service.php">Book Service</a></li>
                <li><a href="Chemical.php">Chemicals</a></li>
                <li><a href="about.php">About us</a></li>
                <?php if ($isLoggedIn): ?>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Register/Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
<br>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Your Past Bookings</h2>
        
        <?php if ($result->num_rows > 0): ?>
            <?php while($booking = $result->fetch_assoc()): ?>
                <div class="booking-card">
                    <div class="booking-header">
                        <h4>Booking Date: <?php echo date('F j, Y', strtotime($booking['bookingDate'])); ?></h4>
                    </div>
                    <div class="booking-details">
                        <div class="detail-item">
                            <span class="detail-label">Name:</span>
                            <span><?php echo htmlspecialchars($booking['name']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Contact:</span>
                            <span><?php echo htmlspecialchars($booking['contactNo']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Farm Location:</span>
                            <span><?php echo htmlspecialchars($booking['farmLocation']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">City:</span>
                            <span><?php echo htmlspecialchars($booking['city']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">State:</span>
                            <span><?php echo htmlspecialchars($booking['state']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Farm Size:</span>
                            <span><?php echo htmlspecialchars($booking['farmSize']); ?> acres</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Crop:</span>
                            <span><?php echo htmlspecialchars($booking['crop']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Chemical:</span>
                            <span><?php echo htmlspecialchars($booking['chemicalName']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Quantity:</span>
                            <span><?php echo htmlspecialchars($booking['chemicalQuantity']); ?> liters</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Growth Stage:</span>
                            <span><?php echo htmlspecialchars($booking['growthStage']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Water Source:</span>
                            <span><?php echo htmlspecialchars($booking['waterSource']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Power Supply:</span>
                            <span><?php echo htmlspecialchars($booking['powerSource']); ?></span>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-bookings">
                <h3>No Past Bookings Found</h3>
                <p>You haven't made any bookings yet.</p>
                <a href="Book-service.php" class="btn btn-primary">Book a Service Now</a>
            </div>
        <?php endif; ?>
    </div>

    <footer class="bg-dark text-white pt-5 pb-4">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-lg-4 col-xl-3 mb-4">
                    <h6 class="text-uppercase font-weight-bold mb-4">About</h6>
                    <p class="container">
                        We are professionals in the field offering modern and Agriculture Mechanization rental services by
                        understanding the exact requirements for increasing the production of crops.
                    </p>
                </div>
                <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
                    <h6 class="text-uppercase font-weight-bold mb-4">Products</h6>
                    <p><a href="home.php" class="text-white">Home</a></p>
          <p><a href="Book-service.php" class="text-white">Book a Service</a></p>
          <p><a href="about.php" class="text-white">About us</a></p>
          <p><a href="Chemical.php" class="text-white">Chemicals</a></p>
                </div>
                <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
                    <h6 class="text-uppercase font-weight-bold mb-4">Legals</h6>
                    <p><a href="#" class="text-white">Terms & Conditions</a></p>
                    <p><a href="#" class="text-white">Return Policy</a></p>
                </div>
                <div class="col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-4">
                    <h6 class="text-uppercase font-weight-bold mb-4">Contact</h6>
                    <p><i class="fas fa-map-marker-alt me-2"></i> Gujarat, India</p>
                    <p><i class="fas fa-envelope me-2"></i> info@example.com</p>
                    <p><i class="fas fa-phone-alt me-2"></i> +91 98765 43210</p>
                </div>
            </div>
        </div>
        <div class="footer-copyright text-center py-3">
            © 2023 Copyright:
            <a href="#" class="text-white"> DronAcharya</a>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./Scripts/past-bookings.js"></script>
</body>
</html> 