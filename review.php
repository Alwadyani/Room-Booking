<?php
include 'connection.php';
include 'header.php';

//  new comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['comment'])) {
    $user_id = 1; // ((((somthing wrong here))))
    $comment = $conn->real_escape_string($_POST['comment']);

    $sql = "INSERT INTO reviews (room_id, user_id, comment) VALUES ('$user_id', '$comment')";
    if ($conn->query($sql) === TRUE) {
        echo "<p>Comment added successfully!</p>";
    } else {
        echo "Error: " . $conn->error;
    }
}

// all comments
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
        :root {
            --colorBack: #f4f4f9;
            --colorShadow: #333;
            --colorLabel: #555;
            --colorFirst: #007BFF;
            --colorSecond: #0056b3;
        }

        body {
            font-family: Arial, sans-serif;
            background: var(--colorBack);
            margin: 0;
            padding: 20px;
        }

        .reviews-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .reviews-container h1 {
            font-size: 2rem;
            color: var(--colorShadow);
            text-align: center;
            margin-bottom: 20px;
        }

        .comment-form {
            margin-bottom: 30px;
        }

        .comment-form textarea {
            width: 97%;
            height: 100px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            font-size: 1rem;
            resize: none;
        }

        .comment-form button {
            background: var(--colorFirst);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .comment-form button:hover {
            background: var(--colorSecond);
        }

        .comment-box {
            margin-top: 20px;
        }

        .comment {
            padding: 15px;
            margin-bottom: 10px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .comment strong {
            color: var(--colorShadow);
            font-size: 1.1rem;
        }

        .comment small {
            color: var(--colorLabel);
            font-size: 0.9rem;
            display: block;
            margin-bottom: 5px;
        }

        .comment p {
            color: #333;
            font-size: 1rem;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="reviews-container">
        <h1>Room Reviews</h1>
        <form method="POST" class="comment-form">
            <textarea name="comment" placeholder="Write your comment here..." required></textarea><br>
            <button type="submit">Submit Comment</button>
        </form>

        <h2>Previous Comments</h2>
        <div class="comment-box">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="comment">
                        <strong><?php echo htmlspecialchars($row['username']); ?></strong>
                        <small><?php echo htmlspecialchars($row['created_at']); ?></small>
                        <p><?php echo htmlspecialchars($row['comment']); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No comments yet. Be the first to comment!</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>

