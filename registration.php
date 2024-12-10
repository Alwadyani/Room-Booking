<?php
include 'connection.php';
if(isset($_SESSION['id'])){
    header("Location: index.php");
}
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = $_POST["name"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmpassword = $_POST["confirmpassword"];

    $email_pattern = '/^[0-9]{8,9}@stu\.uob\.edu\.bh$/';
    if (!preg_match($email_pattern, $email)) {
        echo "<script>alert('Invalid email format. Please use your university email ex: 12345678@stu.uob.edu.bh');</script>";
    }
    else {
      $duplicate = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username' OR email = '$email'");
        if(mysqli_num_rows($duplicate) > 0){
            echo "<script>alert('username or Email already exist');</script>";
        }
        else{
            if ($password !== $confirmpassword) {
                echo "<script>alert('Passwords do not match. Please try again.');</script>";
            }
            else{
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $query = "INSERT INTO user VALUES('','$name','$username','$email','$hashed_password','')";
                mysqli_query($conn,$query);
                echo "<script>alert('Register Successful');</script>";
                $_SESSION['id'] = true;
                header("Location: index.php");


            }
        }
      }
    

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/logRegStyles.css">
    <title>Registration Page</title>
    <style>
      
    </style>
</head>
<body>
    <div class="wrapper">
    <h2>Registration</h2>
    <form action="" method="post" >
      <div class="input-box">
        <input type="text" name="name" id="name" placeholder="Enter your Name" required>
      </div>
      <div class="input-box">
        <input type="text" name="username" id="username" placeholder="Enter username" required>
      </div>
      <div class="input-box">
        <input type="email" name="email" id="email" placeholder="Enter your Email" required>
      </div>
      <div class="input-box">
        <input type="password" name="password" id="password" placeholder="Create a password" required>
      </div>
      <div class="input-box">
        <input type="password" name="confirmpassword" id="confirmpassword" placeholder="Confirm password" required>
      </div>
      <div class="input-box button">
        <input type="Submit" value="Register">
      </div>
      <div class="text">
        <h3>Already have an account?<a href="login.php"> Login</a></h3>
      </div>
    </form>
  </div>
</body>
</html>