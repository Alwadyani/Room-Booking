<?php
include 'connection.php';


if(!isset($_SESSION["id"])){
    header("Location: login.php");
} else {
    $id = $_SESSION['id'];
    $sql = "SELECT * FROM user WHERE id = $id";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();}

// profile update
$successMessage = '';
if (isset($_POST['update_profile'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);

    // Update name and email in the database
    $sql = "UPDATE user SET name='$name', email='$email' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        $successMessage = "Profile updated successfully!";
        // Reload the user data
        $sql = "SELECT * FROM user WHERE id = $id";
        $result = $conn->query($sql);
        $user = $result->fetch_assoc();
    } else {die("Error updating profile: " . $conn->error);}

    // Photo upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['size'] > 0) {
        $target_dir = "images/ppicture/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (getimagesize($_FILES["profile_picture"]["tmp_name"])) {
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                $sql = "UPDATE user SET profile_picture='$target_file' WHERE id=$id";
                if ($conn->query($sql) === TRUE) {
                    $successMessage .= " Profile picture updated successfully!";
                } else {
                    die("Error updating profile picture: " . $conn->error);
                }
            } else {
                die("Error uploading file.");
            }
        } else {
            die("Uploaded file is not a valid image.");}
    }header("Refresh:2");} 
    

    // photo upload if a new one is selected
    if(isset($_FILES['profile_picture']) && $_FILES['profile_picture']['size'] > 0) {
        $target_dir = "images/ppicture/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a valid image
        if(getimagesize($_FILES["profile_picture"]["tmp_name"])) {
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                $sql = "UPDATE user SET profile_picture='$target_file' WHERE id=$id";
                if($conn->query($sql) === TRUE) {
                    $successMessage = "Profile picture updated successfully!";
                } else {
                    echo "Error updating profile picture: " . $conn->error;
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "File is not an image.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 40px;
            margin-top: 2%;
            background-color: #fff;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
        }
        .left-section { 
            width: 45%;
            text-align: center;
        }
        .left-section img {
            width: 150px; 
            height: 150px; 
            margin-top: 20%;
            border-radius: 50%; 
            border: 3px solid #ddd;
        }
        .left-section .profile-info {
            margin-top: 20px;
        }
        .left-section .profile-info p {
            font-size: 18px;
            color: #333;
        }
        .right-section {
            width: 45%;
            padding-left: 20px;
        }
        .right-section h2 {
            color: #333;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            font-size: 16px;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .update-btn {
            display: inline-block;
            background-color: #8739F9;
            color: white;
            padding: 15px 30px;
            font-size: 18px;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            width: 100%;
            cursor: pointer;
            margin-top: 20px;
            border: none;
        }

        .update-btn:hover {
            background-color: #37B9F1;
        }

        .success-message {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #37B9F1;
            color: white;
            padding: 20px;
            border-radius: 5px;
            font-size: 18px;
            display: none;
            z-index: 1000;
        }

        @media (max-width: 1024px) { 
    .container {
        flex-direction: column;
        align-items: center;
    }
    .left-section,
    .right-section {
        width: 90%;
        text-align: center;
    }
    .left-section img {
        margin-top: 10%;
    }
    .right-section {
        padding-left: 0;
    }
}

@media (max-width: 768px) { 
    .container {
        width: 100%;
        padding: 20px;
    }
    .left-section img {
        width: 120px;
        height: 120px;
    }
    .form-group input {
        font-size: 14px;
        padding: 8px;
    }
    .update-btn {
        font-size: 16px;
        padding: 10px 20px;
    }
    h2 {
        font-size: 20px;
    }
}

@media (max-width: 480px) { 
    .left-section img {
        width: 100px;
        height: 100px;
    }
    .form-group label {
        font-size: 14px;
    }
    .form-group input {
        font-size: 12px;
    }
    .update-btn {
        font-size: 14px;
    }
}

    </style>
</head>
<body>

<?php include 'header.php'; ?>  <!-- Include header here -->

<!-- Success message that appears when profile is updated -->
<?php if ($successMessage): ?>
    <div class="success-message" id="successMessage">
        <?php echo $successMessage; ?>
        
    </div>
<?php endif; ?>

<div class="container">
    <div class="left-section">
        <?php
            // Display user photo if available, otherwise show a default photo
            $profile_picture = isset($user['profile_picture']) && $user['profile_picture'] ? $user['profile_picture'] : 'images/ppicture/default_profile.jpg';
        ?>
        <img src="<?php echo $profile_picture; ?>" alt="Profile Picture">
        <div class="profile-info">
            <p><?php echo $user['name']; ?></p>
            <p><?php echo $user['email']; ?></p>
        </div>
    </div>

    <div class="right-section">
        <h2>Update Profile Information</h2>
        <form action="profile.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name: </label>
                <input type="text" name="name" id="name" value="<?php echo $user['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email: </label>
                <input type="email" name="email" id="email" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="profile_picture">Upload New Profile Picture: </label>
                <input type="file" name="profile_picture" id="profile_picture" accept="image/*">
            </div>
            <button type="submit" name="update_profile" class="update-btn">Update Profile</button>
        </form>
    </div>
</div>

<script>
    // If there is a success message, show it for 3 seconds and hide after that
    <?php if ($successMessage): ?>
        document.getElementById('successMessage').style.display = 'block';
        setTimeout(function() {
            document.getElementById('successMessage').style.display = 'none';
        }, 3000);  // Hide after 3 seconds
    <?php endif; ?>
</script>

</body>
</html>
