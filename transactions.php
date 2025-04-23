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

// First, ensure bookingId column exists and add if it doesn't
$check_column = "SHOW COLUMNS FROM bookingdata LIKE 'bookingId'";
$result = mysqli_query($conn, $check_column);

if (mysqli_num_rows($result) == 0) {
    // Add bookingId column if it doesn't exist
    $add_column = "ALTER TABLE bookingdata ADD COLUMN bookingId VARCHAR(10) UNIQUE";
    mysqli_query($conn, $add_column);
    
    // Update existing records with random 10-digit numbers
    $update_existing = "UPDATE bookingdata SET bookingId = LPAD(FLOOR(RAND() * 9999999999), 10, '0') WHERE bookingId IS NULL";
    mysqli_query($conn, $update_existing);
}

// Add bookingId column to transactions if it doesn't exist
$check_trans_column = "SHOW COLUMNS FROM transactions LIKE 'bookingId'";
$result_trans = mysqli_query($conn, $check_trans_column);

if (mysqli_num_rows($result_trans) == 0) {
    // Add bookingId column to transactions table
    $add_trans_column = "ALTER TABLE transactions ADD COLUMN bookingId VARCHAR(10)";
    mysqli_query($conn, $add_trans_column);
    
    // Add foreign key constraint
    $add_foreign_key = "ALTER TABLE transactions ADD FOREIGN KEY (bookingId) REFERENCES bookingdata(bookingId)";
    mysqli_query($conn, $add_foreign_key);
}

$email = $_SESSION['email'];

// Updated query to use bookingId for joining
$transactions_sql = "
    SELECT t.*, b.bookingId, b.farmLocation, b.farmSize, b.crop, b.chemicalName, b.bookingDate 
    FROM transactions t 
    INNER JOIN bookingdata b ON t.bookingId = b.bookingId
    WHERE b.email = ?
    ORDER BY t.transactionDate DESC";

$transactions_stmt = $conn->prepare($transactions_sql);
$transactions_stmt->bind_param("s", $email);
$transactions_stmt->execute();
$transactions = $transactions_stmt->get_result();

// For debugging
$debug = true;
if ($debug) {
    echo "Logged in Email: " . $email . "<br>";
    echo "Number of transactions found: " . $transactions->num_rows . "<br>";
    
    // Get username for verification
    $user_sql = "SELECT username FROM registration WHERE email = ?";
    $user_stmt = $conn->prepare($user_sql);
    $user_stmt->bind_param("s", $email);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();
    $user = $user_result->fetch_assoc();
    if ($user) {
        echo "Username: " . $user['username'] . "<br>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History - DronAcharya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="Style/home.css">
    <link rel="stylesheet" href="Style/transactions.css">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .transactions-section {
            padding: 120px 0 60px;
            flex: 1;
        }
        .transaction-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .transaction-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .transaction-id {
            color: #666;
            font-size: 0.9rem;
        }
        .transaction-date {
            color: #888;
            font-size: 0.85rem;
        }
        .transaction-amount {
            font-size: 1.2rem;
            font-weight: 600;
            color: #28a745;
        }
        .booking-details {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin-top: 15px;
        }
        .booking-details h6 {
            color: #495057;
            margin-bottom: 10px;
        }
        .no-transactions {
            text-align: center;
            padding: 40px 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 0.9rem;
        }
        .detail-label {
            color: #6c757d;
            font-weight: 500;
        }
        .detail-value {
            color: #495057;
        }
        footer {
            margin-top: auto;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="transactions-section">
        <div class="container">
            <h1 class="page-title">Transaction History</h1>

            <?php if ($transactions && $transactions->num_rows > 0): ?>
                <?php while ($transaction = $transactions->fetch_assoc()): ?>
                    <div class="transaction-card">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="transaction-id mb-1">
                                    Transaction ID: <?php echo htmlspecialchars($transaction['transactionid']); ?>
                                </p>
                                <p class="transaction-date mb-2">
                                    Transaction Date: <?php echo date('F j, Y', strtotime($transaction['transactionDate'])); ?>
                                </p>
                                <p class="mb-0">
                                    <strong>Card Number:</strong> 
                                    <?php 
                                        $cardNumber = $transaction['cardNumber'];
                                        echo "xxxx-xxxx-xxxx-" . substr($cardNumber, -4);
                                    ?>
                                </p>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <p class="transaction-amount mb-0">
                                    ₹<?php echo number_format($transaction['totalAmount'], 2); ?>
                                </p>
                            </div>
                        </div>

                        <div class="booking-details mt-3">
                            <h6><i class="fas fa-info-circle"></i> Booking Details</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="detail-row">
                                        <span class="detail-label">Booking ID:</span>
                                        <span class="detail-value"><?php echo htmlspecialchars($transaction['bookingId'] ?? 'N/A'); ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label">Farm Location:</span>
                                        <span class="detail-value"><?php echo htmlspecialchars($transaction['farmLocation'] ?? 'N/A'); ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label">Farm Size:</span>
                                        <span class="detail-value"><?php echo htmlspecialchars($transaction['farmSize'] ?? 'N/A'); ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label">Crop Type:</span>
                                        <span class="detail-value"><?php echo htmlspecialchars($transaction['crop'] ?? 'N/A'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="detail-row">
                                        <span class="detail-label">Chemical Used:</span>
                                        <span class="detail-value"><?php echo htmlspecialchars($transaction['chemicalName'] ?? 'N/A'); ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label">Booking Date:</span>
                                        <span class="detail-value">
                                            <?php echo $transaction['bookingDate'] ? date('F j, Y', strtotime($transaction['bookingDate'])) : 'N/A'; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-transactions">
                    <i class="fas fa-receipt fa-3x mb-3 text-muted"></i>
                    <p class="mb-3">No transaction history found.</p>
                    <p class="text-muted mb-4">Your transactions will appear here once you make a booking.</p>
                    <a href="Book-service.php" class="btn btn-primary">Book a Service</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="Scripts/home.js"></script>
    <script src="Scripts/transactions.js"></script>
</body>
</html> 