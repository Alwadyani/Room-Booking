<?php
include 'connection.php';
include 'header.php';

if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];
$selected_room_id = isset($_GET['room']) && is_numeric($_GET['room']) ? $_GET['room'] : null;

if (isset($_POST['book_room'])) {
    $room_id = $_POST['room_id'];
    $booking_date = $_POST['booking_date'];
    $booking_time = $_POST['booking_time'];
    $datetime = $booking_date . ' ' . $booking_time;

    // Check if room is available for the selected datetime
    $sql = "SELECT * FROM bookings WHERE room_id = ? AND booking_date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $room_id, $datetime);  // This should be the correct format
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error_message = "The room is already booked for the selected date and time.";
    } else {
        
        $sql = "INSERT INTO bookings (user_id, room_id, booking_date, booking_time) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        $stmt->bind_param("iiss", $user_id, $room_id, $booking_date, $booking_time);
        
        if ($stmt->execute()) {
            $success_message = "Room booked successfully!";
        } else {
            $error_message = "Error booking the room: " . $conn->error;
        }
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Room</title>
    <style>
        /* Add some styling for the booking form */
        .booking-form {
            width: 40%;
            margin: 50px auto;
            background: #f9f9f9;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: bold;
        }
        .form-group select {
            width: 94%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-group input {
            width: 90%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .submit-btn {
            display: inline-block;
            background-color: #8739F9;
            color: white;
            padding: 10px 15px;
            font-size: 18px;
            border-radius: 5px;
            cursor: pointer;
            width: 40%;
        }

        .submit-btn:hover {
            background-color: #37B9F1;
        }

        .error-message, .success-message {
            color: #fff;
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
        }

        .error-message {
            background-color: #f44336;
        }

        .success-message {
            background-color: #4CAF50;
        }
    </style>
</head>
<body>

<section class="booking-form">
    <h2>Book a Room</h2>

    <?php if (isset($error_message)): ?>
        <div class="error-message"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <?php if (isset($success_message)): ?>
        <div class="success-message"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <form action="booking.php" method="POST">
        <div class="form-group">
            <h3 for="room_id">Choose The Room:</h3>
            <select name="room_id" id="room_id" required>
                <option value="">--Choose here--</option>
                <?php
               
                $sql = "SELECT * FROM rooms";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    $selected = ($selected_room_id == $row['id']) ? "selected" : "";
                    echo "<option value='" . $row['id'] . "' $selected>" . htmlspecialchars($row['name']) . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="booking_date">Select Date:</label>
            <input type="date" name="booking_date" id="booking_date" required>
        </div>

        <div class="form-group">
            <label for="booking_time">Select Time:</label>
            <input type="time" name="booking_time" id="booking_time" required>
        </div>

        <button type="submit" name="book_room" class="submit-btn">Book Room</button>
    </form>
</section>

</body>
</html>

<?php $conn->close(); ?>
