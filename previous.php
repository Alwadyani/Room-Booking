<?php

include 'connection.php';


$bookings_sql = "
    SELECT b.*, r.name AS room_name, u.name AS student_name
    FROM bookings b
    INNER JOIN rooms r ON b.room_id = r.id
    INNER JOIN user u ON b.user_id = u.id
    WHERE b.booking_date < ? 
    ORDER BY b.booking_date DESC, b.booking_time DESC
";


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
    <title>Previous Bookings</title>
    <style>
    :root {
            --colorfirst: #8739F9; /*primary contents*/
            --colorSecond: #37B9F1; /*secondary contents*/
            --colorback: #F2F5F5; /*background for contents*/
            --colorShadow: #565360; /*main background*/
            --colorLabel: #908E9B; /*accessory use*/
            --colorDisabled: #E1DFE9; /*accessory use*/
            --lengths1: 0.25rem; /* small 1*/
            --lengths2: 0.375rem; /*small 2*/
            --lengths3: 0.5rem; /*small 3*/
            --lengthm1: 1rem; /*medium 1*/
            --lengthm2: 1.25rem; /*medium 2*/
            --lengthm3: 1.5rem; /*medium 3*/
            --lengthl1: 2rem; /*large 1*/
            --lengthl2: 3rem; /*large 2*/
            --lengthl3: 4rem; /*large 3*/
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            margin-top: 50px;
            font-size: 2em;
            color: var(--colorfirst);
        }

        .table-container {
            width: 80%;
            margin: 0 auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-top: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: var(--colorfirst);
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        td {
            font-size: 1.1em;
        }

        .back-btn {
            display: block;
            width: 200px;
            margin: 30px auto;
            text-align: center;
            padding: 12px;
            background-color: var(--colorfirst);
            color: white;
            font-size: 1.1em;
            border-radius: 6px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover {
            background-color: var(--colorShadow);
        }

        .LB {
            margin-top: 20px;
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

    <a href="adminDashboard.php" class="LB">Back to Dashboard</a>
    <a href="logout.php" class="LB">Logout</a>
    <h2>Previous Room Bookings</h2>
     <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Room</th>
                    <th>Booking Date</th>
                    <th>Booking Time</th>
                    <th>Student Name</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($booking = $bookings_result->fetch_assoc()):
                    $bookingTime = new DateTime($booking['booking_time']);
                    $formattedBookingTime = $bookingTime->format('H:i');
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($booking['room_name']); ?></td>
                        <td><?php echo date('d-m-Y', strtotime($booking['booking_date'])); ?></td>
                        <td><?php echo $formattedBookingTime; ?></td>
                        <td><?php echo htmlspecialchars($booking['student_name']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
