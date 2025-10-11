<?php
//Before of 46.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "../connect.php";

// 1. Retrieve data from the request
$user_email = filterRequest('user_email');
$user_password = sha1(filterRequest('user_password')); // Fix: Hash the actual password from the request

// 2. Validate inputs
if (empty($user_email) || empty(filterRequest('user_password'))) {
    printFailureMessage("Email or password cannot be empty");
    exit;
}
if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
    printFailureMessage("Invalid email format");
    exit;
}

// 3. Prepare and execute the query
// $stmt = $con->prepare("SELECT * FROM users WHERE user_email = ? AND user_password = ? AND user_approve = 1");
// $stmt->execute([$user_email, $user_password]);
// $count = $stmt->rowCount();

getData("users", "user_email = ? AND user_password = ? AND user_approve = 1", array($user_email, $user_password));
// 4. Return the result
// result($count,"Signin successfully","email or password is incorrect");
?>