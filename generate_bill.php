<?php
require_once('tcpdf/tcpdf.php');

// Database connection
$servername = "127.0.0.1";
$username = "root";
$password = "";
$database = "project";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get transaction ID from URL
$transaction_id = $_GET['transaction_id'] ?? null;
if (!$transaction_id) {
    die("Invalid transaction ID.");
}

// Fetch transaction details including bookingId
$transaction_sql = "SELECT t.*, b.* FROM transactions t 
                   JOIN bookingdata b ON t.bookingId = b.bookingId 
                   WHERE t.transactionid = ?";
$transaction_stmt = $conn->prepare($transaction_sql);
$transaction_stmt->bind_param("s", $transaction_id);
$transaction_stmt->execute();
$result = $transaction_stmt->get_result();
$transaction_result = $result->fetch_assoc();

if (!$transaction_result) {
    die("Transaction not found.");
}

// Create PDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

// Logo and Title
$html = "
<img src='logo.png' alt='Logo' style='width: 100px; height: 100px;'>
<h1>DronAcharya Spraying Service</h1>
<h1>Bill</h1>
<p><strong>Booking ID:</strong> " . htmlspecialchars($transaction_result['bookingId'] ?? 'N/A') . "</p>
<p><strong>Name:</strong> " . htmlspecialchars($transaction_result['name'] ?? 'N/A') . "</p>
<p><strong>Email:</strong> " . htmlspecialchars($transaction_result['email'] ?? 'N/A') . "</p>
<p><strong>Mobile No:</strong> " . htmlspecialchars($transaction_result['contactNo'] ?? 'N/A') . "</p>
<p><strong>Farm Size:</strong> " . htmlspecialchars($transaction_result['farmSize'] ?? 'N/A') . " acres</p>
<p><strong>Crop Type:</strong> " . htmlspecialchars($transaction_result['crop'] ?? 'N/A') . "</p>
<p><strong>Chemical Name:</strong> " . htmlspecialchars($transaction_result['chemicalName'] ?? 'N/A') . "</p>
<p><strong>Chemical Quantity:</strong> " . htmlspecialchars($transaction_result['chemicalQuantity'] ?? 'N/A') . " liters</p>

<h2>Transaction Details</h2>
<p><strong>Transaction ID:</strong> " . htmlspecialchars($transaction_result['transactionid'] ?? 'N/A') . "</p>
<p><strong>Total Payment:</strong> ₹" . htmlspecialchars($transaction_result['totalAmount'] ?? 'N/A') . "</p>
<p><strong>Payment Status:</strong> <span style='color: green;'>Successful</span></p>
";

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('bill.pdf', 'D');

$conn->close();
?>
