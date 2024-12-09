<?php
include 'connection.php';
include 'header.php';

if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];


$sql = "SELECT b.*, r.name AS room_name FROM bookings b INNER JOIN rooms r ON b.room_id = r.id WHERE b.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();


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
        /* Styling for bookings list */
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
    </style>
</head>
<body>

<section class="bookings-list">
    <h2>Your Bookings</h2>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($booking = $result->fetch_assoc()): ?>
            <div class="booking-card">
                <h3><?php echo htmlspecialchars($booking['room_name']); ?></h3>
                <p><strong>Booking Date:</strong> <?php echo htmlspecialchars($booking['booking_date']); ?></p>
                <p><strong>Booking Time:</strong> <?php echo htmlspecialchars($booking['booking_time']); ?></p>
                
                <!-- Booking action buttons -->
                <div class="booking-actions">
                    <a href="?edit=<?php echo $booking['id']; ?>">Edit</a>
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

<?php $conn->close(); ?>
