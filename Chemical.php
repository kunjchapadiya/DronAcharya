<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href= 
"./Image/drone.png" 
          type="image/x-icon">
  <title>Document</title>
  <link rel="stylesheet" href="style/Chemical.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,400;0,700;1,700&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=dark_mode" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>

<body>
<?php
session_start();
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
  <br><br><br>
  <h2 class="text-center">Select one Crop for Chemical Details </h2>
  <br>
  <div class="container">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
      <div class="col">
        <div class="card">
          <a href="wheat.php"><img src="Image/wheat.png" class="rounded mx-auto d-block" alt="..."
              style="width: 180px; height: 180px;"></a>
          <div class="card-body">
            <h3 class="card-title text-center">Wheat - गेहूं</h3>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card">
          <a href="rice.php"><img src="Image/rice.png" class="rounded mx-auto d-block" alt="..."
              style="width: 180px; height: 180px;"></a>
          <div class="card-body">
            <h3 class="card-title text-center">Rice - चावल</h3>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card">
          <a href="maize.php"><img src="Image/corn.png" class="rounded mx-auto d-block" alt="..."
              style="width: 180px; height: 180px;"></a>
          <div class="card-body">
            <h3 class="card-title text-center">Maize - मक्का</h3>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card">
          <a href="sugarcane.php"><img src="Image/sugarcane.png" class="rounded mx-auto d-block" alt="..."
              style="width: 180px; height: 180px;"></a>
          <div class="card-body">
            <h3 class="card-title text-center">Sugarcane - गन्ना</h3>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card">
          <a href="barely.php"><img src="Image/barley.png" class="rounded mx-auto d-block" alt="..."
              style="width: 180px; height: 180px;"></a>
          <div class="card-body">
            <h3 class="card-title text-center">Barley - जौ</h3>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card">
          <a href="groundnut.php"><img src="Image/groundnut.png" class="rounded mx-auto d-block" alt="..."
              style="width: 180px; height: 180px;"></a>
          <div class="card-body">
            <h3 class="card-title text-center">Groundnut - मूंगफली</h3>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card">
          <a href="pulses.php"><img src="Image/pulses.png" class="rounded mx-auto d-block" alt="..."
              style="width: 180px; height: 180px;"></a>
          <div class="card-body">
            <h3 class="card-title text-center">Pulses - दालें</h3>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card">
          <a href="cotton.php"><img src="Image/cotton.png" class="rounded mx-auto d-block" alt="..."
              style="width: 180px; height: 180px;"></a>
          <div class="card-body">
            <h3 class="card-title text-center">Cotton - कपास</h3>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card">
          <a href="tea.php"><img src="Image/tea.png" class="rounded mx-auto d-block" alt="..."
              style="width: 180px; height: 180px;"></a>
          <div class="card-body">
            <h3 class="card-title text-center">Tea - चाय</h3>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card">
          <a href="coffee.php"><img src="Image/coffee.png" class="rounded mx-auto d-block" alt="..."
              style="width: 180px; height: 180px;"></a>
          <div class="card-body">
            <h3 class="card-title text-center">Coffee - कॉफी</h3>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card">
          <a href="spices.php"><img src="Image/spices.png" class="rounded mx-auto d-block" alt="..."
              style="width: 180px; height: 180px;"></a>
          <div class="card-body">
            <h3 class="card-title text-center">Spices - मसाले</h3>
          </div>
        </div>
      </div>
    </div>
  </div>

  <br><br>
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
      <a href="#" class="text-white"> Your Website</a>
    </div>
  </footer>
  <script src="Scripts/chemical.js"></script>
</body>

</html>