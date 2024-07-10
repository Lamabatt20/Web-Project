<?php
session_start();
include 'dbconfig.in.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'customer') {
    header('Location: Login.html');
    exit();
}

$pdo = db_connect();
$customer_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];
$current_date = date('Y-m-d');

$update_status_sql = "UPDATE Rentals SET rental_status = 
                        CASE
                            WHEN pick_up_date > :current_date THEN 'future'
                            WHEN return_date >= :current_date THEN 'current'
                            ELSE 'past'
                        END
                        WHERE customer_id = :customer_id";
$update_status_stmt = $pdo->prepare($update_status_sql);
$update_status_stmt->execute(['current_date' => $current_date, 'customer_id' => $customer_id]);


$sql = "SELECT Rentals.invoice_id, Rentals.pick_up_date, Rentals.return_date, Rentals.pick_up_location, Rentals.return_location, 
        Cars.type AS car_type, Cars.model AS car_model, Invoices.invoice_date
        FROM Rentals 
        INNER JOIN Cars ON Rentals.car_id = Cars.car_id
        INNER JOIN Invoices ON Rentals.invoice_id = Invoices.invoice_id
        WHERE Rentals.customer_id = ?
        ORDER BY Rentals.pick_up_date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$customer_id]);
$rented_cars = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Rented Cars</title>
    <link rel="stylesheet" href="StyleHome.css">
</head>
<body>
    <header>
        <nav class="header-nav">
            <a href="Home.php"><img src="logo.jpg" alt="Logo" width="300" height="100"></a>
            <a href="about.php"><span class="link-icon"><img src="icons8-about-us-24.png" alt="About Us" width="40" height="40">About Us</span></a>
            <a href="cart.php"><span class="link-icon"><img src="icons8-cart-50.png" alt="Cart" width="40" height="40">Cart</span></a>
            <a href="profile.php"><span class="link-icon"><img src="icons8-male-user-50.png" alt="Profile" width="40" height="40">My Profile</span></a>
            <a href="Login.html"><span class="link-icon"><img src="icons8-add-administrator-50.png" alt="Login/Register" width="40" height="40">Login/Register</span></a>
            <a href="logout.php"><span class="link-icon"><img src="icons8-logout-24.png" alt="Logout" width="40" height="40">Logout</span></a>
        </nav>
    </header>
    <div class="container_view">
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
        <main id="rented-cars-main">
            <section id="view_rented_cars_section">
                <h2>View Rented Cars</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Invoice ID</th>
                            <th>Invoice Date</th>
                            <th>Car Type</th>
                            <th>Car Model</th>
                            <th>Pick-up Date</th>
                            <th>Pick-up Location</th>
                            <th>Return Date</th>
                            <th>Return Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rented_cars as $car) : ?>
                        <tr class="<?php echo htmlspecialchars($car['rental_status']); ?>">
                            <td><?php echo htmlspecialchars($car['invoice_id']); ?></td>
                            <td><?php echo htmlspecialchars($car['invoice_date']); ?></td>
                            <td><?php echo htmlspecialchars($car['car_type']); ?></td>
                            <td><?php echo htmlspecialchars($car['car_model']); ?></td>
                            <td><?php echo htmlspecialchars($car['pick_up_date']); ?></td>
                            <td><?php echo htmlspecialchars($car['pick_up_location']); ?></td>
                            <td><?php echo htmlspecialchars($car['return_date']); ?></td>
                            <td><?php echo htmlspecialchars($car['return_location']); ?></td>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
    <footer>
        <p>&copy; 2024 AUTO DRIVE. All rights reserved.</p>
        <p>Address: 1234 AUTO DRIVE St, Ramallah, Palestine</p>
        <p>Email: support@AUTODRIVE.com | Phone: +1234567890</p>
        <a href="contact.php">Contact Us</a>
    </footer>
</body>
</html>


