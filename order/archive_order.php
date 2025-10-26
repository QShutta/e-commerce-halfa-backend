<?php
// Error reporting (Good practice for development)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//Before of 165
include "../connect.php"; // Assumes this includes your database connection and functions like filterRequest, deleteData.
//The goal of this filke is to change the order staus to archive.
//When the dilivery man arrive the order to the user.or the user recive the order from the store.
//the order_status should be=4 .means the order_status is archived
$order_id = filterRequest('order_id');

// 2. SECURELY get the user's ID (ASSUMPTION: This comes from a secure session variable, NOT the request)
// **CRITICAL:** You must replace this with your actual session retrieval logic
$user_id = filterRequest('user_id'); 
$data=[
    'order_status' => 4
];
updateData(
    "orders",
    $data,
    "order_id = $order_id AND order_user_id = $user_id AND order_status = 3" 
);

?>