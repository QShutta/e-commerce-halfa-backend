<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "../connect.php";
//The job of this file is to check wither the cupon code is valid or not
//And if it's valid what is the discout that this cupon provide?


//Until know i am counfused about the cupon name?xxxxxxxxxxxxxxxxxxxxx
//What did the course instructor means by that?did he mean the coupon code it self or what?xxxxxxxxxxxxxxxx
// This is the actual code the user types, like "DISCOUNT10" or "SUMMER20".
// We will use this value to check in the database if the coupon is valid
// and to find out what discount it provides.
$coupon_name = filterRequest('coupon_name');
// We must put single quotes around $coupon_name because in SQL
// text/string values must be enclosed in quotes.
// Without the quotes, SQL thinks $coupon_name is a column or variable name,
// not a string, and this will cause an error.
// Example that works:  coupon_name='SUMMER20'
// Example that fails:  coupon_name=SUMMER20

//suppose that if the expire_data is invalid the cupon should not be displayd so what did we have to 
//do to achive that?
$now=date("Y-m-d H:i:s");
//دة هيجيب لي الكوبونات المنتهية بس .
//كل مرة حتيم استخدام ال 
//coupon
//قيمة coupon_count حتنقص . بي واحد
getData("coupon","coupon_name='$coupon_name' AND coupon_expire_date>'$now' AND coupon_count>0");


?>