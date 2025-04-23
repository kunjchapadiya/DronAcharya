<?php
session_start();
require_once('connect.php');

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];
$message = '';

// Database connection
$conn = mysqli_connect("localhost", "root", "", "project");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = $_POST['username'];
    $new_email = $_POST['email'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    // Verify current password
    $check_sql = "SELECT password FROM registration WHERE email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && $user['password'] === $current_password) {
        // Update profile
        $update_sql = "UPDATE registration SET email = ?, username = ?";
        $params = [$new_email, $new_username];
        $types = "ss";

        if (!empty($new_password)) {
            $update_sql .= ", password = ?";
            $params[] = $new_password;
            $types .= "s";
        }

        $update_sql .= " WHERE email = ?";
        $params[] = $email;
        $types .= "s";

        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param($types, ...$params);

        if ($update_stmt->execute()) {
            $_SESSION['email'] = $new_email;
            $message = "Profile updated successfully!";
        } else {
            $message = "Error updating profile.";
        }
    } else {
        $message = "Current password is incorrect.";
    }
}

// Fetch user data
$user_sql = "SELECT * FROM registration WHERE email = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("s", $email);
$user_stmt->execute();
$user = $user_stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Security - DronAcharya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="Style/common.css">
    <link rel="stylesheet" href="Style/security.css">
</head>
<body>
<?php
    $isLoggedIn = isset($_SESSION["user_id"]);
    ?>

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
                <li><a href="home.php">Home</a></li>
                <li><a href="Book-service.php">Book Service</a></li>
                <li><a href="Chemical.php">Chemicals</a></li>
                <li><a href="about.php">About us</a></li>
                <?php if ($isLoggedIn): ?>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Register/Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
<br><br><br>
    <div class="security-section">
        <div class="container">
            <h1 class="page-title">Login & Security</h1>
            
            <?php if ($message): ?>
                <div class="alert alert-info"><?php echo $message; ?></div>
            <?php endif; ?>
<br>
            <div class="security-card">
                <form method="POST">
                    <div class="form-group">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" 
                               value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>
<br>
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
<br>
                    <div class="form-group">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="current_password" 
                               name="current_password" required>
                    </div>
<br>
                    <div class="form-group">
                        <label for="new_password" class="form-label">New Password (optional)</label>
                        <input type="password" class="form-control" id="new_password" 
                               name="new_password">
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">Update Security Settings</button>
                </form>
            </div>
        </div>
    </div>
<br><br>
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="Scripts/home.js"></script>
    <script src="Scripts/security.js"></script>
</body>
</html> 