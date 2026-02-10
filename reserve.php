<?php
session_start();
include 'db_connection.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: account.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details (using fullname now)
$user_query = $conn->query("SELECT fullname, school_id, phone, email FROM users WHERE id = '$user_id'");
if (!$user_query) die("Error fetching user: " . $conn->error);
$user = $user_query->fetch_assoc();

$message = "";

// Time slots
$time_slots = ['08:00', '11:00', '14:00', '17:00'];

// Handle reservation form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['clear_reservation'])) {
        $delete = $conn->query("DELETE FROM reservations WHERE user_id='$user_id'");
        if (!$delete) die("Error clearing reservation: " . $conn->error);
        $message = "<div class='alert alert-warning text-center'>Reservation cleared. You can make a new reservation.</div>";
    }

    if (isset($_POST['reserve_date']) && isset($_POST['reserve_time'])) {
        $date = $_POST['reserve_date'];
        $time = $_POST['reserve_time'];
        $dayOfWeek = date('l', strtotime($date));

        // Check valid day
        if (!in_array($dayOfWeek, ['Monday','Wednesday','Friday'])) {
            $message = "<div class='alert alert-danger text-center'>Reservations are only allowed on Monday, Wednesday, or Friday.</div>";
        }
        // Check if date is not past
        else if (strtotime($date) < strtotime(date('Y-m-d'))) {
            $message = "<div class='alert alert-danger text-center'>You cannot reserve a past date.</div>";
        } 
        else {
            $check = $conn->query("SELECT * FROM reservations WHERE user_id = '$user_id'");
            if (!$check) die("Error checking reservation: " . $conn->error);

            if ($check->num_rows > 0) {
                $update = $conn->query("UPDATE reservations SET reserved_date='$date', reserved_time='$time' WHERE user_id='$user_id'");
                if (!$update) die("Error updating reservation: " . $conn->error);
                $message = "<div class='alert alert-success text-center'>Reservation rescheduled to $date at $time!</div>";
            } else {
                $insert = $conn->query("INSERT INTO reservations (user_id, reserved_date, reserved_time) VALUES ('$user_id','$date','$time')");
                if (!$insert) die("Error inserting reservation: " . $conn->error);
                $message = "<div class='alert alert-success text-center'>Reservation successful for $date at $time!</div>";
            }
        }
    }
}

// Fetch reservation
$res_query = $conn->query("SELECT * FROM reservations WHERE user_id = '$user_id'");
$reservation = $res_query ? $res_query->fetch_assoc() : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reserve - Phinma SmartQueue</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<style>
body {
    margin:0; padding:0; font-family:'Poppins', sans-serif;
    background: url('images/bgui.jpg') center/cover no-repeat; min-height:100vh; display:flex; flex-direction:column; color:white;
}
.background-overlay {
    position: fixed; top:0; left:0; width:100%; height:100%;
    background: rgba(0,0,0,0.4); z-index: -1;
}
.navbar { background-color: rgba(255,255,255,0.4) !important; backdrop-filter: blur(10px); box-shadow: 0 2px 6px rgba(0,0,0,0.2); }
.dropdown .btn { background-color: rgba(255,255,255,0.5); color: black; font-weight: 600; border: 1px solid rgba(0,0,0,0.16); }
.dropdown .btn:hover, .dropdown .btn:focus { background-color: rgba(255,255,255,0.85); }
.dropdown-menu { background-color: rgba(255,255,255,0.96); border-radius: 10px; min-width:180px; }
.dropdown-item { color:#111 !important; font-weight:600; padding:10px 14px; }
.dropdown-item:hover { background-color: rgba(0,0,0,0.06);}
.dropdown-item.text-danger { color:#b00000 !important; background: rgba(255,0,0,0.04); border-top:1px solid rgba(0,0,0,0.06);}

.main-content { display:flex; flex-wrap:wrap; justify-content:center; align-items:flex-start; gap:20px; flex:1; padding:100px 5%; }

.box {
    background: rgba(255,255,255,0.2); backdrop-filter: blur(10px);
    padding:30px; border-radius:15px; box-shadow:0 6px 20px rgba(0,0,0,0.35); width:45%; min-width:300px;
}
h2 { font-weight:700; color:white; margin-bottom:20px; }
.label-icon { display:flex; align-items:center; gap:8px; font-weight:600; color:white; margin-bottom:8px; }
.label-icon i { font-size:1.1rem; }

.countdown {
    font-weight: bold;
    font-size: 1.2rem;
    margin-top: 10px;
    display: flex;
    gap: 10px;
    justify-content: center;
    flex-wrap: wrap;
}
.countdown span {
    background: linear-gradient(135deg, #FFD700, #FF8C00);
    padding: 8px 12px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    min-width: 50px;
    text-align: center;
    font-family: 'Poppins', sans-serif;
    animation: glow 1.5s ease-in-out infinite alternate;
}

/* Consistent button style */
.btn-custom, .btn-reschedule {
    background: linear-gradient(135deg, #FFD700, #FF8C00);
    color: #fff;
    font-weight: 700;
    border: none;
    padding: 0.7rem 1rem;
    border-radius: 50px;
    width: 100%;
    transition: all 0.3s ease;
    box-shadow: 0 6px 20px rgba(255, 140, 0, 0.5);
    backdrop-filter: blur(10px);
    text-transform: uppercase;
    letter-spacing: 1px;
}
.btn-custom:hover, .btn-reschedule:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(255, 165, 0, 0.7);
    background: linear-gradient(135deg, #FF8C00, #FFD700);
}

@keyframes glow {
    0% { box-shadow: 0 0 10px #FFD700; }
    100% { box-shadow: 0 0 20px #FF8C00; }
}

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
        <?php if(isset($_SESSION['user_id'])): ?>
        <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="main-content container">
    <!-- Reservation Status -->
    <div class="box">
        <h2>Reservation Status</h2>
        <?php if ($reservation): 
            $dayName = date('l', strtotime($reservation['reserved_date']));
            $pos_query = $conn->query("SELECT COUNT(*) as cnt FROM reservations 
                                       WHERE reserved_date='{$reservation['reserved_date']}' 
                                       AND reserved_time='{$reservation['reserved_time']}' 
                                       AND id <= {$reservation['id']}");
            $pos_result = $pos_query->fetch_assoc();
            $position = $pos_result['cnt'];
        ?>
            <p class="label-icon"><i class="fa-solid fa-user"></i> <strong>Name:</strong> <?php echo ucwords(strtolower($user['fullname'])); ?></p>
            <p class="label-icon"><i class="fa-solid fa-id-card"></i> <strong>School ID:</strong> <?php echo $user['school_id']; ?></p>
            <p class="label-icon"><i class="fa-solid fa-phone"></i> <strong>Phone:</strong> <?php echo $user['phone']; ?></p>
            <p class="label-icon"><i class="fa-solid fa-envelope"></i> <strong>Email:</strong> <?php echo $user['email']; ?></p>
            <p class="label-icon"><i class="fa-solid fa-calendar-days"></i> <strong>Date Reserved:</strong> <?php echo date('F d, Y', strtotime($reservation['reserved_date'])) . " / " . $dayName; ?></p>
            <p class="label-icon"><i class="fa-solid fa-clock"></i> <strong>Time Slot:</strong> <?php echo date('h:i A', strtotime($reservation['reserved_time'])); ?></p>
            <p class="label-icon"><i class="fa-solid fa-users"></i> <strong>Your Position:</strong> <?php echo $position; ?></p>
            <p class="label-icon"><i class="fa-solid fa-hourglass-half"></i> <strong>Time Remaining:</strong> <span id="countdown"></span></p>
            <form method="POST">
                <button type="submit" name="clear_reservation" class="btn btn-reschedule mt-3"><i class="fa-solid fa-calendar-arrow-up"></i> Reschedule</button>
            </form>
        <?php else: ?>
            <p class="fw-bold text-danger">You have not reserved yet.</p>
        <?php endif; ?>
    </div>

    <!-- Make Reservation -->
    <div class="box">
        <h2><?php echo $reservation ? "Reservation Made" : "Make a Reservation"; ?></h2>
        <?php echo $message; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label"><i class="fa-solid fa-calendar-days"></i> Select Date (Mon, Wed, Fri):</label>
                <input type="date" name="reserve_date" class="form-control" min="<?php echo date('Y-m-d'); ?>" <?php if($reservation) echo "value='{$reservation['reserved_date']}' disabled"; else echo "required"; ?> >
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="fa-solid fa-clock"></i> Select Time:</label>
                <select name="reserve_time" class="form-control" <?php if($reservation) echo "disabled"; else echo "required"; ?> >
                    <option value="">--Select--</option>
                    <?php foreach($time_slots as $slot): ?>
                        <option value="<?php echo $slot; ?>" <?php if($reservation && $reservation['reserved_time']==$slot) echo "selected"; ?>>
                            <?php echo date('h:i A', strtotime($slot)); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php if(!$reservation): ?>
                <button type="submit" class="btn btn-custom w-100"><i class="fa-solid fa-calendar-check"></i> Reserve</button>
            <?php endif; ?>
        </form>
    </div>
</div>

<script>
<?php if($reservation): ?>
var countDownDate = new Date("<?php echo $reservation['reserved_date']." ".$reservation['reserved_time']; ?>").getTime();
var countdownElem = document.getElementById("countdown");
var x = setInterval(function() {
    var now = new Date().getTime();
    var distance = countDownDate - now;

    if (distance < 0) {
        countdownElem.innerHTML = "<span>Time's up!</span>";
        clearInterval(x);
    } else {
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        countdownElem.innerHTML = `<span>${days}d</span> <span>${hours}h</span> <span>${minutes}m</span> <span>${seconds}s</span>`;
    }
}, 1000);
<?php endif; ?>
</script>

<footer>© 2025 Phinma SmartQueue. All rights reserved.</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
