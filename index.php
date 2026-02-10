<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Home - Phinma SmartQueue</title>

  <!-- Match the font used in account.php -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600;700&display=swap" rel="stylesheet">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root{
      --glass-nav: rgba(255,255,255,0.4);
      --glass-card: rgba(255,255,255,0.2);
      --glass-btn: rgba(255,255,255,0.7);
    }

    body {
      margin: 0;
      padding: 0;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      position: relative;
      color: white;
      font-family: 'Poppins', system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }

    .background {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: url(images/bgui.jpg);
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      z-index: -2;
    }

    .background-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.4);
      z-index: -1;
    }

    /* Navbar - glass style matching account.php */
    .navbar {
      background-color: var(--glass-nav) !important;
      backdrop-filter: blur(10px);
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }

    .navbar-brand img { display:block; }

    /* Dropdown toggle button styling */
    .dropdown .btn {
      background-color: rgba(255,255,255,0.5);
      color: black;
      border: 1px solid rgba(0,0,0,0.16);
      font-weight: 600;
      padding: .45rem .65rem;
    }

    .dropdown .btn:hover,
    .dropdown .btn:focus {
      background-color: rgba(255,255,255,0.85);
    }

    /* Dropdown menu styling */
    .dropdown-menu {
      background-color: rgba(255,255,255,0.96);
      border: none;
      border-radius: 10px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.18);
      min-width: 180px;
    }

    .dropdown-item {
      color: #111 !important;
      font-weight: 600;
      padding: 10px 14px;
    }

    .dropdown-item:hover {
      background-color: rgba(0,0,0,0.06);
    }

    .dropdown-item.text-danger {
      color: #b00000 !important;
      background-color: rgba(255,0,0,0.04);
      border-top: 1px solid rgba(0,0,0,0.06);
      font-weight: 700;
    }

    .dropdown-item.text-danger:hover {
      background-color: rgba(255,0,0,0.12);
    }

    /* Main content layout (kept same sizes as your original) */
    .content-section {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex: 1;
      padding: 120px 5% 100px;
      flex-wrap: wrap;
    }

    .left-section {
      width: 45%;
      min-width: 300px;
    }

    /* Heading & paragraph weights to match account.php */
    .left-section h1 {
      font-size: 2.8rem;
      font-weight: 700;
      margin: 0;
    }

    .left-section p {
      font-size: 1.1rem;
      line-height: 1.6;
      margin-top: 15px;
      font-weight: 500;
    }

    /* Get started button: match the rounded, bold, glassy button from account.php */
    .btn-get-started {
      margin-top: 25px;
      background-color: var(--glass-btn);
      color: black;
      font-weight: 700;
      border: none;
      padding: 10px 25px;
      border-radius: 30px;
      transition: transform .15s ease, background-color .15s ease;
      backdrop-filter: blur(5px);
    }

    .btn-get-started:hover {
      background-color: rgba(255,255,255,0.92);
      transform: translateY(-3px);
    }

    .carousel {
      width: 45%;
      min-width: 300px;
      margin-top: 20px;
    }

    .fixed-carousel-img {
      height: 500px;
      object-fit: cover;
      object-position: center;
      border-radius: 10px;
    }

    @media (max-width: 768px) {
      .fixed-carousel-img { height: 300px; }
    }

    footer {
      background-color: rgba(255,255,255,0.3);
      color: white;
      text-align: center;
      padding: 10px;
      position: fixed;
      bottom: 0;
      width: 100%;
      backdrop-filter: blur(10px);
    }

    @media (max-width: 992px) {
      .content-section {
        flex-direction: column;
        justify-content: center;
        text-align: center;
        padding-top: 140px;
      }
      .left-section, .carousel { width: 100%; }
    }
  </style>
</head>
<body>
  <div class="background"></div>
  <div class="background-overlay"></div>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <a class="navbar-brand" href="#">
        <img src="images/logonew.png" alt="Logo" width="200">
      </a>

      <div class="dropdown">
        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
          ☰ Menu        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
          <li><a class="dropdown-item" href="index.php">Home</a></li>

          <?php if (isset($_SESSION['user_id'])): ?>
            <li><a class="dropdown-item" href="reserve.php">Reserve</a></li>
            <li><a class="dropdown-item" href="contact.php">Contact Us</a></li>
            <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
          <?php else: ?>
            <li><a class="dropdown-item" href="account.php">Reserve</a></li>
            <li><a class="dropdown-item" href="account.php">Account</a></li>
            <li><a class="dropdown-item" href="contact.php">Contact Us</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <section class="content-section">
    <div class="left-section">
      <h1>Phinma SmartQueue</h1>
      <p>
        The Phinma SmartQueue system helps students schedule their finance appointments in advance
        — so you can skip the long lines and handle tuition and other school-related payments 
        with ease and efficiency.
      </p>

      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="reserve.php" class="btn-get-started">Get Started</a>
      <?php else: ?>
        <a href="account.php" class="btn-get-started">Get Started</a>
      <?php endif; ?>
    </div>

    <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="images/c1.jpg" class="d-block w-100 fixed-carousel-img" alt="...">
        </div>
        <div class="carousel-item">
          <img src="images/c2.jpg" class="d-block w-100 fixed-carousel-img" alt="...">
        </div>
        <div class="carousel-item">
          <img src="images/c3.jpg" class="d-block w-100 fixed-carousel-img" alt="...">
        </div>
      </div>

      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>

      <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
  </section>

  <footer>
    <p>© 2025 Phinma SmartQueue. All rights reserved.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
