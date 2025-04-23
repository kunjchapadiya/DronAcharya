<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch only chemical names that have stock
$sql = "SELECT name FROM fertilizers WHERE inventory_stock > 0";
$result = $conn->query($sql);

$chemicals = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $chemicals[] = array(
            'name' => $row['name']
        );
    }
}

// Return as JSON
header('Content-Type: application/json');
echo json_encode($chemicals);

$conn->close();
?> 