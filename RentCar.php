<?php
session_start();

// Check if customer is logged in, redirect to login page if not
if (!isset($_SESSION['customer_id'])) {
    header("Location: Login.html?redirect=RentCar.php");
    exit;
}

// Handle form submission for initial rent
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $car_id = $_POST['car_id'];
    $car_model = $_POST['car_model'];
    $car_description = $_POST['car_description'];
    $car_fuel_type = $_POST['car_fuel_type'];
    $pick_up_date = $_POST['pick_up_date'];
    $pick_up_time = $_POST['pick_up_time'];
    $return_date = $_POST['return_date'];
    $return_time = $_POST['return_time'];
    $return_location =$_POST['return_location'];
    $pick_up_location = $_POST['pick_up_location'];
    $total_rent_amount = $_POST['total_rent_amount'];
    $special_requirements = isset($_POST['special_requirements']) ? $_POST['special_requirements'] : [];
    
    // Store data in session
    $_SESSION['car_id'] = $car_id;
    $_SESSION['car_fuel_type']=$car_fuel_type;
    $_SESSION['car_model'] = $car_model;
    $_SESSION['car_description'] = $car_description;
    $_SESSION['return_location']=$return_location;
    $_SESSION['pick_up_date'] = $pick_up_date;
    $_SESSION['pick_up_time'] = $pick_up_time;
    $_SESSION['return_date'] = $return_date;
    $_SESSION['return_time'] = $return_time;
    $_SESSION['pick_up_location'] = $pick_up_location;
    $_SESSION['total_rent_amount'] = $total_rent_amount;
    $_SESSION['special_requirements'] = $special_requirements;

    // Redirect to step 2 (invoice)
    header("Location: RentInvoice.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rent Car</title>
    <link rel="stylesheet" href="StyleCss.css">
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
<main>
    <h1>Rent Car</h1>
    <p>Please complete the form to rent the car.</p>
    <form action="RentCar.php" method="POST">
        <label for="car_id">Car ID:</label>
        <input type="text" id="car_id" name="car_id" value="<?php echo $_SESSION['car_id']; ?>"><br>

        <label for="car_model">Car Model:</label>
        <input type="text" id="car_model" name="car_model" value="<?php echo $_SESSION['car_model']; ?>"><br>

        <label for="car_description">Car Description:</label>
        <input type="text" id="car_description" name="car_description" value="<?php echo $_SESSION['car_description']; ?>"><br>
        
        <input type="hidden" id="car_fuel_type" name="car_fuel_type" value="<?php echo $_SESSION['car_fuel_type']; ?>"><br>
        <label for="pick_up_date">Pick Up Date:</label>
        <input type="date" id="pick_up_date" name="pick_up_date" value="<?php echo $_SESSION['pick_up_date']; ?>"><br>

        <label for="pick_up_time">Pick Up Time:</label>
        <input type="time" id="pick_up_time" name="pick_up_time" value="<?php echo $_SESSION['pick_up_time']; ?>"><br>

        <label for="return_date">Return Date:</label>
        <input type="date" id="return_date" name="return_date" value="<?php echo $_SESSION['return_date']; ?>"><br>

        <label for="return_time">Return Time:</label>
        <input type="time" id="return_time" name="return_time" value="<?php echo $_SESSION['return_time']; ?>"><br>

        <label for="pick_up_location">Pick Up Location:</label>
        <input type="text" id="pick_up_location" name="pick_up_location" value="<?php echo $_SESSION['pick_up_location']; ?>"><br>

        <label for="total_rent_amount">Total Rent Amount:</label>
        <input type="text" id="total_rent_amount" name="total_rent_amount" value="<?php echo $_SESSION['total_rent_amount']; ?>"><br>

        <h2>Special Requirements</h2>
        <label for="return_different_location">Return to a Different Location:</label>
        <select id="return_different_location" name="special_requirements[]">
            <option value="none">No</option>
            <option value="different_location">Yes</option>
        </select><br>
        
         <div id="different_location_container" style="display: yes;">
            <label for="return_location">Return Location:</label>
            <input type="text" id="return_location" name="return_location" value="<?php echo $_SESSION['return_location']; ?>"><br>

        </div>

        <label for="baby_seat">Baby Seat:</label>
        <select id="baby_seat" name="special_requirements[]">
            <option value="none">No</option>
            <option value="baby_seat">Yes</option>
        </select><br>

        <label for="insurance">Insurance:</label>
        <select id="insurance" name="special_requirements[]">
            <option value="none">No</option>
            <option value="insurance">Yes</option>
        </select><br>
        <button type="submit" class="rent-button">Proceed to Invoice</button>
    </form>
</main>
<footer>
    <p>&copy; 2024 AUTO DRIVE. All rights reserved.</p>
    <p>Address: 1234 AUTO DRIVE St, Ramallah, Palestine</p>
    <p>Email: support@AUTODRIVE.com | Phone: +1234567890</p>
    <a href="contact.html">Contact Us</a>
</footer>
</body>
</html>

