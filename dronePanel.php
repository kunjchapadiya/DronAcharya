<?php
$servername = "localhost";
$username = "root"; // Change if needed
$password = ""; // Change if needed
$dbname = "project"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add Drone
if (isset($_POST['add'])) {
    $drone_name = $_POST['drone_name'];
    $manufacture = $_POST['manufacture'];
    $payloadCapacity = $_POST['payloadCapacity'];
    $battery = $_POST['battery'];
    $status = $_POST['status'];
    $lastMaintenance = $_POST['lastMaintenance'];
    
    $query = "INSERT INTO drones (droneName, manufacture, payloadCapicity, battery, status, lastMaintenance) 
              VALUES ('$drone_name', '$manufacture', '$payloadCapacity', '$battery', '$status', '$lastMaintenance')";

    if ($conn->query($query) === TRUE) {
        echo "<script>alert('Drone added successfully!'); window.location.href='dronePanel.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// Delete Drone
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    
    $query = "DELETE FROM drones WHERE id = $id";
    if ($conn->query($query) === TRUE) {
        echo "<script>alert('Drone removed successfully!'); window.location.href='dronePanel.php';</script>";
    } else {
        echo "<script>alert('Error deleting record: " . $conn->error . "');</script>";
    }
}

$result = $conn->query("SELECT * FROM drones");

?>
<!DOCTYPE html>
<html>
<head>
    <title>Drone Admin Panel</title>
    <link rel="icon" href= 
"./Image/drone.png" 
          type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="./Style/dronePanel.css">
    <link rel="stylesheet" href="./Style/Common.css">
</head>
<body>
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
        <li><a href="./admin.php"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="./users.php"><i class="fas fa-user"></i> Users</a></li>
        <li><a href="./dronePanel.php"><i class="fas fa-helicopter"></i> Drone</a></li>
        <li><a href="./operators.php"><i class="fas fa-user"></i> Pilots</a></li>
        <li><a href="./fertilizerPanel.php"><i class="fas fa-flask"></i> Fertilizer</a></li>
        <li><a href="./logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
      </ul>
    </nav>
  </div>
</header>
<br><br>

<h2>Add a Drone </h2> <br>
<form method="POST" enctype="multipart/form-data" action="dronePanel.php">
    <div class="form-group">
        <input type="text" name="drone_name" placeholder="Drone Name" required>
        <input type="text" name="manufacture" placeholder="Manufacture" required>
        <input type="number" name="payloadCapacity" placeholder="Payload Capacity" required>
    </div>
    <div class="form-group">
        <input type="number" name="battery" placeholder="Battery" required>
        <label for="">Drone Status</label>
        <select name="status">
            <option value="Available">Available</option>
            <option value="Running">Running</option>
            <option value="Maintenance">Maintenance</option>
            <option value="Offline">Offline</option>
        </select>
        <label for="">Last Maintenance</label>
        <input type="date" name="lastMaintenance" required>
        <button type="submit" name="add">Add Drone</button>
    </div>
</form>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Manufacture</th>
        <th>Payload</th>
        <th>Battery</th>
        <th>Status</th>
        <th>Last Maintenance</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['droneName'] ?></td>
            <td><?= $row['manufacture'] ?></td>
            <td><?= $row['payloadCapicity'] ?></td>
            <td><?= $row['battery'] ?></td>
            <td>
                <select class="status-dropdown" data-id="<?= $row['id'] ?>">
                    <option value="Available" <?= $row['status'] == 'Available' ? 'selected' : '' ?>>Available</option>
                    <option value="Running" <?= $row['status'] == 'Running' ? 'selected' : '' ?>>Running</option>
                    <option value="Maintenance" <?= $row['status'] == 'Maintenance' ? 'selected' : '' ?>>Maintenance</option>
                    <option value="Offline" <?= $row['status'] == 'Offline' ? 'selected' : '' ?>>Offline</option>
                </select>
                <button class="update-status-btn" data-id="<?= $row['id'] ?>">Update</button>
            </td>
            <td><?= $row['lastMaintenance'] ?></td>
            <td>
                <a href="dronePanel.php?delete=<?= urlencode($row['id']) ?>">
                    <button type="button" name="delete"><i class="fa-solid fa-trash" style="color: #fa0019;"></i> Remove</button>
                </a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".update-status-btn").forEach(function (button) {
        button.addEventListener("click", function () {
            let droneId = this.getAttribute("data-id");
            let dropdown = document.querySelector(`.status-dropdown[data-id='${droneId}']`);
            let newStatus = dropdown.value;

            fetch("update_status.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: `id=${droneId}&status=${encodeURIComponent(newStatus)}`
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (!data.success) {
                    location.reload(); // Reload if update fails
                }
            })
            .catch(error => console.error("Error:", error));
        });
    });
});
</script>
<script>
const menuBtn = document.querySelector(".header .menu-btn");
const menu = document.querySelector(".header .menu");

function toggleMenu() {
  menuBtn.classList.toggle("active");
  menu.classList.toggle("open");
}
menuBtn.addEventListener("click", toggleMenu);

</script>

</body>
</html>
