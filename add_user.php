<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Secure password

    $sql = "INSERT INTO registration (email, username, password) VALUES ('$email', '$username', '$password')";
    
    if ($conn->query($sql)) {
        header("Location: users.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
