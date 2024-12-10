<?php
// Include required files
include 'connection.php';
include 'header.php';

// Check if user is logged in
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];

// Fetch bookings for the logged-in user
$sql = "SELECT b.*, r.name AS room_name, r.image AS room_image FROM bookings b INNER JOIN rooms r ON b.room_id = r.id WHERE b.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Handle delete request
if (isset($_GET['delete'])) {
    $booking_id = $_GET['delete'];

    $delete_sql = "DELETE FROM bookings WHERE id = ? AND user_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("ii", $booking_id, $user_id);
    if ($delete_stmt->execute()) {
        echo "Booking deleted successfully.";
    } else {
        echo "Error deleting booking.";
    }
}

// Handle edit request
if (isset($_GET['edit'])) {
    $booking_id = $_GET['edit'];
    header("Location: edit_booking.php?id=" . $booking_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Bookings</title>
    <style>
        .bookings-list {
            padding: 40px;
            max-width: 800px;
            margin: auto;
            background: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .booking-card {
            padding: 20px;
            margin-bottom: 20px;
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
        .booking-actions {
            margin-top: 10px;
        }
        .booking-actions a {
            margin-right: 10px;
            color: #007bff;
            text-decoration: none;
        }
        .booking-actions a.delete {
            color: #dc3545;
        }
        .room-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 20px;
        }
        .room-info {
            display: flex;
            align-items: center;
        }
    </style>
</head>
<body>

<section class="bookings-list">
    <h2>Your Bookings</h2>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($booking = $result->fetch_assoc()): ?>
            <div class="booking-card">
                <div class="room-info">
                    <!-- Display Room Image -->
                    <?php if ($booking['room_image']): ?>
                        <img src="images/<?php echo htmlspecialchars($booking['room_image']); ?>" alt="Room Image" class="room-image">
                    <?php else: ?>
                        <img src="images/default-room.jpg" alt="Default Room Image" class="room-image">
                    <?php endif; ?>
                    <h3><?php echo htmlspecialchars($booking['room_name']); ?></h3>
                </div>

                <p>
                    <strong>Booking Date:</strong> 
                    <?php 
                    // Format the booking date
                    $bookingDate = new DateTime($booking['booking_date']); 
                    echo $bookingDate->format('d-m-Y'); 
                    ?>
                </p>
                <p>
                    <strong>Booking Time:</strong> 
                    <?php 
                    $rawBookingTime = $booking['booking_time']; 

                    // Add ":00" for missing minutes
                    if (preg_match('/^\d{1,2}$/', $rawBookingTime)) {
                        $rawBookingTime .= ":00"; 
                    }

                    // Validate and display the time
                    if (preg_match('/^\d{1,2}:\d{2}(:\d{2})?$/', $rawBookingTime)) {
                        $bookingTime = new DateTime($rawBookingTime); 
                        echo $bookingTime->format('H:i'); 
                    } else {
                        echo htmlspecialchars($rawBookingTime) ?: 'Invalid time format';
                    }
                    ?>
                </p>

                <div class="booking-actions">
                    <a href="editBooking.php?edit=<?php echo $booking['id']; ?>">Edit</a>
                    <a href="?delete=<?php echo $booking['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this booking?');">Delete</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>You have no bookings yet.</p>
    <?php endif; ?>
</section>

</body>
</html>

<?php 
// Close the database connection
$conn->close(); 
?>


