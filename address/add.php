<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "../connect.php";
//Why did the course instructor create the address table while he can add the address in the user table.
//Because here in this e-commerce app the user may have more than one address:Home,job,jim
//But if we are in another application and the user have one address we will just add the address
//to the users table without the need of creating a new table.
$user_id    = filterRequest('address_user_id');
$address_name  = filterRequest('address_name');
$user_city  = filterRequest('address_city');
$user_street= filterRequest('address_street');
$lat        = filterRequest('address_lat');
$lang       = filterRequest('address_lang');

$data = [
    "address_user_id" => $user_id,
    "address_name"    =>  $address_name,
    "address_city"    => $user_city,
    "address_street"  => $user_street,
    "address_lat"     => $lat,
    "address_lang"    => $lang
];

insertData("address", $data);
?>
