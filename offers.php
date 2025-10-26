<?php
// Error reporting (Good practice for development)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//Before of 165
include "connect.php";
//The goal of this file is to be able to get all of the products that there is an offer on them


//Notice that here we bring the data of all of the products that they don't belong to any spcfic user.we just want to display
//All of the products that there is a discount on them.


//Why did we bring the data fromt he products table instead of bring it from the product_view?
//Because of here in the offers page we just want to display all of the products that there is a discount 
//over them.we don't cart about that this product is belonging to that catogery.
//So bring the data directly from the products table benfits are:
//Make the perfomance faster because there is no join operation here .
//Make the code simpler and easy to understand.
getAllData(
    "products",
    "product_discount!=0"
);

?>