<?php

$adminDashboard = array();

$adminDashboard["getDashboard"] = function(){
    global $utilities;
    global $database;
    
    //get user id from decoded token 
    $userID = $_SERVER["decodedToken"]->userID;
    try{
        //get admin data apart from password
        $adminData = $database->findOne($database->tables["admins"], "id", $userID);
        //remove the password
        unset($adminData["password"]);

        //send data
        $responseData = array("status"=> 200, "admin_data"=> $adminData);
        $utilities["sendResponse"](200, "Content-Type: application/json", $responseData, true);
        return;

    }
    catch(Exception $ex){
        $errorObj = array("status"=> 500, "msg"=> "server error");
        $utilities["sendResponse"](500, "Content-Type: application/json", $errorObj, true);
        return; 
    }

};




$adminDashboard["addStudent"] = function(){
    global $utilities;
    global $database;

    try{
        //get payload
        $payload = json_decode(file_get_contents('php://input'), true);

        //trim payload
        //$payload = $utilities["dataTrimmer"]($payload);

        //validate payload

        //set reg number
        $regNo = $database->findOne($database->tables["reg_number_count"], "id", 1); 
        $payload["reg_no"] = "".$payload["session"]."/".$regNo["count"]."";

        //update the reg_number_count
        $updateData = array("count"=> $regNo["count"] + 1);
        $querry = array("id"=> $regNo["id"]);
        $database->updateOne($database->tables["reg_number_count"], $updateData, $querry);

        //hash password
        $payload["password"] = password_hash($payload["password"], PASSWORD_DEFAULT);
        
        //add to students table
        $database->insertOne($database->tables["students"], $payload, count($payload));

        //
        //get student id
        $studentDetail = $database->findOne($database->tables["students"], "email", $payload["email"]);

        $duration = $payload["duration"];
        $sessions = array();
        $currentSession = 2023;
        $session = "";

        for($i = 0; $i < $duration; $i++ ){
            $session .= $currentSession;
            $session .= "/".++$currentSession."";
            $sessionData = array("user_id"=> $studentDetail["id"], "session"=> $session, "school_fees"=> 0, "course_reg"=> 0);
            $database->insertOne($database->tables["sessions"], $sessionData, count($sessionData));

            $session="";
        }

        //send data
       $responseData = array("status"=> 200, "matric_no"=> $payload["reg_no"]);
       $utilities["sendResponse"](200, "Content-Type: application/json", $responseData, true);
        return;

    }
    catch(Exception $ex){
        $errorObj = array("status"=> 500, "msg"=> "server error");
        $utilities["sendResponse"](500, "Content-Type: application/json", $errorObj, true);
        return; 
    }

};
        
return $adminDashboard;
?>