<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "connect.php";
$response = sendFcmNotification("hi", "How Are You", "users81", "123", "productPage");
if ($response) {
    echo "FCM Response: " . $response;
} else {
    echo "FCM Response: FAILED (check error_log)";
}

?>
