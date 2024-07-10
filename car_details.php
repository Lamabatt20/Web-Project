<?php
session_start();

// Include database connection function
include 'dbconfig.in.php';

// Establish database connection
$pdo = db_connect();

// Function to fetch car details based on car_id
function fetchCarDetails($pdo, $car_id) {
    // Prepare SQL query
    $sql = "SELECT car_id, model, make, type, registration_year, color, description, price_per_day,
                   capacity_people, capacity_suitcases, fuel_type, avg_consumption,
                   horsepower, length, width, gear_type, conditions, photo1, photo2, photo3
            FROM Cars
            WHERE car_id = :car_id";

    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':car_id' => $car_id,
    ]);

    // Fetch car details
    $car = $stmt->fetch(PDO::FETCH_ASSOC);

    return $car;
}

// Check if car_id is provided in the query string
if (!isset($_GET['car_id'])) {
    die("Car ID not specified.");
}

// Fetch car details based on car_id
$car_id = $_GET['car_id'];
$car = fetchCarDetails($pdo, $car_id);

// Function to calculate total price for renting period (example calculation)
function calculateTotalPrice($price_per_day, $start_date, $end_date) {
    // Example calculation: assume each day has the same price
    $start = new DateTime($start_date);
    $end = new DateTime($end_date);
    $interval = $start->diff($end);
    $days = $interval->days + 1; // Including both start and end days
    $total_price = $days * $price_per_day;
    return $total_price;
}

// Define start and end dates (default or from session)
$start_date = isset($_SESSION['start_date']) ? $_SESSION['start_date'] : date('Y-m-d');
$end_date = isset($_SESSION['end_date']) ? $_SESSION['end_date'] : date('Y-m-d', strtotime('+3 days'));

// Calculate total price for renting period
$total_price = calculateTotalPrice($car['price_per_day'], $start_date, $end_date);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Car Details</title>
    <link rel="stylesheet" href="StyleCss.css">
</head>
<body>
    <header>
        <nav class="header-nav">
            <a href="Home.html"><img src="logo.jpg" alt="Course Image" width="300" height="100"></a>
            <a href="about.html"><span class="link-icon"><img src="icons8-about-us-24.png" alt="Course Image" width="40" height="40">About Us</span></a>
            <a href="cart.html"><span class="link-icon"><img src="icons8-cart-50.png" alt="Course Image" width="40" height="40">Cart</span></a>
            <a href="profile.php"><span class="link-icon"><img src="icons8-male-user-50.png" alt="Course Image" width="40" height="40">My Profile</span></a>
            <a href="Login.html"><span class="link-icon"><img src="icons8-add-administrator-50.png" alt="Course Image" width="40" height="40">Login/Register</span></a>
            <a href="logout.php"><span class="link-icon"><img src="icons8-logout-24.png" alt="Course Image" width="40" height="40">Logout</span></a>
        </nav>
    </header>
    <main>
        <div class="car-details">
            <div class="car-photos">
                <img src="<?php echo $car['photo1']; ?>" alt="Car photo"><br>
                <img src="<?php echo $car['photo2']; ?>" alt="Car photo"><br>
                <img src="<?php echo $car['photo3']; ?>" alt="Car photo">
            </div>
            <div class="car-description">
                <ul>
                    <li>Car Reference Number: <?php echo $car['car_id']; ?></li>
                    <li>Car Model: <?php echo $car['model']; ?></li>
                    <li>Car Type: <?php echo $car['type']; ?></li>
                    <li>Car Make: <?php echo $car['make']; ?></li>
                    <li>Registration Year: <?php echo $car['registration_year']; ?></li>
                    <li>Color: <?php echo $car['color']; ?></li>
                    <li>Brief Description: <?php echo $car['description']; ?></li>
                    <li>Price per Day: <?php echo $car['price_per_day']; ?></li>
                    <li>Capacity of People: <?php echo $car['capacity_people']; ?></li>
                    <li>Capacity of Suitcases: <?php echo $car['capacity_suitcases']; ?></li>
                    <li>Total Price for Renting Period: <?php echo $total_price; ?></li>
                    <li>Fuel Type: <?php echo $car['fuel_type']; ?></li>
                    <li>Average Consumption: <?php echo $car['avg_consumption']; ?> per 100 km</li>
                    <li>Horsepower: <?php echo $car['horsepower']; ?></li>
                    <li>Length: <?php echo $car['length']; ?></li>
                    <li>Width: <?php echo $car['width']; ?></li>
                    <li>Gear Type: <?php echo $car['gear_type']; ?></li>
                    <li>Conditions: <?php echo $car['conditions']; ?></li>
                </ul>
                <form action="RentCar.php" method="POST">
                    <input type="hidden" name="car_id" value="<?php echo $car['car_id']; ?>">
                    <input type="hidden" name="car_model" value="<?php echo $car['model']; ?>">
                    <input type="hidden" name="car_description" value="<?php echo $car['description']; ?>">
                     <input type="hidden" name="car_fuel_type" value="<?php echo $car['fuel_type']; ?>">
                    <input type="hidden" name="pick_up_date" value="<?php echo $start_date; ?>">
                    <input type="hidden" name="pick_up_time" value="<?php echo isset($_SESSION['pick_up_time']) ? $_SESSION['pick_up_time'] :'09:00'; ?>">
                    <input type="hidden" name="return_date" value="<?php echo $end_date; ?>">
                    <input type="hidden" name="return_time" value="<?php echo isset($_SESSION['return_time']) ? $_SESSION['return_time'] :'09:00'; ?>">
                    <input type="hidden" name="pick_up_location" value="<?php echo isset($_SESSION['pick_up_location']) ? $_SESSION['pick_up_location'] :'Hebron'; ?>">
                    <input type="hidden" name="return_location" value="<?php echo isset($_SESSION['return_location']) ? $_SESSION['return_location'] :'Hebron'; ?>">
                    <input type="hidden" name="price_per_day" value="<?php echo $car['price_per_day']; ?>">
                    <input type="hidden" name="total_rent_amount" value="<?php echo $total_price; ?>">
                    <button type="submit" class="rent-button">Rent-a-Car</button>
                </form>
            </div>
            <div class="car-marketing">
                <h3>Marketing Information</h3>
                <p>This car is enjoyable to drive and offers a smooth ride. Discounts available for long-term rentals.</p>
            </div>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 AUTO DRIVE. All rights reserved.</p>
        <p>Address: 1234 AUTO DRIVE St, Ramallah, Palestine</p>
        <p>Email: support@AUTODRIVE.com | Phone: +1234567890</p>
        <a href="contact.html">Contact Us</a>
    </footer>
</body>
</html>

