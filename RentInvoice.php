<?php
session_start();
include 'dbconfig.in.php';

$pdo = db_connect();


if (!isset($_SESSION['customer_id'])) {
    header("Location: Login.html?redirect=RentInvoice.php");
    exit;
}

$customer_id = $_SESSION['customer_id'];  

try {
    $pdo = db_connect();

    
    $stmt = $pdo->prepare("SELECT * FROM Customers WHERE customer_id = :customer_id");
    $stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $customer_name = $row['name'];
        $customer_address = $row['address'];
        $customer_phone = $row['telephone'];
    } else {
        
        echo "Customer not found.";
        exit;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}

$invoice_date = date("Y-m-d");
$car_id = isset($_SESSION['car_id']) ? $_SESSION['car_id'] : '';
$car_model = isset($_SESSION['car_model']) ? $_SESSION['car_model'] : '';
$car_fuel_type = isset($_SESSION['car_fuel_type']) ? $_SESSION['car_fuel_type'] : '';
$car_description = isset($_SESSION['car_description']) ? $_SESSION['car_description'] : '';
$pick_up_date = isset($_SESSION['pick_up_date']) ? $_SESSION['pick_up_date'] : '';
$pick_up_time = isset($_SESSION['pick_up_time']) ? $_SESSION['pick_up_time'] : '';
$return_date = isset($_SESSION['return_date']) ? $_SESSION['return_date'] : '';
$return_time = isset($_SESSION['return_time']) ? $_SESSION['return_time'] : '';
$return_location = isset($_SESSION['return_location']) ? $_SESSION['return_location'] : '';
$pick_up_location = isset($_SESSION['pick_up_location']) ? $_SESSION['pick_up_location'] : '';
$total_rent_amount = isset($_SESSION['total_rent_amount']) ? $_SESSION['total_rent_amount'] : '';
$special_requirements = isset($_SESSION['special_requirements']) ? $_SESSION['special_requirements'] : [];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and process credit card details
    $credit_card_number = htmlspecialchars($_POST['credit_card_number']);
    $expiration_date = htmlspecialchars($_POST['expiration_date']);
    $holder_name = htmlspecialchars($_POST['holder_name']);
    $card_type = htmlspecialchars($_POST['card_type']);
    $terms_accepted = isset($_POST['terms_accepted']) ? true : false;
    $customer_name = htmlspecialchars($_POST['customer_name']);
    $rent_date = date("Y-m-d"); 

    if (!preg_match("/^\d{9}$/", $credit_card_number)) {
        $error_message = "Credit card number must be 9 digits.";
    }
    header("Location: RentConfirm.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rent Invoice</title>
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
    <h1>Rent Invoice</h1>
    <h2>Invoice Details</h2>
    <p><strong>Invoice Date:</strong> <?php echo $invoice_date; ?></p>
    <p><strong>Customer ID:</strong> <?php echo $customer_id; ?></p>
    <p><strong>Customer Name:</strong> <?php echo $customer_name; ?></p>
    <p><strong>Customer Address:</strong> <?php echo $customer_address; ?></p>
    <p><strong>Customer Phone:</strong> <?php echo $customer_phone; ?></p>
    <!-- Display rent details -->
    <p><strong>Car ID:</strong> <?php echo $car_id; ?></p>
    <p><strong>Car Model:</strong> <?php echo $car_model; ?></p>
    <p><strong>Fuel Type:</strong> <?php echo $car_fuel_type; ?></p>
    <p><strong>Car Description:</strong> <?php echo $car_description; ?></p>
    <p><strong>Pick Up Date:</strong> <?php echo $pick_up_date; ?></p>
    <p><strong>Pick Up Time:</strong> <?php echo $pick_up_time; ?></p>
    <p><strong>Return Date:</strong> <?php echo $return_date; ?></p>
    <p><strong>Return Time:</strong> <?php echo $return_time; ?></p>
    <p><strong>Pick Up Location:</strong> <?php echo $pick_up_location; ?></p>
    <p><strong>Return Location:</strong> <?php echo $return_location; ?></p>
    <p><strong>Total Rent Amount:</strong> <?php echo $total_rent_amount; ?></p>
    <p><strong>Special Requirements:</strong> 
        <?php 
        if (in_array('different_location', $special_requirements)) {
            echo "Return to a different location<br>";
        }
        if (in_array('baby_seat', $special_requirements)) {
            echo "Baby Seat<br>";
        }
        if (in_array('insurance', $special_requirements)) {
            echo "Insurance<br>";
        }
        ?>
    </p>

    <!-- Credit Card Details Form -->
    <h2>Payment Details</h2>
    <form action="RentInvoice.php" method="POST">
        <label for="credit_card_number">Credit Card Number:</label>
        <input type="text" id="credit_card_number" name="credit_card_number" required><br>

        <label for="expiration_date">Expiration Date:</label>
        <input type="text" id="expiration_date" name="expiration_date" placeholder="MM/YYYY" required><br>

        <label for="holder_name">Card Holder Name:</label>
        <input type="text" id="holder_name" name="holder_name" required><br>

        <label>Card Type:</label><br>
        <input type="radio" id="visa" name="card_type" value="Visa" required>
        <label for="visa">Visa</label><br>
        <input type="radio" id="mastercard" name="card_type" value="MasterCard" required>
        <label for="mastercard">MasterCard</label><br>
        <!-- Add other card types as needed -->

        <h2>Terms and Conditions</h2>
        <label for="terms_accepted">
            <input type="checkbox" id="terms_accepted" name="terms_accepted" required>
            I accept the terms and conditions
        </label><br>

        <label for="customer_name">Customer Name:</label>
        <input type="text" id="customer_name" name="customer_name" required><br>

        <button type="submit">Confirm Rent</button>
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

