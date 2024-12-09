<?php

include 'connection.php';
include 'header.php';

//room details for room_id = 1 (wrong)
if (isset($_GET['room_id'])) {
    $room_id = intval($_GET['room_id']);
    $sql_room = "SELECT * FROM rooms WHERE id = ?";
    $stmt_room = $conn->prepare($sql_room);
    $stmt_room->bind_param("i", $room_id);
    $stmt_room->execute();
    $result_room = $stmt_room->get_result();

// Check if room is found
if ($result_room->num_rows > 0) {
    $room = $result_room->fetch_assoc();
} else {
    
    $room = null;
    $_SESSION['booking_error'] = "Room not found.";
    header("Location: booking.php");
    exit();
}

$stmt_room->close();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $booking_date = $_POST['booking_date'];
    $timeslot = $_POST['timeslot'];

    // Check for conflicts
    $stmt_check = $conn->prepare("SELECT * FROM bookings WHERE room_id = ? AND booking_date = ? AND timeslot = ?");
    $stmt_check->bind_param("iss", $room_id, $booking_date, $timeslot);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $_SESSION['booking_error'] = "Room is already booked for the selected date and timeslot.";
    } else {
        // Book the room
        $stmt_book = $conn->prepare("INSERT INTO bookings (user_id, room_id, booking_date, timeslot) VALUES (?, ?, ?, ?)");
        $stmt_book->bind_param("iiss", $user_id, $room_id, $booking_date, $timeslot);
        $stmt_book->execute();
        $_SESSION['booking_success'] = "Room booked successfully!";
        $stmt_book->close();
    }
    $stmt_check->close();
    header("Location: booking.php");
    exit();
}

// Handle booking cancellation
if (isset($_GET['cancel_booking_id']) && isset($_SESSION['user_id'])) {
    $booking_id = intval($_GET['cancel_booking_id']);
    $stmt_cancel = $conn->prepare("DELETE FROM bookings WHERE id = ? AND user_id = ?");
    $stmt_cancel->bind_param("ii", $booking_id, $_SESSION['user_id']);
    $stmt_cancel->execute();
    if ($stmt_cancel->affected_rows > 0) {
        $_SESSION['booking_success'] = "Booking cancelled successfully.";
    } else {
        $_SESSION['booking_error'] = "Unable to cancel booking.";
    }
    $stmt_cancel->close();
    header("Location: booking.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking - IT Room Booking System</title>
    <style>
        /* Styling for booking page */
        .room-details {
            padding: 40px 20px;
            max-width: 800px;
            margin: auto;
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

        .new-booking-section h3{
            text-align: center;
            font-size: 30px;
        }

        .new-booking-section form {
            background: var(--colorback);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }

        .new-booking-section label {
            display: block;
            margin-bottom: 10px;
            color: var(--colorLabel);
        }

        .new-booking-section input,
        .new-booking-section select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .new-booking-section button {
            background: var(--colorfirst);
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }

        .new-booking-section button:hover {
            background: var(--colorSecond);
        }
    </style>
</head>
<body>

<section class="new-booking-section">
<h3>Booking Room</h3>
    <form action="booking.php?room_id=<?php echo $room_id; ?>" method="POST">
        <div>
            <!-- Display the room name based on the selected room_id -->
            <label for="room_name">Room:</label>
            <form action="booking.php?room_id=<?php echo $room_id; ?>" method="POST">
        </div>
        <div>
            <label for="booking_date">Select Date:</label>
            <input type="date" name="booking_date" id="booking_date" required>
        </div>
        <div>
            <label for="timeslot">Select Timeslot:</label>
            <input type="time" name="timeslot" id="timeslot" required>
        </div>
        <button type="submit" class="btn">Book Room</button>
    </form>
</section>

</body>
</html>

<?php $conn->close(); ?>


