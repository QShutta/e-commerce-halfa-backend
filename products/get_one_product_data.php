<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//Before of 115
// تضمين ملف الاتصال بقاعدة البيانات
include "../connect.php";
$product_id = filterRequest('products_id');
getData("products","products_id=?",[$product_id],$json=true);
?>
