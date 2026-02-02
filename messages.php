<?php
session_start();
include "config.php";

if (!isset($_SESSION["id"]) || $_SESSION["role"] != "receiver") {
    echo "<div style='color:red;text-align:center;'>Access denied.</div>";
    exit();
}

$user_id = $_SESSION["id"];
$result = $conn->query("SELECT m.message, m.created_at, u.username AS sender
                        FROM messages m
                        JOIN users u ON m.sender_id = u.id
                        WHERE m.receiver_id = '$user_id'
                        ORDER BY m.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Messages | BloodBridge</title>
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
        width: 700px;
        text-align: center;
    }
    h2 {
        color: #b30000;
        margin-bottom: 30px;
    }
    .msg-card {
        background: #f9f9f9;
        border-left: 6px solid #b30000;
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 10px;
        text-align: left;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .msg-card p { margin: 5px 0; }
    .timestamp {
        font-size: 12px;
        color: #888;
    }
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
    <h2>Messages from Donors</h2>
    <?php
    if ($result->num_rows > 0) {
        while ($msg = $result->fetch_assoc()) {
            echo "
            <div class='msg-card'>
                <p><strong>From:</strong> {$msg['sender']}</p>
                <p>{$msg['message']}</p>
                <p class='timestamp'>{$msg['created_at']}</p>
            </div>
            ";
        }
    } else {
        echo "<div class='msg-card'>No messages yet.</div>";
    }
    ?>
    <a href='dashboard.php' class='back'>← Back to Dashboard</a>
</div>
</body>
</html>
