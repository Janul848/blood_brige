<?php
session_start();
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user["password"])) {
            $_SESSION["id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"] = $user["role"];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No user found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BloodBridge | Login</title>
<style>
    * {
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }
    body {
        margin: 0;
        padding: 0;
        background: linear-gradient(135deg, #ff3d3d, #8b0000);
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .login-container {
        background: #fff;
        padding: 40px 30px;
        border-radius: 15px;
        box-shadow: 0 0 30px rgba(0,0,0,0.2);
        width: 350px;
        text-align: center;
    }
    .login-container h2 {
        color: #b30000;
        margin-bottom: 25px;
    }
    .login-container input[type="email"],
    .login-container input[type="password"] {
        width: 100%;
        padding: 12px;
        margin: 10px 0 20px 0;
        border: 2px solid #ccc;
        border-radius: 8px;
        outline: none;
        transition: 0.3s;
    }
    .login-container input[type="email"]:focus,
    .login-container input[type="password"]:focus {
        border-color: #b30000;
    }
    .login-container input[type="submit"] {
        background-color: #b30000;
        color: white;
        border: none;
        padding: 12px;
        width: 100%;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
    }
    .login-container input[type="submit"]:hover {
        background-color: #ff1a1a;
    }
    .error {
        color: red;
        font-size: 14px;
        margin-bottom: 15px;
    }
    .footer {
        margin-top: 10px;
        font-size: 13px;
        color: #666;
    }
    .footer a {
        color: #b30000;
        text-decoration: none;
        font-weight: 600;
    }
</style>
</head>
<body>
    <div class="login-container">
        <h2>BloodBridge Login</h2>

        <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>
            <input type="password" name="password" placeholder="Enter your password" required>
            <input type="submit" value="Login">
        </form>
        <div class="footer">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</body>
</html>
