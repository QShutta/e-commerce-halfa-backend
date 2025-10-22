<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "../connect.php";

$order_id = filterRequest('order_id');
//Why did we bring the data accoding just to the order_id?
//the user may have more than one order.but the order belong only to one user .
//Because of that we filter using the order_id
//You can normally add the condition with bot order_id and user_id 
//لكن ما حيفيد بي اي شي ولا حيضطر بي شي وزيادة كود ساي .
getAllData("order_details_view","cart_order=?",[$order_id]);
?>