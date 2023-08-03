<?php

$adminAuth = require ("controllers/adminController/adminAuth.php");
$adminDashboard = require("controllers/adminController/adminDashboard.php");
$admittedStudentsController = require("controllers/admittedStudentsController/admittedStudentsController.php");
$middlewear = require("lib/middleware.php");


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


    case($path == "/admin/get-dashboard" and $method == "GET"):
        if($middlewear["isTokenValid"]()){
        
            if($middlewear["isAdmin"]()){
                $adminDashboard["getDashboard"]();
            }
            else{
                $errorObj = array("status"=> 400, "msg"=> "This account is not authorized to access this route");
                $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
            }    
        }
        else{ 
            $errorObj = array("status"=> 400, "msg"=> "Unauthorized");
            $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
        } 
    break;

    case($path == "/admitted-student/get-admitted-student" and $method == "GET"):
                
        $admittedStudentsController["getAdmittedStudent"]();       
    break;
    
    default:
    echo "404 no page found";

}






// if($path == "/admin/login" && $method == "PUT"){
//     $adminAuth["login"]();
    
// }

// else{
//     echo "404 no page found";
// }l
?>