<?php
include 'connection.php';
if(isset($_SESSION['id'])){
    header("Location: index.php");
}
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $usernameemail = $_POST["usernameemail"];
    $password = $_POST["password"];
    $sql = "SELECT * FROM user WHERE email = '$usernameemail' OR username ='$usernameemail'";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();
    if($result->num_rows > 0 ){
        if ($password == $user['password']){
            $_SESSION["login"] = true;
            $_SESSION["id"] = $user["id"];
            header("Location: index.php");
        }
        else{
            //it is password not match but we wright this to keep scammars away
            echo "<script> alert('username or password not matched')</script>";
        }
    }
    else{
             echo "<script> alert('user not exist')</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Login Page</title>
</head>
<body>
    <div class="wrapper">
    <h2>Login</h2>
    <form action="" method="post">
      <div class="input-box">
        <input type="text" name="usernameemail" id="usernameemail" placeholder="Enter Username or Email" required>
      </div>
      <div class="input-box">
        <input type="password" name="password" id="password" placeholder="Enter Password" required>
      </div>
      <div class="input-box button">
        <input type="Submit" value="Login">
      </div>
      <div class="text">
        <h3>Not have an account?<a href="registration.php"> Register</a></h3>
      </div>
    </form>
  </div>
</body>
</html>