<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch Drone Operators
$operatorQuery = "SELECT * FROM drone_operators ORDER BY id DESC";
$operatorResult = $conn->query($operatorQuery);

// Handle Operator Actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $name = $conn->real_escape_string($_POST['name']);
                $email = $conn->real_escape_string($_POST['email']);
                $phone = $conn->real_escape_string($_POST['phone']);
                $license = $conn->real_escape_string($_POST['license']);
                $status = $conn->real_escape_string($_POST['status']);
                
                $addQuery = "INSERT INTO drone_operators (name, email, phone, license_number, status) 
                           VALUES ('$name', '$email', '$phone', '$license', '$status')";
                if ($conn->query($addQuery)) {
                    $_SESSION['success'] = "Operator added successfully!";
                } else {
                    $_SESSION['error'] = "Error adding operator: " . $conn->error;
                }
                break;
                
            case 'update':
                $id = $conn->real_escape_string($_POST['id']);
                $name = $conn->real_escape_string($_POST['name']);
                $email = $conn->real_escape_string($_POST['email']);
                $phone = $conn->real_escape_string($_POST['phone']);
                $license = $conn->real_escape_string($_POST['license']);
                $status = $conn->real_escape_string($_POST['status']);
                
                $updateQuery = "UPDATE drone_operators 
                              SET name='$name', email='$email', phone='$phone', 
                                  license_number='$license', status='$status' 
                              WHERE id='$id'";
                if ($conn->query($updateQuery)) {
                    $_SESSION['success'] = "Operator updated successfully!";
                } else {
                    $_SESSION['error'] = "Error updating operator: " . $conn->error;
                }
                break;
                
            case 'delete':
                $id = $conn->real_escape_string($_POST['id']);
                $deleteQuery = "DELETE FROM drone_operators WHERE id='$id'";
                if ($conn->query($deleteQuery)) {
                    $_SESSION['success'] = "Operator deleted successfully!";
                } else {
                    $_SESSION['error'] = "Error deleting operator: " . $conn->error;
                }
                break;
        }
        // Redirect to refresh the page
        header("Location: operators.php");
        exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./Image/drone.png" type="image/x-icon">
    <title>Drone Operators Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="./Style/admin.css">
    <link rel="stylesheet" href="./Style/operators.css">
    <link rel="stylesheet" href="./Style/Common.css"></head>
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

    <div class="content">
        <div class="page-header">
            <h1>Drone Operators Management</h1>
            <button class="add-btn" onclick="showAddOperatorForm()">
                <i class="fas fa-plus"></i> Add New Operator
            </button>
        </div>

        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert success">
                <?php 
                echo $_SESSION['success'];
                unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert error">
                <?php 
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <!-- Add Operator Form -->
        <div id="addOperatorForm" class="form-section" style="display: none;">
            <h2>Add New Operator</h2>
            <form method="POST" class="operator-form">
                <input type="hidden" name="action" value="add">
                
                <div class="form-group">
                    <label>Name:</label>
                    <input type="text" name="name" required>
                </div>
                
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label>Phone:</label>
                    <input type="tel" name="phone" required>
                </div>
                
                <div class="form-group">
                    <label>License Number:</label>
                    <input type="text" name="license" required>
                </div>
                
                <div class="form-group">
                    <label>Status:</label>
                    <select name="status" required>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                        <option value="On Leave">On Leave</option>
                    </select>
                </div>
                
                <div class="form-buttons">
                    <button type="submit" class="submit-btn">Save Operator</button>
                    <button type="button" class="cancel-btn" onclick="hideAddOperatorForm()">Cancel</button>
                </div>
            </form>
        </div>

        <div class="operators-section">
            <div class="operators-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>License Number</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($operator = $operatorResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $operator['id']; ?></td>
                            <td><?php echo $operator['name']; ?></td>
                            <td><?php echo $operator['email']; ?></td>
                            <td><?php echo $operator['phone']; ?></td>
                            <td><?php echo $operator['license_number']; ?></td>
                            <td>
                                <span class="status-badge <?php echo strtolower($operator['status']); ?>">
                                    <?php echo $operator['status']; ?>
                                </span>
                            </td>
                            <td class="action-buttons">
                                <button class="edit-btn" onclick="showEditOperatorForm(<?php echo htmlspecialchars(json_encode($operator)); ?>)">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="delete-btn" onclick="deleteOperator(<?php echo $operator['id']; ?>)">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Edit Operator Form -->
        <div id="editOperatorForm" class="form-section" style="display: none;">
            <h2>Edit Operator</h2>
            <form method="POST" class="operator-form">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" id="editOperatorId">
                
                <div class="form-group">
                    <label>Name:</label>
                    <input type="text" name="name" id="editOperatorName" required>
                </div>
                
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" id="editOperatorEmail" required>
                </div>
                
                <div class="form-group">
                    <label>Phone:</label>
                    <input type="tel" name="phone" id="editOperatorPhone" required>
                </div>
                
                <div class="form-group">
                    <label>License Number:</label>
                    <input type="text" name="license" id="editOperatorLicense" required>
                </div>
                
                <div class="form-group">
                    <label>Status:</label>
                    <select name="status" id="editOperatorStatus" required>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                        <option value="On Leave">On Leave</option>
                    </select>
                </div>
                
                <div class="form-buttons">
                    <button type="submit" class="submit-btn">Update Operator</button>
                    <button type="button" class="cancel-btn" onclick="hideEditOperatorForm()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script src="./Scripts/admin.js"></script>
    <script src="./Scripts/operators.js"></script>
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