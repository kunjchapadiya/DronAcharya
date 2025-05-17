<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Monthly Revenue Data (Current Year)
$monthlyRevenueQuery = "SELECT 
    MONTH(STR_TO_DATE(bookingDate, '%Y-%m-%d')) as month,
    SUM(t.totalAmount) as total_revenue
FROM bookingdata b
LEFT JOIN transactions t ON b.bookingId = t.bookingId
WHERE YEAR(STR_TO_DATE(bookingDate, '%Y-%m-%d')) = YEAR(CURDATE())
GROUP BY MONTH(STR_TO_DATE(bookingDate, '%Y-%m-%d'))
ORDER BY month ASC";
$monthlyRevenueResult = $conn->query($monthlyRevenueQuery);
$monthlyRevenueData = [];
while ($row = $monthlyRevenueResult->fetch_assoc()) {
    $monthlyRevenueData[] = $row;
}

// Trending Fertilizer (Top 5 by usage)
$fertilizerQuery = "SELECT chemicalName, COUNT(*) as count 
FROM bookingdata 
WHERE chemicalName IS NOT NULL AND chemicalName != ''
GROUP BY chemicalName 
ORDER BY count DESC 
LIMIT 5";
$fertilizerResult = $conn->query($fertilizerQuery);
$fertilizerData = [];
while ($row = $fertilizerResult->fetch_assoc()) {
    $fertilizerData[] = $row;
}

// Crop Type Distribution (Current Year)
$cropTypeQuery = "SELECT crop, COUNT(*) as crop_count 
FROM bookingdata 
WHERE crop IS NOT NULL AND crop != '' AND YEAR(STR_TO_DATE(bookingDate, '%Y-%m-%d')) = YEAR(CURDATE())
GROUP BY crop 
ORDER BY crop_count DESC";
$cropTypeResult = $conn->query($cropTypeQuery);
$cropTypeData = [];
while ($row = $cropTypeResult->fetch_assoc()) {
    $cropTypeData[] = $row;
}

// Statewise Bookings (Current Year)
$statewiseQuery = "SELECT state, COUNT(*) as state_count 
FROM bookingdata 
WHERE state IS NOT NULL AND state != '' AND YEAR(STR_TO_DATE(bookingDate, '%Y-%m-%d')) = YEAR(CURDATE())
GROUP BY state 
ORDER BY state_count DESC";
$statewiseResult = $conn->query($statewiseQuery);
$statewiseData = [];
while ($row = $statewiseResult->fetch_assoc()) {
    $statewiseData[] = $row;
}

// Fetch unique values for filters
$crops = $conn->query("SELECT DISTINCT crop FROM bookingdata WHERE crop IS NOT NULL AND crop != '' ORDER BY crop");
$fertilizers = $conn->query("SELECT DISTINCT chemicalName FROM bookingdata WHERE chemicalName IS NOT NULL AND chemicalName != '' ORDER BY chemicalName");
$cities = $conn->query("SELECT DISTINCT city FROM bookingdata WHERE city IS NOT NULL AND city != '' ORDER BY city");
$states = $conn->query("SELECT DISTINCT state FROM bookingdata WHERE state IS NOT NULL AND state != '' ORDER BY state");

// Handle form submission and CSV download
$reportData = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['from_date'], $_POST['to_date'])) {
    $from = $_POST['from_date'];
    $to = $_POST['to_date'];
    $where = ["DATE(STR_TO_DATE(bookingDate, '%Y-%m-%d')) BETWEEN ? AND ?"];
    $params = [$from, $to];
    $types = 'ss';
    // Multi-select filters
    if (!empty($_POST['crop'])) {
        $in = str_repeat('?,', count($_POST['crop'])-1) . '?';
        $where[] = "crop IN ($in)";
        $params = array_merge($params, $_POST['crop']);
        $types .= str_repeat('s', count($_POST['crop']));
    }
    if (!empty($_POST['fertilizer'])) {
        $in = str_repeat('?,', count($_POST['fertilizer'])-1) . '?';
        $where[] = "chemicalName IN ($in)";
        $params = array_merge($params, $_POST['fertilizer']);
        $types .= str_repeat('s', count($_POST['fertilizer']));
    }
    if (!empty($_POST['city'])) {
        $in = str_repeat('?,', count($_POST['city'])-1) . '?';
        $where[] = "city IN ($in)";
        $params = array_merge($params, $_POST['city']);
        $types .= str_repeat('s', count($_POST['city']));
    }
    if (!empty($_POST['state'])) {
        $in = str_repeat('?,', count($_POST['state'])-1) . '?';
        $where[] = "state IN ($in)";
        $params = array_merge($params, $_POST['state']);
        $types .= str_repeat('s', count($_POST['state']));
    }
    $sql = "SELECT bookingId, name, crop, chemicalName, city, state, farmSize, bookingDate FROM bookingdata WHERE ".implode(' AND ', $where);
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $reportData[] = $row;
    }
    // CSV download
    if (isset($_POST['download_csv'])) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="analysis_report.csv"');
        $out = fopen('php://output', 'w');
        if (!empty($reportData)) {
            fputcsv($out, array_keys($reportData[0]));
            foreach ($reportData as $row) {
                fputcsv($out, $row);
            }
        }
        fclose($out);
        exit;
    }
}

// --- Transaction Analysis Report ---
$transactionReportData = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['from_date'], $_POST['to_date'], $_POST['transaction_report'])) {
    $from = $_POST['from_date'];
    $to = $_POST['to_date'];
    $sql = "SELECT t.transactionid, t.bookingId, b.name, b.email, t.totalAmount, t.transactionDate, b.bookingDate, b.farmSize, b.crop, b.chemicalName
            FROM transactions t
            INNER JOIN bookingdata b ON t.bookingId = b.bookingId
            WHERE DATE(STR_TO_DATE(t.transactionDate, '%Y-%m-%d')) BETWEEN ? AND ?
            ORDER BY t.transactionDate DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $from, $to);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $transactionReportData[] = $row;
    }
    // CSV download for transaction report
    if (isset($_POST['download_transaction_csv'])) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="transaction_analysis_report.csv"');
        $out = fopen('php://output', 'w');
        if (!empty($transactionReportData)) {
            fputcsv($out, array_keys($transactionReportData[0]));
            foreach ($transactionReportData as $row) {
                fputcsv($out, $row);
            }
        }
        fclose($out);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./Image/drone.png" type="image/x-icon">
    <title>Reports - DronAcharya</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="./Style/admin.css">
    <link rel="stylesheet" href="./Style/Common.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script src="./Scripts/admin.js"></script>
    <style>
        .report-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem; }
        .report-card { background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); padding: 1.5rem; }
        .report-card h4 { margin-bottom: 1rem; }
        canvas { width: 100% !important; height: 350px !important; }
        @media (max-width: 600px) {
            .report-card { padding: 0.5rem; }
            canvas { height: 250px !important; }
        }
        .multiselect { height: 120px; }
        .table-responsive { margin-top: 2rem; }
    </style>
</head>
<body class="bg-light">
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
            <li><a href="./admin.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="./users.php"><i class="fas fa-user"></i> Users</a></li>
        <li><a href="./dronePanel.php"><i class="fas fa-helicopter"></i> Drone</a></li>
        <li><a href="./operators.php"><i class="fas fa-user"></i> Pilots</a></li>
        <li><a href="./fertilizerPanel.php"><i class="fas fa-flask"></i> Fertilizer</a></li>
        <li><a href="./reports.php"><i class="fas fa-chart-bar"></i> Reports</a></li>
        <li><a href="./logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>
  </div>
</header>
<br><br>
    <div class="container py-4">
        <h2 class="mb-4 text-center">Reports Dashboard</h2>
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="report-card">
                    <h4>Monthly Revenue (<?php echo date('Y'); ?>)</h4>
                    <canvas id="monthlyRevenueChart"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="report-card">
                    <h4>Trending Fertilizers</h4>
                    <canvas id="fertilizerChart"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="report-card">
                    <h4>Crop Type Distribution</h4>
                    <canvas id="cropTypeChart"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="report-card">
                    <h4>Statewise Bookings</h4>
                    <canvas id="statewiseChart"></canvas>
                </div>
            </div>
        </div>
        <h2 class="mt-4 mb-4 text-center">Analysis Report</h2>
        <form method="POST" class="row g-3 align-items-end mb-4">
            <div class="col-md-3">
                <label for="from_date" class="form-label">From Date</label>
                <input type="date" class="form-control" name="from_date" required value="<?php echo $_POST['from_date'] ?? ''; ?>">
            </div>
            <div class="col-md-3">
                <label for="to_date" class="form-label">To Date</label>
                <input type="date" class="form-control" name="to_date" required value="<?php echo $_POST['to_date'] ?? ''; ?>">
            </div>
            <div class="col-md-6 d-flex gap-2 mt-3 align-items-end">
                <button type="submit" class="btn btn-success">Generate Report</button>
                <?php if (!empty($reportData)): ?>
                    <button type="submit" name="download_csv" class="btn btn-primary">Download Excel (CSV)</button>
                <?php endif; ?>
            </div>
        </form>
        <?php if (!empty($reportData)): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <?php foreach(array_keys($reportData[0]) as $col): ?>
                            <th><?php echo htmlspecialchars($col); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($reportData as $row): ?>
                        <tr>
                            <?php foreach($row as $cell): ?>
                                <td><?php echo htmlspecialchars($cell); ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <div class="alert alert-warning mt-3">No data found for the selected filters.</div>
        <?php endif; ?>
        <h2 class="mt-5 mb-4 text-center">Transaction Analysis Report</h2>
        <form method="POST" class="row g-3 align-items-end mb-4">
            <div class="col-md-3">
                <label for="from_date" class="form-label">From Date</label>
                <input type="date" class="form-control" name="from_date" required value="<?php echo $_POST['from_date'] ?? ''; ?>">
            </div>
            <div class="col-md-3">
                <label for="to_date" class="form-label">To Date</label>
                <input type="date" class="form-control" name="to_date" required value="<?php echo $_POST['to_date'] ?? ''; ?>">
            </div>
            <input type="hidden" name="transaction_report" value="1">
            <div class="col-md-6 d-flex gap-2 mt-3 align-items-end">
                <button type="submit" class="btn btn-success">Generate Transaction Report</button>
                <?php if (!empty($transactionReportData)): ?>
                    <button type="submit" name="download_transaction_csv" class="btn btn-primary">Download Excel (CSV)</button>
                <?php endif; ?>
            </div>
        </form>
        <?php if (!empty($transactionReportData)): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <?php foreach(array_keys($transactionReportData[0]) as $col): ?>
                            <th><?php echo htmlspecialchars($col); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($transactionReportData as $row): ?>
                        <tr>
                            <?php foreach($row as $cell): ?>
                                <td><?php echo htmlspecialchars($cell); ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['transaction_report'])): ?>
            <div class="alert alert-warning mt-3">No transaction records found for the selected dates.</div>
        <?php endif; ?>
    </div>
    <script>
    // Data from PHP
    const monthlyRevenueData = <?php echo json_encode($monthlyRevenueData); ?>;
    const fertilizerData = <?php echo json_encode($fertilizerData); ?>;
    const cropTypeData = <?php echo json_encode($cropTypeData); ?>;
    const statewiseData = <?php echo json_encode($statewiseData); ?>;

    // Monthly Revenue Chart
    new Chart(document.getElementById('monthlyRevenueChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: monthlyRevenueData.map(item => new Date(2000, item.month-1).toLocaleString('default', { month: 'short' })),
            datasets: [{
                label: 'Revenue',
                data: monthlyRevenueData.map(item => item.total_revenue),
                borderColor: '#3498db',
                backgroundColor: 'rgba(52,152,219,0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // Trending Fertilizer Chart
    new Chart(document.getElementById('fertilizerChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: fertilizerData.map(item => item.chemicalName),
            datasets: [{
                label: 'Usage',
                data: fertilizerData.map(item => item.count),
                backgroundColor: '#69A84F',
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // Crop Type Chart
    new Chart(document.getElementById('cropTypeChart').getContext('2d'), {
        type: 'pie',
        data: {
            labels: cropTypeData.map(item => item.crop),
            datasets: [{
                data: cropTypeData.map(item => item.crop_count),
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#69A84F', '#2ecc71', '#e67e22', '#e74c3c'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // Statewise Bookings Chart
    new Chart(document.getElementById('statewiseChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: statewiseData.map(item => item.state),
            datasets: [{
                label: 'Bookings',
                data: statewiseData.map(item => item.state_count),
                backgroundColor: '#FF9F40',
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
    </script>
</body>
</html>
