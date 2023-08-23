<?php
//$database = require "lib/database.php";
//$utilities = require "lib/utilities.php";

$adminAuth = array();

$adminAuth["login"] = function(){
    global $utilities;
    global $database;
    
    //get payload
    $payload = json_decode(file_get_contents('php://input'), true);
    
   try{
        //validate payload
        if($utilities["adminLoginValidator"]($payload, array("username", "password"))["isValid"]){
            //trim data
            $payload = $utilities["dataTrimmer"]($payload);

            //check if admin user exists in database
            $query = array("username"=>$payload["username"]);
            $adminObj = $database->findOne($database->tables["admins"], $query);
            if($adminObj){
                //check if password matches
                if(password_verify($payload["password"], $adminObj["password"])){

                    //send token

                    $token = $utilities["jwt_sign"]("admin", $adminObj["id"]);
                    $responseData["status"] = 200;
                    $responseData["iuToken"] = $token;

                    $utilities["sendResponse"](200, "Content-Type: application/json", $responseData, true);
                    return;
                }
                else{
                    $errorObj = array("status"=> 400, "msg"=> "invalid username or password");
                    $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
                    return;
                }
                
            }
            else{
                $errorObj = array("status"=> 400, "msg"=> "invalid username or passwordd");
                $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
                return;
            }
            
        }
        else{
            $errorObj = array("status"=> 400, "msg"=> $utilities["adminLoginValidator"]($payload, array("username", "password"))["msg"]);
            $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
            return; 
        }

    }
    catch(Exception $ex){
        $errorObj = array("status"=> 500, "msg"=> "server error");
        $utilities["sendResponse"](500, "Content-Type: application/json", $errorObj, true);
        return; 
    }
};
        
return $adminAuth;
?>