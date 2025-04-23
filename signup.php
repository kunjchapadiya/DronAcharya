<?php
include 'connect.php';

$message = '';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username         = $_POST["username"];
    $email            = $_POST["email"];
    $password         = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    
    if ($password !== $confirm_password) {
        $message = "Passwords do not match.";
    } else {
  
        // Insert the new user into the database
        // $sql = "insert into `registration` (username, email, password) values('$username','$email','$password')";
        // $result=mysqli_query($con,$sql);
      
      
        // if ($result) {
        //     $message = "Signup successful";
        // } else {
        //     die(mysqli_error($con));
        // }

        $sql = "select * from `registration` where username = '$username'";

        $result=mysqli_query($con,$sql);
        if($result){
          $num=mysqli_num_rows($result);
          if($num>0){
            $message = "User already exist";
          }else{
            $sql = "insert into `registration` (username, email, password) values('$username','$email','$password')";
            $result=mysqli_query($con,$sql);

            if ($result) {
                echo "<script>alert('Account created successfully!'); window.location.href='login.php';</script>";
              } else {
                  die(mysqli_error($con));
              }

          }
        }

    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
    <link rel="icon" href= 
"./Image/drone.png" 
          type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./Style/login.css"> <!-- External CSS -->
</head>
<body>
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="row w-75 shadow-lg rounded">
        <!-- Left Section: Login Form -->
        <div class="col-md-6 p-5 text-center login-section">
            <h2>Create an Account</h2><br><br>
            <form action="signup.php" method="post">
                <div class="form-group">
                    <!-- <label for="username">Username</label> -->
                    <input type="text" name="username" id="username" class="form-control" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <!-- <label for="email">Email</label> -->
                    <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <!-- <label for="password">Password</label> -->
                    <input type="password" name="password" id="password" placeholder="Password" class="form-control" required>
                </div>
                <div class="form-group">
                    <!-- <label for="confirm_password">Confirm Password</label> -->
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control"  placeholder="Re-Enter Password" required>
                </div>
                <button type="submit" class="btn btn-danger btn-block">Sign In</button>
            </form>
        </div>
        
        <!-- Right Section: Signup Invitation -->
        <div class="col-md-6 p-5 text-center text-white signup-section">
            <h2>DronAcharya</h2>
            <p><b>spayer service</b></p>
            <p>Efficiency from Above, Growth from Below.</p>
            <form action="./login.php">
                <button type="submit" class="btn btn-outline-light">Already Have an Accoount?</button>
            </form>
        </div>
    </div>
</body>
</html>


