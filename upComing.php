<?php

include 'connection.php';


$rooms_sql = "SELECT * FROM rooms";
$rooms_result = $conn->query($rooms_sql);


$current_date = date('Y-m-d');


$bookings_sql = "
    SELECT b.*, r.name AS room_name, r.id AS room_id
    FROM bookings b
    INNER JOIN rooms r ON b.room_id = r.id
    WHERE DATE(b.booking_date) >= ? 
    ORDER BY b.booking_date, b.booking_time
";
$bookings_stmt = $conn->prepare($bookings_sql);
$bookings_stmt->bind_param("s", $current_date);
$bookings_stmt->execute();
$bookings_result = $bookings_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Room Bookings - Admin</title>
    <style>
        /* Style your page with colors, layout, and elements */
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
        .booking-list {
            margin-top: 20px;
        }
        .booking-card {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .booking-card h3 {
            margin: 0;
            color: #333;
        }
        .booking-card p {
            margin: 10px 0;
            color: #666;
        }
        .booking-card .booking-time {
            font-weight: bold;
        }
        .available {
            color: green;
        }
        .unavailable {
            color: red;
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

    <div class="booking-list">
        <?php if ($bookings_result->num_rows > 0): ?>
            <?php while ($booking = $bookings_result->fetch_assoc()): ?>
                <div class="booking-card">
                    <h3>Room: <?php echo htmlspecialchars($booking['room_name']); ?></h3>
                    <p><strong>Booking Date:</strong> <?php echo date('d-m-Y', strtotime($booking['booking_date'])); ?></p>
                    <p><strong>Booking Time:</strong> <?php 
                        $bookingTime = new DateTime($booking['booking_time']);
                        echo $bookingTime->format('H:i'); 
                    ?></p>
                    <p><strong>Status: </strong> <span class="unavailable">Booked</span></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No upcoming bookings available.</p>
        <?php endif; ?>
    </div>
</section>

</body>
</html>

<?php 
$conn->close(); 
?>
