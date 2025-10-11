<?php
//Before of 46.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "../../connect.php";

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
$data=array("user_password"=>$user_password);
updateData("users",$data,"user_email='$user_email'");
?>