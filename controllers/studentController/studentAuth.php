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



$studentAuth["updateEmail"] = function(){
    global $utilities;
    global $database;

    try{
        
        $payload = json_decode(file_get_contents('php://input'), true); 
        
        //get user id from decoded token 
        $userID = $_SERVER["decodedToken"]->userID;
        
        //verify payload
        if($utilities["formValidator"]($payload, array("email", "password"))["isValid"]){
            //check if password is correct
            $studentData = $database->findOne($database->tables["students"], array("id"=>$userID));
            if(password_verify($payload["password"], $studentData["password"])){

                $query = array("id"=> $userID);
                $updateData = array("email"=>"'".$payload["email"]."'");
                $database->updateOne($database->tables["students"], $updateData, $query);

                //GET STUDENT DATA
                $studentData = $database->findOne($database->tables["students"], $query);
                $data = array("email"=>$studentData["email"]);
                //send response
                $responseData = array("status"=> 200, "data"=> $data);
                $utilities["sendResponse"](200, "Content-Type: application/json", $responseData, true);
            return;
            }
            else{
                $errorObj = array("status"=> 400, "msg"=> "invalid password");
                $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
                return;
            }
            
        }
        else{
           //send response
            $errorObj = array("status"=> 400, "msg"=> $utilities["formValidator"]($payload, array("state_of_origin", "phone_number", "guardian_name", "guardian_number"))["msg"]);
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



$studentAuth["updatePassword"] = function(){
    global $utilities;
    global $database;

    try{
        
        $payload = json_decode(file_get_contents('php://input'), true); 
        
        //get user id from decoded token 
        $userID = $_SERVER["decodedToken"]->userID;
        
        //verify payload
        if($utilities["formValidator"]($payload, array("old_password", "new_password"))["isValid"]){
            //check if password is correct
            $studentData = $database->findOne($database->tables["students"], array("id"=>$userID));
            if(password_verify($payload["old_password"], $studentData["password"])){
                //hash new password
                $payload["new_password"] = password_hash($payload["new_password"], PASSWORD_DEFAULT); 

                $query = array("id"=> $userID);
                $updateData = array("password"=>"'".$payload["new_password"]."'");
                $database->updateOne($database->tables["students"], $updateData, $query);

                //send response
                $responseData = array("status"=> 200, "data"=> "password updated successfully");
                $utilities["sendResponse"](200, "Content-Type: application/json", $responseData, true);
            return;
            }
            else{
                $errorObj = array("status"=> 400, "msg"=> "invalid password");
                $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
                return;
            }
            
        }
        else{
           //send response
            $errorObj = array("status"=> 400, "msg"=> $utilities["formValidator"]($payload, array("state_of_origin", "phone_number", "guardian_name", "guardian_number"))["msg"]);
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