<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "connect.php";
$notfication_user_id= filterRequest('notfication_user_id');
getAllData("notfication","notfication_user_id=? ORDER BY notfication_id  DESC",[$notfication_user_id],true);
?>