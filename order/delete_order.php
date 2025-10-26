<?php
// Error reporting (Good practice for development)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "../connect.php"; // Assumes this includes your database connection and functions like filterRequest, deleteData.
//The job of this file is to allow the user to remove the order before of the admin approve the order.
//that means the user can only remove the order if the order_status=0(which means the order is waiting for approvel)


$order_id = filterRequest('order_id');

// 2. SECURELY get the user's ID (ASSUMPTION: This comes from a secure session variable, NOT the request)
// **CRITICAL:** You must replace this with your actual session retrieval logic
$user_id = filterRequest('user_id'); 

// 3. Perform the secure and conditional deletion
// The WHERE clause MUST include three conditions:
// a) order_id: To target the specific order.
// b) user_id: To ensure the user owns the order (SECURITY).
// c) order_status = 0: To ensure the order is in the 'waiting for approval' state (LOGIC).

deleteData(
    "orders",
    "order_id = $order_id AND order_user_id = $user_id AND order_status = 0"
);

?>