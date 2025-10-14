<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "../../connect.php";
//What is the prupose of this file?
//The purpose of this file is that when the user made an order this order will be waiting for approvel 
//when the admin click on the approve button the order status will be change from 0 to 1 .
//Also there is a nofication will apear to the user telling him that the order status change from waiting to prepering
//We have to update the order status from 0 to 1
$order_id = filterRequest('order_id');
$data=[
    "order_status"=>"1"
];
updateData("orders",$data,"order_id='$order_id' And order_status=0");
$user_id= filterRequest('user_id');
sendFcmNotification("Hey","You'r order with the id $order_id has been approved and now it's preparing","users$user_id","none","none");
?>