<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>header</title>
    <style>
    :root{
    --colorfirst:#8739F9; /*primary contents*/
    --colorSecond: #37B9F1; /*secondary contents*/
    --colorback: #F2F5F5; /*backgrouand for contents*/
    --colorShadow: #565360; /*main background*/
    --colorLabel: #908E9B; /*accessory use*/
    --colorDisabled: #E1DFE9; /*accessory use*/
    --lengths1:0.25rem; /* small 1*/
    --lengths2:0.375rem; /*small 2*/
    --lengths3:0.5rem; /*small 3*/
    --lengthm1:1rem; /*medium 1*/
    --lengthm2:1.25rem; /*medium 2*/
    --lengthm3:1.5rem; /*medium 3*/
    --lengthl1:2rem; /*large 1*/
    --lengthl2:3rem; /*large 2*/
    --lengthl3:4rem; /*large 3*/
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
  display: flex;
  gap: 15px;
  font-size: 20px;
  color: var(--colorLabel);
  cursor: pointer;
}

.icons div:hover {
  color: var(--colorfirst);
}


</style>
</head>
<body>
    
    <!--Header start-->    
    <header class="header">
        
        <!-- this is for  uob logo-->
        <a href="#" class="logo"><img src="images/UOB LOGO.png" alt="uob logo"></a>
        <!-- this is for  uob logo end-->    
        
        <!--navbar-->
        <nav class="navbar">
            <a href="index.php">Home</a>
            <a href="#about-us">About us</a>
            <a href="profile.php">Profile</a>
            <a href="rooms.php">Rooms</a>
            <a href="yourBooking.php">Booking</a>
            <a href="#contact-us">Contact us</a>
            <a href="review.php">Reviews</a>
        </nav>
        <!--navbar end-->
        <div class="icons">
            <div class="fas fa-search" id="search-btn"></div>
            <div class="fas fa-bars" id="menu-btn"></div>
        </div>

    </header>
    <!--Header end-->

</body>
</html>