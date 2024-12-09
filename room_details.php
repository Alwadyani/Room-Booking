<?php
include 'connection.php'; 
include 'header.php'; 


if (isset($_GET['room']) && is_numeric($_GET['room'])) {
    $roomId = $_GET['room'];

    //room details from the database
    $sql = "SELECT * FROM rooms WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $roomId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $room = $result->fetch_assoc();
    } else {
        echo "<p>Room not found.</p>";
        $room = null;
    }
    $stmt->close();
} else {
    echo "<p>Invalid room ID.</p>";
    $room = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Details</title>
    <style>
        /* Styling for room details page */
        .room-details {
            padding: 40px 20px;
            max-width: 800px;
            margin: auto;
            background: var(--colorback);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .room-details img {
            width: 100%;
            height: 300px;
            object-fit: cover;
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
    </style>
</head>
<body>

<section class="room-details">
    <?php if ($room): ?>
        <img src="images/<?php echo htmlspecialchars($room['image']); ?>" alt="<?php echo htmlspecialchars($room['name']); ?>">
        <h1><?php echo htmlspecialchars($room['name']); ?></h1>
        <p><strong>Capacity:</strong> <?php echo !empty($room['capacity']) ? htmlspecialchars($room['capacity']) : 'Not specified'; ?> people</p>
        <p><strong>equipment:</strong> <?php echo !empty($room['equipment']) ? htmlspecialchars($room['equipment']) : 'No equipment available.'; ?></p>
        <a href="rooms.php" class="back-btn">Back to Rooms</a>
        <a href="booking.php" class="back-btn">Booking room</a>
    <?php else: ?>
        <p>Room details could not be loaded.</p>
    <?php endif; ?>
</section>

</body>
</html>

<?php $conn->close(); ?>
