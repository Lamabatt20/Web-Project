<?php
session_start();
include 'dbconfig.in.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $date_of_birth = $_POST['date_of_birth'];
    $id_number = $_POST['id_number'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $credit_card_number = $_POST['credit_card_number'];
    $credit_card_expirationDate = $_POST['credit_card_expirationDate']; // Corrected name
    $credit_card_name = $_POST['credit_card_name'];
    $credit_card_bank = $_POST['credit_card_bank'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit();
    }

    
    try {
        // Establish database connection using the function from dbconfig.in.php
        $pdo = db_connect();

        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO Customers (name, address,date_of_birth, id_number, email, telephone, credit_card_number,credit_card_expirationDate,credit_card_name,credit_card_bank,username,password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)");
        $stmt->execute([$name, $address, $date_of_birth, $id_number, $email, $telephone, $credit_card_number, $credit_card_expirationDate, $credit_card_name, $credit_card_bank,$username,$password]);

        $customer_id = $pdo->lastInsertId();

        $stmt = $pdo->prepare("INSERT INTO users (customer_id, username, password) VALUES (?, ?, ?)");
        $stmt->execute([$customer_id, $username, $password]);

        $pdo->commit();

        $_SESSION['user_id'] = $customer_id;
        $_SESSION['username'] = $username;

        header("Location: Login.html");
        exit();
    } catch (Exception $e) {
        if (isset($pdo)) {
            $pdo->rollBack();
        }
        echo "Failed to register: " . $e->getMessage();
    }
}
?>

