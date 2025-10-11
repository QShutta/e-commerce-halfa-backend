<?php
// Replace these variables with your actual database credentials
$dsn = 'mysql:host=localhost;dbname=qasimshu_e-commerce-halfa';
$username = 'qasimshu_e-commerce-halfa';
$password = 'Qasim#09112';
$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8");

try {
    $con = new PDO($dsn,     $username, $password,$options);

    // Set PDO error mode to exception
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With, Access-Control-Allow-Origin");
    header("Access-Control-Allow-Methods: POST, OPTIONS , GET");
    include "functions.php";
    if (!isset($notAuth)) {
       // checkAuthenticate();
    }

} catch (PDOException $e) {
    // Error occurred, display the error message
    //-> == .
    echo "Connection failed: " . $e->getMessage();
}
?>
