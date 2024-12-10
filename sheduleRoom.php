<?php

include 'connection.php';

$rooms_sql = "SELECT * FROM rooms";
$rooms_result = $conn->query($rooms_sql);

$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');


$bookings_sql = "SELECT b.*, r.name AS room_name FROM bookings b INNER JOIN rooms r ON b.room_id = r.id WHERE DATE(b.booking_date) = ?";
$bookings_stmt = $conn->prepare($bookings_sql);
$bookings_stmt->bind_param("s", $date);
$bookings_stmt->execute();
$bookings_result = $bookings_stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Schedule - Admin</title>
    <style>
        :root {
            --colorPrimary: #8739F9;
            --colorSecondary: #37B9F1;
            --colorBackground: #F2F5F5;
            --colorShadow: #565360;
            --colorLabel: #908E9B;
            --colorDisabled: #E1DFE9;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
        }

        .schedule-container {
            margin-top: 5%;
            margin-left: 15%;
            padding: 30px;
            max-width: 800px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .schedule-header {
            margin-bottom: 20px;
        }

        .schedule-header h2 {
            margin-top: 4%;
        }

        .room-schedule {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .room-card {
            width: 23%;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .room-card h3 {
            margin: 0;
            color: #333;
            font-size: 1.2rem;
        }

        .booking-time {
            margin: 10px 0;
            color: #666;
            font-size: 1rem;
        }

        .available {
            background-color: #28a745;
            color: #fff;
            padding: 5px;
            border-radius: 5px;
        }

        .booked {
            background-color: #dc3545;
            color: #fff;
            padding: 5px;
            border-radius: 5px;
            font-weight: bold;
        }

        .date-picker {
            margin: 10px 0;
            padding: 5px;
        }

        .LB {
            margin-top: 20px;
            color: var(--colorShadow);
            text-decoration: none;
            font-weight: bold;
            padding: 10px 20px;
            border: 2px solid var(--colorShadow);
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .LB:hover {
            background-color: var(--colorShadow);
            color: white;
        }

       
        .room-unavailable {
            background-color: #dc3545;
            color: white;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<section class="schedule-container">
    <div class="schedule-header">
        <a href="adminDashboard.php" class="LB">Back to Dashboard</a>
        <a href="logout.php" class="LB">Logout</a>
        <h2>Room Schedule for <?php echo date('d-m-Y', strtotime($date)); ?></h2>
        <!-- Date picker to change the schedule date -->
        <form action="" method="get">
            <label for="date">Select Date: </label>
            <input type="date" name="date" class="date-picker" value="<?php echo $date; ?>" onchange="this.form.submit()">
        </form>
    </div>

    <div class="room-schedule">
        <?php while ($room = $rooms_result->fetch_assoc()): ?>
            <div class="room-card">
                <h3><?php echo htmlspecialchars($room['name']); ?></h3>
                <?php
                $room_bookings_sql = "SELECT * FROM bookings WHERE room_id = ? AND DATE(booking_date) = ?";
                $room_bookings_stmt = $conn->prepare($room_bookings_sql);
                $room_bookings_stmt->bind_param("is", $room['id'], $date);
                $room_bookings_stmt->execute();
                $room_bookings_result = $room_bookings_stmt->get_result();

                if ($room_bookings_result->num_rows > 0):
                    while ($booking = $room_bookings_result->fetch_assoc()):
                        $bookingTime = new DateTime($booking['booking_time']);
                        $formattedBookingTime = $bookingTime->format('H:i');
                ?>
                    <!-- Display "Room Unavailable" with booked time -->
                    <div class="booking-time room-unavailable">
                        <strong>Room Unavailable:</strong> <?php echo $formattedBookingTime; ?>
                    </div>
                <?php endwhile; ?>
                <?php else: ?>
                    <div class="booking-time available">
                        bookings available
                    </div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</section>

</body>
</html>

<?php 
$conn->close(); 
?>
