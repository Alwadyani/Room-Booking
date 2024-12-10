<?php
// Include required files
include 'connection.php';
include 'header.php';

// Check if user is logged in
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Bookings</title>
    <style>
    </style>
</head>
<body>


</body>
</html>

