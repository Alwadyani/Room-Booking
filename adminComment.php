<?php
include 'connection.php';


$success_message = '';
$error_message = '';

//admin can respond to a comment that user sends
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_id'], $_POST['response'])) {
    $comment_id = intval($_POST['comment_id']);
    $response = $conn->real_escape_string($_POST['response']);

    $sql = "UPDATE reviews SET response = '$response' WHERE id = $comment_id";

    if ($conn->query($sql) === TRUE) {
        $success_message = "Response added successfully!";
    } else {
        $error_message = "Error: " . $conn->error;
    }
}

$sql = "SELECT reviews.id, user.username, reviews.comment, reviews.created_at, reviews.response
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
    <title>Admin Comments</title>
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
            padding: 20px;
            background: #f9f9f9;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .message {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .success {
            background-color: #4CAF50;
            color: #fff;
        }
        .error {
            background-color: #f44336;
            color: #fff;
        }
        .comment {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #f5f5f5;
        }
        .comment strong {
            font-size: 1.1em;
        }
        textarea {
            width: 90%;
            height: 80px;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            overflow: auto;
            resize: none;
        }
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
    <a href="adminDashboard.php" class="LB">Back to Dashboard</a>
    <a href="logout.php" class="LB">Logout</a>
        <h2>Admin Comments Management</h2>

        <?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="comment">
            <strong>
                <?php if (!$row['response']): ?>
                    <span style="color: red; font-weight: bold;">New Notification</span> 
                <?php endif; ?>
                User: <?php echo htmlspecialchars($row['username']); ?>
            </strong><br>
            <strong>Comment:</strong> <?php echo htmlspecialchars($row['comment']); ?><br>
            <small>Posted on: <?php echo htmlspecialchars($row['created_at']); ?></small>

            <?php if ($row['response']): ?>
                <p><strong>Admin Response:</strong> <?php echo htmlspecialchars($row['response']); ?></p>
            <?php else: ?>
                <form method="POST">
                    <textarea name="response" placeholder="Write your response here..." required></textarea>
                    <input type="hidden" name="comment_id" value="<?php echo $row['id']; ?>">
                    <button type="submit">Submit Response</button>
                </form>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No comments to display.</p>
<?php endif; ?>

    <script>
        // Hide success message after 2 seconds
        <?php if ($success_message): ?>
            setTimeout(function() {
                document.getElementById('successMessage').style.display = 'none';
            }, 2000); // 2 seconds
        <?php endif; ?>
    </script>
</body>
</html>
