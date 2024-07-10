<?php
session_start();
include 'dbconfig.in.php';

// Ensure user is logged in as manager
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'manager') {
    header('Location: Login.html');
    exit();
}

// Database connection
$pdo = db_connect();

// Fetch cars that are in 'returning' status
$sql = "SELECT R.car_id, C.make AS car_make, C.type AS car_type, C.model AS car_model, R.pick_up_date, R.return_date, R.return_location, R.customer_id
        FROM Rentals R
        INNER JOIN Cars C ON R.car_id = C.car_id
        WHERE C.status = 'returning'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$returning_cars = $stmt->fetchAll();

// Fetch customer names for returning cars
$customer_ids = array_column($returning_cars, 'customer_id');
if (!empty($customer_ids)) {
    $sql = "SELECT id, name FROM Customers WHERE id IN (" . implode(',', array_map('intval', $customer_ids)) . ")";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $customers = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
} else {
    $customers = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Car Returns</title>
    <link rel="stylesheet" href="StyleHome.css">
</head>
<body>
    <header>
        <nav class="header-nav">
            <a href="Home.php"><img src="logo.jpg" alt="Logo" width="300" height="100"></a>
            <a href="about.html"><span class="link-icon"><img src="icons8-about-us-24.png" alt="About Us" width="40" height="40">About Us</span></a>
            <a href="cart.html"><span class="link-icon"><img src="icons8-cart-50.png" alt="Cart" width="40" height="40">Cart</span></a>
            <a href="profile.php"><span class="link-icon"><img src="icons8-male-user-50.png" alt="Profile" width="40" height="40">My Profile</span></a>
            <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'manager') : ?>
                <a href="logout.php"><span class="link-icon"><img src="icons8-logout-24.png" alt="Logout" width="40" height="40">Logout</span></a>
            <?php else : ?>
                <a href="Login.html"><span class="link-icon"><img src="icons8-add-administrator-50.png" alt="Login/Register" width="40" height="40">Login/Register</span></a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
        <section id="manage_return_section">
            <h2>Manage Car Returns</h2>
            <table>
                <thead>
                    <tr>
                        <th>Car Reference Number</th>
                        <th>Car Make</th>
                        <th>Car Type</th>
                        <th>Car Model</th>
                        <th>Pickup Date</th>
                        <th>Return Date</th>
                        <th>Return Location</th>
                        <th>Customer Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($returning_cars as $car) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($car['car_reference_number']); ?></td>
                        <td><?php echo htmlspecialchars($car['car_make']); ?></td>
                        <td><?php echo htmlspecialchars($car['car_type']); ?></td>
                        <td><?php echo htmlspecialchars($car['car_model']); ?></td>
                        <td><?php echo htmlspecialchars($car['pick_up_date']); ?></td>
                        <td><?php echo htmlspecialchars($car['return_date']); ?></td>
                        <td><?php echo htmlspecialchars($car['return_location']); ?></td>
                        <td><?php echo htmlspecialchars($customers[$car['customer_id']] ?? 'Unknown'); ?></td>
                        <td>
                            <form action="process_manager_return.php" method ="POST">
                                <input type="hidden" name="car_id" value="<?php echo htmlspecialchars($car['car_id']); ?>">
                                <input type="hidden" name="car_reference_number" value="<?php echo htmlspecialchars($car['car_reference_number']); ?>">
                                <input type="hidden" name="pickup_location" value="<?php echo htmlspecialchars($car['return_location']); ?>">
                                <label for="car_status">Car Status:</label>
                                <select name="car_status" id="car_status">
                                    <option value="available">Available</option>
                                    <option value="damaged">Damaged</option>
                                    <option value="repair">Under Repair</option>
                                </select>
                                <button type="submit" name="finalize_return">Finalize Return</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 AUTO DRIVE. All rights reserved.</p>
        <p>Address: 1234 AUTO DRIVE St, Ramallah, Palestine</p>
        <p>Email: support@AUTODRIVE.com | Phone: +1234567890</p>
        <a href="contact.php">Contact Us</a>
    </footer>
</body>
</html>

