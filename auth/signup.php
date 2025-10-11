<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "../connect.php";

// جلب بيانات المستخدم من الطلب بطريقة آمنة
$user_name = filterRequest('user_name');
$user_email = filterRequest('user_email');
// تشفير كلمة المرور باستخدام sha1 (ملاحظة: يُفضل استخدام طرق أقوى مثل password_hash)
$user_password = sha1(filterRequest('user_password'));;

// توليد رمز تحقق عشوائي مكون من 6 أرقام
$user_varfy_code = rand(100000, 999999); 


// نتحقق إذا كان البريد الإلكتروني مسجل مسبقًا في قاعدة البيانات
$stmt = $con->prepare("SELECT * FROM users WHERE user_email = ? ");
$stmt->execute([$user_email]);
//The job of the fetch method is to bring the result of one row just one row.
$user = $stmt->fetch();
$rowCount=$stmt->rowCount();
//بنتحقق هل عندنا مستخدم عندمسجل بي نفس الايميل
if ($rowCount>0) {
  //لو عندنا مستخدم بي نفس الايميل دة .and it's user_approve==1 that means the user is signup completely
    if ($user['user_approve'] == 1) {
        // المستخدم مفعل بالفعل
        printFailureMessage("The email is already registered, please try another one.");
    } else {
        // المستخدم غير مفعل → تحديث بياناته
        // $update = $con->prepare("
        //     UPDATE users 
        //     SET user_name = ?, 
        //         user_password = ?, 
        //         user_varfy_code = ?, 
        //         user_created_at = ?
        //     WHERE user_email = ?
        // ");
        // $update->execute([
        //     $user_name,
        //     $user_password,
        //     $user_varfy_code,
        //     date('Y-m-d H:i:s'),
        //     $user_email
        // ]);

         $data = [
        'user_name' => $user_name,
        'user_email' => $user_email,
        'user_password' => $user_password,
        'user_varfy_code' => $user_varfy_code,
        'user_approve' => 0,
        'user_created_at' => date('Y-m-d H:i:s'),
    ];
    updateData("users",$data,"user_email='$user_email'");
        sendEmail(
            $user_email, 
            "Email Verification", 
            "Hello,\n\nYour new verification code is: $user_varfy_code\n\nPlease enter this code in the app to verify your email.\n\nBest regards,\nShutta Team"
        );

        // printSuccessMessage("A new verification code has been sent to your email.");
    }
} else {
    // الإيميل غير موجود → إنشاء مستخدم جديد
    $data = [
        'user_name' => $user_name,
        'user_email' => $user_email,
        'user_password' => $user_password,
        'user_varfy_code' => $user_varfy_code,
        'user_approve' => 0,
        'user_created_at' => date('Y-m-d H:i:s'),
    ];

    insertData("users", $data);

    sendEmail(
        $user_email, 
        "Email Verification", 
        "Hello,\n\nThank you for signing up.\n\nYour verification code is: $user_varfy_code\n\nPlease enter this code in the app to verify your email.\n\nBest regards,\nShutta Team"
    );

}
?>