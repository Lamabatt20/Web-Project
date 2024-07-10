<?php
session_start();
include 'dbconfig.in.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $pdo = db_connect(); 
        $stmt = $pdo->prepare("SELECT user_id, customer_id, password, user_type FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && $password === $user['password']) { 
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $username;
            $_SESSION['customer_id'] = $user['customer_id'];
            $_SESSION['user_type'] = $user['user_type'];

            header("Location: Home.php");
            exit;
        } else {
            echo "Invalid username or password!";
        }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}
?>
