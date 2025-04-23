<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href= 
"./Image/drone.png" 
          type="image/x-icon">
  <title>Document</title>
  <link rel="stylesheet" href="Style/home.css">
  <link rel="stylesheet" href="Style/about.css">
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
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" type="text/css"
    href="https://cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick-theme.css" />
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

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
  <!-- Collaborate with brands -->
  <br><br><br>
  <div class="text-center hd">Founder's Message</div>
  <br>
  <div class="container">
    <div class="row">
      <div class="col-md-4">
        <div class="card d-flex mx-auto">
          <div class="card-image">
            <img class="img-fluid d-flex mx-auto" src="Image/business-corporate-headshots-portrait-male.jpg">
          </div>
          <div class="card-text">
            <div class="card-title">Mr. Kunj Patel</div>
            Wishing you a season of growth and success! May our drone spraying services help you achieve healthier
            crops, higher yields, and greater efficiency in your farming operations. We're committed to supporting your
            agricultural success!


          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card d-flex mx-auto">
          <div class="card-image">
            <img class="img-fluid d-flex mx-auto" src="Image/2022-05-01-Will-0649.jpg">
          </div>
          <div class="card-text">
            <div class="card-title">Mr. Vansh Patel</div>
            May technology take your farm to new heights! Just like our drones soar above the fields, we hope your
            farming ventures reach new levels of productivity and innovation. We’re here to help you maximize your
            potential with precision spraying solutions.
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card d-flex mx-auto ">
          <div class="card-image">
            <img class="img-fluid d-flex mx-auto" src="Image/man image.jpg">
          </div>
          <div class="card-text">
            <div class="card-title">Mr. Ajay Patel</div>
            Thank you for trusting us with your fields! Your support drives our mission to revolutionize agriculture
            through smart technology. We wish you a prosperous season, bountiful harvests, and a future filled with
            success.
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class='container-fluid mx-auto mt-5 mb-5 col-12' style="text-align: center">
    <div class="hd">Why People Believe in Us</div>
    <br><br>
    <div class="row" style="justify-content: center">
      <div class="card col-md-3 col-12">
        <div class="card-content">
          <div class="card-body"> <img class="img" src="Image/tag.png" />
            <div class="shadow"></div>
            <div class="card-title"> We're Lower Price </div>
            <div class="card-subtitle">
              <p> <small class="text-muted">Get the best drone spraying services in India at the lowest prices! Ensure
                  efficient crop protection with advanced technology, saving time and reducing costs for farmers.
                </small> </p>
            </div>
          </div>
        </div>
      </div>
      <div class="card col-md-3 col-12 ml-2">
        <div class="card-content">
          <div class="card-body"> <img class="img" src="Image/target.png" />
            <div class="card-title"> We're Unbiased </div>
            <div class="card-subtitle">
              <p> <small class="text-muted"> Our technology-driven approach prioritizes efficiency, sustainability, and
                  equal access for all farmers, regardless of land size or location. </small> </p>
            </div>
          </div>
        </div>
      </div>
      <div class="card col-md-3 col-12 ml-2">
        <div class="card-content">
          <div class="card-body"> <img class="img rck" src="Image/rocket.png" />
            <div class="card-title"> We Guide you </div>
            <div class="card-subtitle">
              <p> <small class="text-muted"> Our expert support helps you optimize pesticide usage, enhance crop yield,
                  and comply with regulations seamlessly.</small> </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- time line -->

  <div class="container">
    <div class="text-center hd">From Vision to Reality </div><br>
    <div class="main-timeline">

      <!-- start experience section-->
      <div class="timeline">
        <div class="icon"></div>
        <div class="date-content">
          <div class="date-outer">
            <span class="date">
              <span class="month"></span>
              <span class="year">2019</span>
            </span>
          </div>
        </div>
        <div class="timeline-content">
          <h5 class="title">The Beginning</h5>
          <p class="description">
            Initial research and exploration of drone technology in agriculture. Identified the need for precision
            spraying solutions for farmers. Developed and tested the first drone spraying prototype. Conducted pilot
            projects with local farmers to refine efficiency.
          </p>
        </div>
      </div>
      <!-- end experience section-->

      <!-- start experience section-->
      <div class="timeline">
        <div class="icon"></div>
        <div class="date-content">
          <div class="date-outer">
            <span class="date">
              <span class="month">2 Years</span>
              <span class="year">2021</span>
            </span>
          </div>
        </div>
        <div class="timeline-content">
          <h5 class="title">Official Launch</h5>
          <p class="description">
            Established the drone spraying service company. Built partnerships with agricultural organizations and local
            farmers. Expanded operations to multiple regions.

          </p>
        </div>
      </div>
      <!-- end experience section-->

      <!-- start experience section-->
      <div class="timeline">
        <div class="icon"></div>
        <div class="date-content">
          <div class="date-outer">
            <span class="date">
              <span class="month">2 Years</span>
              <span class="year">2023</span>
            </span>
          </div>
        </div>
        <div class="timeline-content">
          <h5 class="title">Expansion & Innovation</h5>
          <p class="description">
            Achieved 1,000+ successful drone spraying operations.
            Partnered with major agri-tech firms for advanced spraying techniques.
            Recognized as a leading drone service provider in the industry.
          </p>
        </div>
      </div>
      <!-- end experience section-->

      <!-- start experience section-->
      <div class="timeline">
        <div class="icon"></div>
        <div class="date-content">
          <div class="date-outer">
            <span class="date">
              <span class="month">1 Years</span>
              <span class="year">2024</span>
            </span>
          </div>
        </div>
        <div class="timeline-content">
          <h5 class="title">Future of Morern Agriculture</h5>
          <p class="description">
            Scaling up nationwide to serve thousands of farmers.
            Implementing automated drone fleets with IoT & AI.
            Working on sustainable and eco-friendly spraying solutions.
          </p>
        </div>
      </div>
      <!-- end experience section-->

    </div>
  </div>

  <!-- testimonials -->
  <div class="text-center hd">Testimonials </div>
  <div class="items">

    <div class="card">
      <div class="card-body">
        <h4 class="card-title"><img src="https://img.icons8.com/ultraviolet/40/000000/quote-left.png"></h4>

        <div class="template-demo">
          <p>Amazing service! The drone spraying saved us time and reduced chemical waste. Our crops look healthier than ever. Highly recommend this technology!"

          </p>
        </div>

        <hr>

        <div class="row">

          <div class="col-sm-2">

            <img class="profile-pic" src="Image/man.png">

          </div>

          <div class="col-sm-10">

            <div class="profile">

              <h4 class="cust-name">Ramanbhai Patel</h4>
              <p class="cust-profession">Gujarat,India</p>

            </div>


          </div>


        </div>
      </div>
    </div>


    <div class="card">
      <div class="card-body">
        <h4 class="card-title"><img src="https://img.icons8.com/ultraviolet/40/000000/quote-left.png"></h4>

        <div class="template-demo">
          <p>Fast, efficient, and precise! The drones covered our entire field evenly, and we saw better pest control results. Will definitely use again!</p>
        </div>

        <hr>

        <div class="row">

          <div class="col-sm-2">

            <img class="profile-pic" src="Image/man.png">



          </div>

          <div class="col-sm-10">

            <div class="profile">

              <h4 class="cust-name">Shivnath Shinde</h4>
              <p class="cust-profession
">Maharastra, India</p>

            </div>


          </div>


        </div>
      </div>
    </div>


    <div class="card">
      <div class="card-body">
        <h4 class="card-title"><img src="https://img.icons8.com/ultraviolet/40/000000/quote-left.png"></h4>

        <div class="template-demo">
          <p>Great cost-effective solution! Saved labor costs and improved spray accuracy. The team was professional and helpful throughout the process.</p>
        </div>

        <hr>

        <div class="row">

          <div class="col-sm-2">

            <img class="profile-pic" src="Image/woman.png">

          </div>

          <div class="col-sm-10">

            <div class="profile">

              <h4 class="cust-name">Mithiben Verma</h4>
              <p class="cust-profession
">Telangana,India</p>

            </div>


          </div>


        </div>
      </div>
    </div>


    <div class="card">
      <div class="card-body">
        <h4 class="card-title"><img src="https://img.icons8.com/ultraviolet/40/000000/quote-left.png"></h4>

        <div class="template-demo">
          <p>Impressive results! The drones reached areas that were hard to spray manually. Our yield improved, and the process was hassle-free. Very satisfied!</p>
        </div>

        <hr>

        <div class="row">

          <div class="col-sm-2">

            <img class="profile-pic" src="Image/man.png">

          </div>

          <div class="col-sm-10">

            <div class="profile">

              <h4 class="cust-name">Hardik Sharma</h4>
              <p class="cust-profession
">Uttar Pradesh, India</p>
            </div>
          </div>
        </div>
      </div>
    </div>


    <div class="card">
      <div class="card-body">
        <h4 class="card-title"><img src="https://img.icons8.com/ultraviolet/40/000000/quote-left.png"></h4>

        <div class="template-demo">
          <p>Highly efficient and eco-friendly! The drone spraying reduced water and chemical usage while ensuring even coverage. Our farm operations have never been smoother!</p>
        </div>

        <hr>

        <div class="row">

          <div class="col-sm-2">

            <img class="profile-pic" src="Image/man.png">

          </div>

          <div class="col-sm-10">

            <div class="profile">

              <h4 class="cust-name">Manoj Charles</h4>
              <p class="cust-profession
">Kerala, India</p>

            </div>


          </div>


        </div>
      </div>
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
</body>
<script src="Scripts/home.js"></script>
<script src="Scripts/about.js"></script>
</footer>

</html>