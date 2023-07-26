<?php

$adminAuth = require ("controllers/adminController/adminAuth.php");


$uri = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$path = $_SERVER["REQUEST_URI"];
$method = $_SERVER['REQUEST_METHOD'];


if($path == "/admin/login" && $method == "PUT"){
    $adminAuth["login"]();
    
}
else{
    echo "404 no page found";
}
?>