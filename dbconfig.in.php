<?php
define('DBHOST', 'localhost');
define('DBNAME', 'web1210922_db');
define('DBUSER', 'web1210922_dbuser');
define('DBPASS', 'lama2003batta');
function db_connect($dbhost = DBHOST, $dbname = DBNAME , $username = DBUSER, $password = DBPASS){
    try {

        $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $username, $password);

        return $pdo;

    } catch (PDOException $e) {
        die($e->getMessage());
    }
}
?>
