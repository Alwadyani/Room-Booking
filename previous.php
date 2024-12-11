<?php
include 'connection.php';

// Fetch all bookings with user information
$sql = "SELECT b.*, r.name AS room_name, r.image AS room_image, u.name AS user_name, u.email AS user_email 
        FROM bookings b 
        INNER JOIN rooms r ON b.room_id = r.id
        INNER JOIN users u ON b.user_id = u.id";

// Prepare the SQL query
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    // Error preparing the query
    echo "Error preparing the SQL query: " . htmlspecialchars($conn->error);
    exit;
}

$stmt->execute();
$result = $stmt->get_result();

// Handle delete request (admin can delete any booking)
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $booking_id = intval($_GET['delete']); 

    // Prepare the delete SQL query
    $delete_sql = "DELETE FROM bookings WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    if ($delete_stmt === false) {
        // Error preparing the delete query
        echo "Error preparing the delete query: " . htmlspecialchars($conn->error);
        exit;
    }

    $delete_stmt->bind_param("i", $booking_id);

    // Execute the delete query
    if ($delete_stmt->execute()) {
        if ($delete_stmt->affected_rows > 0) {
            echo "<p style='color: green;'>Booking deleted successfully.</p>";
        } else {
            echo "<p style='color: red;'>Booking not found.</p>";
        }
    } else {
        echo "<p style='color: red;'>Error deleting booking: " . htmlspecialchars($conn->error) . "</p>";
    }

    $delete_stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Bookings</title>
    <style>
        .bookings-list {
            padding: 40px;
            max-width: 1000px;
            margin-top: 5%;
            margin-left: 10%;
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
        /* Desktop view */
        @media screen and (min-width: 1025px) {
            .bookings-list {
                margin-left: 10%;
                margin-right: 10%;
                padding: 40px;
            }

            .room-image {
                width: 120px;
                height: 120px;
            }

            .booking-card {
                padding: 30px;
            }
        }

        /* Tablet view */
        @media screen and (max-width: 1024px) and (min-width: 768px) {
            .bookings-list {
                margin-left: 5%;
                margin-right: 5%;
                padding: 20px;
            }

            .room-image {
                width: 100px;
                height: 100px;
            }

            .booking-card {
                padding: 20px;
            }
        }

        /* Mobile view */
        @media screen and (max-width: 767px) {
            .bookings-list {
                margin-left: 2%;
                margin-right: 2%;
                padding: 15px;
            }

            .room-image {
                width: 80px;
                height: 80px;
            }

            .booking-card {
                padding: 15px;
            }

            .room-info {
                flex-direction: column;
                align-items: center;
            }

            .booking-actions {
                text-align: center;
            }

            .booking-actions a {
                display: block;
                margin-top: 5px;
                text-align: center;
            }
        }
    </style>
</head>
<body>

<section class="bookings-list">
    <h2>All Bookings</h2>

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

                <p><strong>User Name:</strong> <?php echo htmlspecialchars($booking['user_name']); ?></p>
                <p><strong>User Email:</strong> <?php echo htmlspecialchars($booking['user_email']); ?></p>
                <p><strong>Booking Date:</strong> 
                    <?php 
                    // Format the booking date
                    $bookingDate = new DateTime($booking['booking_date']); 
                    echo $bookingDate->format('d-m-Y'); 
                    ?>
                </p>

                <p><strong>Booking Time:</strong> 
                    <?php 
                    $rawBookingTime = $booking['booking_time']; 
                    if (preg_match('/^\d{1,2}$/', $rawBookingTime)) {
                        $rawBookingTime .= ":00"; 
                    }
                    if (preg_match('/^\d{1,2}:\d{2}(:\d{2})?$/', $rawBookingTime)) {
                        $bookingTime = new DateTime($rawBookingTime); 
                        echo $bookingTime->format('H:i'); 
                    } else {
                        echo htmlspecialchars($rawBookingTime) ?: 'Invalid time format';
                    }
                    ?>
                </p>

                <div class="booking-actions">
                    <a href="?delete=<?php echo $booking['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this booking?');">Delete</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No bookings found.</p>
    <?php endif; ?>
</section>

</body>
</html>

<?php 
// Close the database connection
$conn->close(); 
?>
