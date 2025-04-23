<?php
// Database Connection
$servername = "localhost";
$username = "root"; // Change if different
$password = ""; // Change if different
$dbname = "project"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch Users (Default Query)
$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM registration WHERE username LIKE '%$search%' OR email LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM registration";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="./Image/drone.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Users</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="./Style/admin.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">DronAcharya</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="./admin.php"><i class="fas fa-home"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="./users.php"><i class="fas fa-user"></i> Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./dronePanel.php"><i class="fas fa-helicopter"></i> Drone</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./operators.php"><i class="fas fa-user"></i> Pilots</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./fertilizerPanel.php"><i class="fas fa-flask"></i> Fertilizer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">Add a User</h4>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="add_user.php">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="username" class="form-control" placeholder="Username" required>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" name="add" class="btn btn-success">Add User</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Search Bar -->
                    <div class="card mt-4">
                        <div class="card-header bg-info text-white">
                            <h4 class="mb-0">Search Users</h4>
                        </div>
                        <div class="card-body">
                            <form method="GET" class="row g-3">
                                <div class="col-md-8">
                                    <input type="text" name="search" class="form-control" placeholder="Search by username or email" value="<?= htmlspecialchars($search) ?>">
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                    <?php if($search): ?>
                                        <a href="users.php" class="btn btn-secondary">Clear</a>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- User List -->
                    <div class="card mt-4">
                        <div class="card-header bg-dark text-white">
                            <h4 class="mb-0">User List</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Email</th>
                                            <th>Username</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?= $row['id'] ?></td>
                                                <td><?= $row['email'] ?></td>
                                                <td><?= $row['username'] ?></td>
                                                <td>
                                                    <a href="delete_user.php?id=<?= $row['id'] ?>" 
                                                       onclick="return confirm('Are you sure you want to delete this user?')"
                                                       class="btn btn-danger btn-sm">
                                                        <i class="fa-solid fa-trash"></i> Remove
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
