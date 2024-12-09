<?php
include 'connection.php'; // Include the database connection file
include 'header.php'; // Include the header for navigation

// Fetch user bookings
$user_bookings = [];
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt_fetch = $conn->prepare("SELECT bookings.id AS booking_id, rooms.name AS room_name, bookings.booking_date, bookings.timeslot
                                  FROM bookings
                                  INNER JOIN rooms ON bookings.room_id = rooms.id
                                  WHERE bookings.user_id = ?
                                  ORDER BY bookings.booking_date, bookings.timeslot");
    $stmt_fetch->bind_param("i", $user_id);
    $stmt_fetch->execute();
    $result_fetch = $stmt_fetch->get_result();
    $user_bookings = $result_fetch->fetch_all(MYSQLI_ASSOC);
    $stmt_fetch->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Bookings - IT Room Booking System</title>
    <style>
        /* Styling for booking page */
        .room-details {
            padding: 40px 20px;
            max-width: 800px;
            margin: auto;
            margin-top: 5%;
            background: var(--colorback);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .room-details h1 {
            color: var(--colorShadow);
            font-size: 2rem;
            margin: 20px 0;
        }

        .room-details p {
            font-size: 1rem;
            color: var(--colorLabel);
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .room-details .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: var(--colorfirst);
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .room-details .back-btn:hover {
            background: var(--colorSecond);
        }

        .booking-table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }

        .booking-table th,
        .booking-table td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        .booking-table th {
            background-color: var(--colorfirst);
            color: #fff;
        }

        .booking-table td {
            text-align: center;
        }

        .btn {
            background-color: var(--colorfirst);
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn:hover {
            background-color: var(--colorSecond);
        }
    </style>
</head>
<body>

<section class="room-details">
    <h1>Your Bookings</h1>
    <?php
    if (isset($_SESSION['booking_success'])) {
        echo "<p style='color: green;'>" . $_SESSION['booking_success'] . "</p>";
        unset($_SESSION['booking_success']);
    }
    if (isset($_SESSION['booking_error'])) {
        echo "<p style='color: red;'>" . $_SESSION['booking_error'] . "</p>";
        unset($_SESSION['booking_error']);
    }
    ?>
    <table class="booking-table">
        <thead>
        <tr>
            <th>Room</th>
            <th>Date</th>
            <th>Timeslot</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php if (count($user_bookings) > 0): ?>
            <?php foreach ($user_bookings as $booking): ?>
                <tr>
                    <td><?php echo htmlspecialchars($booking['room_name']); ?></td>
                    <td><?php echo htmlspecialchars($booking['booking_date']); ?></td>
                    <td><?php echo htmlspecialchars($booking['timeslot']); ?></td>
                    <td>
                        <a href="booking.php?cancel_booking_id=<?php echo $booking['booking_id']; ?>" class="btn">Cancel</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No bookings found.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</section>

</body>
</html>

<?php $conn->close(); ?>


