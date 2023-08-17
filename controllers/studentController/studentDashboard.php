<?php

$studentDashboard = array();

$studentDashboard["getDashboard"] = function(){
    global $utilities;
    global $database;
    
    //get user id from decoded token 
    $userID = $_SERVER["decodedToken"]->userID;
    try{
        //get admin data apart from password
        $studentData = $database->findOne($database->tables["students"], "id", $userID);
        //remove the password
        unset($studentData["password"]);

        //send data
        $responseData = array("status"=> 200, "student_data"=> $studentData);
        $utilities["sendResponse"](200, "Content-Type: application/json", $responseData, true);
        return;

    }
    catch(Exception $ex){
        $errorObj = array("status"=> 500, "msg"=> "server error");
        $utilities["sendResponse"](500, "Content-Type: application/json", $errorObj, true);
        return; 
    }

};


$studentDashboard["getSchoolFees"] = function(){
    global $utilities;
    global $database;
    
    //get user id from decoded token 
    $userID = $_SERVER["decodedToken"]->userID;
    try{
        $year = $_REQUEST["year"];
        $fees = 0;
        if($year){
            if($year == '1'){
                //get year one school fees
                $fees = $database->findOne($database->tables["year_one_fees"], "id", 1);
    
            }
            else if($year == '2'){
                $fees = $database->findOne($database->tables["year_two_fees"], "id", 1);
            }
            else if($year == '3'){
                $fees = $database->findOne($database->tables["year_three_fees"], "id", 1);
            }
            else if($year == '4'){
                $fees = $database->findOne($database->tables["year_four_fees"], "id", 1);
            }
            else if($year == '5'){
                $fees = $database->findOne($database->tables["year_five_fees"], "id", 1);
            }
            else if($year == '6'){
                $fees = $database->findOne($database->tables["year_six_fees"], "id", 1);
            }
            else{
                $responseData = array("status"=> 400, "msg"=> "invalid data");
                $utilities["sendResponse"](400, "Content-Type: application/json", $responseData, true);
                return;  
            }
        
    
            //send data
            $responseData = array("status"=> 200, "school_fees_data"=> $fees);
            $utilities["sendResponse"](200, "Content-Type: application/json", $responseData, true);
            return;

        }
        else{
            $responseData = array("status"=> 400, "msg"=> "missing data");
            $utilities["sendResponse"](400, "Content-Type: application/json", $responseData, true);
            return;
        }
        
    }
    catch(Exception $ex){
        $errorObj = array("status"=> 500, "msg"=> "server error");
        $utilities["sendResponse"](500, "Content-Type: application/json", $errorObj, true);
        return; 
    }

};


$studentDashboard["schoolFeesPayment"] = function(){
    global $utilities;
    global $database;
    
    //get user id from decoded token 
    $userID = $_SERVER["decodedToken"]->userID;
    try{
        $payload = json_decode(file_get_contents('php://input'), true);

        //validate data

        //pay schoolfees
        $updateData = array("school_fees"=> 1);
        $querry = array("user_id"=> $userID, "session"=> "'".$payload["session"]."'");
        $database->updateOne($database->tables["sessions"], $updateData, $querry);
    

        //send data
        $responseData = array("status"=> 200, "msg"=> "success");
        $utilities["sendResponse"](200, "Content-Type: application/json", $responseData, true);
        return;
    }
    catch(Exception $ex){
        $errorObj = array("status"=> 500, "msg"=> "server error");
        $utilities["sendResponse"](500, "Content-Type: application/json", $errorObj, true);
        return; 
    }

};


        
return $studentDashboard;
?>