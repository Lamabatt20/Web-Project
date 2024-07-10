<?php
session_start();

// Include database configuration
include 'dbconfig.in.php';

// Default search parameters if not provided by user
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d', strtotime('+3 days'));
$car_type = isset($_GET['car_type']) ? $_GET['car_type'] : 'sedan';
$location = isset($_GET['location']) ? $_GET['location'] : 'Birzeit';
$min_price = isset($_GET['min_price']) ? $_GET['min_price'] : 200;
$max_price = isset($_GET['max_price']) ? $_GET['max_price'] : 1000;

// Function to search for cars based on specified criteria
function searchCars($pdo, $start_date, $end_date, $car_type, $location, $min_price, $max_price) {
    // Prepare SQL query
    $sql = "SELECT car_id, model, make, type, registration_year, description, price_per_day,
                   capacity_people, capacity_suitcases, color, fuel_type, avg_consumption,
                   horsepower, length, width, gear_type, conditions, photo1, photo2, photo3
            FROM Cars
            WHERE type LIKE :car_type
            AND price_per_day BETWEEN :min_price AND :max_price
            AND car_id NOT IN (
                SELECT car_id FROM Rentals WHERE pick_up_date <= :end_date AND return_date >= :start_date
            )";

    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':car_type' => '%' . $car_type . '%', // Using LIKE for partial match
        ':min_price' => $min_price,
        ':max_price' => $max_price,
        ':start_date' => $start_date,
        ':end_date' => $end_date,
    ]);

    // Fetch results
    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $cars;
}

// Establish database connection (from dbconfig.in.php)
try {
    $pdo = db_connect(); // Using function from dbconfig.in.php
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Fetch cars based on search parameters
$cars = searchCars($pdo, $start_date, $end_date, $car_type, $location, $min_price, $max_price);

// Sorting functionality
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'price_per_day';
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'asc';

if ($sort_by === 'price_per_day' || $sort_by === 'type' || $sort_by === 'fuel_type') {
    usort($cars, function($a, $b) use ($sort_by, $sort_order) {
        $comparison = strcmp($a[$sort_by], $b[$sort_by]);
        return ($sort_order === 'asc') ? $comparison : -$comparison;
    });
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Car Search Results</title>
    <link rel="stylesheet" href="StyleHome.css">
    <style>
        .petrol { background-color: #cceeff; }
        .diesel { background-color: #ffcc99; }
        .electric { background-color: #ccffcc; }
        .hybrid { background-color: #ffffcc; }
    </style>
</head>
<body>
    <header>
        <nav class="header-nav">
            <a href="Home.html"><img src="logo.jpg" alt="Course Image" width="300" height="100"></a>
            <a href="about.html"><span class="link-icon"><img src="icons8-about-us-24.png" alt="Course Image" width="40" height="40">About Us</span></a>
            <a href="cart.html"><span class="link-icon"><img src="icons8-cart-50.png" alt="Course Image" width="40" height="40">Cart</span></a>
            <a href="profile.html"><span class="link-icon"><img src="icons8-male-user-50.png" alt="Course Image" width="40" height="40">My Profile</span></a>
            <a href="Login.html"><span class="link-icon"><img src="icons8-add-administrator-50.png" alt="Course Image" width="40" height="40">Login/Register</span></a>
            <a href="logout.php"><span class="link-icon"><img src="icons8-logout-24.png" alt="Course Image" width="40" height="40">Logout</span></a>
        </nav>
    </header>
    <div class="container_search">
    <nav class="side-nav">
       <a href="Search.php"><span class="link-icon"><img src="icons8-search-24.png" alt="Search Car" width="40" height="40">Search a Car</span></a>
            <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'manager'): ?>
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
    <main id='Search'>
        <form action="Search.php" method="GET">
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
            
            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
            <label for="car_type">Car Type:</label>
            <input type="text" id="car_type" name="car_type" value="<?php echo htmlspecialchars($car_type); ?>">
            <label for="location">Location:</label>
            <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($location); ?>">
            
            <label for="min_price">Min Price:</label>
            <input type="number" id="min_price" name="min_price" value="<?php echo htmlspecialchars($min_price); ?>">
            
            <label for="max_price">Max Price:</label>
            <input type="number" id="max_price" name="max_price" value="<?php echo htmlspecialchars($max_price); ?>">
            
            <button type="submit">Search</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>Select</th>
                    <th><a href="?sort_by=price_per_day&sort_order=<?php echo ($sort_by === 'price_per_day' && $sort_order === 'asc') ? 'desc' : 'asc'; ?>">Price per Day</a></th>
                    <th><a href="?sort_by=type&sort_order=<?php echo ($sort_by === 'type' && $sort_order === 'asc') ? 'desc' : 'asc'; ?>">Car Type</a></th>
                    <th><a href="?sort_by=fuel_type&sort_order=<?php echo ($sort_by === 'fuel_type' && $sort_order === 'asc') ? 'desc' : 'asc'; ?>">Fuel Type</a></th>
                    <th>Photo</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cars as $car): ?>
                    <tr class="<?php echo strtolower($car['fuel_type']); ?>">
                        <td><input type="checkbox" name="selected_cars[]" value="<?php echo $car['car_id']; ?>"></td>
                        <td><?php echo $car['price_per_day']; ?></td>
                        <td><?php echo $car['type']; ?></td>
                        <td><?php echo $car['fuel_type']; ?></td>
                        <td>
                            <img src="<?php echo $car['photo1']; ?>" alt="Car photo" width="60">
                            <img src="<?php echo $car['photo2']; ?>" alt="Car photo" width="60">
                            <img src="<?php echo $car['photo3']; ?>" alt="Car photo" width="60">
                        </td>
                          <td>
                            <form action="car_details.php" method="get">
                                <input type="hidden" name="car_id" value="<?php echo $car['car_id']; ?>">
                                <button type="submit" class="rent-button">Rent</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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







