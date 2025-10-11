<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "connect.php";

// نستقبل user_id من POST
$user_id = filterRequest('user_id');

// نتأكد إنو في user_id
if (empty($user_id)) {
    echo json_encode([
        "success" => false,
        "message" => "Error: user_id is required"
    ]);
    exit();
}

// نتأكد إنو المستخدم موجود
$checkStmt = $con->prepare("SELECT COUNT(*) FROM users WHERE user_id = ?");
$checkStmt->execute([$user_id]);
$userExists = $checkStmt->fetchColumn();

if ($userExists == 0) {
    echo json_encode([
        "success" => false,
        "message" => "Error: No user found with this ID"
    ]);
    exit();
}

// نرفع الصورة
$imageUploaded = uploadeImage("user_image_url");

// لو اتـرفعت الصورة
if ($imageUploaded !== false) {
    // نخزن في قاعدة البيانات
    $stmt = $con->prepare("UPDATE users SET user_image_url = ? WHERE user_id = ?");
    $stmt->execute([$imageUploaded, $user_id]);

    echo json_encode([
        "success" => true,
        "image_url" => "upload/users_images/" . $imageUploaded,
        "message" => "Image uploaded and saved in DB successfully!"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Error uploading image!"
    ]);
}

?>