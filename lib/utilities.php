<?php
require_once "vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$utilities = array();

$utilities["sendResponse"] = function($statusCode, $contentType, $data, $isJson){
    if($isJson){
        header($contentType); 
        http_response_code($statusCode);
        echo json_encode($data);
    }
    else{
        header($contentType);
        http_response_code($statusCode);
        echo $data;
    }    
};

$utilities["adminLoginValidator"] = function($data, $expectedData){
    $dataKeys = array_keys($data);
    if(count($dataKeys) == count($expectedData)){
        
        foreach ($dataKeys as &$key) {
            if(!in_array($key, $expectedData)){
                $errorObj["isValid"] = false;
                $errorObj["msg"] = "{$key} is an invalid field";
                return $errorObj;
            }
            if(!is_string($data[$key])){
                $errorObj["isValid"] = false;
                $errorObj["msg"] = "{$key} should be in string format";
                return $errorObj;
            }
        }

        $successObj["isValid"] = true;
        return $successObj;
    }
    else{
        $errorObj["isValid"] = false;
        $errorObj["msg"] = "insufficient or excess data";
        return $errorObj;
    }
};


$utilities["dataTrimmer"] = function($data){
    $dataKeys = array_keys($data);

    foreach ($dataKeys as &$key) {
        $data[$key] = trim($data[$key]);
    }
    return $data;
 
};

$utilities["jwt_sign"] = function($account, $id){
    $key = $_ENV['JWT_KEY'];
    $jwtObj = [
        "iss" => "internship_university",
        "accType"=> $account,
        "userID" => $id,
        "exp" => time() + 10000,
        "iat" => time(),
    ];
    
    return JWT::encode($jwtObj, $key, "HS256");
 
};

$utilities["jwt_validate"] = function($token){
    $key = $_ENV['JWT_KEY'];
    try{
        return JWT::decode($token, new Key($key, "HS256"));
    }
    catch(Exception $ex){
        return null;
    }
 
};


return $utilities;
?>