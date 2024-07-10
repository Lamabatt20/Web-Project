<?php
session_start();
// Include database configuration file
include 'dbconfig.in.php';

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    try {
        // Connect to the database
        $pdo = db_connect(); // Implement this function in dbconfig.in.php to establish PDO connection
        
        // Prepare and execute statement to fetch user details
        $stmt = $pdo->prepare("SELECT user_id, customer_id, user_type FROM users WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();

        // Check if user exists and set user_type
        if ($user) {
            $user_type = $user['user_type'];
        } else {
            // Redirect to login if user not found or session is invalid
            header("Location: login.html");
            exit;
        }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
} else {
    // Redirect to login if session variables are not set
    header("Location: login.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Auto Drive </title>
    <link rel="stylesheet" href="StyleHome.css">
</head>
<body>
    <header>
    <nav class="header-nav">
            <a href="Home.php"><img src="logo.jpg" alt="Course Image" width="300" height="100"></a>
            <a href="about.html"><span class="link-icon"><img src="icons8-about-us-24.png" alt="Course Image" width="40" height="40">About Us</span></a>
            <a href="cart.html"><span class="link-icon"><img src="icons8-cart-50.png" alt="Course Image" width="40" height="40">Cart</span></a>
            <a href="profile.php"><span class="link-icon"><img src="icons8-male-user-50.png" alt="Course Image" width="40" height="40">My Profile</span></a>
            <a href="Login.html"><span class="link-icon"><img src="icons8-add-administrator-50.png" alt="Course Image" width="40" height="40">Login/Register</span></a>
            <a href="logout.php"><span class="link-icon"><img src="icons8-logout-24.png" alt="Course Image" width="40" height="40">Logout</span></a>
        </nav>
    </header>
    <div class="container">
        <nav class="side-nav">
            <a href="Search.php"><span class="link-icon"><img src="icons8-search-24.png" alt="Search Car" width="40" height="40">Search a Car</span></a>
            <?php if ($user_type === 'manager'): ?>
                <a href="AddCar.php"><span class="link-icon"><img src="icons8-add-car-48.png" alt="Add Car" width="40" height="40">Add a Car</span></a>
                <a href="manager_return.php"><span class="link-icon"><img src="icons8-return-64.png" alt="Return Car" width="40" height="40">Return a Car</span></a>
                <a href="cars_inquire.php"><span class="link-icon"><img src="icons8-search-24.png" alt="Search Cars" width="40" height="40">Inquire Cars</span></a>
                <a href="AddLocation.php"><span class="link-icon"><img src="icons8-location-24 (1).png" alt="Add Location" width="40" height="40">Add a Location</span></a>
            <?php else: ?>
                <a href="RentCar.php"><span class="link-icon"><img src="icons8-car-rental-24.png" alt="Rent Car" width="40" height="40">Rent a Car</span></a>
                <a href="CReturnCar.php"><span class="link-icon"><img src="icons8-return-64.png" alt="Return Car" width="40" height="40">Return a Car</span></a>
                <a href="view_rented_cars.php"><span class="link-icon"><img src="icons8-view-24.png" alt="View Rented Cars" width="40" height="40">View Rented Cars</span></a>
            <?php endif; ?>
        </nav>
        <main>
            <div class="main-working-area">
                <div class="content">
                    <h2>Welcome to <span>Auto Drive</span></h2>
                    <h3>We Make It Quick and Simple</h3>
                </div>
            </div>
            <div class="offers-section">
                <h2>Car Offers</h2>
                <div class="offers-container">
                    <div class="offer-box">
                        <img src="car1.jpg" alt="Car 1" class="offer-image">
                        <h3>Jaguar F-Type</h3>
                        <p>$100</p>
                    </div>
                    <div class="offer-box">
                        <img src="car2.jpeg" alt="Car 2" class="offer-image">
                        <h3>BMW 320i</h3>
                        <p>$150</p>
                    </div>
                    <div class="offer-box">
                        <img src="car3.jpeg" alt="Car 3" class="offer-image">
                        <h3>VOLKSWAGEN GOLF</h3>
                        <p>$60</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <footer>
        <p>&copy; 2024 AUTO DRIVE. All rights reserved.</p>
        <p>Address: 1234 AUTO DRIVE St, Ramallah, Palestine</p>
        <p>Email: support@AUTODRIVE.com | Phone: +1234567890</p>
        <a href="contact.html">Contact Us</a>
    </footer>
</body>
</html>
