<?php
include 'connection.php';
include 'header.php';

// Check user login
if (!isset($_SESSION['id'])) {
    echo "<p>Please log in to comment.</p>";
    exit;
}

$user_id = $_SESSION['id'];
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['comment'])) {
    $comment = $conn->real_escape_string($_POST['comment']);
    $sql = "INSERT INTO reviews (user_id, comment) VALUES ('$user_id', '$comment')";
    if ($conn->query($sql) === TRUE) {
        // Set success message in session
        $_SESSION['success_message'] = "Comment added successfully!";
        // Redirect to the same page
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}

// Retrieve success message from session if exists
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']); // Clear it after use
}

$sql = "SELECT user.username, reviews.comment, reviews.created_at
        FROM reviews
        JOIN user ON reviews.user_id = user.id
        ORDER BY reviews.created_at DESC";
$result = $conn->query($sql);

if (!$result) {
    die("Error retrieving comments: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Reviews</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f9f9f9;
        }
        .container {
            max-width: 600px;
            margin-top: 5%;
            margin-left: 23%;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .success-message {
            display: none;
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        textarea {
        width: 96%;
        height: 80px;
        margin-bottom: 10px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        overflow: auto;
        resize: none;   }

        button {
            padding: 10px 20px;
            background: #8739F9;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #37B9F1;
        }
        .comment {
            margin-top: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #f5f5f5;
        }
        .comment strong {
            font-size: 1.1em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Comments</h2>
        <?php if ($success_message): ?>
            <div id="successMessage" class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <textarea name="comment" placeholder="Write your comment..." required></textarea>
            <button type="submit">Submit Comment</button>
        </form>
        </div>
        <div class="container">
        <h2>Previous Comments</h2>
        <div>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="comment">
                        <strong><?php echo htmlspecialchars($row['username']); ?></strong>
                        <p><?php echo htmlspecialchars($row['comment']); ?></p>
                        <small><?php echo htmlspecialchars($row['created_at']); ?></small>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No comments yet</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const successMessage = document.getElementById("successMessage");
            if (successMessage) {
                successMessage.style.display = "block"; // Show the message
                setTimeout(() => {
                    successMessage.style.display = "none"; // Hide after 3 seconds
                }, 3000);
            }
        });
    </script>
</body>
</html>
