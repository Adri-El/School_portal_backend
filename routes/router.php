<?php

$adminAuth = require ("controllers/adminController/adminAuth.php");
$adminDashboard = require("controllers/adminController/adminDashboard.php");


$uri = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$path = $_SERVER["REQUEST_URI"];
$method = $_SERVER['REQUEST_METHOD'];


header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

switch($path . $method){
    case($path == "/admin/login" and $method == "PUT"):
        $adminAuth["login"]();
        break;

    case($path == "/admin/login" and $method == "PUT"):
        
        $adminDashboard["getAdminData"]();
        break;

    
    default:
    echo "404 no page found";

}

// if($path == "/admin/login" && $method == "PUT"){
//     $adminAuth["login"]();
    
// }
// else if($path == "/admin/get-admin" && $method == "GET"){
//     $adminAuth["login"]();
    
// }
// else{
//     echo "404 no page found";
// }
?>