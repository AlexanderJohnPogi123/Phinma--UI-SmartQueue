<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM users WHERE email = '$email'";
  $result = $conn->query($sql);

  if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['fullname'] = $row['fullname'];
      header("Location: index.php");
      exit();
    } else {
      $error = "Incorrect password!";
    }
  } else {
    $error = "No account found with that email!";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login - Phinma SmartQueue</title>

<!-- Google Font -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600;700&display=swap" rel="stylesheet">

<!-- Bootstrap CSS & Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>
:root{
  --glass-bg: rgba(255,255,255,0.2);
  --glass-btn: rgba(255,255,255,0.7);
  --glass-nav: rgba(255,255,255,0.4);
  --focus-glow: rgba(255,255,255,0.6);
}

body {
  margin:0;
  padding:0;
  min-height:100vh;
  display:flex;
  flex-direction:column;
  color:white;
  font-family:'Poppins', system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
}

.background {
  position:fixed; top:0; left:0; width:100%; height:100%;
  background:url('images/bgui.jpg') center/cover no-repeat;
  z-index:-2;
}

.background-overlay {
  position:fixed; top:0; left:0; width:100%; height:100%;
  background: rgba(0,0,0,0.4);
  z-index:-1;
}

/* Navbar */
.navbar { background-color: var(--glass-nav) !important; backdrop-filter: blur(10px); box-shadow:0 2px 6px rgba(0,0,0,0.2);}
.navbar-brand img { display:block; }
.dropdown .btn { background-color: rgba(255,255,255,0.5); color:black; border:1px solid rgba(0,0,0,0.16); font-weight:600; padding:.45rem .65rem;}
.dropdown .btn:hover,.dropdown .btn:focus { background-color: rgba(255,255,255,0.85);}
.dropdown-menu { background-color: rgba(255,255,255,0.96); border:none; border-radius:10px; box-shadow:0 6px 18px rgba(0,0,0,0.18); min-width:180px;}
.dropdown-item { color:#111 !important; font-weight:600; padding:10px 14px;}
.dropdown-item:hover { background-color: rgba(0,0,0,0.06);}
.dropdown-item.text-danger { color:#b00000 !important; background-color: rgba(255,0,0,0.04); border-top:1px solid rgba(0,0,0,0.06); font-weight:700;}
.dropdown-item.text-danger:hover { background-color: rgba(255,0,0,0.12);}

/* Login Box */
.login-container { flex:1; display:flex; justify-content:center; align-items:center; padding-top:110px; padding-bottom:70px;}
.login-box { background:var(--glass-bg); backdrop-filter: blur(10px); padding:42px; border-radius:14px; box-shadow:0 6px 20px rgba(0,0,0,0.35); width:100%; max-width:440px;}
.login-box h2 { color:white; font-weight:700; font-size:1.75rem; margin-bottom:14px; text-align:center; }

/* Floating input with icon */
.input-wrapper { position:relative; margin-bottom:20px; }
.input-wrapper i { position:absolute; top:50%; left:12px; transform:translateY(-50%); color:#555; font-size:1.1rem; pointer-events:none;}
.floating-input { width:100%; padding:.65rem .75rem .65rem 38px; border-radius:8px; border:none; background-color: rgba(255,255,255,0.85); outline:none; font-size:1rem; color:#000; transition: box-shadow 0.3s ease;}
.floating-input:focus { box-shadow:0 0 12px var(--focus-glow);}
.input-wrapper label { position:absolute; top:50%; left:38px; transform:translateY(-50%); transition:0.3s ease all; color:#555; pointer-events:none; font-weight:500; background:none; padding:0;}
.floating-input:focus + label,
.floating-input:not(:placeholder-shown) + label { top:-8px; left:38px; font-size:0.8rem; color:#222; }

.btn-custom { background-color: var(--glass-btn); color:black; border:none; font-weight:700; padding:.6rem .9rem; border-radius:10rem; transition: transform .15s ease, background-color .15s ease;}
.btn-custom:hover { background-color: rgba(255,255,255,0.92); transform:translateY(-2px);}
.helper-link { color:#fff; font-weight:600; text-decoration:underline; }

footer { background-color: rgba(255,255,255,0.3); color:white; text-align:center; padding:10px; position:fixed; bottom:0; width:100%; backdrop-filter: blur(10px); }

@media(max-width:576px){ .login-box { padding:28px;} .login-box h2 { font-size:1.5rem;} .dropdown .btn { padding:.35rem .5rem;} }
</style>
</head>
<body>
<div class="background"></div>
<div class="background-overlay"></div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top">
<div class="container-fluid d-flex justify-content-between align-items-center">
  <a class="navbar-brand" href="index.php"><img src="images/logonew.png" alt="Logo" width="200"></a>
  <div class="dropdown">
    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">☰ Menu</button>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
      <li><a class="dropdown-item" href="index.php">Home</a></li>
      <li><a class="dropdown-item" href="account.php">Reserve</a></li>
      <li><a class="dropdown-item" href="account.php">Account</a></li>
      <li><a class="dropdown-item" href="contact.php">Contact Us</a></li>
    </ul>
  </div>
</div>
</nav>

<!-- Login Form -->
<div class="login-container">
  <div class="login-box">
    <h2>Login</h2>

    <?php if(isset($error)): ?>
      <div class="alert alert-danger text-center"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="input-wrapper">
        <i class="bi bi-envelope-fill"></i>
        <input type="email" name="email" class="floating-input" placeholder=" " required>
        <label>Email</label>
      </div>
      <div class="input-wrapper">
        <i class="bi bi-lock-fill"></i>
        <input type="password" name="password" class="floating-input" placeholder=" " required>
        <label>Password</label>
      </div>
      <button type="submit" class="btn btn-custom w-100 mt-2">Login</button>
    </form>

    <p class="text-center mt-3">Don’t have an account? <a href="register.php" class="helper-link">Register</a></p>
  </div>
</div>

<footer><p>© 2025 Phinma SmartQueue. All rights reserved.</p></footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
