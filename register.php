<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $role = $_POST["role"];

    $sql = "INSERT INTO users (username, email, password, role)
            VALUES ('$username', '$email', '$password', '$role')";
    if ($conn->query($sql)) {
        $success = "Registration successful! <a href='login.php'>Login now</a>";
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BloodBridge | Register</title>
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
    .register-container {
        background: #fff;
        padding: 40px 30px;
        border-radius: 15px;
        box-shadow: 0 0 30px rgba(0,0,0,0.2);
        width: 350px;
        text-align: center;
    }
    .register-container h2 {
        color: #b30000;
        margin-bottom: 10px;
    }
    .register-container p {
        font-size: 14px;
        color: #555;
        margin-bottom: 20px;
    }
    .register-container input,
    .register-container select {
        width: 100%;
        padding: 12px;
        margin: 10px 0 15px 0;
        border: 2px solid #ccc;
        border-radius: 8px;
        outline: none;
        transition: 0.3s;
    }
    .register-container input:focus,
    .register-container select:focus {
        border-color: #b30000;
    }
    .register-container button {
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
    .register-container button:hover {
        background-color: #ff1a1a;
    }
    .message {
        margin-top: 15px;
        font-size: 14px;
    }
    .success {
        color: green;
    }
    .error {
        color: red;
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
    .footer a:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>
    <div class="register-container">
        <h2>Join BloodBridge</h2>
        <p>Be the bridge between hope and life ❤️</p>

        <form method="POST">
            <input type="text" name="username" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role" required>
                <option value="">Select Role</option>
                <option value="donor">Donor</option>
                <option value="receiver">Receiver</option>
            </select>
            <button type="submit">Register</button>
        </form>

        <?php if (!empty($success)) echo "<p class='message success'>$success</p>"; ?>
        <?php if (!empty($error)) echo "<p class='message error'>$error</p>"; ?>

        <div class="footer">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>
</html>
