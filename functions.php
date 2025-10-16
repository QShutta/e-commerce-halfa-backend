<?php
 define("MP",1048576);
 // Make sure you have the google/auth library installed via Composer
require_once 'vendor/autoload.php';

use Google\Auth\Credentials\ServiceAccountCredentials;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

//This method is for security purpose.
//In summary, the filterRequest function takes a user input
// (specified by $requestName), filters out any potential harmful
// content by converting special characters to HTML entities and removing HTML/PHP tags.
function filterRequest($requestName){
    return  htmlspecialchars(strip_tags($_POST[$requestName]));
}

function failureMessage($message){
    echo json_encode(array("status"=>"failure","message"=>$message));
}
/*
  الفرق بين getAllData و getData:

  ✅ getAllData:
  - ترجع كل الصفوف من الجدول (مثلاً كل المنتجات أو التصنيفات).
  - تقدر تضيف شرط لو عايز (WHERE).
  - فيها متغير $json يحدد طريقة الطباعة:
      - لو true: تطبع البيانات بصيغة JSON (مفيد للـ API).
      - لو false: ترجع البيانات كمصفوفة PHP فقط (مفيد لو عايز تستخدمها داخل كود PHP).

  ✅ getData:
  - ترجع صف واحد فقط (أول صف يطابق الشرط).
  - تستخدمها لما تبحث عن عنصر محدد (مثلاً مستخدم حسب الإيميل).
  - دايمًا تطبع النتيجة بصيغة JSON، وما فيها خيار $json.

  📌 باختصار:
  - getAllData = لجميع البيانات أو عدة صفوف.
  - getData = لصف واحد فقط.
*/


//Why did you say $json=true?
//because of in the case of i want to use this function to get the "catogeries,products" for the home page
//i wnat to change the way of the print.
//The getalldata function will bring all of the rows that exist in the required table.
function getAllData($table, $where = null, $values = null,$json=true)
{
    //connection to the database instance
    global $con;
    //Array to save the data that will come from the db in it .
    $data = array();
    //statment of getting the data from the datbase.
    //لو المستخدم خت "وير" في الحالة دي حنرجع ليهو البيانات حسب شرط معين ,غير كدة حنرجع ليهو كل البيانات .
    if($where==null){
        $stmt = $con->prepare("SELECT  * FROM $table");
    }else{
        $stmt = $con->prepare("SELECT  * FROM $table WHERE   $where");
    }
    $stmt->execute($values);
    //This to bring the data in associative format
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $count  = $stmt->rowCount();
    /*
 * هنا عاملين شرط على المتغير 
 * $json 
 * عشان نحدد طريقة إرجاع البيانات من الدالة.
 * 
 * لما يكون 
 * $json = true
 *  (الافتراضي)، الدالة حتطبع النتيجة بصيغة 
 * JSON.
 * دا مفيد جداً لما الدالة تستخدم في 
 * API أو في تطبيقات زي 
 * React, Flutter, 
 * أو أي مكان بيحتاج بيانات بصيغة JSON.
 * 
 * أما لو 
 * $json = false، 
 * الدالة ما بتطبع شيء، لكن بترجع البيانات كمصفوفة 
 * PHP عادية.
 * دا بيسمح لينا نستخدم البيانات جوه كود 
 * PHP 
 * نفسه، زي في صفحات الإدارة أو أي مكان عايزين نتعامل مع البيانات بشكل مباشر.
 * 
 * يعني الشرط دا بيخلينا نتحكم في شكل النتيجة حسب استخدامنا.
 * فلو دايرين نرسل البيانات للواجهة أو لتطبيق خارجي بنستخدم JSON،
 * ولو عايزين نشتغل بيها جوه الخادم بنرجعها كمصفوفة.
 */
    if($json==true){
        if ($count > 0){
            echo json_encode(array("status" => "success", "data" => $data));
        } else {
            echo json_encode(array("status" => "failure"));
        }
         
    return $count;
    }else if($json==false){
        if ($count > 0){
          return array("status" => "success", "data" => $data);
        } else {
           return  array("status" => "failure");
        }
    }
   
}
//The getData fun will bring only one 
//row from the table even if the table contain more than one row.
//الدالة دي بتستخدم عشان تجيب بيانات من جدول معين في قاعدة البيانات.
function getData($table, $where = null, $values = null,$json=true)
{
    global $con;
    $data = array();
    $stmt = $con->prepare("SELECT  * FROM $table WHERE   $where ");
    $stmt->execute($values);
    // fetch() ترجع صف واحد فقط من النتائج كمصفوفة ترابطية (عمود => قيمة)
    // fetchAll() ترجع جميع الصفوف من النتائج كمصفوفة من مصفوفات ترابطية
    // كلاهما يرجع كل الأعمدة إلا إذا حددت أعمدة معينة في جملة SQL
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $count  = $stmt->rowCount();
    //What is the benfit of this condition?
    //This condition checks if the $json variable is true.
    //If it's true, the function will return the data in JSON format.and print the status code:success,failure.
    //If it's false, will return حنعرجع عدد الصفوف الحترجع لينا .
    //لية نحن عملنا كدة؟
    //لانو نحنا حنستخدم الدالة دي في ملف 
    // php 
    // اخر 
    //واحنا عارفين انو ملف ال 
    // php
    //  مفترض يحتوي علي 
    //json
    //  واحد فقط يعني نطبع 
    // json 
    // واحد فقط 
    //فلو بقي في اكتر من 
    // json it will cause an error
if($json==true){
        if ($count > 0){
            echo json_encode(array("status" => "success", "data" => $data));
        } else {
            echo json_encode(array("status" => "failure"));
        }
}else{
    return $count;
}
    
}


function printFailureMessage($message){
    //This method is use to print the failure message in json format.
    echo json_encode(array("status"=>"failure","message"=>$message));
}

function printSuccessMessage($message){
    //This method is use to print the failure message in json format.
    echo json_encode(array("status"=>"success","message"=>$message));
}


function result($count, $successMessage, $failureMessage) {
    if ($count > 0) {
        printSuccessMessage($successMessage);
    } else {
        printFailureMessage($failureMessage);
    }
}



//What the fcack why did he set the json =true?xxxxxxxxxxxxxxxx
function insertData($table, $data, $json = true)
{

     // هنا بنستخدم 
     // global $con
     // عشان نقدر نستخدم الاتصال بقاعدة البيانات 
     // $con
     //  اللي اتعمل في ملف 
     // connect.php
     //  في الدالة دي.
    global $con;
     // هنا بنمشي على كل عنصر في المصفوفة 
     // $data
    // $field
    //  حيكون اسم العمود 
    // (مثلاً name أو age)
 
    foreach ($data as $field => $v)
    // بنضيف قبل اسم العمود علامة ":" ونخزن الناتج في مصفوفة جديدة اسمها 
    // $ins
        $ins[] = ':' . $field;

    // هنا بنحوّل مصفوفة 
    // $ins
    //  (مثلاً [":name", ":age"])
    // إلى نص مفصول بفواصل 
    // ":name,:age"
    $ins = implode(',', $ins);

    // هنا بنجيب أسماء الأعمدة 
    // (يعني المفاتيح من المصفوفة $data)
    //  ونحولهم إلى نص مفصول بفواصل 
    // "name,age"
    $fields = implode(',', array_keys($data));
 // هنا بنكتب جملة 
 // SQL
 //عشان ندخل بيانات في جدول معين.
// $table
//  هو اسم الجدول، مثلاً 
// "users"
// $fields 
// هي أسماء الأعمدة، مثلاً 
// "name,age"
// $ins
//  هي أماكن البيانات الفاضية، مثلاً 
// ":name,:age"

// مثال:
// "INSERT INTO users (name,age) VALUES (:name,:age)"

// prepare() هنا بيجهز الجملة دي عشان ننفذها بعدين.
// يعني بنقول للـ PHP: "جهز الجملة دي، لكن ما تنفذهاش دلوقتي"

// $stmt = $con->prepare($sql);

    $sql = "INSERT INTO $table ($fields) VALUES ($ins)";

    $stmt = $con->prepare($sql);
    foreach ($data as $f => $v) {
           // ربط القيمة 
           // ($v) 
           // بالمتغير الوهمي 
           // (مثل :name)
           //  في الجملة المُعدة.


        // ':' . $f
        //  يُنشئ اسم المتغير الوهمي 
        // (مثل :name)
        //  بإضافة ":" إلى اسم العمود 
        // ($f).
        // $v
        //  هي القيمة الفعلية 
        // (مثل "John" أو 25) 
        // المراد إدخالها.
        // هذا السطر يضمن إدخال البيانات بأمان لمنع هجمات الحقن البرمجي 
        // (SQL Injection).
        $stmt->bindValue(':' . $f, $v);
    }
    $stmt->execute();
    $count = $stmt->rowCount();
  
    if ($json == true) {
        if ($count > 0) {
            echo json_encode(array("status" => "success","data"=>$data));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }
    //لو كانت 
    // false
    // ، يعني ما داير ترجع رد بصيغة 
    // JSON،
    //  فالكود ما حيطبع أي حاجة للمستخدم.
//لكن برضو حيرجع عدد الصفوف المتأثرة 
// ($count) 
// من الدالة، يعني ممكن تستخدمها داخليًا في الكود بدون طباعة أي شيء.

    return $count;
}

//We replace the mail mehtod with phpMailer why?
//لأن 
// mail()
//  أحياناً ما تشتغل في السيرفرات المشتركة.
function sendEmail($to, $subject, $message)
{
    require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
    require_once __DIR__ . '/PHPMailer/src/SMTP.php';
    require_once __DIR__ . '/PHPMailer/src/Exception.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer();
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Change to your SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'abolkasm11@gmail.com'; // Change to your SMTP username
        $mail->Password   = 'xgvlzlqtarkifhpx'; // Change to your SMTP password
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        //Recipients
        $mail->setFrom('test@qasimshutta.shop', 'Shutta Team');
        $mail->addAddress($to);

        //Content
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        return true;
    } catch (Exception $e) {
        // You can log the error or handle it as needed
        return false;
    }
}



function updateData($table, $data, $where, $json = true)
{
    global $con;
    $cols = array();
    $vals = array();

    // هنا بنمشي على كل عنصر في المصفوفة
    // $data
    //  (اللي هي البيانات الجديدة اللي عايزين نحدثها)
    // $key
    //  هو اسم العمود
    // (مثلاً "name" أو "age")

    foreach ($data as $key => $val) {

        // بنضيف اسم العمود مع علامة "=" وقيمة المتغير الوهمي
        // (مثلاً "name = :name")
        // $vals
        //  هي مصفوفة حتحتوي على القيم الفعلية اللي حندخلها في الجدول.
        // $val
        //  هي القيمة الجديدة للعمو
        $vals[] = "$val";
        // هنا بنضيف اسم العمود مع علامة "=" وقيمة المتغير الوهمي
        // (مثلاً "name = :name")
        // هذا السطر يضمن أن كل عمود في الجدول سيتم تحديثه بالقيمة الجديدة.


        $cols[] = "`$key` =  ? ";
    }
    $sql = "UPDATE $table SET " . implode(', ', $cols) . " WHERE $where";

    $stmt = $con->prepare($sql);
    $stmt->execute($vals);
    $count = $stmt->rowCount();
    if ($json == true) {
    if ($count > 0) {
        echo json_encode(array("status" => "success"));
    } else {
        echo json_encode(array("status" => "failure"));
    }
    }
    return $count;
}




//This is a fucnction that we sue to send  notification to the user using firebase cloud messaging.
//What are the pageid,pageNme?
//The pageid,pageName are the data that we will send to the flutter app 
// to navigate to the specific page when the user click on the notification.
//For example if the user click on the notification that contain the product page
// the app will navigate to the product page directly.
function sendFcmNotification(string $title, string $message, string $topic, string $pageId, string $pageName): ?string
{
    // === CONFIGURATION ===
    // Path to your Firebase service account JSON file.
    // الملف دا فيه بيانات سرية بتخلي النظام دا يقدر يتعامل مع Firebase.
   $serviceAccountFile = '/home/qasimshu/domains/qasimshutta.shop/privite_files/service-acount.json';

    // Your Firebase Project ID
    $projectId = 'e-commerce-halfa';
    // =====================

    try {
        // 1. Get Access Token for authentication using Google Auth library
        // الكود دا بيعمل الحكاية دي باستخدام مكتبة Google Auth.
        $credentials = new ServiceAccountCredentials(
            'https://www.googleapis.com/auth/firebase.messaging', // Scope for FCM
            $serviceAccountFile // Path to service account file
        );
        
        // Get OAuth2 token
        $accessToken = $credentials->fetchAuthToken()['access_token']; 

        // 2. FCM HTTP v1 endpoint for sending messages
        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        // 3. Build the notification payload
        $payload = [
            'message' => [
                'topic' => $topic, // Topic to send notification to
                'notification' => [
                    'title' => $title, // Notification title
                    'body' => $message, // Notification body
                ],
                'data' => [ // Custom data for Flutter app navigation
                    'pageid' => $pageId,
                    'pagename' => $pageName,
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK', // Required for Flutter push handling
                ],
                'android' => [ // Android-specific options
                    'priority' => 'high',
                ],
                'apns' => [ // iOS-specific options
                    'payload' => [
                        'aps' => [
                            'sound' => 'default',
                            'content-available' => 1, // Allow background processing
                        ],
                    ],
                ],
            ],
        ];

        // 4. Send the HTTP POST request using Guzzle
        $client = new Client();
        $response = $client->post($url, [
            //What is the Bearer token?
            //when our server ask firebase to send notifcation Google will check if the request is come from the authenticated server or not
            //if the request is come from the authenticated server it will send the notification to the user.
            //How to know if the request come from the authenticated server?
            //The request should contain a special token called Bearer token.
            //This token is generated using the service account file.
            //يعن الزول العامل طلب دة لو ما عندو توكن صحيح ما حيرسل ليهو الاشعار.
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken, // Bearer token for authentication
                /*🧠 What Content-Type means:
                      Content-Type = type of data you are sending.
                      application/json = means the body (payload) is written in JSON format (like { "title": "Hi", "body": "Message" }).*/
                'Content-Type' => 'application/json', // JSON payload
            ],
            'json' => $payload, // The notification data
        ]);

        // 5. Return the response from FCM (can be logged or checked for errors)
        return (string) $response->getBody();
    } catch (Exception $e) {
        // Handle general errors (e.g., file not found, auth issues)
        error_log("FCM General Error: " . $e->getMessage());
        return null;
    } catch (GuzzleException $e) {
        // Handle HTTP request errors
        error_log("FCM Guzzle Error: " . $e->getMessage());
        return null;
    }
}

function deleteData($table, $where, $json = true)
{
    global $con;
    $stmt = $con->prepare("DELETE FROM $table WHERE $where");
    $stmt->execute();
    $count = $stmt->rowCount();
    if ($json == true) {
        if ($count > 0) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }
    return $count;
}

function uploadeImage($requestName){
    //*The first things is recive the image data like "name,type,size,temp location".
    //Why did we use rand()?Becuase of we want the image name to be unique
    $imageName  = rand(1000,10000) . "_" . $_FILES[$requestName]['name'];

    $tmpLocation= $_FILES[$requestName]['tmp_name'];
    $imageSize  = $_FILES[$requestName]['size'];
    //We make it global var so it can be accessable outside the function.
    global $errorMsg;
    //This var will containe the allowed extend for the image.
    $allowExt   = array("jpg","png","gif","jpeg");
 //أكيد، هذا هو السطر مع التعليق المضاف بشكل مرتب يمكنك نسخه مباشرة في الكود:


// هنا نستخدم الدالة 
// explode 
// لفصل اسم الصورة عن امتدادها.
// نفترض أن $imageName = "cat.png".
// explode(".", $imageName) بتقسم النص عند كل نقطة ".".
// فتصبح النتيجة مصفوفة مثل ["cat", "png"].
// الهدف هو استخراج امتداد الصورة (مثل png) للتحقق من نوع الملف.
    $strToArray = explode(".", $imageName);


    //2-Get the last element in the array and it's the "extend".
    $ext=end($strToArray);
    //3-Make sure that the extend in lower case:
    $ext=strtolower($ext);
// هنا بنتأكد إنو اسم الصورة مش فاضي (يعني في صورة مرفوعة)
// وبنشيك إذا كان امتداد الصورة 
// $ext 
// مش موجود ضمن الامتدادات المسموحة 
// $allowExt
// لو الامتداد غير مسموح، الشرط حيكون صحيح ونضيف رسالة خطأ
if (!empty($imageName) && !in_array($ext, $allowExt)) {

       $errorMsg[]="File type is not allowed"; 
    }
    //Make sure that the image size is the allowed image size
    if($imageSize> 2*MP){
        $errorMsg[]="Image size is greter than the allowed size";
    }
    if(empty($errorMsg)){
        //The "move_uploaded" file method is use to upload the files to the server and it takes:
        //1-المسار المؤقت
        //2-المسار الذي سيتم وضع الملف فية في السيرفر.
        //note the '.' sympoal is use for concatnation.
        //Note:because of we are working in a lcoal server
        //the "upload" folder will represent the folder that we will save the image on it.
        move_uploaded_file($tmpLocation,"upload/users_images/".$imageName);
        return $imageName;
    }else{
          echo json_encode([
        "success" => false,
        "errors" => $errorMsg
    ]);
    return false;
    }
    
}


   function deleteImage($dir,$imageName){
    //This method is use to delete the image from the server.
    //We will use the "unlink" method to delete the image.
    //The unlink method take the path of the image that we want to delete.
    if(file_exists($dir."/".$imageName)){
        unlink($dir."/".$imageName);
    }}


//الدالة دي ما مفترض تحظها مفترض بس تعرف كيف تتعامل معاها :
//By using this method we will secure our api with username & password.
function checkAuthenticate(){
    if (isset($_SERVER['PHP_AUTH_USER'])  && isset($_SERVER['PHP_AUTH_PW'])) {
//Here we will just add the "username&password" for securing our sever and this username&password.
//shoud also be added in flutter app.
        if ($_SERVER['PHP_AUTH_USER'] != "qasim" ||  $_SERVER['PHP_AUTH_PW'] != "qasim#0909"){
            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'Page Not Found';
            exit;
        }
    } else {
        exit;
    }
}
//The overall goal is to copine the 2 process in just one proccess insert the notfication to the notication table.
//send the notfication to the user.
//We are going to create function.
//The goal of the function is to insert the notfication in the DB Table
//Why?in the app we have page called notfication page .in this page we have to display all of the notfication 
//that belong to spacfic user.so we have to save the notfication in spacfic table in db.
function insertNotfication($title,$body,$userId,$topic,$pageId,$pageName){
 global $con;
 $stmt=$con->prepare("INSERT INTO `notfication`( `notfication_title`, `notfication_body`, `notfication_user_id`) VALUES (?,?,?)");
 //why did we need the  user id?
 //Because of we need to know that this notfication will be send to each
 $stmt->execute([$title,$body,$userId]);
 //We don't want to have to seprate fucntion  one that send notfication.and another one that save the notfication in the DB.
 sendFcmNotification(
  $title,
  $body,
  $topic,
  $pageId,
  $pageName,
);
//If we wnat to know the result of the proccess will use this var 'count'
$count=$stmt->rowCount();
return $count;
}
?>