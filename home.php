<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// تضمين ملف الاتصال بقاعدة البيانات
include "connect.php";
//Before of 173

// إنشاء مصفوفة فاضية لتجميع البيانات من أكثر من جدول
$allData = array();
 //لية اضفنا staus=success
//عشان 
//in the home_controller.dart 
//معتمدين علي وجود staus
// عشان نعرف إذا كانت العملية نجحت ولا لأ
// لو ما ضفناها، ممكن التطبيق يواجه مشكلة في التعامل مع البيانات
$allData['status']= "success";
// استخدمنا 
// false 
// في الباراميتر الرابع 
// ($json)
// عشان نمنع الدالة من طباعة البيانات بصيغة 
// JSON مباشرة
// وبدلاً من ذلك، ترجع البيانات كمصفوفة 
// PHP
// نقدر نستخدمها جوه الكود
$catogeries = getAllData("catogeries", null, null, false);

//نحنا هنا يا مكتب حنخزن اكتر من بيانات يعني نحنا لي عملنا الجوطة والجوباك دة كلو يا مكتب؟
// عشان نرجع بيانات من جداول مختلفة في نفس الوقت
$allData['catogeries'] = $catogeries;

$user_id = filterRequest('user_id');

//قلنا ليهو جيب جميع المنتجات العليها خصم.



// $products = getAllData("products1_view", "product_discount!=0", null, false);
// $allData['products'] = $products;
$stmt = $con->prepare(
    " -- الجزء الأول: جلب المنتجات المفضلة فقط
    SELECT products1_view.*, 1 AS fav ,
    (product_price-(product_price*product_discount/100)) As priceAfterDiscount
     FROM products1_view 
     INNER JOIN favorite 
     ON favorite.product_id = products1_view.products_id 
     AND favorite.user_id = $user_id 
     where product_discount!=0
     
     UNION
     SELECT products1_view.*, 0 AS fav ,
     (product_price-(product_price*product_discount/100)) As priceAfterDiscount
     FROM products1_view
     where 
     product_discount!=0
     and  products_id NOT IN (
         SELECT products1_view.products_id 
         FROM products1_view 
         INNER JOIN favorite 
         ON favorite.product_id = products1_view.products_id 
         AND favorite.user_id = $user_id
     )"
);




$stmt->execute();
$products=$stmt->fetchAll(PDO::FETCH_ASSOC);
$allData['products']=$products;









//to add the top selling products to the getAllData map.
$topSelling=getAllData("top_selling_view", "1=1", null, false);

$allData['topSelling']=$topSelling;

$texts=getAllData("settings", "1=1", null, false);

$allData['texts']=$texts;


echo json_encode($allData);
?>
