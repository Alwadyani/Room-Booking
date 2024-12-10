<?php

include 'connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Room Bookings - Admin</title>
    <style>
        
        .schedule-container {
            margin-top: 5%;
            margin-left: 5%;
            padding: 30px;
            max-width: 800px;
            background: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .schedule-header {
            margin-bottom: 20px;
        }
        .schedule-header h2 {
            margin-top: 4%;
        }
        
        .LB {
            margin-top: 600%;
            padding-top: 200%;
            color: #565360;
            text-decoration: none;
            font-weight: bold;
            padding: 10px 20px;
            border: 2px solid #565360;
            border-radius: 10px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .LB:hover {
            background-color: #565360;
            color: white;
        }
    </style>
</head>
<body>

<section class="schedule-container">
    <div class="schedule-header">
        <a href="adminDashboard.php" class="LB">Back to Dashboard</a>
        <a href="logout.php" class="LB">Logout</a>
        <h2>Upcoming Room Bookings</h2>
    </div>

</section>

</body>
</html>

<?php 
$conn->close(); 
?>
