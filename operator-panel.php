<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle status update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $bookingId = $_POST['booking_id'];
    $newStatus = $_POST['service_status'];

    // Validate $newStatus to prevent SQL injection if it's not from a fixed list
    $allowed_statuses = ['Completed', 'Cancelled', 'Incomplete', 'Ongoing', 'Confirmed', 'Pending'];
    if (in_array($newStatus, $allowed_statuses)) {
        $updateQuery = "UPDATE bookingdata SET serviceStatus = ? WHERE bookingId = ?";
        $stmt = $conn->prepare($updateQuery);
        if ($stmt) {
            $stmt->bind_param("ss", $newStatus, $bookingId);
            if ($stmt->execute()) {
                // Optional: Add a success message or redirect
                 echo "<script>alert('Status updated successfully for Booking ID: " . htmlspecialchars($bookingId) . "'); window.location.href = 'operator-panel.php';</script>";
            } else {
                echo "<script>alert('Error updating status: " . htmlspecialchars($stmt->error) . "');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Error preparing statement: " . htmlspecialchars($conn->error) . "');</script>";
        }
    } else {
        echo "<script>alert('Invalid status value.');</script>";
    }
}

// Get today's bookings
$today = date('Y-m-d');
$todayBookingsQuery = "SELECT COUNT(*) as count FROM bookingdata WHERE DATE(bookingDate) = '$today'";
$todayBookingsResult = $conn->query($todayBookingsQuery);
$todayBookings = $todayBookingsResult->fetch_assoc()['count'];

// Get available drones
$availableDronesQuery = "SELECT * FROM drones WHERE status = 'Available'";
$availableDrones = $conn->query($availableDronesQuery);

// Get future bookings
    $futureBookingsQuery = "SELECT *, name as customer_name
                            FROM bookingdata
                       WHERE DATE(bookingDate) > '$today' 
                       ORDER BY bookingDate ASC
                       LIMIT 5";
$futureBookings = $conn->query($futureBookingsQuery);

// Get today's services
$todayServicesQuery = "SELECT *, name as customer_name
                      FROM bookingdata 
                      WHERE DATE(bookingDate) = '$today'
                      ORDER BY bookingDate ASC";
$todayServices = $conn->query($todayServicesQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./Image/drone.png" type="image/x-icon">
    <title>Operator Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="./Style/operator.css">
    <link rel="stylesheet" href="./Style/Common.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        .status-form-select {
            border-radius: 20px;
            padding: 6px 18px;
            border: 1px solid #69A84F;
            margin-right: 8px;
            min-width: 120px;
            background: #f8fff5;
            transition: border-color 0.2s;
        }
        .status-form-select:focus {
            border-color: #558B3F;
            outline: none;
        }
        .status-form-btn {
            border-radius: 20px;
            background: #69A84F;
            color: #fff;
            border: none;
            padding: 6px 22px;
            font-weight: 500;
            transition: background 0.2s;
        }
        .status-form-btn:hover {
            background: #558B3F;
        }
        @media (max-width: 576px) {
            .status-form-select, .status-form-btn {
                width: 100%;
                margin-bottom: 6px;
            }
        }
    </style>
</head>
<body>
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
        <li><a href="./operator-panel.php"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="./logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
      </ul>
    </nav>
  </div>
</header>

    <div class="content">
        <h1>Operator Dashboard</h1>
        <br>
        <div class="dashboard">
            <div class="card">
                <i class="fas fa-calendar-day"></i>
                <h3>Today's Bookings</h3>
                <h2><?php echo $todayBookings; ?></h2>
            </div>
            <div class="card">
                <i class="fas fa-helicopter"></i>
                <h3>Available Drones</h3>
                <h2><?php echo $availableDrones->num_rows; ?></h2>
            </div>
            <div class="card">
                <i class="fas fa-calendar-alt"></i>
                <h3>Upcoming Bookings</h3>
                <h2><?php echo $futureBookings->num_rows; ?></h2>
            </div>
        </div>

        <!-- Today's Services Section -->
        <div class="bookings-section">
            <h2>Today's Services</h2>
            <div class="table-responsive">
                <table class="booking-table">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Customer Name</th>
                            <th>Phone</th>
                            <th>Location</th>
                            <th>City</th>
                            <th>Crop</th>
                            <th>Chemical Name</th>
                            <th>Chemical Quantity</th>
                            <th>Growth Stage</th>
                            <th>Water Source</th>
                            <th>Power Source</th>
                            <th>Farm Size</th>
                            <th>Current Status</th>
                            <th>Update Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($service = $todayServices->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $service['bookingId']; ?></td>
                            <td><?php echo htmlspecialchars($service['customer_name'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($service['contactNo']); ?></td>
                            <td><?php echo htmlspecialchars($service['farmLocation']); ?></td>
                            <td><?php echo htmlspecialchars($service['city']); ?></td>
                            <td><?php echo htmlspecialchars($service['crop']); ?></td>
                            <td><?php echo htmlspecialchars($service['chemicalName']); ?></td>
                            <td><?php echo htmlspecialchars($service['chemicalQuantity']); ?></td>
                            <td><?php echo htmlspecialchars($service['growthStage']); ?></td>
                            <td><?php echo htmlspecialchars($service['waterSource']); ?></td>
                            <td><?php echo htmlspecialchars($service['powerSource']); ?></td>
                            <td><?php echo $service['farmSize']; ?> acres</td>
                            <td><?php echo htmlspecialchars(isset($service['serviceStatus']) ? $service['serviceStatus'] : 'Pending'); ?></td>
                            <td>
                                <form method="POST" action="operator-panel.php">
                                    <input type="hidden" name="booking_id" value="<?php echo $service['bookingId']; ?>">
                                    <select name="service_status" class="status-form-select form-select">
                                        <option value="Completed" <?php echo (isset($service['serviceStatus']) && $service['serviceStatus'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                                        <option value="Cancelled" <?php echo (isset($service['serviceStatus']) && $service['serviceStatus'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                        <option value="Incomplete" <?php echo (isset($service['serviceStatus']) && $service['serviceStatus'] == 'Incomplete') ? 'selected' : ''; ?>>Incomplete</option>
                                        <option value="Ongoing" <?php echo (isset($service['serviceStatus']) && $service['serviceStatus'] == 'Ongoing') ? 'selected' : ''; ?>>Ongoing</option>
                                        <option value="Confirmed" <?php echo (isset($service['serviceStatus']) && $service['serviceStatus'] == 'Confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                                        <option value="Pending" <?php echo (!isset($service['serviceStatus']) || $service['serviceStatus'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                    </select>
                                    <button type="submit" name="update_status" class="status-form-btn">Update</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Future Bookings Section -->
        <div class="bookings-section">
            <h2>Upcoming Services</h2>
            <div class="table-responsive">
                <table class="booking-table">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Customer Name</th>
                            <th>Phone</th>
                            <th>Location</th>
                            <th>City</th>
                            <th>Crop</th>
                            <th>Chemical Name</th>
                            <th>Chemical Quantity</th>
                            <th>Growth Stage</th>
                            <th>Water Source</th>
                            <th>Power Source</th>
                            <th>Farm Size</th>
                            <th>Service Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($booking = $futureBookings->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $booking['bookingId']; ?></td>
                            <td><?php echo htmlspecialchars($booking['customer_name'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($booking['contactNo']); ?></td>
                            <td><?php echo htmlspecialchars($booking['farmLocation']); ?></td>
                            <td><?php echo htmlspecialchars($booking['city']); ?></td>
                            <td><?php echo htmlspecialchars($booking['crop']); ?></td>
                            <td><?php echo htmlspecialchars($booking['chemicalName']); ?></td>
                            <td><?php echo htmlspecialchars($booking['chemicalQuantity']); ?></td>
                            <td><?php echo htmlspecialchars($booking['growthStage']); ?></td>
                            <td><?php echo htmlspecialchars($booking['waterSource']); ?></td>
                            <td><?php echo htmlspecialchars($booking['powerSource']); ?></td>
                            <td><?php echo $booking['farmSize']; ?> acres</td>
                            <td><?php echo date('F j, Y', strtotime($booking['bookingDate'])); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Available Drones Section -->
        <div class="drones-section">
            <h2>Available Drones</h2>
            <div class="drones-grid">
                <?php while($drone = $availableDrones->fetch_assoc()): ?>
                <div class="drone-card">
                    <div class="drone-header">
                        <h3><?php echo htmlspecialchars($drone['droneName']); ?></h3>
                        <span class="status-badge available">Available</span>
                    </div>
                    <div class="drone-details">
                        <div class="detail-item">
                            <span class="detail-label">Manufacturer:</span>
                            <span><?php echo htmlspecialchars($drone['manufacture']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Payload Capacity:</span>
                            <span><?php echo $drone['payloadCapicity']; ?> kg</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Battery:</span>
                            <span><?php echo $drone['battery']; ?>%</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Last Maintenance:</span>
                            <span><?php echo date('F j, Y', strtotime($drone['lastMaintenance'])); ?></span>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <script src="./Scripts/operator.js"></script>
    <script>
const menuBtn = document.querySelector(".header .menu-btn");
const menu = document.querySelector(".header .menu");

function toggleMenu() {
  menuBtn.classList.toggle("active");
  menu.classList.toggle("open");
}
menuBtn.addEventListener("click", toggleMenu);

</script>
</body>
</html> 