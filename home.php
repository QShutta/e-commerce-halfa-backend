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
//قلنا ليهو جيب جميع المنتجات العليها خصم.
$products = getAllData("products1_view", "product_discount!=0", null, false);
$allData['products'] = $products;
//to add the top selling products to the getAllData map.
$topSelling=getAllData("top_selling_view", "1=1", null, false);

$allData['topSelling']=$topSelling;

$texts=getAllData("settings", "1=1", null, false);

$allData['texts']=$texts;


echo json_encode($allData);
?>
