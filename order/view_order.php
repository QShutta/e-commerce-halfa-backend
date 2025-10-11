<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "../connect.php";

//Before of 144
$order_user_id = filterRequest('order_user_id');

getAllData("orders","order_user_id=?",[$order_user_id],true);


?>