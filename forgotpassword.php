<?php
include 'connect.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    
    if ($password !== $confirm_password) {
        $message = "Passwords do not match.";
    } else {
        // Check if the user exists
        $sql = "SELECT * FROM `registration` WHERE email = '$email'";
        $result = mysqli_query($con, $sql);
        
        if ($result) {
            $num = mysqli_num_rows($result);
            if ($num > 0) {
                // Update password without hashing
                $update_sql = "UPDATE `registration` SET password='$password' WHERE email='$email'";
                $update_result = mysqli_query($con, $update_sql);
                
                if ($update_result) {
                    $message = "Password updated successfully. <a href='login.php'>Login here</a>";
                } else {
                    $message = "Error updating password: " . mysqli_error($con);
                }
            } else {
                $message = "No account found with this email.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="icon" href= 
"./Image/drone.png" 
          type="image/x-icon">
    <!-- Bootstrap CSS for responsiveness -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .form-container {
            margin-top: 50px;
            max-width: 500px;
        }
    </style>
</head>
<body>
<div class="container d-flex justify-content-center">
    <div class="form-container w-100">
        <h2 class="mb-4">Reset Password</h2>
        <?php if ($message != '') { echo '<div class="alert alert-info">' .$message. '</div>'; } ?>
        <form action="./forgotpassword.php" method="post">
             <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
             </div>
             <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
             </div>
             <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
             </div>
             <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
             <p class="mt-3">Remember your password? <a href="login.php">Login</a></p>
        </form>
    </div>
</div>
</body>
</html>
