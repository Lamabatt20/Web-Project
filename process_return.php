<?php
session_start();
include 'dbconfig.in.php';

// Ensure user is logged in as customer
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'customer') {
    header('Location: Login.html');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['return_car'])) {
    $pdo = db_connect();
    $car_id = $_POST['car_id'];

    $sql = "UPDATE Rentals SET status = 'returning', return_location = pick_up_location WHERE car_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$car_id]);

    header('Location: CReturnCar.php');
    exit();
}
?>


