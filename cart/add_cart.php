<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "../connect.php";
//Before of 101.
//الاستاذ اتكلم عن الفرق بين المفضلة والسلة :
//قال انو السلة بتختلف عن المفضلة في انو الزول في السلة ممكمن يكون ضايف المنتج اكتر من مرة .يعني 
//هنا مثلا في سلة المشتريات ممكن يكون عندنا 3 من نفس المنتج
//عاشن كدة نحنا حنعمل chekc 
//اذا كان المنتج غير مضاف حنعمل عملية insert 
//واذا كان مضاف حنعمل عملية update


$userId = filterRequest('cart_user_id');
$productId = filterRequest('cart_product_id');
//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
//انا السطر دة كاتبو مع الاستاذ لكن انا شايف حاليا مافي ليهو داعي؟لية ؟
//بي بساطة لانو نحن ما مستخدمنو في اي شي نحن قاعدين نعمل اضافة مباشرة من غير ما نتحقق سواء كان المنتج
//موجود في المفضلة او لا .
//الا اذا عدلنا عليهو في المستقبل .
// $count=getData("cart" ,"cart_product_id=? And cart_user_id=?",array($productId,$userId),false );
//What is the job of this condtion 
//This condition checks if the product is already in the cart.
//if it's already exist we will just update the quantity
//otherwise we will insert a new product to the cart table.

    $data=array(
        "cart_user_id"=>$userId,
        "cart_product_id"=>$productId
    );
    insertData("cart",$data);

//The course instrucor said that there is 2 ways to achive our goal(adding product to cart and the product is already exist)
//and get the quantity of the product 
//MYSQL
//PHP
//1-MYSQL:will insert the product many times then will get count of the repeated products and create a view.
//2-PHP:will just check if the product is already in the cart and update it if it is already added.
//Here in this file we will add proudct tot he cart using "MYSQL" not the PHP method.
?>
