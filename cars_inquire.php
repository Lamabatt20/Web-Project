<?php
session_start();
include 'dbconfig.in.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'manager') {
    header('Location: Login.html');
    exit();
}

$pdo = db_connect();
$results = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $from_date = $_POST['from_date'] ?? '';
    $to_date = $_POST['to_date'] ?? '';
    $pick_up_location = $_POST['pick_up_location'] ?? '';
    $return_date = $_POST['return_date'] ?? '';
    $return_location = $_POST['return_location'] ?? '';
    $status = $_POST['status'] ?? '';

    $sql = "SELECT car_id, type, model, description, photo1, photo2, photo3, fuel_type, status 
            FROM Cars 
            WHERE 1=1";

    $params = [];

    if (!empty($from_date) && !empty($to_date)) {
        $sql .= " AND car_id NOT IN (
                    SELECT car_id 
                    FROM Rentals 
                    WHERE (pick_up_date BETWEEN ? AND ?) OR (return_date BETWEEN ? AND ?)
                )";
        $params[] = $from_date;
        $params[] = $to_date;
        $params[] = $from_date;
        $params[] = $to_date;
    }
    if (!empty($pick_up_location)) {
        $sql .= " AND pick_up_location = ?";
        $params[] = $pick_up_location;
    }
    if (!empty($return_date)) {
        $sql .= " AND car_id IN (
                    SELECT car_id 
                    FROM Rentals 
                    WHERE return_date = ?
                )";
        $params[] = $return_date;
    }
    if (!empty($return_location)) {
        $sql .= " AND car_id IN (
                    SELECT car_id 
                    FROM Rentals 
                    WHERE return_location = ?
                )";
        $params[] = $return_location;
    }
    if (!empty($status)) {
        $sql .= " AND status = ?";
        $params[] = $status;
    }

    if (empty($from_date) && empty($to_date) && empty($pick_up_location) && empty($return_date) && empty($return_location) && empty($status)) {
        $sql .= " AND status = 'available' AND car_id NOT IN (
                    SELECT car_id 
                    FROM Rentals 
                    WHERE pick_up_date < DATE_ADD(CURDATE(), INTERVAL 1 WEEK)
                )";
    }

    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute($params);
        $results = $stmt->fetchAll();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Cars Inquire</title>
    <link rel="stylesheet" href="StyleHome.css">
</head>

<body>
    <header>
        <nav class="header-nav">
            <a href="Home.php"><img src="logo.jpg" alt="Logo" width="300" height="100"></a>
            <a href="about.html"><span class="link-icon"><img src="icons8-about-us-24.png" alt="About Us" width="40" height="40">About Us</span></a>
            <a href="cart.html"><span class="link-icon"><img src="icons8-cart-50.png" alt="Cart" width="40" height="40">Cart</span></a>
            <a href="profile.php"><span class="link-icon"><img src="icons8-male-user-50.png" alt="Profile" width="40" height="40">My Profile</span></a>
            <a href="Login.html"><span class="link-icon"><img src="icons8-add-administrator-50.png" alt="Login/Register" width="40" height="40">Login/Register</span></a>
            <a href="logout.php"><span class="link-icon"><img src="icons8-logout-24.png" alt="Logout" width="40" height="40">Logout</span></a>
        </nav>
    </header>
    <main>
        <section id="inquire_cars_section">
            <h2>Cars Inquire</h2>
            <form method="post" action="cars_inquire.php">
                <label for="from_date">From Date:</label>
                <input type="date" id="from_date" name="from_date">
                <label for="to_date">To Date:</label>
                <input type="date" id="to_date" name="to_date">
                <label for="pick_up_location">Pick-up Location:</label>
                <input type="text" id="pick_up_location" name="pick_up_location">
                <label for="return_date">Return Date:</label>
                <input type="date" id="return_date" name="return_date">
                <label for="return_location">Return Location:</label>
                <input type="text" id="return_location" name="return_location">
                <label for="status">Status:</label>
                <select id="status" name="status">
                    <option value="">Any</option>
                    <option value="available">Available</option>
                    <option value="repair">Repair</option>
                    <option value="damaged">Damaged</option>
                </select>
                <button type="submit">Search</button>
            </form>
            <table>
                <thead>
                    <tr>
                        <th>Car ID</th>
                        <th>Type</th>
                        <th>Model</th>
                        <th>Description</th>
                        <th>Photos</th>
                        <th>Fuel Type</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $car) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($car['car_id']); ?></td>
                            <td><?php echo htmlspecialchars($car['type']); ?></td>
                            <td><?php echo htmlspecialchars($car['model']); ?></td>
                            <td><?php echo htmlspecialchars($car['description']); ?></td>
                            <td>
                                <img src="<?php echo $car['photo1']; ?>" alt="Car photo" width="60">
                                <img src="<?php echo $car['photo2']; ?>" alt="Car photo" width="60">
                                <img src="<?php echo $car['photo3']; ?>" alt="Car photo" width="60">
                            </td>
                            <td><?php echo htmlspecialchars($car['fuel_type']); ?></td>
                            <td><?php echo htmlspecialchars($car['status']); ?></td>
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