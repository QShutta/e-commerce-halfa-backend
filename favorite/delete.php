<?php
//Will use this to delete the product from the favorite because of:
/*On the products page:
The user removes a product from favorites by specifying 
the user_id and product_id. This is because,
 on the products page, you know which product is being displayed
and which user is logged in,
but you may not know the unique favorite_id
 (the primary key in the favorites table). */
include "../connect.php";

$user_id = filterRequest('user_id');
$product_id = filterRequest('product_id');

deleteData("favorite","product_id=$product_id AND user_id=$user_id");
?>