<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "../connect.php";
//Here we will get all of the addresses that realated to that spacfic user.
//What did you mean by the addresses?the user may have more than one address:
//Home,job,jim...
$address_user_id = filterRequest('address_user_id');

getAllData("address", "address_user_id=?",[$address_user_id]);
?>
