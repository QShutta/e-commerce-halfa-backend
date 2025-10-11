<?php
//Before of 46.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "../../connect.php";

// 1. Retrieve data from the request
$user_email = filterRequest('user_email');
$user_varfy_code = rand(100000, 999999); 

// 2. Validate inputs
if (empty($user_email) ) {
    printFailureMessage("Email cannot be empty");
    exit;
}
if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
    printFailureMessage("Invalid email format");
    exit;
}

// 3. Prepare and execute the query
$stmt = $con->prepare("SELECT * FROM users WHERE user_email = ? ");
$stmt->execute([$user_email]);
$count = $stmt->rowCount();

// 4. Return the result
result($count, "Verification code sent to your email", "This email is not registered");
if($count>0){
    $data=array("user_varfy_code"=>$user_varfy_code);
    //لية ختينا false?
    //عشان نحنا عندنا اصلا الدالة الاسمها 
    //result($coutn)
    //will print success message
    //and the updateData function also will print success message
    //so we don't want to print the success message again
    //Why did we use the updateData function?
    //Because we want to update the user_varfy_code in the database
    
    updateData("users",$data,"user_email='$user_email'",false);
    sendEmail(
    $user_email, 
    "Password Reset Request", 
    "Hello,\n\nWe received a request to reset your password.\n\nYour reset code is: $user_varfy_code\n\nPlease enter this code in the app to reset your password.\n\nIf you did not request a password reset, please ignore this message.\n\nBest regards,\nShutta Team"
);
}
?>