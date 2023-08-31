<?php


$studentAuth = array();

$studentAuth["login"] = function(){
    global $utilities;
    global $database;
    
    //get payload
    $payload = json_decode(file_get_contents('php://input'), true);
    
   try{
        //validate payload
        if($utilities["adminLoginValidator"]($payload, array("login_id", "password"))["isValid"]){
            //trim data
            $payload = $utilities["dataTrimmer"]($payload);

            //check if student exists in database
            $query = array("login_id"=> $payload["login_id"]);
            $studentObj = $database->findOne($database->tables["students"], $query);
            
            if($studentObj){
                //check if password matches
                if(password_verify($payload["password"], $studentObj["password"])){

                    //send token

                    $token = $utilities["jwt_sign"]("student", $studentObj["id"]);
                    $responseData["status"] = 200;
                    $responseData["iuToken"] = $token;

                    $utilities["sendResponse"](200, "Content-Type: application/json", $responseData, true);
                    return;
                }
                else{
                    $errorObj = array("status"=> 400, "msg"=> "invalid login id or password");
                    $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
                    return;
                }
                
            }
            else{
                $errorObj = array("status"=> 400, "msg"=> "invalid login id or password");
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
        
return $studentAuth;
?>