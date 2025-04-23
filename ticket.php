<?php
session_start();

if (isset($_GET['transactionID'])) {
    $transactionID = $_GET['transactionID'];
} else {
    $transactionID = "Unknown";
}

if (isset($_GET['bookingId'])) {
    $bookingId = $_GET['bookingId'];
} else {
    $bookingId = isset($_SESSION['bookingId']) ? $_SESSION['bookingId'] : "Unknown";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./Image/drone.png" type="image/x-icon">
    <title>Payment Successful</title>
    <style>
        body {
            text-align: center;
            padding: 50px 20px;
            font-family: Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: #f5f5f5;
            margin: 0;
        }

        .container {
            background-color: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            max-width: 500px;
            width: 90%;
            animation: fadeIn 0.5s ease-in;
        }

        .success {
            font-size: 28px;
            color: #2ecc71;
            margin-bottom: 20px;
            animation: slideDown 0.5s ease-out;
        }

        .checkmark {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            animation: scaleIn 0.5s ease-out;
        }

        .checkmark-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #2ecc71;
            position: relative;
        }

        .checkmark-circle::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 30px;
            height: 30px;
            border: solid white;
            border-width: 0 3px 3px 0;
            transform: translate(-50%, -50%) rotate(45deg);
        }

        .transaction-id {
            background-color: #f8f9fa;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 20px 0;
            display: inline-block;
        }

        .button-container {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            transition: all 0.3s ease;
            min-width: 150px;
        }

        .btn-home {
            background-color: #3498db;
            color: white;
        }

        .btn-download {
            background-color: #2ecc71;
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 1.5rem;
            }
            
            .success {
                font-size: 24px;
            }
            
            .button-container {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="checkmark">
            <div class="checkmark-circle"></div>
        </div>
        <h2 class="success">Payment Successful!</h2>
        <div class="transaction-id">
            <p>Your Booking ID: <strong><?php echo htmlspecialchars($bookingId); ?></strong></p>
            <p>Your Transaction ID: <strong><?php echo htmlspecialchars($transactionID); ?></strong></p>
        </div>
        <div class="button-container">
            <a href="./home.php" class="btn btn-home">Return to Home</a>
            <a href="generate_bill.php?transaction_id=<?php echo urlencode($transactionID); ?>" class="btn btn-download">Download Bill</a>
        </div>
    </div>
</body>
</html>
