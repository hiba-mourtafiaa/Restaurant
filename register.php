<?php
session_start();
$conn = new mysqli("localhost", "root", "", "biteme");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "Email already registered!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $firstname, $lastname, $email, $password);
        $stmt->execute();

        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Register</title>
</head>
<body>
  <h2>Create an Account</h2>

  <form method="POST">
    <input type="text" name="firstname" placeholder="First Name" required /><br>
    <input type="text" name="lastname" placeholder="Last Name" required /><br>
    <input type="email" name="email" placeholder="Email" required /><br>
    <input type="password" name="password" placeholder="Password" required /><br>
    <label><input type="checkbox" required> I agree to the Terms & Conditions</label><br>
    <button type="submit">Register</button>
  </form>

  <?php if($error): ?>
    <p style="color:red;"><?php echo $error; ?></p>
  <?php endif; ?>

  <p>Already have an account? <a href="login.php">Log in</a></p>
</body>
</html>
