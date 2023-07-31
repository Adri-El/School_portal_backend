<?php
//$utilities = require "lib/utilities.php";
$middleware = array();

$middleware["isTokenValid"] = function(){
    global $utilities;
   // Get the HTTP headers
    $token = explode(" ", getallheaders()["Authorization"])[1];
    
    if($token){
        if($utilities["jwt_validate"]($token)){
            $_SERVER["decodedToken"] = $utilities["jwt_validate"]($token);
            return true;
        }
        else{
            return false;
        }
    }
    else{
        return false;
    }
    
};

$middleware["isAdmin"] = function(){
    global $utilities;
   // Get account type
    $accType = $_SERVER["decodedToken"]->accType;
    
    if($accType == "admin"){
        return true;
    }
    else{
        return false;
    }
    
};

return $middleware;

?>