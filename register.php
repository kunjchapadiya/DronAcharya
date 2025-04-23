<?php
session_start();
include 'connect.php';

// Check if user is already logged in
if (isset($_SESSION["user_id"])) {
    header("Location: home.php");
    exit();
}

$error = "";
$success = "";

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);
    
    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!')</script>";
    } else {
        // Check if email already exists
        $check_query = "SELECT * FROM registration WHERE email='$email'";
        $check_result = mysqli_query($con, $check_query);
        
        if(mysqli_num_rows($check_result) > 0) {
            echo "<script>alert('Email already exists!')</script>";
        } else {
            // Insert new user
            $insert_query = "INSERT INTO registration (email, username, password) VALUES ('$email', '$username', '$password')";
            $result = mysqli_query($con, $insert_query);
            
            if($result) {
                echo "<script>alert('Registration successful!')</script>";
                header('location:login.php');
            } else {
                echo "<script>alert('Something went wrong!')</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - DronAcharya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./Style/register.css">
</head>
<body>
    <div class="container-fluid">
        <div class="login-container">
            <div class="form-section">
                <h2>Create Account</h2>
                <form method="post" onsubmit="return validatePasswords()">
                    <div class="form-floating">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                        <label for="email">Email</label>
                    </div>
                    <div class="form-floating">
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                        <label for="username">Username</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control" id="password" name="password" minlength="8" maxlength="16" placeholder="Password" required>
                        <label for="password">Password</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" maxlength="8" placeholder="Confirm Password" required>
                        <label for="confirm_password">Confirm Password</label>
                    </div>
                    <button type="submit" name="submit" class="btn btn-submit">Register</button>
                    <div class="text-center mt-3">
                        <p>Already have an account? <a href="login.php">Login here</a></p>
                    </div>
                </form>
            </div>
            <div class="info-section">
                <h1>DronAcharya</h1>
                <p>spayer service</p>
                <p>Efficiency from Above, Growth from Below.</p>
                <a href="login.php" class="btn-create">Sign In</a>
            </div>
        </div>
    </div>

    <script>
    function validatePasswords() {
        var password = document.getElementById("password").value;
        var confirm_password = document.getElementById("confirm_password").value;
        
        if (password !== confirm_password) {
            alert("Passwords do not match!");
            return false;
        }
        return true;
    }
    </script>
</body>
</html> 