<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "../connect.php";

$rating_user_id = filterRequest('rating_user_id');
$rating_product_id = filterRequest('rating_product_id');
//This represent the rating value like 1,2,4,4.2....
$rating_value = filterRequest('rating_value');


//First we need to check wither this user has rate this prdouct before of this time or not.
//If he rate the product before of this time we need to just update the on the product_rating.otherwise we need to insert
$checkData=[$rating_user_id,$rating_product_id];
//The checkRating var his value will be either 0 or 1.if it's 0 that measn the user has rate this product before
//and we just need update.otherwise that means this si the first time he rate thsi product and we will insert
//Why did you say json=false?Because of i need to check wither the user has rate the product or not
//and if i put the jason=false.it will not print json and give me either 0 or 1.
$checkRating=getData("product_rating","rating_user_id=? And rating_product_id=?",$checkData,$json=false);

//If checkRating==1 that means the user has rate this product beofre and we should just update the rating
if($checkRating==1){
    $updateData=["rating_value"=>$rating_value];
      //Which case that this endpoint return failure.in case of the same user .try to rate the same product with the same rating value.
    //And this is not wrong you should just handle this in the UI and tell the user that he can't rate the same prdouct with the same val.
    updateData("product_rating",$updateData,"rating_product_id=$rating_product_id AND rating_user_id=$rating_user_id",$json=true);
  
}else if($checkRating==0){
 $data=[
    "rating_user_id"=>$rating_user_id,
    "rating_product_id"=>$rating_product_id,
    "rating_value"=>$rating_value
];
insertData("product_rating",$data,$json=true);
}
//after the user rate the product wither it's new rate or update his rate.
//We have to recaluclat ethe avarge of the rate for all of the users taht rate this product
//عشان نتحصل بشكل دقيق علي متوسط تقييمات جميع المستخدمين للمنتج المعين .
//Why did you ge tthe rating_value jsut according to the product_id not the user_id too?
//عشان نحنا عاوزين نحسب المتوسط بتاع تقييمات جميع المستخدمين للمتج المعين 
$product_avg=$con->prepare("select AVG(rating_value) from product_rating WHERE rating_product_id=?");
$product_avg->execute([$rating_product_id]);
//fetchColumn() will only get one column from the first (or next) row.
//After calling it once, if you call it again, it moves to the next row and gets the same column from there.
$avgVal=$product_avg->fetchColumn();
$updateData2=["product_rating"=>$avgVal];
updateData("products",$updateData2,"products_id=$rating_product_id",$json=false);
?>