<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    include '../../connect.php';

    $email = filterRequest('user_email');
    //we need the varfy code from the user
    //then compare it with the code in the database
    $varfy_code = filterRequest('user_varfy_code');

    //here we will are going to check and compare between the varcode that the user entered
    // and the varfy code that we have in the database
    $stmt=$con->prepare("SELECT * FROM users WHERE user_email =? AND user_varfy_code = ?");
    $stmt->execute([$email, $varfy_code]);
    $count=$stmt->rowCount();
    if($count>0){
        printSuccessMessage("The verification code is correct.");
    }else{
        printFailureMessage( "The verification code is incorrect or the email does not match.");
    }

    ?>