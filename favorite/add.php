<?php
include "../connect.php";

$user_id = filterRequest('user_id');
$product_id = filterRequest('product_id');
$data=array(
    "user_id"=>$user_id,
    "product_id"=>$product_id,
);
insertData("favorite",$data,true);
?>