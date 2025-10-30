<?php
session_start();

$conn = new mysqli("localhost", "root", "", "biteme");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['firstname'] = $user['firstname'];
            $_SESSION['lastname'] = $user['lastname'];
            $_SESSION['email'] = $user['email'];

            header("Location: profile.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "Email not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | BiteMe</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f4f5ff;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .login-container {
      background: white;
      padding: 40px 50px;
      border-radius: 15px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      width: 350px;
      text-align: center;
    }

    .login-container h2 {
      margin-bottom: 20px;
      color: #4b3eff;
    }

    .login-container input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 8px;
      outline: none;
      transition: border-color 0.3s;
    }

    .login-container input:focus {
      border-color: #4b3eff;
    }

    .login-container button {
      width: 100%;
      background-color: #4b3eff;
      color: white;
      padding: 12px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      margin-top: 10px;
      transition: 0.3s;
    }

    .login-container button:hover {
      background-color: #372cd6;
    }

    .error {
      color: red;
      margin-top: 10px;
      font-size: 14px;
    }

    .register-link {
      margin-top: 20px;
      display: block;
      color: #4b3eff;
      text-decoration: none;
      font-size: 14px;
    }

    .register-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>Login to Your Account</h2>

    <form method="POST">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Log In</button>
    </form>

    <?php if($error): ?>
      <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <a href="register.php" class="register-link">Don't have an account? Register</a>
  </div>
</body>
</html>
