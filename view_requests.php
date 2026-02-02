<?php
session_start();
include "config.php";

if ($_SESSION["role"] != "donor") {
    echo "<div class='denied'>Access denied.</div>";
    exit();
}

// Sorting setup
$orderBy = "r.id DESC";
if (isset($_GET['sort'])) {
    if ($_GET['sort'] == 'urgency') $orderBy = "FIELD(r.urgency, 'High', 'Medium', 'Low')";
    elseif ($_GET['sort'] == 'location') $orderBy = "r.location ASC";
}

// Filtering setup
$filterCondition = "r.status = 'Open'";
if (isset($_GET['blood_group']) && $_GET['blood_group'] != '') {
    $bloodGroup = $_GET['blood_group'];
    $filterCondition .= " AND r.blood_group = '$bloodGroup'";
}

// Fetch requests
$result = $conn->query("SELECT r.id, u.username, u.email, r.blood_group, r.location, r.urgency, r.status
                        FROM blood_requests r
                        JOIN users u ON r.user_id = u.id
                        WHERE $filterCondition
                        ORDER BY $orderBy");

// Handle Accept / Decline
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        $req_id = $_POST['request_id'];
        $action = $_POST['action'];

        if ($action == 'accept') {
            $conn->query("UPDATE blood_requests SET status='Accepted' WHERE id='$req_id'");
        } elseif ($action == 'decline') {
            $conn->query("UPDATE blood_requests SET status='Declined' WHERE id='$req_id'");
        }

        header("Location: view_requests.php");
        exit();
    }

    // Handle Message
    if (isset($_POST['message']) && isset($_POST['email'])) {
        $email = $_POST['email'];
        $message = $_POST['message'];
       $sender_id = $_SESSION["id"];
$receiver = $conn->query("SELECT id FROM users WHERE email='$email'")->fetch_assoc();
$receiver_id = $receiver['id'];
$conn->query("INSERT INTO messages (sender_id, receiver_id, message) VALUES ('$sender_id', '$receiver_id', '$message')");
echo "<script>alert('Message sent successfully!');</script>";

    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Blood Requests | BloodBridge</title>
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
        width: 900px;
        text-align: center;
    }
    h2 {
        color: #b30000;
        margin-bottom: 30px;
    }
    .request-card {
        background: #f8f8f8;
        border-left: 6px solid #b30000;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 10px;
        text-align: left;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .request-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .request-card b { color: #b30000; }
    .actions {
        margin-top: 10px;
        display: flex;
        gap: 10px;
    }
    button {
        background: #b30000;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
        transition: 0.3s;
    }
    button:hover { background: #ff4d4d; }
    .decline { background: #666; }
    .decline:hover { background: #999; }
    .msg-btn { background: #ff2e63; }
    .msg-btn:hover { background: #ff597a; }
    .sort-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        background: #f8f8f8;
        padding: 15px;
        border-radius: 10px;
    }
    select {
        padding: 8px;
        border-radius: 6px;
        border: 1px solid #bbb;
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
    .no-data {
        background: #fff;
        color: #555;
        border-radius: 10px;
        padding: 20px;
        margin-top: 30px;
        font-weight: 500;
    }
    /* Message Modal */
    .modal {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.6);
        justify-content: center;
        align-items: center;
    }
    .modal-content {
        background: white;
        color: #333;
        padding: 25px;
        border-radius: 10px;
        width: 400px;
        text-align: center;
        box-shadow: 0 5px 20px rgba(0,0,0,0.3);
    }
    textarea {
        width: 100%;
        height: 80px;
        border-radius: 8px;
        padding: 8px;
        border: 1px solid #ccc;
    }
</style>
<script>
function openMessageModal(email) {
    document.getElementById('msgModal').style.display = 'flex';
    document.getElementById('emailInput').value = email;
}
function closeModal() {
    document.getElementById('msgModal').style.display = 'none';
}
</script>
</head>
<body>
<div class="container">
    <h2>Available Blood Requests</h2>
    
    <!-- SORT & FILTER BAR -->
    <div class="sort-bar">
        <form method="GET" style="display:flex; gap:15px; align-items:center;">
            <label><strong>Sort by:</strong></label>
            <select name="sort">
                <option value="">Default</option>
                <option value="urgency" <?= (isset($_GET['sort']) && $_GET['sort']=='urgency')?'selected':'' ?>>Urgency</option>
                <option value="location" <?= (isset($_GET['sort']) && $_GET['sort']=='location')?'selected':'' ?>>Location</option>
            </select>
            <label><strong>Filter by Blood Group:</strong></label>
            <select name="blood_group">
                <option value="">All</option>
                <?php
                $groups = ["A+","A-","B+","B-","O+","O-","AB+","AB-"];
                foreach ($groups as $g) {
                    $selected = (isset($_GET['blood_group']) && $_GET['blood_group']==$g) ? 'selected' : '';
                    echo "<option value='$g' $selected>$g</option>";
                }
                ?>
            </select>
            <button type="submit">Apply</button>
        </form>
    </div>

    <!-- REQUEST LIST -->
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "
            <div class='request-card'>
                <p><b>Request #{$row['id']}</b></p>
                <p><strong>Requested by:</strong> {$row['username']}</p>
                <p><strong>Blood Group:</strong> {$row['blood_group']}</p>
                <p><strong>Location:</strong> {$row['location']}</p>
                <p><strong>Urgency:</strong> {$row['urgency']}</p>
                <p><strong>Status:</strong> {$row['status']}</p>
                <div class='actions'>
                    <form method='POST'>
                        <input type='hidden' name='request_id' value='{$row['id']}'>
                        <button type='submit' name='action' value='accept'>Accept</button>
                        <button type='submit' name='action' value='decline' class='decline'>Decline</button>
                    </form>
                    <button type='button' class='msg-btn' onclick=\"openMessageModal('{$row['email']}')\">Send Message</button>
                </div>
            </div>";
        }
    } else {
        echo "<div class='no-data'>No open requests available right now.</div>";
    }
    ?>
    <a href="dashboard.php" class="back">← Back to Dashboard</a>
</div>

<!-- Message Modal -->
<div class="modal" id="msgModal">
    <div class="modal-content">
        <h3>Send Message</h3>
        <form method="POST">
            <input type="hidden" name="email" id="emailInput">
            <textarea name="message" placeholder="Type your message..." required></textarea><br><br>
            <button type="submit">Send</button>
            <button type="button" class="decline" onclick="closeModal()">Cancel</button>
        </form>
    </div>
</div>
</body>
</html>
