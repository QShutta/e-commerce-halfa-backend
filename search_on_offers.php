<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "./connect.php";
$searchText=filterRequest('search_text');
// Beofre of try to add the rating to the app.
if (empty($searchText)) {
    echo json_encode(array("status" => "failure", "message" => "Search text is empty"));
    exit;
}
//To understand this statment you have to revesion the sql like statment this will learn you:https://www.w3schools.com/sql/sql_like.asp
//What did this statment means?
//Bring all of the products in the products table .but the product_name_ar or product_name_en contains the search text
getAllData("products1_view","product_discount!=0 AND (product_name_ar Like '%$searchText%' OR  proudct_name_en Like '%$searchText%')");
?>