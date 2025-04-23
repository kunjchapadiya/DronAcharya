<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href= 
"./Image/drone.png" 
          type="image/x-icon"> 
  <title>DronAcharya: Drone Sprayer Service</title>
  <link rel="stylesheet" href="Style/home.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
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

// Database connection
$conn = mysqli_connect("localhost", "root", "", "project");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get count of registered farmers
$farmerQuery = "SELECT COUNT(*) as count FROM registration";
$farmerResult = mysqli_query($conn, $farmerQuery);
$farmerCount = 0;
if ($farmerResult) {
    $row = mysqli_fetch_assoc($farmerResult);
    $farmerCount = $row['count'];
}

// Get total acres from bookingdata (farmSize field)
$acresQuery = "SELECT SUM(CAST(REPLACE(farmSize, 'acres', '') AS DECIMAL(10,2))) as total_acres FROM bookingdata";
$acresResult = mysqli_query($conn, $acresQuery);
$totalAcres = 0;
if ($acresResult) {
    $row = mysqli_fetch_assoc($acresResult);
    $totalAcres = $row['total_acres'] ? round($row['total_acres']) : 0;
}

mysqli_close($conn);
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
                <?php if ($isLoggedIn): ?>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Register/Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>


  <div class="bg-image d-flex align-items-center">
    <div class="container">
      <div class="row">
        <div class="col-md-8 offset-md-2 text-center content">
          <h1 class="display-4 mb-4">
            <b id="animated-text">Modern Agriculture </b>
          </h1>
          <p class="lead mb-5">
            We are professionals in the field offering modern and Agriculture Mechanization rental services by
            understanding the exact requirements for increasing the production of crops.
          </p>
        </div>
      </div>
    </div>
  </div>


  <section class="drone-section">
    <div class="image-container">
      <img src="Image/drone 1.jpg" alt="Drone spraying on crops">
    </div>
    <div class="content-container">
      <h2 class="title">Drone Spray</h2>
      <p class="subtitle">About Drone Spray Services</p>
      <p class="description">
        DronAcharya is offering the best drone service for farming and agriculture with our latest drone technology. We
        have helped many agricultural and farm businesses all over India manage irrigation, monitor crops, and detect
        growth patterns to achieve substantial yields. At DronAcharya, we provide a complete, integrated drone solution
        that includes everything you need to leverage advanced agricultural technology.
      </p>
      <p class="description">
        With expertise in utilizing drones for cultivation, DronAcharya is the one-stop solution for all agricultural
        needs. Choose our drone services to reduce crop losses and increase profits at harvest time.</p>

      <div class="buttons">
        <a href="https://youtu.be/2_jArLJT4x4?si=3ebiNzKFphXJV58K"><button class="btnWatchVideo">Watch Video</button></a>
        <a href="./Book-service.php"><button class="btnBookNow">Book Now</button></a>
      </div>
    </div>
  </section>
  </div>
  <section class="stats-section">
    <div class="overlay">
      <h2>We are an Expert in this field</h2>
      <p>We strive hard for the farming community to make the production process more profitable.</p>
      <div class="stats-container">
        <div class="stat">
          <div class="icon red"><img src="Image/counter_customer.png" alt=""></div>
          <h3 class="count" data-target="<?php echo $farmerCount; ?>">0</h3>
          <p>Happy Farmers</p>
        </div>
        <div class="stat">
          <div class="icon yellow"><img src="Image/counter_project.png" alt=""></div>
          <h3 class="count" data-target="5">0</h3>
          <p>Contracts Signed</p>
        </div>
        <div class="stat">
          <div class="icon orange"><img src="Image/counter_branch.png" alt=""></div>
          <h3 class="count" data-target="20">0</h3>
          <p>Serving Locations</p>
        </div>
        <div class="stat">
          <div class="icon blue"><img src="Image/counter_winner.png" alt=""></div>
          <h3 class="count" data-target="<?php echo $totalAcres; ?>">0</h3>
          <p>Acres</p>
        </div>


      </div>
    </div>
  </section>

  <div class="why-choose-us">
    <div class="content con1">
      <h1>Why Choose us</h1>
      <ul class="features-list">
        <li><span>🌿</span> As a place for all agriculture needs, WhiteOx provides the technological solution for
          spraying fertilizers.</li><br>
        <li><span>🌿</span> The technological innovation consumes less time to spray than usual.</li><br>
        <li><span>🌿</span> DronAcharya drone spray service offers various advantages to achieve the goal.</li><br>
        <li><span>🌿</span> We ensure our spraying service confirms better results.</li><br>
        <li><span>🌿</span> No matter the type of pest, we ensure to kill pests and safeguard the crops.</li><br>
        <li><span>🌿</span> Our thorough analysis and up-to-date market research help us stay on top of the market.</li>
        <br>
        <li><span>🌿</span> Our stringent policies in handling crops ensure quality and productivity.</li><br>
      </ul>
    </div>
    <div class="content con3">
      <div class="image-slider">
        <img id="slide" src="https://whiteox.in/images/drone_service2.jpg" alt="Drone in field">
      </div>
      <div class="slider-controls">
        <button class="btnslider" onclick="prevSlide()">❮</button>
        <button class="btnslider" onclick="nextSlide()">❯</button>
      </div>
    </div>
  </div>

  <div class="vision-container">
    <h1 class="vision-text-heading">Our Vision</h1>
    <div class="vision-underline"></div>
  </div>

  <section class="vision-section-1">
    <div class="vision-content">
      <div class="vision-text">
        <h2>Modern Technology</h2>
        <div class="underline"></div>
        <p>Existing technologies such as autonomous vehicles, artificial intelligence and machine vision could be
          adapted to the modern agricultural domain. If modern agriculture is applied widely in the near future,
          millions of farmers will be able to benefit from the acquisition of real-time farm information. Farmers need
          not spend significant amount of time on acquiring farm data and will have access to disaster warnings and
          weather information when a disaster event occurs.</p>
      </div>
      <div class="vision-image">
        <img src="Image/vision1.jpg" alt="Modern Technology">
      </div>
    </div>
  </section>

  <section class="vision-section-2">
    <div class="vision-content">
      <div class="vision-text">
        <h2>Feed Healthy Food To The World</h2>
        <div class="underline"></div>
        <p>Healthy eating can't be achieved without eating garden-fresh fruits and vegetables. After produce is
          harvested, it passes through many hands, increasing the contamination risk. To avoid this, we have introduced
          vegetable processing for sustainable food production.</p>
      </div>
      <div class="vision-image">
        <img src="Image/vision2.jpg" alt="Healthy Food">
      </div>

    </div>
  </section>

  <section class="vision-section-1">
    <div class="vision-content">
      <div class="vision-text">
        <h2>Advanced Technology for Precision Farming</h2>
        <div class="underline"></div>
        <p>As advanced technologies like Automation and AI will change agriculture, Drone technology enables farmers to
          gather the quality and quantity of data required for precision agriculture, that help in efficiency and cost.
        </p>
      </div>
      <div class="vision-image">
        <img src="Image/vision3.jpg" alt="Modern Technology">
      </div>
    </div>
  </section>

  <div class="discover-container">
    <section class="services-section">
      <h2>Discover Services We Provided</h2>
      <p class="section-subtitle">
        Every link in our service offerings is important—if one is missing, then the others won't have as much impact.
      </p>
      <div class="services-grid">
        <div class="service-card">
          <img src="Image/icon1.png" alt="Fertilizer">
          <h3>Fertilizer</h3>
          <p>Our drone spray service uses only 70% of fertilizer for farming. Hence, it does not cause any side effects
            on the plants.</p>
        </div>
        <div class="service-card">
          <img src="Image/icon2.png" alt="Water Spraying">
          <h3>Water Spraying</h3>
          <p>We spray 12 liters of water to the agricultural land. It consumes less water spraying than any other power
            spraying.</p>
        </div>
        <div class="service-card">
          <img src="Image/icon3.png" alt="Time Efficient">
          <h3>Time Efficient</h3>
          <p>It just takes 15 minutes to spray the fertilizer and water. So, the farmers don't have to spend more time
            in the hot summer.</p>
        </div>
        <div class="service-card">
          <img src="Image/icon4.png" alt="Plants Safety">
          <h3>Plants Safety</h3>
          <p>By following our drone spray service, plants will never get spoiled or damaged. Because we used to spray at
            the right level.</p>
        </div>
      </div>
    </section>
  </div>

  <div class="newsletter">
    <section class="news-letter" id="News-letter">
        <div class="news">
            <div class="container">
                <h1 class="news-heading"><br><br>Subscribe To Get The Latest <br> News About Us</h1>
                <p class="des how-de">
                    Get the Latest news about Modern Farming to Your Pocket. Drop your <br> email below to get daily updates about us.
                </p>

                <form id="subscribeForm">
                    <input type="email" id="subscribeEmail" maxlength="50" required placeholder="Enter your email address" class="form-control w-50 mx-auto mb-3">
                    <button type="submit" class="bt">Subscribe</button>
                </form>

                <br><br><br><br>
            </div>
        </div>
    </section>
</div>

<!-- Toast Notification -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="subscribeToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                Thank you for subscribing! We'll keep you updated with the latest news.
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- Add this script before the closing body tag -->
<script>
document.getElementById('subscribeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get the email value
    const email = document.getElementById('subscribeEmail').value;
    
    // Show the toast
    const toastElement = document.getElementById('subscribeToast');
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
    
    // Clear the form
    this.reset();
});
</script>

  <div class="image-gallery">
    <div class="heading-gallery">
      <h1>Our Gallery</h1>
      <p>Our gallery has nearly 1,500 photos that we have taken while performing drone spraying.</p>
    </div>
    <div class="gallery">
      <img src="Image/g4.jpg" alt="Drone Spraying Image 1">
      <img src="Image/g5.jpg" alt="Drone Spraying Image 1">
      <img src="Image/g6.jpg" alt="Drone Spraying Image 1">
      <img src="Image/g7.jpg" alt="Drone Spraying Image 1">
      <img src="Image/g8.jpg" alt="Drone Spraying Image 1">
    </div>
    <div class="modal" id="myModal">
      <span class="close">&times;</span>
      <img class="modal-content" id="img01">
    </div>

  </div>
  <!-- Footer -->
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
  <script src="Scripts/home.js"></script>
</body>

</html>

<style>
    /* Add these styles to your existing CSS */
    .dropdown {
        position: relative;
    }

    .dropdown-toggle {
        cursor: pointer;
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        background-color: #fff;
        min-width: 160px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        z-index: 1000;
        padding: 0;
        margin: 0;
        list-style: none;
        border-radius: 4px;
    }

    .dropdown:hover .dropdown-menu {
        display: block;
    }

    .dropdown-menu li {
        padding: 0;
    }

    .dropdown-menu a {
        padding: 10px 15px;
        display: block;
        color: #333;
        text-decoration: none;
        transition: background-color 0.3s;
    }

    .dropdown-menu a:hover {
        background-color: #f8f9fa;
        color: #007bff;
    }
</style>