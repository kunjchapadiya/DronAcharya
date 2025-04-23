<?php
session_start();
include 'connect.php';
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if user is already logged in
if (isset($_SESSION["user_id"])) {
    header("Location: home.php");
    exit();
}

// Check if operator is already logged in
if (isset($_SESSION["operator_id"])) {
    header("Location: operator-panel.php");
    exit();
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    
    // Check for admin login
    if ($email === "admin@dronacharya.com" && $password === "admin123") {
        $_SESSION["user_id"] = "admin";
        $_SESSION["username"] = "Admin";
        header("Location: admin.php");
        exit();
    }

    // Check for operator login
    if ($email === "operator@dronacharya.com" && $password === "operator1234") {
        $_SESSION["operator_id"] = "operator";
        $_SESSION["username"] = "Operator";
        header("Location: operator-panel.php");
        exit();
    }

    // Operator login
    if (substr($email, -15) === '@dronacharya.com') {
        $query = "SELECT * FROM operator WHERE email = '$email'";
        $result = mysqli_query($con, $query);
        if (mysqli_num_rows($result) > 0) {
            $operator = mysqli_fetch_assoc($result);
            if ($operator['password'] === $password) {
                $_SESSION["operator_id"] = $operator['id'];
                $_SESSION["username"] = $operator['username'];
                header("Location: operator-panel.php");
                exit();
            }
        }
    } else {
        $query = "SELECT * FROM registration WHERE email = '$email'";
        $result = mysqli_query($con, $query);
        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            if ($user['password'] === $password) {
                $_SESSION["user_id"] = $user['id'];
                $_SESSION["username"] = $user['username'];
                $_SESSION["email"] = $user['email'];
                header("Location: home.php");
                exit();
            }
        }
    }

    $message = "Invalid email or password!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DronAcharya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./Style/login.css">
</head>
<body>
    <div class="container-fluid">
        <div class="login-container">
            <div class="form-section">
                <h2>Login</h2>
                <form method="post" action="">
                    <div class="form-floating">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                        <label for="email">Email</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control" id="password" name="password" maxlength="16" minlength="8" placeholder="Password" required>
                        <label for="password">Password</label>
                    </div>
                    <div class="forgot-password">
                        <a href="forgotPassword.php">Forgot your password?</a>
                    </div>
                    <button type="submit" name="submit" class="btn btn-submit">Sign In</button>
                </form>
            </div>
            <div class="info-section">
                <h1>DronAcharya</h1>
                <p>spayer service</p>
                <p>Efficiency from Above, Growth from Below.</p>
                <a href="register.php" class="btn-create">Create an Account</a>
            </div>
        </div>
    </div>
</body>
</html>

