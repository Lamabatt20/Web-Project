<?php
session_start();
include 'dbconfig.in.php';

$pdo = db_connect();

if (!isset($_SESSION['customer_id'])) {
    header("Location: Login.html?redirect=RentConfirm.php");
    exit;
}

$customer_id = $_SESSION['customer_id'];

// Retrieve session variables
$car_id = isset($_SESSION['car_id']) ? $_SESSION['car_id'] : '';
$pick_up_date = isset($_SESSION['pick_up_date']) ? $_SESSION['pick_up_date'] : '';
$return_date = isset($_SESSION['return_date']) ? $_SESSION['return_date'] : '';
$total_rent_amount = isset($_SESSION['total_rent_amount']) ? $_SESSION['total_rent_amount'] : '';
$pick_up_time = isset($_SESSION['pick_up_time']) ? $_SESSION['pick_up_time'] : '';
$return_time = isset($_SESSION['return_time']) ? $_SESSION['return_time'] : '';
$pick_up_location = isset($_SESSION['pick_up_location']) ? $_SESSION['pick_up_location'] : '';
$return_location = isset($_SESSION['return_location']) ? $_SESSION['return_location'] : '';
$special_requirements = isset($_SESSION['special_requirements']) ? $_SESSION['special_requirements'] : '';

// Assuming additional session variables for special requirements
$requirement_id = isset($_SESSION['requirement_id']) ? $_SESSION['requirement_id'] : '';
$additional_cost = isset($_SESSION['additional_cost']) ? $_SESSION['additional_cost'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and process credit card details (this part is simplified for demonstration)
    $credit_card_number = htmlspecialchars($_POST['credit_card_number']);
    $expiration_date = htmlspecialchars($_POST['expiration_date']);
    $holder_name = htmlspecialchars($_POST['holder_name']);
    $card_type = htmlspecialchars($_POST['card_type']);
    $terms_accepted = isset($_POST['terms_accepted']) ? true : false;
    $customer_name = htmlspecialchars($_POST['customer_name']);

    try {
        $pdo->beginTransaction();

        // Insert into Invoices table
        $invoice_date = date("Y-m-d");
        $stmt = $pdo->prepare("INSERT INTO Invoices (customer_id, invoice_date, total_amount) 
                               VALUES (:customer_id, :invoice_date, :total_amount)");
        $stmt->bindparam(':customer_id', $customer_id, PDO::PARAM_INT);
        $stmt->bindparam(':invoice_date', $invoice_date, PDO::PARAM_STR);
        $stmt->bindparam(':total_amount', $total_rent_amount, PDO::PARAM_STR);
        $stmt->execute();
        $invoice_id = $pdo->lastInsertId(); // Get the ID of the inserted invoice

        // Insert into Rentals table
        $stmt = $pdo->prepare("INSERT INTO Rentals (customer_id, car_id, pick_up_date, return_date, pick_up_location, return_location, special_requirements, total_rent_amount, status, rental_status, pick_up_time, return_time, invoice_id) 
                               VALUES (:customer_id, :car_id, :pick_up_date, :return_date, :pick_up_location, :return_location, :special_requirements, :total_rent_amount, 'active', 'current', :pick_up_time, :return_time, :invoice_id)");
        $stmt->bindparam(':customer_id', $customer_id, PDO::PARAM_INT);
        $stmt->bindparam(':car_id', $car_id, PDO::PARAM_INT);
        $stmt->bindparam(':pick_up_date', $pick_up_date, PDO::PARAM_STR);
        $stmt->bindparam(':return_date', $return_date, PDO::PARAM_STR);
        $stmt->bindparam(':pick_up_location', $pick_up_location, PDO::PARAM_STR);
        $stmt->bindparam(':return_location', $return_location, PDO::PARAM_STR);
        $stmt->bindparam(':special_requirements', $special_requirements, PDO::PARAM_STR);
        $stmt->bindparam(':total_rent_amount', $total_rent_amount, PDO::PARAM_STR);
        $stmt->bindparam(':pick_up_time', $pick_up_time, PDO::PARAM_STR);
        $stmt->bindparam(':return_time', $return_time, PDO::PARAM_STR);
        $stmt->bindparam(':invoice_id', $invoice_id, PDO::PARAM_INT);
        $stmt->execute();
        $rental_id = $pdo->lastInsertId(); // Get the ID of the inserted rental

        // Insert into SpecialRequirements table
        if (!empty($special_requirements)) {
            $stmt = $pdo->prepare("INSERT INTO SpecialRequirements (rental_id, description, additional_cost) 
                                   VALUES (:rental_id, :description, :additional_cost)");
            $stmt->bindparam(':rental_id', $rental_id, PDO::PARAM_INT);
            $stmt->bindparam(':description', $special_requirements, PDO::PARAM_STR);
            $stmt->bindparam(':additional_cost', $additional_cost, PDO::PARAM_STR);
            $stmt->execute();
        }

        // Update the rental_id in the Invoices table
        $stmt = $pdo->prepare("UPDATE Invoices SET rental_id = :rental_id WHERE invoice_id = :invoice_id");
        $stmt->bindparam(':rental_id', $rental_id, PDO::PARAM_INT);
        $stmt->bindparam(':invoice_id', $invoice_id, PDO::PARAM_INT);
        $stmt->execute();

        // Commit the transaction
        $pdo->commit();

        // Clear session variables or perform other cleanup as needed
        /*unset($_SESSION['car_id']);
        unset($_SESSION['pick_up_date']);
        unset($_SESSION['return_date']);
        unset($_SESSION['total_rent_amount']);
        unset($_SESSION['pick_up_time']);
        unset($_SESSION['return_time']);
        unset($_SESSION['pick_up_location']);
        unset($_SESSION['return_location']);
        unset($_SESSION['special_requirements']);
        unset($_SESSION['requirement_id']);
        unset($_SESSION['additional_cost']);*/

    } catch (PDOException $e) {
        // Rollback the transaction on error
        $pdo->rollback();
        $error_message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rent Confirmation</title>
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
<main>
        <h1>Rent Successful</h1>
        <p>Your rental has been successfully confirmed. Thank you!</p>
    <a href="Home.php">Back to Home</a>
</main>
<footer>
    <p>&copy; 2024 AUTO DRIVE. All rights reserved.</p>
    <p>Address: 1234 AUTO DRIVE St, Ramallah, Palestine</p>
    <p>Email: support@AUTODRIVE.com | Phone: +1234567890</p>
    <a href="contact.html">Contact Us</a>
</footer>
</body>
</html>

