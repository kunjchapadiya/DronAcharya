<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drone Sprayer Admin Panel</title>
    <link rel="stylesheet" href="./Style/admin.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="#dashboard">Dashboard</a></li>
            <li><a href="#drones">Drones</a></li>
            <li><a href="#jobs">Jobs</a></li>
            <li><a href="#reports">Reports</a></li>
            <li><a href="#users">Users</a></li>
        </ul>
    </div>
    <div class="main-content">
        <section id="dashboard" class="active">
            <h1>Dashboard</h1>
            <canvas id="jobChart"></canvas>
        </section>
        <section id="drones">
            <h1>Drone Management</h1>
            <p>Track and manage drones.</p>
        </section>
        <section id="jobs">
            <h1>Job Scheduling</h1>
            <p>Assign and track spraying jobs.</p>
        </section>
        <section id="reports">
            <h1>Reports & Analytics</h1>
            <p>View service reports.</p>
        </section>
        <section id="users">
            <h1>User Management</h1>
            <p>Manage operators and customers.</p>
        </section>
    </div>
    <script src="Scripts/admin.js"></script>
</body>
</html>
