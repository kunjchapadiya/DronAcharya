<?php
$servername = "localhost";
$username = "root"; // Change if needed
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add Fertilizer
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $inventory_stock = $_POST['inventory_stock'];
    $expiry_date = $_POST['expiry_date'];

    $query = "INSERT INTO fertilizers (name, quantity, inventory_stock, expiry_date) 
              VALUES ('$name', '$quantity', '$inventory_stock', '$expiry_date')";

    if ($conn->query($query) === TRUE) {
        echo "<script>alert('Fertilizer added successfully!'); window.location.href='fertilizerPanel.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// Update Inventory Stock
if (isset($_POST['update_stock'])) {
    $id = (int)$_POST['id'];
    $stock_change = (int)$_POST['stock_change'];

    $check_query = "SELECT inventory_stock FROM fertilizers WHERE id = $id";
    $result = $conn->query($check_query);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $new_stock = $row['inventory_stock'] + $stock_change; // Update stock

        if ($new_stock < 0) {
            echo "<script>alert('Error: Stock cannot be negative!');</script>";
        } else {
            $update_query = "UPDATE fertilizers SET inventory_stock = $new_stock WHERE id = $id";
            if ($conn->query($update_query) === TRUE) {
                echo "<script>alert('Inventory stock updated successfully!'); window.location.href='fertilizerPanel.php';</script>";
            } else {
                echo "<script>alert('Error updating stock: " . $conn->error . "');</script>";
            }
        }
    }
}

// Delete Fertilizer
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $query = "DELETE FROM fertilizers WHERE id = $id";
    if ($conn->query($query) === TRUE) {
        echo "<script>alert('Fertilizer removed successfully!'); window.location.href='fertilizerPanel.php';</script>";
    } else {
        echo "<script>alert('Error deleting record: " . $conn->error . "');</script>";
    }
}

$result = $conn->query("SELECT * FROM fertilizers");
?>
<!DOCTYPE html>
<html>
<head>
<link rel="icon" href= 
"./Image/drone.png" 
          type="image/x-icon">
    <title>Fertilizer Management</title>
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
    <h2>Add Fertilizer</h2> <br>
    <form method="POST" enctype="multipart/form-data" action="fertilizerPanel.php">
    <div class="form-group">
        <input type="text" name="name" placeholder="Fertilizer Name" required>
        <input type="text" name="quantity" placeholder="Fertilizer Quantity" required>
        <label for="">ml/Acre</label>
        <input type="number" name="inventory_stock" placeholder="Inventory Stock" required>
        <label for="">Expiry Date</label>
        <input type="date" name="expiry_date" id="">
        <button type="submit" name="add">Add Fertilizer</button>
    </div>
</form>
<br>    

<h2>Fertilizer Inventory</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Quantity</th>
        <th>Inventory Stock</th>
        <th>Expiry Date</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['quantity'] ?> ml/Acres</td>
            <td><?= $row['inventory_stock'] ?> </td>
            <td><?= $row['expiry_date'] ?></td>
            <td>
                <a href="fertilizerPanel.php?delete=<?= $row['id'] ?>">
                    <button>Delete</button>
                </a>
                <br><br>
                <!-- Inventory Stock Update Form -->
                <form method="POST" action="fertilizerPanel.php">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <input type="number" name="stock_change" placeholder="Update Stock (+/-)" required>
                    <button type="submit" name="update_stock">Update</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
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
