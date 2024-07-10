<?php
session_start();
include 'dbconfig.in.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: Login.html');
    exit();
}

$pdo = db_connect();

$user_id = $_SESSION['customer_id'];
$sql = "SELECT * FROM Customers WHERE customer_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo "Error: User not found.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $address = $_POST['address'] ?? '';
    $date_of_birth = $_POST['date_of_birth'] ?? '';
    $email = $_POST['email'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $credit_card_number = $_POST['credit_card_number'] ?? '';
    $credit_card_expiry = $_POST['credit_card_expirationDate'] ?? '';

    $update_sql = "UPDATE Customers SET name = ?, address = ?, date_of_birth = ?, email = ?, telephone = ?, credit_card_number = ?, credit_card_expirationDate = ? WHERE customer_id = ?";
    $update_stmt = $pdo->prepare($update_sql);
    $update_stmt->execute([$name, $address, $date_of_birth, $email, $telephone, $credit_card_number, $credit_card_expiry, $user_id]);

    header('Location: profile.php?update=success');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <link rel="stylesheet" href="StyleHome.css">
</head>
<body>
    <header>
        <nav class="header-nav">
            <a href="Home.php"><img src="logo.jpg" alt="Course Image" width="300" height="100"></a>
            <a href="about.html"><span class="link-icon"><img src="icons8-about-us-24.png" alt="Course Image" width="40" height="40">About Us</span></a>
            <?php if (isset($_SESSION['user_id'])) : ?>
            <a href="cart.html"><span class="link-icon"><img src="icons8-cart-50.png" alt="Course Image" width="40" height="40">Cart</span></a>
            <a href="profile.php"><span class="link-icon"><img src="icons8-male-user-50.png" alt="Course Image" width="40" height="40">My Profile</span></a>
            <a href="logout.php"><span class="link-icon"><img src="icons8-logout-24.png" alt="Course Image" width="40" height="40">Logout</span></a>
            <?php else : ?>
            <a href="Login.html"><span class="link-icon"><img src="icons8-add-administrator-50.png" alt="Course Image" width="40" height="40">Login/Register</span></a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
        <section id="profile_section">
            <h2>User Profile</h2>
            <?php if (isset($_GET['update']) && $_GET['update'] == 'success'): ?>
                <p>Profile updated successfully!</p>
            <?php endif; ?>
            <form method="post" action="profile.php">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>
                <label for="date_of_birth">Date of Birth:</label>
                <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo htmlspecialchars($user['date_of_birth']); ?>" required>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                <label for="telephone">Telephone:</label>
                <input type="tel" id="telephone" name="telephone" value="<?php echo htmlspecialchars($user['telephone']); ?>" required>
                <label for="credit_card_number">Credit Card Number:</label>
                <input type="text" id="credit_card_number" name="credit_card_number" value="<?php echo htmlspecialchars($user['credit_card_number']); ?>" required>
                <label for="credit_card_expirationDate">Credit Card Expiry:</label>
                <input type="text" id="credit_card_expirationDate" name="credit_card_expirationDate" value="<?php echo htmlspecialchars($user['credit_card_expirationDate']); ?>" required>
                <button type="submit">Update Profile</button>
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
