<?php
session_start();
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard | BloodBridge</title>
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
        color: #333;
    }
    .dashboard-container {
        background: #fff;
        width: 400px;
        padding: 40px 30px;
        border-radius: 20px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
        text-align: center;
        animation: fadeIn 0.8s ease;
    }
    @keyframes fadeIn {
        from {opacity: 0; transform: scale(0.9);}
        to {opacity: 1; transform: scale(1);}
    }
    h2 {
        color: #b30000;
        margin-bottom: 5px;
    }
    p {
        font-size: 15px;
        color: #555;
        margin-bottom: 25px;
    }
    a {
        display: block;
        text-decoration: none;
        background: #b30000;
        color: white;
        padding: 12px;
        border-radius: 10px;
        font-weight: 600;
        transition: 0.3s;
        margin-bottom: 15px;
    }
    a:hover {
        background: #ff1a1a;
        transform: translateY(-2px);
    }
    .logout {
        background: #333;
    }
    .logout:hover {
        background: #555;
    }
    .role-badge {
        display: inline-block;
        background: #ffe0e0;
        color: #b30000;
        padding: 5px 12px;
        border-radius: 12px;
        font-weight: 500;
        font-size: 13px;
    }
</style>
</head>
<body>
    <div class="dashboard-container">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h2>
        <p>Your Role: <span class="role-badge"><?php echo ucfirst($_SESSION["role"]); ?></span></p>

        <?php if ($_SESSION["role"] == "receiver") { ?>
            <a href="make_request.php">🩸 Make New Blood Request</a>
            <a href="my_requests.php">📋 View My Requests & Status</a>
            <a href="messages.php">💬 View Messages from Donors</a>
        <?php } ?>

        <?php if ($_SESSION["role"] == "donor") { ?>
            <a href="view_requests.php">❤️ View Blood Requests</a>
        <?php } ?>

        <a href="logout.php" class="logout">🚪 Logout</a>
    </div>
</body>
</html>
