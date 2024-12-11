<?php

include 'connection.php';
include 'header.php';

// Check if the user is logged in
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];

// upcoming bookings
$sql_upcoming = "SELECT b.*, r.name AS room_name, r.image AS room_image FROM bookings b INNER JOIN rooms r ON b.room_id = r.id WHERE b.user_id = ? AND b.booking_date >= CURDATE() ORDER BY b.booking_date ASC";
$stmt_upcoming = $conn->prepare($sql_upcoming);
$stmt_upcoming->bind_param("i", $user_id);
$stmt_upcoming->execute();
$result_upcoming = $stmt_upcoming->get_result();

// past bookings
$sql_past = "SELECT b.*, r.name AS room_name, r.image AS room_image FROM bookings b INNER JOIN rooms r ON b.room_id = r.id WHERE b.user_id = ? AND b.booking_date < CURDATE() ORDER BY b.booking_date DESC";
$stmt_past = $conn->prepare($sql_past);
$stmt_past->bind_param("i", $user_id);
$stmt_past->execute();
$result_past = $stmt_past->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        .dashboard-container {
            padding: 40px;
            max-width: 800px;
            margin-top: 5%;
            margin-left: 13%;
            background: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .section {
            margin-bottom: 40px;
        }
        .booking-card {
            padding: 20px;
            margin-bottom: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .room-info {
            display: flex;
            align-items: center;
        }
        .room-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 20px;
        }
        .booking-actions a {
            margin-right: 10px;
            color: #007bff;
            text-decoration: none;
        }
        .booking-actions a.delete {
            color: #dc3545;
        }
        .no-bookings {
            text-align: center;
            font-size: 18px;
            color: #666;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <h2>User Dashboard</h2>

    <!-- Upcoming Bookings Section -->
    <div class="section">
        <h3>Upcoming Bookings</h3>
        <?php if ($result_upcoming->num_rows > 0): ?>
            <?php while ($booking = $result_upcoming->fetch_assoc()): ?>
                <div class="booking-card">
                    <div class="room-info">
                        <img src="images/<?php echo htmlspecialchars($booking['room_image']); ?>" alt="Room Image" class="room-image">
                        <h3><?php echo htmlspecialchars($booking['room_name']); ?></h3>
                    </div>
                    <p><strong>Booking Date:</strong> <?php echo date('d-m-Y', strtotime($booking['booking_date'])); ?></p>
                    <p><strong>Booking Time:</strong> <?php echo date('H:i', strtotime($booking['booking_time'])); ?></p>

                    <div class="booking-actions">
                        <a href="user_dashboard.php?delete=<?php echo $booking['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this booking?');">Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-bookings">You have no upcoming bookings.</p>
        <?php endif; ?>
    </div>

    <!-- Past Bookings Section -->
    <div class="section">
        <h3>Past Bookings</h3>
        <?php if ($result_past->num_rows > 0): ?>
            <?php while ($booking = $result_past->fetch_assoc()): ?>
                <div class="booking-card">
                    <div class="room-info">
                        <img src="images/<?php echo htmlspecialchars($booking['room_image']); ?>" alt="Room Image" class="room-image">
                        <h3><?php echo htmlspecialchars($booking['room_name']); ?></h3>
                    </div>
                    <p><strong>Booking Date:</strong> <?php echo date('d-m-Y', strtotime($booking['booking_date'])); ?></p>
                    <p><strong>Booking Time:</strong> <?php echo date('H:i', strtotime($booking['booking_time'])); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-bookings">You have no past bookings.</p>
        <?php endif; ?>
    </div>
</div>

<?php
// delete request
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $booking_id = intval($_GET['delete']); 

    
    $delete_sql = "DELETE FROM bookings WHERE id = ? AND user_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("ii", $booking_id, $user_id);

    
    if ($delete_stmt->execute()) {
        
        if ($delete_stmt->affected_rows > 0) {
            echo "<script>alert('Booking deleted successfully.'); window.location.href='user_dashboard.php';</script>";
        }
    } else {
        
        echo "<p style='color: red;'>Error deleting booking: " . htmlspecialchars($conn->error) . "</p>";
    }

    $delete_stmt->close();
}

$conn->close();
?>

</body>
</html>
