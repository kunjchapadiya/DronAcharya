<?php
// Database connection
$servername = "127.0.0.1"; // or "localhost"
$username = "root"; // Change this if using a different username
$password = ""; // Change this if you have a password
$database = "project"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

// Function to Generate Random 10-digit Transaction ID
function generateTransactionID() {
    return strval(rand(1000000000, 9999999999)); // Random 10-digit number
}

$transactionID = generateTransactionID();

// Get farm size from URL
$farmSize = isset($_GET['farmSize']) ? floatval($_GET['farmSize']) : 0;
$base_price = $farmSize * 100; // ₹100 per acre
$tax_amount = $base_price * 0.05; // 5% tax
$total_amount = $base_price + $tax_amount;

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cardHolderName = $_POST["cardName"];
    $cardNumber = $_POST["cardNum"];
    $transactionID = $_POST["transactionID"]; // Hidden input for transaction ID
    $bookingId = $_SESSION['bookingId']; // Get bookingId from session

   // Insert transaction into database with updated field names
   $sql = "INSERT INTO transactions (transactionid, bookingId, cardHolderName, cardNumber, totalAmount) 
   VALUES (?, ?, ?, ?, ?)";

   $stmt = $conn->prepare($sql);
   $stmt->bind_param("ssssd", $transactionID, $bookingId, $cardHolderName, $cardNumber, $total_amount);

   if ($stmt->execute()) {
    header("Location: ticket.php?transactionID=" . urlencode($transactionID) . "&bookingId=" . urlencode($bookingId));
    exit();
   } else {
       echo "Error: " . $stmt->error;
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href= 
"./Image/drone.png" 
          type="image/x-icon">
    <title>Payment Details</title>
    <link rel="stylesheet" href="./Style/payment.css">
</head>
<body>

<div class="container">

    <!-- Left Side: Payment Details -->
    <div class="payment-details">
        <h2>Payment Details</h2>
        <table>
            <tr>
                <th>Booking ID:</th>
                <td id="bookingID"><?php echo isset($_SESSION['bookingId']) ? htmlspecialchars($_SESSION['bookingId']) : 'N/A'; ?></td>
            </tr>
            <tr>
                <th>Transaction ID:</th>
                <td id="transactionID"><?php echo htmlspecialchars($transactionID); ?></td>
            </tr><tr>
                <th>Transaction Date:</th>
                <td id="transactionDate"><?php echo date("d-m-y");; ?></td>
            </tr>
            <tr>
                <th>Farm Size (Acres)</th>
                <td id="farmSizeDisplay"><?php echo htmlspecialchars($farmSize); ?></td>
            </tr>
            <tr>
                <th>Base Price (₹100 per acre)</th>
                <td>₹<span id="basePrice"><?php echo number_format($base_price, 2); ?></span></td>
            </tr>
            <tr>
                <th>Tax (5%)</th>
                <td>₹<span id="taxAmount"><?php echo number_format($tax_amount, 2); ?></span></td>
            </tr>
            <tr>
                <th>Total Amount</th>
                <td>₹<span id="totalAmount"><?php echo number_format($total_amount, 2); ?></span></td>
            </tr>
        </table>
    </div>

    <!-- Right Side: Card Payment Form -->
    <div class="payment-form">
        <h3 class="title">Payment</h3>
        <form method="POST">
            <input type="hidden" name="transactionID" value="<?php echo $transactionID; ?>">

            <div class="inputBox">
                <label>Card Accepted:</label>
                <img src="./Image/payment.png" alt="credit/debit card image">
            </div>

            <div class="inputBox">
                <label>Name On Card:</label>
                <input type="text" name="cardName" placeholder="Enter card name" required>
            </div>

            <div class="inputBox">
                <label>Credit Card Number:</label>
                <input type="text" name="cardNum" placeholder="1111-2222-3333-4444" maxlength="16" required pattern="[0-9]{16}" title="Please enter exactly 16 digits">
                <span class="error-message">Card number must be exactly 16 digits</span>
            </div>

            <div class="inputBox">
                <label>Exp Month:</label>
                <select name="ExpMonth" required>
                    <option value="">Choose month</option>
                    <option value="January">January</option>
                    <option value="February">February</option>
                    <option value="March">March</option>
                    <option value="April">April</option>
                    <option value="May">May</option>
                    <option value="June">June</option>
                    <option value="July">July</option>
                    <option value="August">August</option>
                    <option value="September">September</option>
                    <option value="October">October</option>
                    <option value="November">November</option>
                    <option value="December">December</option>
                </select>
            </div>

            <div class="flex">
                <div class="inputBox">
                    <label>Exp Year:</label>
                    <select name="expYear" required>
                        <option value="">Choose Year</option>
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                        <option value="2027">2027</option>
                    </select>
                </div>

                <div class="inputBox">
                    <label>CVV</label>
                    <input type="text" name="cvv" placeholder="123" required pattern="[0-9]{3}" title="Please enter exactly 3 digits">
                    <span class="error-message">CVV must be exactly 3 digits</span>
                </div>
            </div>

            <button type="submit" class="submit_btn">Proceed to Checkout</button>
        </form>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cardNumInput = document.querySelector('input[name="cardNum"]');
    const cvvInput = document.querySelector('input[name="cvv"]');
    const form = document.querySelector('form');

    // Remove spaces from card number input
    cardNumInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/\s/g, '');
    });

    // Validate card number
    cardNumInput.addEventListener('input', function() {
        if (this.value.length !== 16) {
            this.setCustomValidity('Card number must be exactly 16 digits');
        } else {
            this.setCustomValidity('');
        }
    });

    // Validate CVV
    cvvInput.addEventListener('input', function() {
        if (this.value.length !== 3) {
            this.setCustomValidity('CVV must be exactly 3 digits');
        } else {
            this.setCustomValidity('');
        }
    });

    // Form submission validation
    form.addEventListener('submit', function(e) {
        if (cardNumInput.value.length !== 16) {
            e.preventDefault();
            alert('Please enter a valid 16-digit card number');
            return false;
        }
        if (cvvInput.value.length !== 3) {
            e.preventDefault();
            alert('Please enter a valid 3-digit CVV');
            return false;
        }
    });
});
</script>

</body>
</html>
