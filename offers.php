<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//Before of 115
// تضمين ملف الاتصال بقاعدة البيانات
include "./connect.php";
//Before of 85

$stmt = $con->prepare(
    " SELECT products1_view.*, 1 AS fav ,
    (product_price-(product_price*product_discount/100)) As priceAfterDiscount
     FROM products1_view 
     INNER JOIN favorite 
     ON favorite.product_id = products1_view.products_id 
     And product_discount!=0
     UNION
     SELECT products1_view.*, 0 AS fav ,
     (product_price-(product_price*product_discount/100)) As priceAfterDiscount
     FROM products1_view
     WHERE
       product_discount!=0 And
      products_id NOT IN (
         SELECT products1_view.products_id 
         FROM products1_view 
         INNER JOIN favorite 
         ON favorite.product_id = products1_view.products_id  )"
);

$stmt->execute();
$products=$stmt->fetchAll(PDO::FETCH_ASSOC);
$count=$stmt->rowCount();
if($count>0){
  echo json_encode(array("status" => "success", "data" => $products));
}else{
echo json_encode(array("status" => "failure"));
}
?>
