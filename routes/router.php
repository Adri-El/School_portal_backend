<?php

$adminAuth = require ("controllers/adminController/adminAuth.php");


$uri = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$path = $_SERVER["REQUEST_URI"];
$method = $_SERVER['REQUEST_METHOD'];


header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');


if($path == "/admin/login" && $method == "PUT"){
    $adminAuth["login"]();
    
}
else{
    echo "404 no page found";
}
?>