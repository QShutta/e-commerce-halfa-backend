<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "../connect.php";

$address_id    = filterRequest('address_id');
$address_name  = filterRequest('address_name');
$user_city  = filterRequest('address_city');
$user_street= filterRequest('address_street');
$lat        = filterRequest('address_lat');
$lang       = filterRequest('address_lang');
if(!$address_id){
    echo json_encode(["status"=>"failure", "message"=>"address_id missing"]);
    exit;
}
//نحنا حنحدث شنو ؟حنحدث علي المدينة والشارع والاحداثي السيني والصادي لكن اعمل حسابك حاجات زي 
//user_id and address_id can't be updated.
$data = [
    "address_name"  =>$address_name,
    "address_city"   => $user_city,
    "address_street" => $user_street,
    "address_lat"    => $lat,
    "address_lang"   => $lang
];
//Why did we use the address_id no the user_id
//Because of the user may have more than one address :one for the home,job...
updateData("address", $data, "address_id=$address_id");
?>
