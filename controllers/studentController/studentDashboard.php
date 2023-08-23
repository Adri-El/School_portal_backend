<?php

$studentDashboard = array();

$studentDashboard["getDashboard"] = function(){
    global $utilities;
    global $database;
    
    //get user id from decoded token 
    $userID = $_SERVER["decodedToken"]->userID;
    try{
        //get admin data apart from password
        $query = array("id"=>$userID);
        $studentData = $database->findOne($database->tables["students"], $query);
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
            //check how many year course the person is doing
            $query = array("user_id"=> $userID) ;
           // $sessions = $database->findMany($database->tables["sessions"], "user_id", $userID);
           $sessions = $database->findMany($database->tables["sessions"], $query);
    
        
            if((int)$year > count($sessions)){
                $responseData = array("status"=> 400, "msg"=> "out of range");
                $utilities["sendResponse"](400, "Content-Type: application/json", $responseData, true);
                return;
            }
            
            if($year == '1'){
                //get year one school fees
                $query = array("id"=> 1);
                $fees = $database->findOne($database->tables["year_one_fees"], $query);
    
            }
            else if($year == '2'){
                $query = array("id"=> 1);
                $fees = $database->findOne($database->tables["year_two_fees"], $query);
            }
            else if($year == '3'){
                $query = array("id"=> 1);
                $fees = $database->findOne($database->tables["year_three_fees"], $query);
            }
            else if($year == '4'){
                $query = array("id"=> 1);
                $fees = $database->findOne($database->tables["year_four_fees"], $query);
            }
            else if($year == '5'){
                $query = array("id"=> 1);
                $fees = $database->findOne($database->tables["year_five_fees"], $query);
            }
            else if($year == '6'){
                $query = array("id"=> 1);
                $fees = $database->findOne($database->tables["year_six_fees"], $query);
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


$studentDashboard["getRegistrationCourses"] = function(){
    global $utilities;
    global $database;

    try{
        $level = (int)$_REQUEST["level"];
        $semester = (int)$_REQUEST["semester"];
        
        //get user id from decoded token 
        $userID = $_SERVER["decodedToken"]->userID;
        $query = array("id"=> $userID);
        $student = $database->findOne($database->tables["students"], $query);
        
        $query = array("department"=> "'".$student["department"]."'", "level"=> $level, "semester"=>$semester);
        $courses = $database->findMany($database->tables["courses"], $query);

        echo json_encode($courses);
        return;

    }
    catch(Exception $ex){
        $errorObj = array("status"=> 500, "msg"=> "server error");
        $utilities["sendResponse"](500, "Content-Type: application/json", $errorObj, true);
        return; 
    }

};


$studentDashboard["registerCourses"] = function(){
    global $utilities;
    global $database;

    try{
        $session = $_REQUEST["session"];
        $semester = (int) $_REQUEST["semester"];
        $payload = json_decode(file_get_contents('php://input'), true); 
        
        //get user id from decoded token 
        $userID = $_SERVER["decodedToken"]->userID;

        
        //check whether the student has paid school fees for the session
        $query = array("user_id"=> $userID, "session"=> $session);
        
        $sessionCheck = $database->findOne($database->tables["sessions"], $query);
        if($sessionCheck["school_fees"]){
            
            if($semester == 1){
                //check if student has already registered for the semester
                if(!$sessionCheck["course_reg_semester1"]){
                    //rgister the courses
                    $updateQuery = array("user_id"=> $userID, "session"=> "'".$session."'");
                    $data = array("course_reg_semester1"=> 1, "semester1_courses"=> "'".implode(',', $payload)."'"); 
                    $database->updateOne($database->tables["sessions"], $data, $updateQuery);

                    //send response
                    $responseData = array("status"=> 200, "msg"=> "sucess");
                    $utilities["sendResponse"](200, "Content-Type: application/json", $responseData, true);
                    return;
                }
                else{
                    //send response
                    $responseData = array("status"=> 400, "msg"=> "this semester is registered already");
                    $utilities["sendResponse"](400, "Content-Type: application/json", $responseData, true);
                    return;
                }

            }
            else if($semester == 2){
                //check if student has already registered for the semester
                if(!$sessionCheck["course_reg_semester2"]){
                    //rgister the courses
                    $updateQuery = array("user_id"=> $userID, "session"=> "'".$session."'");
                    $data = array("course_reg_semester2"=> 1, "semester2_courses"=> "'".implode(',', $payload)."'"); 
                    $database->updateOne($database->tables["sessions"], $data, $updateQuery);

                    //send response
                    $responseData = array("status"=> 200, "msg"=> "sucess");
                    $utilities["sendResponse"](200, "Content-Type: application/json", $responseData, true);
                    return;
                }
                else{
                    //send response
                    $responseData = array("status"=> 400, "msg"=> "this semester is registered already");
                    $utilities["sendResponse"](400, "Content-Type: application/json", $responseData, true);
                    return;
                }
            }
            else{
                $responseData = array("status"=> 400, "msg"=> "invalid semester");
                $utilities["sendResponse"](400, "Content-Type: application/json", $responseData, true);
                return;
            }
            
        }
        else{
            $responseData = array("status"=> 400, "msg"=> "session school fees not paid");
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

        
return $studentDashboard;
?>