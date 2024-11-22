<?php
include 'connection.php';
if(!isset($_SESSION["id"])){
    header("Location: login.php");
}
else{
    $id = $_SESSION['id'];
    $sql = "SELECT * FROM user WHERE id = $id";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
</head>
<body>
    <h1>Welcome <?php echo $user['name'];?></h1>
    <a href="logout.php">Logout</a>
</body>
</html>