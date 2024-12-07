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
    <style>
        h1 {
            text-align: center;
            color: var(--colorShadow);
            margin-top: -200px;
            
        }
        h1 span {
            color: var(--colorfirst);
        }

        /* Button Styles */
        .btn {
            display: inline-block;
            background-color: var(--colorfirst);
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            text-align: center;
            border-radius: 5px;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: var(--colorSecond);
        }

        /* Centering the content */
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;  
            flex-direction: column; 
        }

        a {
            text-decoration: none;
            color: var(--colorfirst);
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        #about-us {
            background-color: #F2F5F5; 
            padding: 20px 0; 
            text-align: center;
            margin-top: -6%;
        }

        #about-us .section-container {
            max-width: 800px; 
            margin: 0 auto; 
            padding: 20px;
            background-color: #ffffff; 
            border-radius: 10px; 
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); 
        }

        #about-us h2 {
            font-size: 2rem; 
            color: var(--colorfirst); 
            margin-bottom: 20px;
        }

        #about-us h2 span {
            color: var(--colorSecond); /* Secondary color for the span */
        }

        #about-us p {
            font-size: 20px; 
            color: #555; 
            line-height: 1.6; 
            margin-bottom: 20px;
        }

        #about-us .btn {
            display: inline-block;
            background-color: var(--colorfirst); 
            color: white; 
            padding: 10px 30px; 
            border-radius: 5px; 
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease; 
        }

        #about-us .btn:hover {
            background-color: var(--colorSecond); 
        }

        /* Contact Us Section Styling */
        #contact-us {
            background-color: #F2F5F5; /* Light background */
            padding: 50px 0; /* Space above and below the section */
            text-align: center;
        }

        #contact-us .section-container {
            max-width: 800px; /* Set max-width for the content */
            margin: 0 auto; /* Center the content */
            padding: 20px;
            background-color: #ffffff; /* White background for the form */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); /* Add shadow */
        }

        #contact-us h2 {
            font-size: 2rem; /* Set font size for the heading */
            color: var(--colorfirst); /* Primary color for heading */
            margin-bottom: 20px;
        }

        #contact-us h2 span {
            color: var(--colorSecond); /* Secondary color for span */
        }

        #contact-us p {
            font-size: 1.1rem; /* Font size for the paragraph */
            color: #555; /* Gray text color */
            margin-bottom: 20px; /* Space below the paragraph */
        }

        #contact-us .form-group {
            margin-bottom: 20px; /* Space below each form group */
            text-align: left; /* Align form labels and text areas to the left */
        }

        #contact-us .form-group label {
            font-size: 1rem; /* Font size for labels */
            color: #333; /* Darker text color for labels */
            display: block; /* Block display for proper spacing */
            margin-bottom: 5px;
        }

        #contact-us .form-group textarea {
            width: 100%; /* Full width for the textarea */
            padding: 10px;
            font-size: 1rem; /* Font size for input text */
            border: 1px solid var(--colorDisabled); /* Border color */
            border-radius: 5px; /* Rounded corners */
            resize: none; /* Disable resize option */
        }

        #contact-us .form-group textarea:focus {
            outline: none; /* Remove focus outline */
            border-color: var(--colorSecond); /* Highlight border on focus */
            box-shadow: 0 0 5px rgba(55, 185, 241, 0.5); /* Subtle focus effect */
        }

        #contact-us .submit-button {
            display: inline-block;
            background-color: var(--colorfirst); /* Button color */
            color: white; /* Text color */
            padding: 10px 30px; /* Padding for button */
            font-weight: bold; /* Bold text */
            border: none; /* Remove border */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor */
            transition: background-color 0.3s ease; /* Smooth hover transition */
        }

        #contact-us .submit-button:hover {
            background-color: var(--colorSecond); /* Change color on hover */
        }

/* CSS of footer */
.footer {
    background: var(--colorSecond); /* Matches the header background */
}

.footer .box-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); /* Adjust grid for responsive layout */
    gap: 20px; /* Space between boxes */
}

.footer .box-container .box h3 {
    font-size: 25px;
    color: #243642 ;
    padding: 15px 0;
}

.footer .box-container .box p {
    font-size: 15px;
    color: var(--colorLabel); 
    padding: 10px 0;
    line-height: 1.8; 
}

.footer .box-container .box a {
    display: block;
    font-size: 15px;
    color: var(--colorDisabled); /* Subtle gray for links */
    padding: 5px 0;
    transition: color 0.3s ease; /* Smooth hover effect */
}

.footer .box-container .box a:hover {
    color: var(--colorfirst); /* Highlighted color on hover */
}

.footer .box-container .box a i {
    padding-right: 10px;
    color: var(--colorSecond); /* Icon-specific color */
}

.footer .end {
    margin-top: 20px;
    padding: 15px;
    border-top: 1px solid rgba(255, 255, 255, 0.2); /* Subtle border for separation */
    text-align: center;
    color: var(--colorLabel); /* Light gray for text */
    font-size: 16px;
}

.footer .end span {
    color: var(--colorfirst); /* Primary color for emphasis */
    font-weight: bold; /* Highlighted text */
}

    </style>
</head>
<body>
    <?php include 'header.php'; ?>  <!-- Include header here -->
    <div class="container">
        <h1>Welcome <span><?php echo $user['name']; ?> </span>to IT College Room Booking System</h1>
        <a href="php/rooms.php" class="btn">Start Booking Rooms</a>
    </div>

    <!-- About Us Section -->
    <section id="about-us" class="about-us-section">
    <div class="section-container">
        <h2>About <span>Us</span></h2>
        <p>Welcome to IT College's Room Booking System! Our platform is designed to streamline 
            the process of reserving rooms for students, faculty, and staff. Whether you need 
            a space for a class, meeting, or study session, our system provides an easy to use
            interface to check availability, book rooms, and manage schedules. We aim to create
            a seamless experience for everyone involved in the academic environment, ensuring
            that all your room reservation needs are met efficiently.</p>
            <a href="#" class="btn">Read More</a>
        
    </div>
</section>

<!-- Contact Us Section -->
<section id="contact-us" class="contact-us-section">
    <div class="section-container">
        <h2><span>Contact </span>Us</h2>
        <p>If you have any questions or need assistance, please don't hesitate to reach out to us. We are here to help!</p>
        <form action="/submit-message" method="POST">
            <div class="form-group">
                <label for="message">Your Message</label>
                <textarea id="message" name="message" rows="6" required placeholder="Write your message here..."></textarea>
            </div>

            <button type="submit" class="submit-button">Send Message</button>
        </form>
        </form>
    </div>
</section>

 <!-- footer section starts -->
 <section class="footer">
        <div class="box-container">

            <div class="box">
                <h3> Room Booking System </h3>
                <p>room booking system for the IT College, in corporating various
                    features to enhance user experience and administrative
                    capabilities..</p>
            </div>

            <div class="box">
                <h3>category</h3>
                <a href="#"> about us</a>
                <a href="#"> Special Offers</a>
                <a href="#"> Terms & Conditions</a>
                <a href="#"> privacy policy</a>
                <a href="#"> contact us</a>
                <a href="#"> FAQs</a>
            </div>

            <div class="box">
                <h3>important links</h3>
                <a href="#" target="_blank" class="fab fa-linkedin"></a>
                <a href="#" target="_blank" class="fab fa-instagram"></a>
                <a href="#">Call Us: +973 1663 3366 </a>
                <a href="#">Email Us: uob@itcollege.edu</a>
                
            </div>
        </div>
        <div class="end"> &copy;2024-2025 by <span> University of Bahrain</span></div>
    </section><!-- section ends -->


    <a href="logout.php">Logout</a>
</body>
</html>
