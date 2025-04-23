<?php
// Database Connection
$host = "localhost";
$user = "root";  // Change if needed
$password = "";
$database = "project"; // Change to your database name

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch last 12 months' total payments
$query = "SELECT 
             DATE_FORMAT(transactionDate, '%Y-%m') AS month, 
             SUM(totalAmount) AS total_payment 
          FROM transactions 
          WHERE transactionDate >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
          GROUP BY month
          ORDER BY month";

$result = $conn->query($query);
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Last Year Payment Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: white;
            text-align: center;
            margin: 0;
            padding: 20px;
        }
        .chart-container {
            width: 80%;
            max-width: 900px;
            margin: auto;
            background: #1e1e1e;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(255, 255, 255, 0.1);
        }
        canvas {
            max-width: 100%;
        }
    </style>
</head>
<body>

    <h2>Last Year Total Payment</h2>
    <div class="chart-container">
        <canvas id="paymentChart"></canvas>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const rawData = <?php echo json_encode($data); ?>;
            console.log("Fetched Data:", rawData);

            const labels = rawData.map(item => item.month);
            const values = rawData.map(item => parseFloat(item.total_payment));

            const ctx = document.getElementById('paymentChart').getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Payment ($)',
                        data: values,
                        backgroundColor: 'rgba(0, 255, 0, 0.5)',
                        borderColor: 'lime',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: value => '$' + value.toLocaleString()
                            }
                        }
                    },
                    plugins: {
                        legend: { display: true }
                    }
                }
            });
        });
    </script>

</body>
</html>
