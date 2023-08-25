<?php

$lecturerDashboard = array();

$lecturerDashboard["getDashboard"] = function(){
    global $utilities;
    global $database;
    
    //get user id from decoded token 
    $userID = $_SERVER["decodedToken"]->userID;
    try{
        //get admin data apart from password
        $query = array("id"=>$userID);
        $lecturerData = $database->findOne($database->tables["lecturers"], $query);
        //remove the password
        unset($lecturerData["password"]);

        //send data
        $responseData = array("status"=> 200, "lecturer_data"=> $lecturerData);
        $utilities["sendResponse"](200, "Content-Type: application/json", $responseData, true);
        return;

    }
    catch(Exception $ex){
        $errorObj = array("status"=> 500, "msg"=> "server error");
        $utilities["sendResponse"](500, "Content-Type: application/json", $errorObj, true);
        return; 
    }

};

return $lecturerDashboard;
?>