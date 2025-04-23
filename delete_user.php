<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->query("DELETE FROM registration WHERE id=$id");
    header("Location: users.php");
}
?>
