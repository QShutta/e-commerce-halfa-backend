<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "../connect.php";

//Why did we delete according to the address_id not the user_id?
//Because of the user may have more than one address:one for home,job,jim...
//So when deleting will not delete all of the addresses that related to that 
//spacfic user.
$address_id = filterRequest('address_id');

deleteData(
    "address", "address_id=$address_id"
);

?>
