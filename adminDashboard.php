<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        :root {
            --colorfirst: #8739F9; 
            --colorSecond: #37B9F1; 
            --colorback: #F2F5F5;
            --colorShadow: #565360; 
            --colorLabel: #908E9B; 
            --colorDisabled: #E1DFE9; 
        }

        body {
            background-color: var(--colorback);
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .header {
            background: var(--colorShadow);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            box-shadow: 0 5px 10px var(--colorDisabled);
            background-color: #37B9F1;
        }

        .header .logo img {
            height: 50px;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin: 30px auto;
            width: 80%;
        }

        .box {
            width: 30%;
            padding: 20px;
            background-color: var(--colorSecond);
            color: white;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        .box:hover {
            background-color: var(--colorfirst);
            transform: translateY(-5px);
        }

        .logout {
            margin-top: 250px;
            color: var(--colorShadow);
            text-decoration: none;
            font-weight: bold;
            padding: 10px 50px;
            border: 2px solid var(--colorShadow);
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .logout:hover {
            background-color: var(--colorShadow);
            color: white;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <a href="#" class="logo"><img src="images/UOB LOGO.png" alt="UOB Logo"></a>
    </header>

    <div class="container">
        <h1>Welcome to Admin IT College Room Booking Dashboard</h1>
    <!-- Dashboard Boxes -->
    <div class="container">
        <a href="admin.php" class="box">Admin Dashboard</a>
        <a href="roomManagment.php" class="box">Room Management</a>
        <a href="Schedule.php" class="box">Room Schedule</a>
        <a href="upcoming_bookings.php" class="box">Upcoming Bookings</a>
        <a href="past_bookings.php" class="box">Previous Bookings</a>
        <a href="Analysis.php" class="box">Analysis</a>
    </div>

    <!-- Logout -->
    <a href="logout.php" class="logout">Logout</a>
</body>
</html>
