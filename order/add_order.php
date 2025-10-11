<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "../connect.php";

//Before of 143
$order_user_id = filterRequest('order_user_id');
$order_price = filterRequest('order_price');
$order_shiping_price = filterRequest('order_shiping_price');
$order_delivery_type = filterRequest('order_delivery_type');
$order_payment_method = filterRequest('order_payment_method');
$order_address = filterRequest('order_address');
//I need the coponid for what?
//To specify is this coupon is valid or not:that means did the coupon_count>0 and the couopn_expire_date>0
//From where did we get the couponid?//Ffrom the flutter app
$order_couponId = filterRequest('order_coupon');
//The represent the dicount precentage like 10% or 20%
//There is no column in the order table called "order_discount"
// we are goin to get his value from the flutter app
//and use this value just to calculate the total price
$order_discount=filterRequest('order_discount');
//The total price is the sum of the order price and the shipping price and if there is a coupon
//حنخصم قيمة الكوبون من السعر الكلي .
//Totoalprice :نحنح قاعدين نجيب قيمتو بي عملية حسابية فما بنحتاج انو نستلمو من المستخدم.
//totalprice will be produced from this php file using calculation operation

//This represent the total price after applying the shipping price and before of applying the discount.

  //
$total_price = $order_price + $order_shiping_price;

//why did we do this?
//When the user click on the checkoutout button we have to make the sure that
//the couon_count>0 and the coupon_expire_date is valid
//So we will do the same check that we did in the checkcupon.php file
$now=date("Y-m-d H:i:s");
//Why did we set the json=false?
//If we make it true it will produce json and 2 json will cause error.

//We just want to check if the coupon is valid or not
$couponCheck=getData("coupon","coupon_id='$order_couponId' AND coupon_expire_date>'$now' AND coupon_count>0",null,false);
//The getdata function will return 1 if the coupon is valid
//And it will return 0 if the coupon is not valid
//In case of the coupon is valid we will apply the discount on the total price
if($couponCheck==1){
  //That means the coupon is valid
  //This was the old code was like this:
  //هنا أنت تخصم الخصم على السعر الكلي (الطلب + الشحن).
  //يعني حتى الشحن يدخل في الخصم.
//in this method to calculate the discound we use this formula:
  //($total_price*($order_discount/100))  here to calculate the discount
  //بنخصم السعر من السعر الاصلي شامل سعر الشحن لانو لمن نجي نتحصل علي 
  //totalprice=totoalprice+shipping price
  // $total_price=$total_price-($total_price*($order_discount/100));
  //But we figure out that most of the e-commerce app implement the discount on the goods only not on the shipping price
  //So we will apply the discount on the order price only not on the shipping price
  //هنا أنت تخصم الخصم على سعر الطلب فقط (المنتجات)،
// الشحن ما يدخل في الخصم.
// هذا هو الشائع في أغلب المتاجر الإلكترونية (الخصم على البضاعة فقط).
//To get the discount we use this this forumla:
  //($order_price*($order_discount/100)) بتوريني نحن حنخصم كم من السعر . بي كدة نحن بنتحصل علي 
  //الخصم فقط من سعر المنتج ما معاهو سعر الشحن .
$total_price=$total_price-($order_price*($order_discount/100));
$stmt=$con->prepare("update coupon set coupon_count=coupon_count-1 where coupon_id=$order_couponId");
$stmt->execute();
}

if($order_delivery_type==1){
  $order_shiping_price=0;
}

$data=[
    'order_user_id' => $order_user_id,
    'order_price' => $order_price,
    'order_total_price' => $total_price,
    'order_shiping_price' => $order_shiping_price,
    'order_delivery_type' => $order_delivery_type,
    'order_payment_method' => $order_payment_method,
    'order_address' => $order_address,
    'order_coupon' => $order_couponId,
    'order_date_create' => date("Y-m-d H:i:s"),
];
//Why ddi we set the 3rd parameter to false ?Becuase of we don't want to print now .the print should be
//After the update.after the update of the cart_order status from 0 to the order id.
$count=insertData("orders",$data,false);











//طبعا موضوع الاضافة واضح ما عاوز اتينن ثلاثة .الجزئية الجاية دي هي العاوزة تركيز .

//عاوز يسوي شنو 
//What is the overall goal?
//Our goal that when the user insert order.that we have to update on the "cart" table in the cart_order
//column to change the value of the column from 0 to the id of the last insearted order.
//To achive that we have to make sure that the inseartion process completet succfuly why?
//Because of if the inseartion did not comopletet succfuly.you will update the cart_order by what?did you get it
//that means we have to make sure that the inseation success then update
if($count>0){
    //لية جبنا لاmax order_id
    //we made a column prevously in the cart table called cart_order  this column his value could be
    //0 or id of the last insearted column
    //How to get the value of the last insearted column?
    //By this stament:
    //بي بساطة عملنا الاستعلام دة عشان نتحصل علي 
    // id of the last insearted element
    //Max() حتجيب ليك اكبر عنصر قاعد في العمود المعين
  $stmt=$con->prepare("select Max(order_id) from orders");
    $stmt->execute();
    //بي كدة حنتحصل علي ال id 
    //of the max(order_id) and by this way we will get the id of the last inseretd order.
   $maxId= $stmt->fetchColumn();
   $data=["cart_order"=>$maxId];
   //after we get the id of the last insearted order  we will update the cart_order column
   //In the cart table so it's value will change from 0 to the id of the last insearted order



   //بي بساطة كدة ومن غير فلسفة لية قلنا ليهو  حدث بناء علي 
   //cart_order=0
   //in the cart_view we diplay the prdoucts that is added to cart  only if the value of cart_order=0
   //after that we  add the order to the order table  we don't want this product to e displayed in the cart
   //page so i will change his status from 0 to the id of the last insearted order
   //by this way the product will not be displayed in the cart page 
   updateData("cart",$data,"cart_user_id=$order_user_id and cart_order=0");
}



?>