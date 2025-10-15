<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "../../connect.php";
//Beofre of 153
//What is the prupose of this file?
//The purpose of this file is that when the user made an order this order will be waiting for approvel 
//when the admin click on the approve button the order status will be change from 0 to 1 .
//Also there is a nofication will apear to the user telling him that the order status change from waiting to prepering
//We have to update the order status from 0 to 1
$order_id = filterRequest('order_id');
//This because of we want to change the status from 0 to 1
$data=[
    "order_status"=>"1"
];
updateData("orders",$data,"order_id='$order_id' And order_status=0");
//after of that we have update the status of the order we have to send notfication to the user to tell him what is going on .
$user_id= filterRequest('user_id');
//Know will use this function to send the notfication.
sendFcmNotification(
  "Order Update",
  "Your order #$order_id has been accepted and is now being prepared.",
  "users$user_id",
  "none",
  //pageName.
  //هنا نحنا ما قاصدين بي اسم الصفحة ياهو الاسم القاعد في الفرونت ظط يعني المهم اي حاجة تكون مفهومة لي في الفرونت توريني الاشعار 
  //دة مقصود بيهو اي صفحة بالتحديد.
  "order"
);

?>