<?php
include 'connection.php';
include 'header.php';

if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];


if (isset($_GET['id'])) {
    $booking_id = $_GET['id'];

  
    $sql = "SELECT * FROM bookings WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $booking_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo "Booking not found or you do not have permission to edit it.";
        exit();
    }

    $booking = $result->fetch_assoc();
} else {
    echo "No booking ID provided.";
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $booking_date = $_POST['booking_date'];
    $booking_time = $_POST['booking_time'];

    
    $update_sql = "UPDATE bookings SET booking_date = ?, booking_time = ? WHERE id = ? AND user_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssii", $booking_date, $booking_time, $booking_id, $user_id);

    if ($update_stmt->execute()) {
        echo "Booking updated successfully.";
    } else {
        echo "Error updating booking.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Booking</title>
</head>
<body>

<h2>Edit Booking</h2>

<form method="post">
    <label for="booking_date">Booking Date:</label>
    <input type="date" name="booking_date" id="booking_date" value="<?php echo htmlspecialchars($booking['booking_date']); ?>" required>
    <br>

    <label for="booking_time">Booking Time:</label>
    <input type="time" name="booking_time" id="booking_time" value="<?php echo htmlspecialchars($booking['booking_time']); ?>" required>
    <br>

    <button type="submit">Update Booking</button>
</form>

</body>
</html>

<?php $conn->close(); ?>
