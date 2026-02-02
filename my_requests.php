<?php
session_start();
include "config.php";

if (!isset($_SESSION["id"]) || $_SESSION["role"] != "receiver") {
    echo "<div style='color:red;text-align:center;'>Access denied.</div>";
    exit();
}

$user_id = $_SESSION["id"];
$result = $conn->query("SELECT id, blood_group, location, urgency, status, created_at 
                        FROM blood_requests 
                        WHERE user_id = '$user_id' 
                        ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Requests | BloodBridge</title>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #ff4d4d, #990000);
        color: white;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-height: 100vh;
        margin: 0;
        padding: 40px 0;
    }
    .container {
        background: white;
        color: #333;
        border-radius: 15px;
        padding: 40px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        width: 800px;
        text-align: center;
    }
    h2 {
        color: #b30000;
        margin-bottom: 30px;
    }
    .req-card {
        background: #f9f9f9;
        border-left: 6px solid #b30000;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 10px;
        text-align: left;
        transition: 0.2s;
    }
    .req-card:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    .status {
        font-weight: bold;
        padding: 5px 12px;
        border-radius: 8px;
    }
    .Open { background: #ffe9b3; color: #b36b00; }
    .Accepted { background: #c8f7c5; color: #256d1b; }
    .Declined { background: #ffd4d4; color: #b00000; }
    a.back {
        display: inline-block;
        margin-top: 20px;
        background: #b30000;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        transition: 0.3s;
    }
    a.back:hover { background: #ff4d4d; }
</style>
</head>
<body>
<div class="container">
    <h2>My Blood Requests</h2>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "
            <div class='req-card'>
                <p><strong>Request #{$row['id']}</strong></p>
                <p><b>Blood Group:</b> {$row['blood_group']}</p>
                <p><b>Location:</b> {$row['location']}</p>
                <p><b>Urgency:</b> {$row['urgency']}</p>
                <p><b>Status:</b> <span class='status {$row['status']}'>{$row['status']}</span></p>
            </div>
            ";
        }
    } else {
        echo "<div class='req-card'>You haven’t made any blood requests yet.</div>";
    }
    ?>
    <a href="dashboard.php" class="back">← Back to Dashboard</a>
</div>
</body>
</html>
