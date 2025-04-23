<?php
session_start();
require_once('connect.php');

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];
$message = '';

// Database connection
$conn = mysqli_connect("localhost", "root", "", "project");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle address deletion
if (isset($_POST['delete_address'])) {
    $address_id = $_POST['address_id'];
    $delete_sql = "DELETE FROM bookingdata WHERE id = ? AND email = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("is", $address_id, $email);
    
    if ($delete_stmt->execute()) {
        $message = "Address deleted successfully!";
    } else {
        $message = "Error deleting address.";
    }
}

// Fetch user's addresses
$addresses_sql = "SELECT id, farmLocation, farmSize FROM bookingdata WHERE email = ? GROUP BY farmLocation";
$addresses_stmt = $conn->prepare($addresses_sql);
$addresses_stmt->bind_param("s", $email);
$addresses_stmt->execute();
$addresses = $addresses_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Addresses - DronAcharya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="Style/home.css">
    <link rel="stylesheet" href="Style/addresses.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="addresses-section">
        <div class="container">
            <h1 class="page-title">Your Farm Locations</h1>
            
            <?php if ($message): ?>
                <div class="alert alert-info"><?php echo $message; ?></div>
            <?php endif; ?>

            <?php if ($addresses->num_rows > 0): ?>
                <?php while ($address = $addresses->fetch_assoc()): ?>
                    <div class="address-card">
                        <h5>Farm Location</h5>
                        <p><?php echo htmlspecialchars($address['farmLocation']); ?></p>
                        <p><strong>Farm Size:</strong> <?php echo htmlspecialchars($address['farmSize']); ?></p>
                        <div class="address-actions">
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="address_id" value="<?php echo $address['id']; ?>">
                                <button type="submit" name="delete_address" class="btn btn-danger btn-sm" 
                                        onclick="return confirm('Are you sure you want to remove this address?')">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-addresses">
                    <p>No farm locations found. Add a farm location when booking a service.</p>
                    <a href="Book-service.php" class="btn btn-primary">Book a Service</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="Scripts/home.js"></script>
    <script src="Scripts/addresses.js"></script>
</body>
</html> 