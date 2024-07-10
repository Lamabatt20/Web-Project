<?php
session_start();


include 'dbconfig.in.php';
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'manager') {
    header('Location: Login.html');
    exit();
}



function fetchCarDetails($pdo, $car_id) {
   
    $sql = "SELECT car_id, model, make, type, registration_year, color, description, price_per_day,
                   capacity_people, capacity_suitcases, fuel_type, avg_consumption,
                   horsepower, length, width, gear_type, conditions, photo1, photo2, photo3
            FROM Cars
            WHERE car_id = :car_id";

    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':car_id' => $car_id,
    ]);

    
    $car = $stmt->fetch(PDO::FETCH_ASSOC);

    return $car;
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_car'])) {

   
    $pdo = db_connect();
    
    $add_car_messages = [];

    
    $model = $_POST["model"];
    $make = $_POST["make"];
    $type = $_POST["type"];
    $registration_year = $_POST["registration_year"];
    $description = $_POST["description"];
    $price_per_day = $_POST["price_per_day"];
    $capacity_people = $_POST["capacity_people"];
    $capacity_suitcases = $_POST["capacity_suitcases"];
    $colors = $_POST["color"];
    $fuel_type = $_POST["fuel_type"];
    $avg_consumption = $_POST["avg_consumption"];
    $horsepower = $_POST["horsepower"];
    $length = $_POST["length"];
    $width = $_POST["width"];
    $conditions = $_POST["conditions"];

    
    $upload_dir = '';
    $uploaded_photos = [];
    $file_names = [];

    
    for ($photo_number = 1; $photo_number <= 3; $photo_number++) {
        if (!empty($_FILES["photo{$photo_number}"]['name'])) {
            $file_name = $_FILES["photo{$photo_number}"]['name'];
            $file_tmp = $_FILES["photo{$photo_number}"]['tmp_name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $valid_extensions = ['jpeg', 'jpg', 'png'];

            if (in_array($file_ext, $valid_extensions)) {
                
                $car_id = mt_rand(1000000000, 9999999999); 
                $new_file_name = "car{$car_id}img{$photo_number}.{$file_ext}";
                $file_path = $upload_dir . $new_file_name;

                
                if (move_uploaded_file($file_tmp, $file_path)) {
                    $uploaded_photos[] = $file_path;
                    $file_names[] = $new_file_name;
                }
            }
        }
    }

    
   try {
    $sql = "INSERT INTO Cars (model, make, type, registration_year, description, price_per_day, 
                              capacity_people, capacity_suitcases, color, fuel_type, avg_consumption, 
                              horsepower, length, width, conditions, photo1, photo2, photo3) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    
    $stmt->execute([$model, $make, $type, $registration_year, $description, $price_per_day, 
                    $capacity_people, $capacity_suitcases, $colors, $fuel_type, $avg_consumption, 
                    $horsepower, $length, $width, $conditions, $file_names[0], $file_names[1], 
                    $file_names[2]]);
    
    
    $car_id = $pdo->lastInsertId();

    
    $add_car_messages[] = "Car added successfully! Car ID: {$car_id}";

} catch (PDOException $e) {
   
    $add_car_messages[] = "Error adding Car: " . $e->getMessage();
    
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Car Rental System</title>
    <link rel="stylesheet" href="StyleHome.css"> 
</head>
<body>
<header>
    <nav class="header-nav">
        <a href="Home.php"><img src="logo.jpg" alt="Course Image" width="300" height="100"></a>
        <a href="about.html"><span class="link-icon"><img src="icons8-about-us-24.png" alt="Course Image" width="40" height="40">About Us</span></a>
        <a href="cart.html"><span class="link-icon"><img src="icons8-cart-50.png" alt="Course Image" width="40" height="40">Cart</span></a>
        <a href="profile.html"><span class="link-icon"><img src="icons8-male-user-50.png" alt="Course Image" width="40" height="40">My Profile</span></a>
        <a href="Login.html"><span class="link-icon"><img src="icons8-add-administrator-50.png" alt="Course Image" width="40" height="40">Login/Register</span></a>
        <a href="logout.php"><span class="link-icon"><img src="icons8-logout-24.png" alt="Course Image" width="40" height="40">Logout</span></a>
    </nav>
</header>
 <div class="container">
        <nav class="side-nav">
            <a href="Search.php"><span class="link-icon"><img src="icons8-search-24.png" alt="Search Car" width="40" height="40">Search a Car</span></a>
            <?php if ($_SESSION['user_type'] === 'customer'): ?>
               <a href="RentCar.php"><span class="link-icon"><img src="icons8-car-rental-24.png" alt="Rent Car" width="40" height="40">Rent a Car</span></a>
                <a href="CReturnCar.php"><span class="link-icon"><img src="icons8-return-64.png" alt="Return Car" width="40" height="40">Return a Car</span></a>
                <a href="view_rented_cars.php"><span class="link-icon"><img src="icons8-view-24.png" alt="View Rented Cars" width="40" height="40">View Rented Cars</span></a>
            <?php else: ?>
            <a href="AddCar.php"><span class="link-icon"><img src="icons8-add-car-48.png" alt="Add Car" width="40" height="40">Add a Car</span></a>
                <a href="manager_return.php"><span class="link-icon"><img src="icons8-return-64.png" alt="Return Car" width="40" height="40">Return a Car</span></a>
                <a href="cars_inquire.php"><span class="link-icon"><img src="icons8-search-24.png" alt="Search Cars" width="40" height="40">Inquire Cars</span></a>
                <a href="AddLocation.php"><span class="link-icon"><img src="icons8-location-24 (1).png" alt="Add Location" width="40" height="40">Add a Location</span></a>
            <?php endif; ?>
        </nav>
<main>
    
<section id="add_car_section">
    <h2>Add New Car (Manager)</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
        <label for="model">Car Model:</label><br>
        <input type="text" id="model" name="model" required><br><br>

        <label for="make">Car Make:</label><br>
        <select id="make" name="make" required>
            <option value="BMW">BMW</option>
            <option value="VW">Volkswagen</option>
            <option value="Volvo">Volvo</option>
            <option value="Octavia">Octavia</option>
            
        </select><br><br>

        <label for="type">Car Type:</label><br>
        <select id="type" name="type" required>
            <option value="Van">Van</option>
            <option value="Min-Van">Min-Van</option>
            <option value="Sedan">Sedan</option>
            <option value="SUV">SUV</option>
        </select><br><br>

        <label for="registration_year">Registration Year:</label><br>
        <input type="number" id="registration_year" name="registration_year" required><br><br>

        <label for="description">Description:</label><br>
        <textarea id="description" name="description" rows="4" cols="50" required></textarea><br><br>

        <label for="price_per_day">Price per Day ($):</label><br>
        <input type="number" id="price_per_day" name="price_per_day" required><br><br>

        <label for="capacity_people">Capacity (People):</label><br>
        <input type="number" id="capacity_people" name="capacity_people" required><br><br>

        <label for="capacity_suitcases">Capacity (Suitcases):</label><br>
        <input type="number" id="capacity_suitcases" name="capacity_suitcases" required><br><br>

        <label for="color">Colors:</label><br>
        <input type="text" id="color" name="color" required><br><br>

        <label for="fuel_type">Fuel Type:</label><br>
        <select id="fuel_type" name="fuel_type" required>
            <option value="petrol">petrol</option>
            <option value="diesel">diesel</option>
            <option value="electric">electric</option>
            <option value="hybrid">hybrid</option>
        </select><br><br>

        <label for="avg_consumption">Average Consumption (km/L):</label><br>
        <input type="number" id="avg_consumption" name="avg_consumption" required><br><br>

        <label for="horsepower">Horsepower:</label><br>
        <input type="number" id="horsepower" name="horsepower" required><br><br>

        <label for="length">Length (m):</label><br>
        <input type="number" id="length" name="length" required><br><br>

        <label for="width">Width (m):</label><br>
        <input type="number" id="width1" name="width" required><br><br>

        <label for="conditions">Conditions:</label><br>
        <textarea id="conditions" name="conditions" rows="4" cols="50" required></textarea><br><br>

        <label for="photo1">Upload Photo 1:</label><br>
        <input type="file" id="photo1" name="photo1" accept="" required><br><br>

        <label for="photo2">Upload Photo 2:</label><br>
        <input type="file" id="photo2" name="photo2" accept="" required><br><br>

        <label for="photo3">Upload Photo 3:</label><br>
        <input type="file" id="photo3" name="photo3" accept="" required><br><br>

        <input type="submit" name="add_car" value="Add Car">
    </form>

   
    <?php if (!empty($add_car_messages)) : ?>
        <div>
            <?php foreach ($add_car_messages as $message) : ?>
                <p><?php echo $message; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
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

