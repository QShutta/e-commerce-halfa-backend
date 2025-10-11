<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    include '../connect.php';
    $user_email = filterRequest('user_email');
    $user_varfy_code = rand(100000, 999999); 
    $data=["user_varfy_code"=>$user_varfy_code];
    updateData("users",$data,"user_email='$user_email'");
     sendEmail(
            $user_email, 
            "Email Verification", 
            "Hello,\n\nYour new verification code is: $user_varfy_code\n\nPlease enter this code in the app to verify your email.\n\nBest regards,\nShutta Team"
        );

    ?>