<?php
session_start();
include "config.php";

if ($_SESSION["role"] != "receiver") {
    echo "<div class='denied'>Access denied.</div>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $blood = $_POST["blood_group"];
    $location = $_POST["location"];
    $urgency = $_POST["urgency"];
    $user_id = $_SESSION["id"];

    $sql = "INSERT INTO blood_requests (user_id, blood_group, location, urgency)
            VALUES ('$user_id', '$blood', '$location', '$urgency')";
    if ($conn->query($sql)) {
        echo "<div class='success'><h3>Request submitted successfully!</h3><a href='dashboard.php'>Go back</a></div>";
    } else {
        echo "<div class='error'>Error: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Make Blood Request | BloodBridge</title>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #ff4d4d, #990000);
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }
    .container {
        background: white;
        color: #333;
        border-radius: 15px;
        padding: 40px 50px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        width: 380px;
        text-align: center;
    }
    h2 {
        color: #b30000;
        margin-bottom: 25px;
    }
    input[type="text"], select {
        width: 100%;
        padding: 10px;
        margin: 10px 0 20px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 15px;
    }
    input[type="submit"] {
        background-color: #b30000;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        transition: 0.3s;
    }
    input[type="submit"]:hover {
        background-color: #ff4d4d;
    }
    a {
        color: #b30000;
        text-decoration: none;
        font-weight: bold;
    }
    a:hover {
        text-decoration: underline;
    }
    .success, .error, .denied {
        background: white;
        color: #333;
        border-radius: 10px;
        padding: 25px;
        text-align: center;
        width: 360px;
        margin: 80px auto;
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
    }
    .success h3 {
        color: #b30000;
        margin-bottom: 15px;
    }
</style>
</head>
<body>
<div class="container">
    <h2>Make Blood Request</h2>
    <form method="POST">
        <label>Blood Group</label>
        <input type="text" name="blood_group" placeholder="e.g., B+" required>

        <label>Location</label>
        <input type="text" name="location" placeholder="Enter your area" required>

        <label>Urgency</label>
        <select name="urgency" required>
            <option value="Low">Low</option>
            <option value="Medium">Medium</option>
            <option value="High">High</option>
        </select>

        <input type="submit" value="Submit Request">
    </form>
    <p><a href="dashboard.php">← Back to Dashboard</a></p>
</div>
</body>
</html>
