<?php
session_start();
include 'db_connection.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: account.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$user_query = $conn->query("SELECT fullname, email FROM users WHERE id = '$user_id'");
if (!$user_query) die("Error fetching user: " . $conn->error);
$user = $user_query->fetch_assoc();

$message = "";

// Google Form URL (replace with your Google Form POST URL)
$google_form_url = "https://docs.google.com/forms/d/e/YOUR_FORM_ID/formResponse";

// Google Form field IDs
$name_entry = "entry.1234567890";       // Name
$email_entry = "entry.0987654321";      // Email
$concern_entry = "entry.1122334455";    // Concern

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['concern'])) {
    $concern = trim($_POST['concern']);

    $post_data = [
        $name_entry => $user['fullname'],
        $email_entry => $user['email'],
        $concern_entry => $concern
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $google_form_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    $response = curl_exec($ch);
    if ($response === false) {
        $message = "<div class='alert alert-danger text-center'>Error submitting your concern. Please try again.</div>";
    } else {
        $message = "<div class='alert alert-success text-center'>Thank you! Your concern has been submitted.</div>";
    }

    curl_close($ch);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Us - Phinma SmartQueue</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { margin:0; padding:0; font-family:'Poppins', sans-serif; background: url('images/bgui.jpg') center/cover no-repeat; min-height:100vh; display:flex; flex-direction:column; color:white; }
.background-overlay { position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.4); z-index: -1; }
.navbar { background-color: rgba(255,255,255,0.4) !important; backdrop-filter: blur(10px); box-shadow: 0 2px 6px rgba(0,0,0,0.2); }
.dropdown .btn { background-color: rgba(255,255,255,0.5); color: black; font-weight: 600; border: 1px solid rgba(0,0,0,0.16); }
.dropdown .btn:hover, .dropdown .btn:focus { background-color: rgba(255,255,255,0.85); }
.dropdown-menu { background-color: rgba(255,255,255,0.96); border-radius: 10px; min-width:180px; }
.dropdown-item { color:#111 !important; font-weight:600; padding:10px 14px; }
.dropdown-item:hover { background-color: rgba(0,0,0,0.06);}
.dropdown-item.text-danger { color:#b00000 !important; background: rgba(255,0,0,0.04); border-top:1px solid rgba(0,0,0,0.06);}
.main-content { display:flex; justify-content:center; flex:1; padding:100px 5%; }
.box { background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); padding:30px; border-radius:15px; box-shadow:0 6px 20px rgba(0,0,0,0.35); width:100%; max-width:600px; height:auto; display:inline-block; }
h2 { font-weight:700; color:white; margin-bottom:20px; text-align:center; }
.form-label { color:white; font-weight:600; }
.form-text { color:white; font-weight:500; margin-bottom:15px; }
textarea { border-radius:8px; padding:.65rem .75rem; min-height:150px; resize:none; width:100%; }
.btn-custom { background-color: rgba(255,255,255,0.7); color:black; font-weight:700; border:none; padding:.6rem .9rem; border-radius:50px; transition:0.3s; width:100%; }
.btn-custom:hover { background-color: rgba(255,255,255,0.92); transform: translateY(-2px); }
footer { background-color: rgba(255,255,255,0.3); text-align:center; padding:10px; font-weight:bold; position: fixed; bottom:0; width:100%; }
</style>
</head>
<body>
<div class="background-overlay"></div>

<nav class="navbar navbar-expand-lg fixed-top">
  <div class="container-fluid d-flex justify-content-between align-items-center">
    <a class="navbar-brand" href="index.php"><img src="images/logonew.png" width="200"></a>
    <div class="dropdown">
      <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown">☰ Menu</button>
      <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item" href="index.php">Home</a></li>
        <li><a class="dropdown-item" href="reserve.php">Reserve</a></li>
        <?php if(!isset($_SESSION['user_id'])): ?>
        <li><a class="dropdown-item" href="account.php">Account</a></li>
        <?php endif; ?>
        <li><a class="dropdown-item" href="contact.php">Contact Us</a></li>
        <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="main-content container">
    <div class="box">
        <h2>Contact Us</h2>
        <?php echo $message; ?>
        <form method="POST">
            <div class="mb-3">
                <p class="form-text"><strong>Name:</strong> <?php echo $user['fullname']; ?></p>
            </div>
            <div class="mb-3">
                <p class="form-text"><strong>Email:</strong> <?php echo $user['email']; ?></p>
            </div>
            <div class="mb-3">
                <label class="form-label">Your Concern</label>
                <textarea name="concern" placeholder="Type your concern here..." required></textarea>
            </div>
            <button type="submit" class="btn btn-custom">Submit Concern</button>
        </form>
    </div>
</div>

<footer>© 2025 Phinma SmartQueue. All rights reserved.</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
