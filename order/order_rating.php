<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "../connect.php";
//the exist of the order_user_id 
//هنا انا خاتيهو من ناحية امنية بحتة ما من نحية تقينة.لانو لو خليتو دي ثقرة ممكن يستخدموها attackers
$order_user_id = filterRequest('order_user_id');
$order_id  = filterRequest('order_id');
//This represent the rating value like 1,2,4,4.2....
$order_rating_value = filterRequest('order_rating_value');
$order_rating_comment = filterRequest('order_rating_comment');


 $data=[
    "order_rating_value"=>$order_rating_value,
    "order_rating_comment"=>$order_rating_comment
];
 updateData("orders",$data,"order_id=$order_id  AND order_user_id=$order_user_id",$json=true);

?>