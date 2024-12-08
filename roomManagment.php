<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'roombooking'); // Update database connection as needed
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Room Addition
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $capacity = intval($_POST['capacity']);
    $image = $_FILES['image']['name'];

    $target_dir = "image/";
    $target_file = $target_dir . basename($images);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        $sql_add_room = "INSERT INTO rooms (name, description, capacity, image) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_add_room);
        $stmt->bind_param("ssis", $name, $description, $capacity, $image);
        $stmt->execute();
        $stmt->close();
        $_SESSION['success'] = "Room added successfully.";
    } else {
        $_SESSION['error'] = "Failed to upload image.";
    }
}

$sql_fetch_rooms = "SELECT * FROM rooms";
$rooms = $conn->query($sql_fetch_rooms);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Management</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        :root {
            --colorfirst: #8739F9;
            --colorSecond: #37B9F1;
            --colorback: #F2F5F5;
            --colorShadow: #565360;
            --colorLabel: #908E9B;
            --colorDisabled: #E1DFE9;
        }

        body {
            background-color: var(--colorback);
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .container h3 {
            color: var(--colorShadow);
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            text-align: left;
            padding: 10px;
            border: 1px solid var(--colorDisabled);
        }

        table th {
            background: var(--colorSecond);
            color: white;
        }

        .btn {
            display: inline-block;
            background-color: var(--colorfirst);
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            text-align: center;
            border-radius: 5px;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: var(--colorSecond);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: var(--colorShadow);
        }

        .form-group input, .form-group textarea {
            width: 100%;
            padding: 7px;
            border: 1px solid var(--colorDisabled);
            border-radius: 5px;
        }

        .alert {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .alert.success {
            background: #d4edda;
            color: #155724;
        }

        .alert.error {
            background: #f8d7da;
            color: #721c24;
        }

        .BL {
            margin-top: 200px;
            color: var(--colorShadow);
            text-decoration: none;

            font-weight: bold;
            padding: 10px 50px;
            border: 2px solid var(--colorShadow);
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .BL:hover {
            background-color: var(--colorShadow);
            color: white;
        }
    </style>
</head>
<body>

<!-- Back to Admin Dashboard -->
<a href="admin.php" class="BL">Back to Admin Dashboard</a>
<a href="logout.php" class="BL">Logout</a>

<div class="container">
    <h3>Room Management</h3>

    <!-- Display Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <!-- Add New Room -->
    <form action="room_management.php" method="POST" enctype="multipart/form-data">
        <h4>Add New Room</h4>
        <div class="form-group">
            <label for="name">Room Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label for="capacity">Capacity:</label>
            <input type="number" id="capacity" name="capacity" required>
        </div>
        <div class="form-group">
            <label for="image">Room Image:</label>
            <input type="file" id="image" name="image" accept="images/*" required>
        </div>
        <button type="submit" class="btn add">Add Room</button>
    </form>

    <!-- All Rooms -->
    <h4>Available Rooms</h4>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Room Name</th>
                <th>Description</th>
                <th>Capacity</th>
                <th>Image</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($rooms->num_rows > 0): ?>
                <?php while ($room = $rooms->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $room['id']; ?></td>
                        <td><?php echo htmlspecialchars($room['name']); ?></td>
                        <td><?php echo htmlspecialchars($room['description']); ?></td>
                        <td><?php echo htmlspecialchars($room['capacity']); ?></td>
                        <td><img src="images/<?php echo htmlspecialchars($room['image']); ?>" alt="Room Image" width="100"></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No rooms available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
<?php $conn->close(); ?>
