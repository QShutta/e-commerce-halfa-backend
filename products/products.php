<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//Before of try to fix the error of thome fav issue.
// تضمين ملف الاتصال بقاعدة البيانات
include "../connect.php";
//Before of 85
$catogery_id = filterRequest('product_catogery');
$user_id = filterRequest('user_id');

//  getAllData("products", "product_catogery = $catogery_id", null, true);



//شرح جزئية الdiscount
//--------------------------------------------------------------------------------------------
// -- هنا نحسب سعر المنتج بعد الخصم: عندنا مثال انو سعر المنتج هو 1000 والخصم هو 10% في المية
// -- 1. نحسب قيمة الخصم: 
// product_price * product_discount / 100
// --    مثال:
//  1000 * 10 / 100 = 100
// -- 2. نطرح الخصم من السعر الأصلي:
//  1000 - 100 = 900
// -- 3. نستخدم 
// "as priceWithDiscount"
//  لإعطاء اسم لهذا العملية الحسابية بحيث يظهر كعمود باسم 
// priceWithDiscount
//--------------------------------------------------------------------------------------------



// الاستعلام الرئيسي لجلب جميع المنتجات للفئة المطلوبة
// ويحدد إذا المنتج مفضّل للمستخدم أم لا
//here i have to statments the secound one will bring the products that in the favoirte
//and the secound will bring the products that not in the favoirte.
//Then merge them with union:union حتدمج الاستعلامين مع حذف التكرارات
$stmt = $con->prepare(
    " -- الجزء الأول: جلب المنتجات المفضلة فقط
    SELECT products1_view.*, 1 AS fav ,
    (product_price-(product_price*product_discount/100)) As priceAfterDiscount
     FROM products1_view 
     INNER JOIN favorite 
     ON favorite.product_id = products1_view.products_id 
     AND favorite.user_id = $user_id 
     WHERE products1_view.product_catogery = $catogery_id

     UNION

    -- الجزء الثاني: جلب المنتجات الغير مفضلة
     SELECT products1_view.*, 0 AS fav ,
     (product_price-(product_price*product_discount/100)) As priceAfterDiscount
     FROM products1_view
     WHERE products1_view.product_catogery = $catogery_id
     AND products_id NOT IN (
         SELECT products1_view.products_id 
         FROM products1_view 
         INNER JOIN favorite 
         ON favorite.product_id = products1_view.products_id 
         AND favorite.user_id = $user_id
     )"
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
