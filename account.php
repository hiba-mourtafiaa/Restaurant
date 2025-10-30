<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Your Account</title>
</head>
<body>
  <h1>Welcome, <?php echo htmlspecialchars($_SESSION['firstname']); ?>!</h1>

  <p>You are successfully logged in.</p>
  <a href="logout.php">Log Out</a>
</body>
</html>