<?php
// Database Connection
$conn = new mysqli('localhost', 'root', '', 'roombooking'); 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);}

// Action and ID Variables
$action = $_GET['action'] ?? 'list'; 
$id = $_GET['id'] ??  null; 
$message = '';

// Handle Add/Edit Room Actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $capacity = intval($_POST['capacity']);
    $equipment = $_POST['equipment'];
    $image = $_FILES['image']['name'] ?? null;

    // Image Upload
    if ($image) {
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $upload_dir = 'images/';
        move_uploaded_file($image_tmp_name, $upload_dir . $image);
    } else {
        $image = $_POST['existing_image'] ?? null;
    }

    
    if ($action == 'add') {
        $stmt = $conn->prepare("INSERT INTO rooms (name, capacity, equipment, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siss", $name, $capacity, $equipment, $image);
        $stmt->execute();
        $message = "Room added successfully!";
        $stmt->close();
    }
    
    elseif ($action == 'edit' && $id) {
        $stmt = $conn->prepare("UPDATE rooms SET name = ?, capacity = ?, equipment = ?, image = ? WHERE id = ?");
        $stmt->bind_param("sissi", $name, $capacity, $equipment, $image, $id);
        $stmt->execute();
        $message = "Room updated successfully!";
        $stmt->close();
    }

    // Redirect to Room List
    header("Location: roomManagment.php");
    exit;
}

// Handle Delete Action
if ($action == 'delete' && $id) {
    $conn->query("DELETE FROM rooms WHERE id = $id");
    $message = "Room deleted successfully!";
    header("Location: roomManagment.php");
    exit;
}

// Fetch All Rooms
$sql_fetch_rooms = "SELECT * FROM rooms";
$rooms = $conn->query($sql_fetch_rooms);

// Fetch Room Details for Editing
$current_room = null;
if ($action == 'edit' && $id) {
    $current_room = $conn->query("SELECT * FROM rooms WHERE id = $id")->fetch_assoc();
}
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
        --colorfirst: #8739F9; /*primary contents*/
        --colorSecond: #37B9F1; /*secondary contents*/
        --colorback: #F2F5F5; /*background for contents*/
        --colorShadow: #565360; /*main background*/
        --colorLabel: #908E9B; /*accessory use*/
        --colorDisabled: #E1DFE9; /*accessory use*/
        --lengths1: 0.25rem; /* small 1*/
        --lengths2: 0.375rem; /*small 2*/
        --lengths3: 0.5rem; /*small 3*/
        --lengthm1: 1rem; /*medium 1*/
        --lengthm2: 1.25rem; /*medium 2*/
        --lengthm3: 1.5rem; /*medium 3*/
        --lengthl1: 2rem; /*large 1*/
        --lengthl2: 3rem; /*large 2*/
        --lengthl3: 4rem; /*large 3*/
    }

    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: var(--colorback);
        color: var(--colorShadow);
    }

    .container {
        max-width: 900px;
        margin: 20px auto;
        background: #fff;
        border-radius: var(--lengths2);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        padding: var(--lengthm1);
    }

    h1 {
        text-align: center;
        color: var(--colorfirst);
        margin-bottom: var(--lengthm2);
        font-size: 2.5rem;
    }

    .message {
        text-align: center;
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        padding: var(--lengths2);
        border-radius: var(--lengths1);
        margin-bottom: var(--lengthm1);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: var(--lengthm2);
    }

    table thead {
        background-color: var(--colorfirst);
        color: white;
    }

    table th, table td {
        text-align: left;
        padding: var(--lengths2);
        border: 1px solid #ddd;
    }

    table tr:nth-child(even) {
        background-color: var(--colorback);
    }

    table tr:hover {
        background-color: #e9ecef;
    }

    table img {
        border-radius: var(--lengths1);
    }

    .btn {
        display: inline-block;
        background: var(--colorfirst);
        color: white;
        padding: var(--lengths2) var(--lengthm1);
        text-decoration: none;
        border-radius: var(--lengths1);
        font-size: 1rem;
        margin-bottom: var(--lengthm2);
        text-align: center;
    }

    .btn:hover {
        background-color: var(--colorSecond);
    }

    form {
        margin-top: 5%;
        display: flex;
        flex-direction: column;
        gap: var(--lengths2);
    }

    form label {
        font-weight: bold;
        margin-bottom: var(--lengths1);
        color: var(--colorfirst);
    }

    form input, form textarea, form button {
        padding: var(--lengths2);
        font-size: 1rem;
        border: 1px solid #ddd;
        border-radius: var(--lengths1);
        outline: none;
    }

    form textarea {
        resize: none;
        height: 100px;
    }

    form button {
        background-color: var(--colorfirst);
        color: white;
        border: none;
        cursor: pointer;
        font-size: 1rem;
    }

    form button:hover {
        background-color: var(--colorSecond);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        table img {
            width: 80px;
        }

        table th, table td {
            font-size: 0.9rem;
        }

        h1 {
            font-size: 2rem;
        }

        form button {
            font-size: 0.9rem;
        }

        .btn {
            font-size: 0.9rem;
        }
    }

    .LB{
        margin-top: 250px;
      
        color: var(--colorShadow);
        text-decoration: none;
        font-weight: bold;
        padding: var(--lengths2) var(--lengthl1);
        border: 2px solid var(--colorShadow);
        border-radius: var(--lengths1);
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .LB:hover {
        background-color: var(--colorShadow);
        color: white;
    }
</style>
</head>
<body>

<div class="container">
    <h2>Room Management</h2>
    <a href="adminDashboard.php" class="LB">Back to Dashboard</a>
    <a href="logout.php" class="LB">Logout</a>
    <?php if ($message): ?>
        <p class="message"><?= htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <?php if ($action == 'list'): ?>
        <h4>Available Rooms</h4>
        <table>
            <thead>
                <tr>
                    <th>ID NO.</th>
                    <th>Room Name</th>
                    <th>The Capacity</th>
                    <th>The Equipment</th>
                    <th>Image</th>
                    <th>Update or Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($room = $rooms->fetch_assoc()): ?>
                    <tr>
                        <td><?= $room['id']; ?></td>
                        <td><?= htmlspecialchars($room['name']); ?></td>
                        <td><?= $room['capacity']; ?></td>
                        <td><?= htmlspecialchars($room['equipment']); ?></td>
                        <td><img src="images/<?= $room['image']; ?>" alt="Room Image" width="100"></td>
                        <td>
                            <a href="roomManagment.php?action=edit&id=<?= $room['id']; ?>">Edit</a> | 
                            <a href="roomManagment.php?action=delete&id=<?= $room['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="roomManagment.php?action=add" class="btn">Add Room</a>
    <?php endif; ?>

    <!-- Add/Edit Form -->
    <?php if ($action == 'add' || $action == 'edit'): ?>
            <form method="POST" enctype="multipart/form-data">
                <label>Room Name:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($current_room['name'] ?? '') ?>" required>

                <label>Capacity:</label>
                <input type="number" name="capacity" value="<?= $current_room['capacity'] ?? '' ?>" required>

                <label>Equipment:</label>
                <textarea name="equipment" required><?= htmlspecialchars($current_room['equipment'] ?? '') ?></textarea>


                <label>Image:</label>
                <input type="file" name="image" accept="images/*">
                <?php if ($action == 'edit' && $current_room): ?>
                    <input type="hidden" name="existing_image" value="<?= $current_room['image'] ?>">
                    <p>Current Image: <img src="../images/<?= $current_room['image'] ?>" alt="Current Image" style="width: 100px;"></p>
                <?php endif; ?>

                <button type="submit"><?= $action == 'add' ? 'Add Room' : 'Update Room' ?></button>
            </form>
            <?php endif; ?>
</div>

</body>
</html>
