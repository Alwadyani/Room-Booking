<?php
session_start();
$dsn = 'mysql:host=localhost;dbname=roombooking';
$username = 'root';
$password = '';

$conn = new PDO($dsn,$username,$password);


?>