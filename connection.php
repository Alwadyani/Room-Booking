<?php
session_start();
$dsn = 'mysql:host=localhost;dbname=roombooking';
$username = 'root';
$password = '';

$db = new PDO($dsn,$username,$password);


?>