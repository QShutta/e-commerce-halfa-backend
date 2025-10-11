<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "../connect.php";
//نحن هنا بالحذف نحن ممكن يكون عندنا المنتج شنو ؟مضاف يعني عندنا منو مثلا 10 او 12 
//فنحن هنا حنحذف واحد واحد تلو الاخر 
//حنحذف فقط اخر واحد انضاف .

$userId = filterRequest('cart_user_id');
$productId = filterRequest('cart_product_id');
// هنا نحن عاوزين نحذف منتج من جدول 
// cart
// الفكرة: ممكن نفس المنتج يكون مضاف أكثر من مرة (مثلاً 10 مرات)
// لكن نحن ما عاوزين نحذف كل النسخ مع بعض، بل نحذف نسخة واحدة فقط
// لذلك بنحدد 
// cart_id
//  لصف واحد فقط عن طريق استعلام فرعي 
// (subquery)
// الاستعلام الفرعي: (select cart_id from cart where cart_user_id=$userId and cart_product_id=$productId limit 1)
// دا بيرجع صف واحد فقط من 
// cart 
// خاص بالمستخدم 
// (بسبب LIMIT 1)
// وبكده الحذف حيتم لصف واحد فقط بدل ما ينحذفوا كلهم
// النتيجة: الكمية تنقص واحدة (من 10 → 9) بدل ما تختفي كلها
//What is the job of limit 1?
//The job of limit 1 is to ensure that only one specific record is selected for deletion,  
// rather than all matching records.

//why did you said the cart_order=0 نحنا قلنا كدة عشان المنتجات البتتعرض في السلة 
//Are only the product that there is =0.if the id is not =0 that means it's removed from cart and been taked to 
//order statusتم طلبة او تم شراءة
deleteData(
    "cart",
    "cart_id=(select cart_id from cart where cart_user_id=$userId AND cart_product_id=$productId and cart_order=0 limit 1)" 
);
//Why did we write the cart.cart_order=0 outside of the subquery?
/*If you write cart_order=0 outside the parentheses, SQL gets confused:
Does it mean “delete just the one row that matches the cart_id from the subquery” and also cart_order=0?
Or does it mean “delete all rows that have cart_id = cart_order=0”?
Putting cart_order=0 inside the subquery makes it clear:
SQL will select exactly one row (LIMIT 1) that satisfies all conditions: correct user, correct product, and cart_order=0.
Then DELETE will remove only that row. */

?>
