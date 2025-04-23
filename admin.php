<?php
$servername = "localhost";
$username = "root"; // Change if different
$password = ""; // Change if different
$dbname = "project"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Total Users
$userQuery = "SELECT COUNT(*) AS totalUsers FROM registration";
$userResult = $conn->query($userQuery);
$totalUsers = ($userResult->num_rows > 0) ? $userResult->fetch_assoc()['totalUsers'] : 0;

// Total Amount
$amountQuery = "SELECT SUM(totalAmount) AS totalAmount FROM transactions";
$amountResult = $conn->query($amountQuery);
$totalAmount = ($amountResult->num_rows > 0) ? $amountResult->fetch_assoc()['totalAmount'] : 0;

//Total service booked

// Total Service Count
$serviceQuery = "SELECT COUNT(id) AS totalServices FROM bookingdata";
$serviceResult = $conn->query($serviceQuery);
$totalServices = ($serviceResult->num_rows > 0) ? $serviceResult->fetch_assoc()['totalServices'] : 0;

// Today's Service Count
$todayServiceQuery = "SELECT COUNT(id) AS todayServices 
                     FROM bookingdata 
                     WHERE DATE(STR_TO_DATE(bookingDate, '%Y-%m-%d')) = CURDATE()";
$todayServiceResult = $conn->query($todayServiceQuery);
$todayServices = ($todayServiceResult->num_rows > 0) ? $todayServiceResult->fetch_assoc()['todayServices'] : 0;

// Total Farm Size
$farmQuery = "SELECT SUM(farmSize) AS totalFarmSize FROM bookingdata";
$farmResult = $conn->query($farmQuery);
$totalFarmSize = ($farmResult->num_rows > 0) ? $farmResult->fetch_assoc()['totalFarmSize'] : 0;

//Drone Status
$droneQuery = $conn->query("SELECT status, COUNT(*) AS count FROM drones GROUP BY status");
$droneStatus = ["Available" => 0, "Offline" => 0, "Maintenance" => 0, "Running" => 0];
while ($row = $droneQuery->fetch_assoc()) {
    $droneStatus[$row['status']] = $row['count'];
}

// Crop Type Data (Current Year)
$cropTypeQuery = "SELECT crop, COUNT(*) as crop_count 
              FROM bookingdata 
                  WHERE YEAR(STR_TO_DATE(bookingDate, '%Y-%m-%d')) = YEAR(CURDATE())
              GROUP BY crop
              ORDER BY crop_count DESC";

$cropTypeResult = $conn->query($cropTypeQuery);
$cropTypeData = [];
$totalCropCount = 0;
while ($row = $cropTypeResult->fetch_assoc()) {
    $cropTypeData[] = $row;
    $totalCropCount += $row['crop_count'];
}

// Convert crop counts into percentages
foreach ($cropTypeData as &$crop) {
    $crop['percentage'] = round(($crop['crop_count'] / $totalCropCount) * 100, 2);
}

// Yearly Revenue Data
$yearlyRevenueQuery = "SELECT 
                        YEAR(STR_TO_DATE(bookingDate, '%Y-%m-%d')) as year,
                        SUM(t.totalAmount) as total_revenue
                      FROM bookingdata b
                      LEFT JOIN transactions t ON b.bookingId = t.bookingId
                      GROUP BY YEAR(STR_TO_DATE(bookingDate, '%Y-%m-%d'))
                      ORDER BY year DESC
                      LIMIT 5";
$yearlyRevenueResult = $conn->query($yearlyRevenueQuery);
$yearlyRevenueData = [];
while ($row = $yearlyRevenueResult->fetch_assoc()) {
    $yearlyRevenueData[] = $row;
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

// Chemical Usage Data
$chemicalQuery = "SELECT chemicalName as chemical, COUNT(*) as chemical_count 
                  FROM bookingdata 
                  WHERE YEAR(STR_TO_DATE(bookingDate, '%Y-%m-%d')) = YEAR(CURDATE())
                  GROUP BY chemicalName
                  ORDER BY chemical_count DESC";
$chemicalResult = $conn->query($chemicalQuery);
$chemicalData = [];
$totalChemicalCount = 0;
while ($row = $chemicalResult->fetch_assoc()) {
    $chemicalData[] = $row;
    $totalChemicalCount += $row['chemical_count'];
}

// Convert chemical counts into percentages
foreach ($chemicalData as &$chemical) {
    $chemical['percentage'] = round(($chemical['chemical_count'] / $totalChemicalCount) * 100, 2);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./Image/drone.png" type="image/x-icon">
    <title>Admin Panel Sidebar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <link rel="stylesheet" href="./Style/admin.css">
    <link rel="stylesheet" href="./Style/Common.css">
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
            <li><a href="./admin.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="./users.php"><i class="fas fa-user"></i> Users</a></li>
        <li><a href="./dronePanel.php"><i class="fas fa-helicopter"></i> Drone</a></li>
        <li><a href="./operators.php"><i class="fas fa-user"></i> Pilots</a></li>
        <li><a href="./fertilizerPanel.php"><i class="fas fa-flask"></i> Fertilizer</a></li>
            <li><a href="./logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>
  </div>
</header>
<br><br>
    <div class="content">
        <h1>Admin Dashboard</h1>
        <br>
        <div class="dashboard">
            <div class="card">💰 Total Revenue <h2>₹<?php echo number_format($totalAmount, 2); ?></h2></div>
            <div class="card">👤 Total Users <h2><?php echo $totalUsers; ?></h2></div>
            <div class="card">🌾 Total Farm Size <h2><?php echo number_format($totalFarmSize, 2); ?> acres</h2></div>
            <div class="card">📅 Today's Services <h2><?php echo $todayServices; ?></h2></div>
            <div class="card">🌾 Total Services <h2><?php echo number_format($totalServices); ?></h2></div>
            <div class="card">🚁 Available Drones <h2><?php echo $droneStatus['Available']; ?></h2></div>
            <div class="card">🔴 Offline Drones <h2><?php echo $droneStatus['Offline']; ?></h2></div>
            <div class="card">🛠️ Maintenance Drones <h2><?php echo $droneStatus['Maintenance']; ?></h2></div>
            <div class="card">🟢 Running Drones <h2><?php echo $droneStatus['Running']; ?></h2></div>
        </div>
        <br>

        <!-- Recent Bookings Section -->
        <div class="recent-bookings">
            <h2>Recent Bookings</h2>
            <div class="table-responsive">
                <table class="booking-table">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Customer Name</th>
                            <th>Farm Size</th>
                            <th>Crop Type</th>
                            <th>Booking Date</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch recent bookings
                        $recentBookingsQuery = "SELECT b.*, r.username as customer_name, t.totalAmount 
                                              FROM bookingdata b 
                                              JOIN registration r ON b.email = r.email 
                                              LEFT JOIN transactions t ON b.bookingId = t.bookingId 
                                              ORDER BY STR_TO_DATE(bookingDate, '%Y-%m-%d') DESC 
                                              LIMIT 10";
                        $recentBookingsResult = $conn->query($recentBookingsQuery);
                        
                        while($booking = $recentBookingsResult->fetch_assoc()):
                            $amount = isset($booking['totalAmount']) ? $booking['totalAmount'] : 0;
                        ?>
                        <tr>
                            <td>#<?php echo $booking['bookingId']; ?></td>
                            <td><?php echo $booking['customer_name']; ?></td>
                            <td><?php echo $booking['farmSize']; ?> acres</td>
                            <td><?php echo $booking['crop']; ?></td>
                            <td><?php echo $booking['bookingDate']; ?></td>
                            <td>₹<?php echo number_format($amount, 2); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="chart-container">
            <!-- First Row: Revenue Charts -->
            <div class="chart-row">
                <div class="chart-box">
                    <h2>Yearly Revenue</h2>
                    <canvas id="yearlyRevenueChart"></canvas>
                </div>
                <br>
                <div class="chart-box">
                    <h2>Monthly Revenue (<?php echo date('Y'); ?>)</h2>
                    <canvas id="monthlyRevenueChart"></canvas>
                </div>
            </div>
            <!-- Second Row: Distribution Charts -->
            <div class="chart-row">
                <div class="chart-box">
                    <h2>Crop Type Distribution</h2>
                    <canvas id="cropTypeChart"></canvas>
                </div>
                <br>
        <div class="chart-box">
                    <h2>Chemical Usage Distribution</h2>
                    <canvas id="chemicalChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleMenu() {
            var navLinks = document.getElementById("navLinks");
            navLinks.classList.toggle("show");
        }

        const cropTypeData = <?php echo json_encode($cropTypeData); ?>;
        const yearlyRevenueData = <?php echo json_encode($yearlyRevenueData); ?>;
        const monthlyRevenueData = <?php echo json_encode($monthlyRevenueData); ?>;
        const chemicalData = <?php echo json_encode($chemicalData); ?>;

        // Yearly Revenue Chart
        const yearlyRevenueCtx = document.getElementById('yearlyRevenueChart').getContext('2d');
        new Chart(yearlyRevenueCtx, {
            type: 'bar',
            data: {
                labels: yearlyRevenueData.map(item => item.year),
                datasets: [{
                    label: 'Yearly Revenue',
                    data: yearlyRevenueData.map(item => item.total_revenue),
                    backgroundColor: '#3498db',
                    borderColor: '#2980b9',
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
                            callback: function(value) {
                                return '₹' + value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Yearly Revenue Overview',
                        font: {
                            size: 16
                        }
                    }
                }
            }
        });

        // Monthly Revenue Chart
        const monthlyRevenueCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
        new Chart(monthlyRevenueCtx, {
            type: 'line',
            data: {
                labels: monthlyRevenueData.map(item => {
                    const date = new Date(2000, item.month - 1);
                    return date.toLocaleString('default', { month: 'short' });
                }),
                datasets: [{
                    label: 'Monthly Revenue',
                    data: monthlyRevenueData.map(item => item.total_revenue),
                    borderColor: '#2ecc71',
                    backgroundColor: 'rgba(46, 204, 113, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₹' + value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Monthly Revenue Trend',
                        font: {
                            size: 16
                        }
                    }
                }
            }
        });

        // Crop Type Pie Chart
        const cropTypeCtx = document.getElementById('cropTypeChart').getContext('2d');
        new Chart(cropTypeCtx, {
            type: 'pie',
            data: {
                labels: cropTypeData.map(item => `${item.crop} (${item.percentage}%)`),
                datasets: [{
                    data: cropTypeData.map(item => item.crop_count),
                    backgroundColor: [
                        '#FF6384',  // Pink
                        '#36A2EB',  // Blue
                        '#FFCE56',  // Yellow
                        '#4BC0C0',  // Teal
                        '#9966FF',  // Purple
                        '#FF9F40',  // Orange
                        '#FF6384',  // Pink
                        '#36A2EB'   // Blue
                    ],
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            font: {
                                size: 14,
                                weight: '500'
                            },
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    title: {
                        display: true,
                        text: 'Crop Type Distribution',
                        font: {
                            size: 20,
                            weight: '600'
                        },
                        padding: {
                            bottom: 20
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const data = cropTypeData[context.dataIndex];
                                return `${data.crop}: ${data.percentage}% (${data.crop_count} bookings)`;
                            }
                        }
                    }
                },
                cutout: '60%',
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });

        // Chemical Usage Chart
        const chemicalCtx = document.getElementById('chemicalChart').getContext('2d');
        new Chart(chemicalCtx, {
            type: 'doughnut',
            data: {
                labels: chemicalData.map(item => `${item.chemical} (${item.percentage}%)`),
                datasets: [{
                    data: chemicalData.map(item => item.chemical_count),
                    backgroundColor: [
                        '#FF6384',  // Pink
                        '#36A2EB',  // Blue
                        '#FFCE56',  // Yellow
                        '#4BC0C0',  // Teal
                        '#9966FF',  // Purple
                        '#FF9F40',  // Orange
                        '#FF6384',  // Pink
                        '#36A2EB'   // Blue
                    ],
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            font: {
                                size: 14,
                                weight: '500'
                            },
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    title: {
                        display: true,
                        text: 'Chemical Usage Distribution',
                        font: {
                            size: 20,
                            weight: '600'
                        },
                        padding: {
                            bottom: 20
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const data = chemicalData[context.dataIndex];
                                return `${data.chemical}: ${data.percentage}% (${data.chemical_count} bookings)`;
                            }
                        }
                    }
                },
                cutout: '60%',
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });
</script>
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

