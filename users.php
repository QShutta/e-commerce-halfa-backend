<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "connect.php";
//حانشئ الملف دة هو شغلو بس يتحصل لي علي جميع بيانا المستخدمين المسجلين عندي في التطبيق
$user_id=filterRequest("user_id");
getData("users","user_id=$user_id");
?>