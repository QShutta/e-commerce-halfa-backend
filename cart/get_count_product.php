<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "../connect.php";

$userId = filterRequest('cart_user_id');
$productId = filterRequest('cart_product_id');

// الاستعلام ده هدفه نعرف كم مرة تم إضافة هذا المنتج 
//  بواسطة هذا المستخدم 
// 1. نستخدم 
// COUNT 
// علشان نحسب عدد الصفوف اللي تحقق الشرط 
// (cart_user_id و cart_product_id)
// 2. النتيجة تتخزن في 
// alias اسمه productsCount

$stmt=$con->prepare("SELECT COUNT(cart.cart_id) AS  productsCount FROM cart WHERE cart_user_id=? AND cart_product_id=? and cart.cart_order=0");
$stmt->execute(array($userId,$productId));
// Number of rows returned by the query (useful to check if there is any result at all)
$count = $stmt->rowCount();

// Get the actual count value (عدد المنتجات المضافة بواسطة المستخدم لهذا المنتج)
$data = $stmt->fetchColumn();
if($count>0){
    echo json_encode(array("status"=>"success","data"=>$data));
}else{
    echo json_encode(array("status"=>"success","data"=>"0"));
}
?>