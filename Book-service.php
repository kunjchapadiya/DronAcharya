<?php
// Database connection
$servername = "127.0.0.1"; // or "localhost"
$username = "root"; // Change this if using a different username
$password = ""; // Change this if you have a password
$database = "project"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

// If user is not logged in, redirect to login page
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php?redirect=Book-service.php");
    exit();
}

// Check number of bookings in last 24 hours
$user_email = $_SESSION["email"]; // Ensure this is set correctly
$check_bookings_sql = "SELECT COUNT(*) as booking_count FROM bookingdata 
                      WHERE email = ? 
                      AND bookingDate >= NOW() - INTERVAL 24 HOUR";

$stmt = $conn->prepare($check_bookings_sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$booking_count = $result->fetch_assoc()['booking_count'];

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if user has already made 4 bookings in last 24 hours
    if ($booking_count >= 4) {
        echo "<script>
            alert('You have reached the maximum limit of 4 bookings in 24 hours. Please try again later.');
            window.location.href='home.php';
        </script>";
        exit();
    }

    // Check chemical availability
    $chemicalName = $_POST['chemicalName'];
    $chemicalQuantity = $_POST['chemicalQuantity'];
    
    // Query to check chemical availability
    $check_stock_sql = "SELECT inventory_stock FROM fertilizers WHERE name = ?";
    $stmt = $conn->prepare($check_stock_sql);
    $stmt->bind_param("s", $chemicalName);
    $stmt->execute();
    $stock_result = $stmt->get_result();
    
    if ($stock_result->num_rows === 0) {
        echo "<script>
            alert('Selected chemical is not available in our inventory. Please select a different chemical.');
            window.history.back();
        </script>";
        exit();
    }
    
    $stock_row = $stock_result->fetch_assoc();
    $available_stock = $stock_row['inventory_stock'];
    
    if ($available_stock < $chemicalQuantity) {
        echo "<script>
            alert('Sorry, we only have " . $available_stock . " units of this chemical in stock. Please adjust your quantity.');
            window.history.back();
        </script>";
        exit();
    }

    // Generate a random 10-digit booking ID
    $bookingId = str_pad(rand(0, 9999999999), 10, '0', STR_PAD_LEFT);
    $_SESSION['bookingId'] = $bookingId;

    $name = $_POST['name'];
    $email = $_POST['email'];
    $contactNo = $_POST['contactNo'];
    $farmLocation = $_POST['farmLocation'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $farmSize = $_POST['farmSize'];
    $crop = $_POST['crop'];
    $terrainInformation = $_POST['terrainInfo'];
    $bookingDate = $_POST['bookingDate'];
    $growthStage = $_POST['growingStage'];
    $permissions = $_POST['permissions'];
    $waterSource = $_POST['waterSource'];
    $powerSource = $_POST['powerSupply'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert booking data
        $sql = "INSERT INTO bookingdata (bookingId, name, email, contactNo, farmLocation, city, state, farmSize, crop, terrainInformation, chemicalName, chemicalQuantity, bookingDate, growthStage, permissions, waterSource, powerSource) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssssssssssss", 
            $bookingId, $name, $email, $contactNo, $farmLocation, $city, $state, 
            $farmSize, $crop, $terrainInformation, $chemicalName, 
            $chemicalQuantity, $bookingDate, $growthStage, $permissions, 
            $waterSource, $powerSource);

        if (!$stmt->execute()) {
            throw new Exception("Error inserting booking data: " . $stmt->error);
        }

        // Update inventory stock
        $update_stock_sql = "UPDATE fertilizers SET inventory_stock = inventory_stock - ? WHERE name = ?";
        $stmt = $conn->prepare($update_stock_sql);
        $stmt->bind_param("is", $chemicalQuantity, $chemicalName);
        
        if (!$stmt->execute()) {
            throw new Exception("Error updating inventory: " . $stmt->error);
        }

        // Commit transaction
        $conn->commit();

        // Store bookingId in session and redirect to payment page
        $_SESSION['bookingId'] = $bookingId;
        header("Location: payment.php?farmSize=" . urlencode($farmSize));
        exit();

    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo "<script>
            alert('Error: " . $e->getMessage() . "');
            window.history.back();
        </script>";
        exit();
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["farmSize"])) {
  $farmSize = $_POST["farmSize"];
  header("Location: payment.php?farmSize=" . urlencode($farmSize)); // Redirect with farm size
  exit();
}

// Remove the alert banner
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href= 
"./Image/drone.png" 
          type="image/x-icon">
  <title>Document</title>
  <link rel="stylesheet" href="Style/Book-service.css">
  <link rel="stylesheet" href="Style/Common.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,400;0,700;1,700&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=dark_mode" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
  <script src="Scripts/book-service.js"></script>
</head>

<body>
<?php
// session_start();
$isLoggedIn = isset($_SESSION["user_id"]); // Check if user is logged in
?>

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
                <li><a href="home.php">Home</a></li>
                <li><a href="Book-service.php">Book Service</a></li>
                <li><a href="Chemical.php">Chemicals</a></li>
                <li><a href="about.php">About us</a></li>
                <li><a href="profile.php">Profile</a></li>
                <?php if ($isLoggedIn): ?>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Register/Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
  <div class="container mt-5">
    <br><br>
    <h2 class="text-center mb-4">Book Drone Sprayer Service</h2>
    <form action="/Project/Book-service.php" method="POST" id="bookingForm">
      <!-- Contact Details Section with Border -->
      <div class="contact-details border border-success p-4">
        <h4 class="mt-4">Contact Information</h4>
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="contactName" class="form-label">Name</label>
            <input type="text" class="form-control" id="contactName" name="name" placeholder="Enter your name" required>
          </div>
          <div class="col-md-6">
            <label for="contactEmail" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" id="contactEmail" placeholder="Enter your email" required>
          </div>
        </div>
        <div class="mb-3">
          <label for="contactPhone" class="form-label">Phone Number</label>
          <input type="tel" class="form-control" id="contactPhone" name="contactNo" 
       placeholder="Enter your phone number" required 
       pattern="\d{10}" maxlength="10" minlength="10" 
       title="Phone number must be exactly 10 digits">

        </div>
      </div>
      <br><br>
      <!-- Booking Service Section with Border -->
      <div class="booking-service  border border-success p-4 mb-4">
        <h4>Farm Details</h4>
        <div class="row mb-3">


          <div class="col-md-6">
            <label for="farmSize" class="form-label">Farm Size (in acres)</label>
            <input type="number" class="form-control" id="farmSize" name="farmSize" placeholder="Enter farm size" min="1" step="1" required>
          </div>

          <div class="col-md-6">
            <label for="farmLocation" class="form-label">Farm Location (GPS or Address)</label>
            <input type="text" class="form-control" id="farmLocation" name="farmLocation" placeholder="Enter your farm location" required>
          </div>

          <div class="col-md-6">
            <label for="city" class="form-label">City</label>
            <input type="text" class="form-control" id="city" name="city" placeholder="Enter city name" required>
          </div>
          <div class="col-md-6">
            <label for="state" class="form-label">State</label>
            <select class="form-control" id="state" name="state" required>
              <option value="">Select State</option>
              <option value="Andhra Pradesh">Andhra Pradesh</option>
              <option value="Arunachal Pradesh">Arunachal Pradesh</option>
              <option value="Assam">Assam</option>
              <option value="Bihar">Bihar</option>
              <option value="Chhattisgarh">Chhattisgarh</option>
              <option value="Delhi">Delhi</option>
              <option value="Goa">Goa</option>
              <option value="Gujarat">Gujarat</option>
              <option value="Haryana">Haryana</option>
              <option value="Himachal Pradesh">Himachal Pradesh</option>
              <option value="Jammu and Kashmir">Jammu and Kashmir</option>
              <option value="Jharkhand">Jharkhand</option>
              <option value="Karnataka">Karnataka</option>
              <option value="Kerala">Kerala</option>
              <option value="Ladakh">Ladakh</option>
              <option value="Madhya Pradesh">Madhya Pradesh</option>
              <option value="Maharashtra">Maharashtra</option>
              <option value="Manipur">Manipur</option>
              <option value="Meghalaya">Meghalaya</option>
              <option value="Mizoram">Mizoram</option>
              <option value="Nagaland">Nagaland</option>
              <option value="Odisha">Odisha</option>
              <option value="Puducherry">Puducherry</option>
              <option value="Punjab">Punjab</option>
              <option value="Rajasthan">Rajasthan</option>
              <option value="Sikkim">Sikkim</option>
              <option value="Tamil Nadu">Tamil Nadu</option>
              <option value="Telangana">Telangana</option>
              <option value="Tripura">Tripura</option>
              <option value="Uttar Pradesh">Uttar Pradesh</option>
              <option value="Uttarakhand">Uttarakhand</option>
              <option value="West Bengal">West Bengal</option>
            </select>
          </div>

        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label for="cropSelect" class="form-label">Select Crop</label>
            <select class="form-select" id="cropSelect" name="crop" required>
              <option value="">Select a crop</option>
              <option value="Rice - चावल">Rice - चावल</option>
              <option value="Wheat - गेहूं">Wheat - गेहूं</option>
              <option value="Maize - मक्का">Maize - मक्का</option>
              <option value="Cotton - कपास">Cotton - कपास</option>
              <option value="Sugarcane - गन्ना">Sugarcane - गन्ना</option>
              <option value="Tea - चाय">Tea - चाय</option>
              <option value="Coffee - कॉफी">Coffee - कॉफी</option>
              <option value="Pulses - दालें">Pulses - दालें</option>
              <option value="Barley - जौ">Barley - जौ</option>
              <option value="Groundnut - मूंगफली">Groundnut - मूंगफली</option>
              <option value="Spices - मसाले">Spices - मसाले</option>
            </select>
          </div>
          <div class="col-md-6">
          <label for="chemicalSelect" class="form-label">Type of Chemical</label>
    <label for="chemicalName" class="form-label">Chemical Name</label>
    <select class="form-control form-select" id="chemicalName" name="chemicalName" required>
        <option value="">Select a chemical</option>
    </select>
          </div>
        </div>

        <!-- Spray Material -->
        <div class="row mb-3">
        <div class="col-md-6">
        <label for="terrainInfo" class="form-label">Terrain Information</label>
        <textarea class="form-control" id="terrainInfo" name="terrainInfo" rows="1" placeholder="Describe the terrain (e.g., obstacles, water bodies)"></textarea>
</div>
          <div class="col-md-6">
            <label for="chemicalQuantity" class="form-label">Quantity (in liters)</label>
            <input type="number" class="form-control"
              id="chemicalQuantity" name="chemicalQuantity" placeholder="Enter the quantity required" min="1" step="1" required>
          </div>
        </div>

        <!-- Timing and Conditions -->
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="sprayingDate" class="form-label">Preferred Date</label>
            <input type="date" class="form-control" id="sprayingDate"
              name="bookingDate" min="<?php echo date('Y-m-d'); ?>" required>
          </div>
          <div class="col-md-6">
            <label for="growthStage" class="form-label">Growth Stage of Crop</label>
            <select class="form-select" id="growthStage" name="growingStage" required>
              <option value="">Select growth stage</option>
              <option value="early">Early Growth</option>
              <option value="mid">Mid Growth</option>
              <option value="flowering">Flowering</option>
              <option value="mature">Mature</option>
            </select>
          </div>
        </div>

        <!-- Permissions and Compliance -->
        <div class="mb-3">
          <label for="permissions" class="form-label">Permissions (if any)</label>
          <textarea class="form-control" id="permissions" rows="3" name="permissions" placeholder="Provide details of any required permissions"></textarea>
        </div>

        <!-- On-Site Requirements -->
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="waterSource" class="form-label">Water Source Availability</label>
            <select class="form-select" id="waterSource" name="waterSource" required>
              <option value="">Select availability</option>
              <option value="yes">Yes</option>
              <option value="no">No</option>
            </select>
          </div>
          <div class="col-md-6">
            <label for="powerSupply" class="form-label">Power Supply Availability</label>
            <select class="form-select" id="powerSupply" name="powerSupply" required>
              <option value="">Select availability</option>
              <option value="yes">Yes</option>
              <option value="no">No</option>
            </select>
          </div>
        </div>
      </div>



      <!-- Submit Button -->
      <br>
      <!-- <button type="submit" class="btn btn-primary w-5" onclick="redirectToPayment(event)">Process Payment</button> -->
      <button type="submit" class="btn btn-primary w-5">Process Payment</button>
      <button type="reset" class="btn btn-danger w-5">Cancel Booking </button>
      <br><br>
    </form>
  </div>
  </div>

  <footer class="bg-dark text-white pt-5 pb-4">
    <div class="container">
      <div class="row">
        <div class="col-md-3 col-lg-4 col-xl-3 mb-4">
          <h6 class="text-uppercase font-weight-bold mb-4">About</h6>
          <p class="container">
            We are professionals in the field offering modern and Agriculture Mechanization rental services by
            understanding the exact requirements for increasing the production of crops.
          </p>
        </div>
        <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
          <h6 class="text-uppercase font-weight-bold mb-4">Products</h6>
          <p><a href="home.php" class="text-white">Home</a></p>
          <p><a href="Book-service.php" class="text-white">Book a Service</a></p>
          <p><a href="about.php" class="text-white">About us</a></p>
          <p><a href="Chemical.php" class="text-white">Chemicals</a></p>
        </div>
        <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
          <h6 class="text-uppercase font-weight-bold mb-4">Legals</h6>
          <p><a href="#" class="text-white">Terms & Conditions</a></p>
          <p><a href="#" class="text-white">Return Policy</a></p>
        </div>
        <div class="col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-4">
          <h6 class="text-uppercase font-weight-bold mb-4">Contact</h6>
          <p><i class="fas fa-map-marker-alt me-2"></i> Gujarat, India</p>
          <p><i class="fas fa-envelope me-2"></i> info@example.com</p>
          <p><i class="fas fa-phone-alt me-2"></i> +91 98765 43210</p>
        </div>

      </div>
    </div>
    <div class="footer-copyright text-center py-3">
      © 2023 Copyright:
      <a href="#" class="text-white"> DronAcharya</a>
    </div>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const menuBtn = document.querySelector(".header .menu-btn");
const menu = document.querySelector(".header .menu");

function toggleMenu()
{
    menuBtn.classList.toggle("active");
    menu.classList.toggle("open");
}
menuBtn.addEventListener("click", toggleMenu);

  </script>
</body>

</html>