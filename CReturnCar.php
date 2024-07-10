<?php
session_start();
include 'dbconfig.in.php';

// Ensure user is logged in as customer
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'customer') {
    header('Location: Login.html');
    exit();
}

// Database connection
$pdo = db_connect();
$customer_id = $_SESSION['user_id'];

// Fetch active rentals for the customer
$sql = "SELECT R.car_id, C.make AS car_make, C.type AS car_type, C.model AS car_model, R.pick_up_date, R.return_date, R.return_location
        FROM Rentals R
        INNER JOIN Cars C ON R.car_id = C.car_id
        WHERE R.customer_id=? AND C.status = 'active'";
$stmt = $pdo->prepare($sql);
$stmt->execute([$customer_id]);
$active_rentals = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Return a Car</title>
    <link rel="stylesheet" href="StyleHome.css">
</head>
<body>
    <header>
        <nav class="header-nav">
            <a href="Home.php"><img src="logo.jpg" alt="Logo" width="300" height="100"></a>
            <a href="about.html"><span class="link-icon"><img src="icons8-about-us-24.png" alt="About Us" width="40" height="40">About Us</span></a>
            <a href="cart.html"><span class="link-icon"><img src="icons8-cart-50.png" alt="Cart" width="40" height="40">Cart</span></a>
            <a href="profile.php"><span class="link-icon"><img src="icons8-male-user-50.png" alt="Profile" width="40" height="40">My Profile</span></a>
            <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'customer') : ?>
                <a href="logout.php"><span class="link-icon"><img src="icons8-logout-24.png" alt="Logout" width="40" height="40">Logout</span></a>
            <?php else : ?>
                <a href="Login.html"><span class="link-icon"><img src="icons8-add-administrator-50.png" alt="Login/Register" width="40" height="40">Login/Register</span></a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
        <section id="return_car_section">
            <h2>Return a Car</h2>
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
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($active_rentals as $rental) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($rental['car_id']); ?></td>
                        <td><?php echo htmlspecialchars($rental['car_make']); ?></td>
                        <td><?php echo htmlspecialchars($rental['car_type']); ?></td>
                        <td><?php echo htmlspecialchars($rental['car_model']); ?></td>
                        <td><?php echo htmlspecialchars($rental['pick_up_date']); ?></td>
                        <td><?php echo htmlspecialchars($rental['return_date']); ?></td>
                        <td><?php echo htmlspecialchars($rental['return_location']); ?></td>
                        <td>
                            <form action="process_return.php" method="POST">
                                <input type="hidden" name="car_id" value="<?php echo htmlspecialchars($rental['car_id']); ?>">
                                <button type="submit" name="return_car">Return</button>
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

