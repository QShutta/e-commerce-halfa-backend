<?php
//Before of 105
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "../connect.php";
//Weal said that this backend file will not just bring the products that 
//Is added to the cart just.It will also bring the total price and the quantity required from this product.

$userId = filterRequest('cart_user_id');


//the data var will contain the product details like what?id,name,price,description...

$data=getAllData("cart_view","cart_user_id=$userId",null,false);
//We want to get how many products are in the cart for this user and the subtotal price.
//The orignal price of the product will only used in the ui to display how much the user save from the original price.
//but here we need the product price after discount
$stmt=$con->prepare("SELECT SUM(productPriceAfterDiscopunt) as subTotal,SUM(productCount) as product_count FROM cart_view
WHERE cart_view.cart_user_id=? and cart_order=0");
$stmt->execute([$userId]);
//This var will contain the total price of the products that the user put them in the cart.
//Also it will contain the quantity of the products that the user select them.
// NOTE: We use fetch() here instead of fetchAll()
// because the query with SUM() + GROUP BY returns only ONE row (total price and total count).
// fetchAll() is only needed when the query returns MULTIPLE rows (like listing all products).
$data_count_price=$stmt->fetch(PDO::FETCH_ASSOC);
echo json_encode(array("status"=>"success","data"=>$data,"count_price"=>$data_count_price));
?>