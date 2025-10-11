<?php
//The job of this php file is to get all of the favorite products that belong to spacfic user
include "../connect.php";

$user_id = filterRequest('user_id');
//What is the diff between using this and using the values?xxxxxxxxxxxxxxxxxxxxxxxxxx
// getAllData("myFavorite","user_id=$user_id");
//and this:

// نستخدم هذه الطريقة لمنع هجمات 
// SQL Injection.
// علامة الاستفهام (?) تعمل كـ 
// "placeholder"
//  للقيمة، مما يضمن أن يتم التعامل مع 
// $user_id
//  كبيانات فقط، وليس كجزء من استعلام 
// SQL،
// مما يحمي قاعدة البيانات من أي كود ضار قد يتم إدخاله.
getAllData("myFavorite","user_id=?",[$user_id]);
?>