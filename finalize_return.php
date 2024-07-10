<?php
session_start();
include 'dbconfig.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'manager') {
    header('Location: Login.html');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['finalize_return'])) {
    $pdo = db_connect();
    $car_reference_number = $_POST['car_id'];
    $pickup_location = $_POST['pick_up_location'];
    $car_status = $_POST['status'];

    $sql = "UPDATE CarRentals SET car_status = ?, pickup_location = ? WHERE car_reference_number = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$car_status, $pickup_location, $car_reference_number]);

    header('Location: manager_return.php');
    exit();
}
?>
