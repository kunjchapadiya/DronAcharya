<?php
$servername = "localhost";
$username = "root"; // Change if necessary
$password = ""; // Change if necessary
$dbname = "project"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed: " . $conn->connect_error]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['id']) || !isset($_POST['status'])) {
        die(json_encode(["success" => false, "message" => "Invalid request. Missing parameters."]));
    }

    $id = intval($_POST['id']); // Ensure it's an integer
    $status = trim($_POST['status']); // Trim any spaces

    // Check if ID exists in the database
    $checkQuery = "SELECT id FROM drones WHERE id = ?";
    $stmtCheck = $conn->prepare($checkQuery);
    $stmtCheck->bind_param("i", $id);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();

    if ($result->num_rows == 0) {
        die(json_encode(["success" => false, "message" => "Drone ID not found."]));
    }

    $stmtCheck->close();

    // Update status
    $query = "UPDATE drones SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die(json_encode(["success" => false, "message" => "SQL Prepare failed: " . $conn->error]));
    }

    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Status updated successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error updating status: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}

$conn->close();
?>
