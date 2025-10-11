<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    include '../connect.php';
    // عندما يقوم المستخدم بإنشاء حساب في التطبيق، سيُدخل بريدًا إلكترونيًا
    // نحن نحتاج إلى التأكد أن هذا البريد يعود فعلاً للمستخدم
    // لذلك سنرسل له رسالة تحقق (Verification Email) إلى بريده الإلكتروني
    // تحتوي على رمز تحقق (Verification Code) أو رابط
    // نقوم بحفظ هذا الرمز في قاعدة البيانات مؤقتًا
    // ثم عندما يدخل المستخدم الرمز في التطبيق، نطابقه مع الرمز المحفوظ
    // إذا كان مطابقًا، نؤكد أن البريد تابع له ونعلم النظام بذلك

    // هذه الصفحة خاصة بعملية التحقق من الكود 
    // (Verification Code)
    // المستخدم في واجهة المستخدم 
    // (UI) signup
    // أدخل الكود الذي وصله على الإيميل
    // وغالبًا في نفس الفورم أدخل الإيميل أيضًا، أو تم تخزينه مؤقتًا عند التسجيل
    // نحن هنا نستقبل الإيميل المُرسل من واجهة المستخدم للتحقق مع الكود

    $email = filterRequest('user_email');
    //we need the varfy code from the user
    //then compare it with the code in the database
    $varfy_code = filterRequest('user_varfy_code');


    //here we will are going to check and compare between the varcode that the user entered
    // and the varfy code that we have in the database
    $stmt=$con->prepare("SELECT * FROM users WHERE user_email =? AND user_varfy_code = ?");
    $stmt->execute([$email, $varfy_code]);
    $count=$stmt->rowCount();
    if($count>0){
        //لو المستخدم ادخل الكود بشكل صحيح نحن حنعدل علي قاعدة البيانات بحيث 
        //will change the user_approved column from zero to one.
        $data=array("user_approve"=>1);
        
        //update the user_approved column in the database
        updateData("users", $data, "user_email='$email'");
    }else{
        printFailureMessage( "The verification code is incorrect or the email does not match.");
    }

    ?>