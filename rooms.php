<?php
include 'connection.php';
include 'header.php'; // Include the header for navigation

// Fetch rooms from the database
$sql = "SELECT * FROM rooms"; 
$result = $conn->query($sql);


if ($result === false){
    echo"<p>Error retrieving rooms: ".$conn->error."</p>";
    $result =null;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms Presentation</title>
    <style>
        /* Main styling for the page */
        .rooms-section {
            padding: 40px 20px;
            background: var(--colorback);
        }

        .rooms-section h1 {
            text-align: center;
            color: var(--colorShadow);
            margin-bottom: 30px;
            font-size: 2rem;
        }

        .rooms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .room-card {
            background: #fff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .room-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .room-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .room-content {
            padding: 20px;
        }

        .room-content h2 {
            color: var(--colorShadow);
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .room-content p {
            color: var(--colorLabel);
            font-size: 0.95rem;
            margin-bottom: 15px;
            line-height: 1.6;
        }

        .room-content .details {
            font-size: 0.9rem;
            color: var(--colorSecond);
            font-weight: bold;
        }

        .room-content .book-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background: var(--colorfirst);
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .room-content .book-btn:hover {
            background: var(--colorSecond);
        }
    </style>
</head>
<body>

<section class="rooms-section">
    <h1>Our <span>Rooms</span></h1>
    <div class="rooms-grid">
        <?php 
        // Check if rooms are available
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="room-card">';
                if (!empty($row['image'])) {
                    echo '<img src="images/' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                }
                echo '<div class="room-content">';
                echo '<h2>' . htmlspecialchars($row['name']) . '</h2>';
                echo '<p>Capacity: ' . (!empty($row['capacity']) ? htmlspecialchars($row['capacity']) : 'Not specified') . ' people</p>';
                echo '<a href="roomDetails.php?room=' . htmlspecialchars($row['id']) . '" class="book-btn">View Details</a>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo "<p>No rooms available.</p>";
        }        
        ?>
    </div>
</section>

</body>
</html>
<?php $conn->close();?>

