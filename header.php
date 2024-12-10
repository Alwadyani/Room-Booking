<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Header</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
    :root {
        --colorfirst: #8739F9; /*primary contents*/
        --colorSecond: #37B9F1; /*secondary contents*/
        --colorback: #F2F5F5; /*background for contents*/
        --colorShadow: #565360; /*main background*/
        --colorLabel: #908E9B; /*accessory use*/
        --colorDisabled: #E1DFE9; /*accessory use*/
    }

    body {
        margin: 0;
        font-family: Arial, sans-serif;
    }

    .header {
        background-color: var(--colorSecond);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 20px;
        box-shadow: 0 5px 10px var(--colorDisabled);
    }

    .header .logo img {
        height: 50px;
    }

    .navbar {
        display: flex;
        gap: 20px;
    }

    .navbar a {
        text-decoration: none;
        color: #243642;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .navbar a:hover {
        color: var(--colorfirst);
    }

    .icons {
        display: none;
        font-size: 20px;
        color: var(--colorLabel);
        cursor: pointer;
    }

    .icons:hover {
        color: var(--colorfirst);
    }
    

    /* Mobile view */
    @media (max-width: 768px) {
        .navbar {
            display: none;
            flex-direction: column;
            gap: 10px;
            position: absolute;
            top: 60px;
            right: 20px;
            background-color: var(--colorback);
            padding: 10px;
            box-shadow: 0 2px 5px var(--colorDisabled);
            border-radius: 5px;
        }

        .navbar.active {
            display: flex;
        }

        .icons {
            display: flex;
        }

        .header .logo img {
            height: 40px;
        }
    }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const menuBtn = document.getElementById("menu-btn");
            const navbar = document.querySelector(".navbar");

            menuBtn.addEventListener("click", () => {
                navbar.classList.toggle("active");
            });
        });
    </script>
</head>
<body>
    <!--Header start-->    
    <header class="header">
        <!-- UOB logo -->
        <a href="#" class="logo"><img src="images/UOB LOGO.png" alt="UOB logo"></a>
        
        <!-- Navbar -->
        <nav class="navbar">
            <a href="index.php">Home</a>
            <a href="#about-us">About Us</a>
            <a href="profile.php">Profile</a>
            <a href="rooms.php">Rooms</a>
            <a href="yourBooking.php">Booking</a>
            <a href="#contact-us">Contact Us</a>
            <a href="review.php">Reviews</a>
            <a href="logout.php">Logout</a>
        </nav>

        <!-- Icons -->
        <div class="icons">
            <i class="fas fa-bars" id="menu-btn"></i>
        </div>
    </header>
    <!--Header end-->
</body>
</html>
