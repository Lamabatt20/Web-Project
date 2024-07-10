<?php
// Start the session
session_start();

// Include database connection
include 'dbconfig.in.php';

// Check if the user is logged in and is a manager
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'manager') {
    // Redirect to login page if not a manager
    header('Location: Login.html');
    exit();
}

// Handle adding a new location
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_location'])) {
    // Establish database connection
    $pdo = db_connect();

    // Fetch and sanitize form data
    $name = $_POST['name'];
    $property_number = $_POST['property_number'];
    $street_name = $_POST['street_name'];
    $city = $_POST['city'];
    $postal_code = $_POST['postal_code'];
    $country = $_POST['country'];
    $telephone = $_POST['telephone'];

    // Insert location details into database
    try {
        $sql = "INSERT INTO Locations (name, property_number, street_name, city, postal_code, country, telephone) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $property_number, $street_name, $city, $postal_code, $country, $telephone]);

        // Get the generated location ID
        $location_id = $pdo->lastInsertId();

        // Display success message
        $add_location_message = "Location added successfully! Location ID: {$location_id}";

    } catch (PDOException $e) {
        $add_location_message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Location</title>
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
        <section id="add_location_section">
            <h2>Add a New Location (Manager)</h2>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <label for="name">Location Name:</label><br>
                <input type="text" id="name" name="name" required><br><br>

                <label for="property_number">Property Number:</label><br>
                <input type="text" id="property_number" name="property_number" required><br><br>

                <label for="street_name">Street Name:</label><br>
                <input type="text" id="street_name" name="street_name" required><br><br>

                <label for="city">City:</label><br>
                <input type="text" id="city" name="city" required><br><br>

                <label for="postal_code">Postal Code:</label><br>
                <input type="text" id="postal_code" name="postal_code" required><br><br>

                <label for="country">Country:</label><br>
                <input type="text" id="country" name="country" required><br><br>

                <label for="telephone">Telephone Number:</label><br>
                <input type="text" id="telephone" name="telephone" required><br><br>

                <button type="submit" name="add_location">Add Location</button>
                <?php if (isset($add_location_message)) : ?>
                    <p><?php echo $add_location_message; ?></p>
                <?php endif; ?>
            </form>
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
