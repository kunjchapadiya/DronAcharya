<?php
session_start();
require_once('connect.php');

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "project");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$email = $_SESSION['email'];
$message = '';

// Handle profile updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $new_email = $_POST['email'];
        $new_username = $_POST['username'];
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];

        // Verify current password
        $check_sql = "SELECT password FROM registration WHERE email = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && $user['password'] === $current_password) {
            // Update profile
            $update_sql = "UPDATE registration SET email = ?, username = ?";
            $params = [$new_email, $new_username];
            $types = "ss";

            if (!empty($new_password)) {
                $update_sql .= ", password = ?";
                $params[] = $new_password;
                $types .= "s";
            }

            $update_sql .= " WHERE email = ?";
            $params[] = $email;
            $types .= "s";

            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param($types, ...$params);

            if ($update_stmt->execute()) {
                $message = "Profile updated successfully!";
                $_SESSION['email'] = $new_email;
            } else {
                $message = "Error updating profile.";
            }
        } else {
            $message = "Current password is incorrect.";
        }
    }
}

// Fetch user profile
$profile_sql = "SELECT * FROM registration WHERE email = ?";
$profile_stmt = $conn->prepare($profile_sql);
$profile_stmt->bind_param("s", $email);
$profile_stmt->execute();
$profile = $profile_stmt->get_result()->fetch_assoc();

// Fetch user's bookings
$bookings_sql = "SELECT * FROM bookingdata WHERE email = ? ORDER BY bookingDate DESC";
$bookings_stmt = $conn->prepare($bookings_sql);
$bookings_stmt->bind_param("s", $email);
$bookings_stmt->execute();
$bookings = $bookings_stmt->get_result();

// Fetch user's transactions
$transactions_sql = "SELECT t.*, b.farmLocation, b.farmSize, b.crop, b.chemicalName, b.bookingDate 
FROM transactions t 
LEFT JOIN bookingdata b ON b.email = ? 
WHERE b.email = ? 
ORDER BY t.transactionDate DESC";
$transactions_stmt = $conn->prepare($transactions_sql);
$transactions_stmt->bind_param("ss", $email, $email);
$transactions_stmt->execute();
$transactions = $transactions_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Account - DronAcharya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="Style/common.css">
    <link rel="stylesheet" href="Style/profile.css">
</head>
<body>

<?php
// ✅ Fixed: use 'email' to determine login state instead of 'user_id'
$isLoggedIn = isset($_SESSION["email"]);
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

<br><br>

<div class="account-section">
    <div class="container">
        <h1 class="page-title">Your Account</h1>

        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <div class="row g-4">
            <!-- Your Services -->
            <div class="col-md-4">
                <a href="past-bookings.php" style="text-decoration: none;">
                    <div class="account-box">
                        <i class="fas fa-helicopter"></i>
                        <h3>Your Services</h3>
                        <p>Track, view, or book drone spraying services</p>
                    </div>
                </a>
            </div>

            <!-- Login & Security -->
            <div class="col-md-4">
                <a href="security.php" style="text-decoration: none;">
                    <div class="account-box">
                        <i class="fas fa-lock"></i>
                        <h3>Login & Security</h3>
                        <p>Edit login, name, and mobile number</p>
                    </div>
                </a>
            </div>

            <!-- Your Addresses -->
            <div class="col-md-4">
                <a href="addresses.php" style="text-decoration: none;">
                    <div class="account-box">
                        <i class="fas fa-map-marker-alt"></i>
                        <h3>Your Addresses</h3>
                        <p>Edit or add farm location addresses</p>
                    </div>
                </a>
            </div>

            <!-- Contact Us -->
            <div class="col-md-4">
                <a href="contact.php" style="text-decoration: none;">
                    <div class="account-box">
                        <i class="fas fa-headset"></i>
                        <h3>Contact Us</h3>
                        <p>Get help with your services or general inquiries</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="Scripts/home.js"></script>
<script src="Scripts/profile.js"></script>
</body>
</html>
