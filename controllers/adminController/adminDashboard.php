<?php

$adminDashboard = array();

$adminDashboard["getDashboard"] = function(){
    global $utilities;
    global $database;
    
    //get user id from decoded token 
    $userID = $_SERVER["decodedToken"]->userID;
    try{
        //get admin data apart from password
        $query= array("id"=> $userID);
        $adminData = $database->findOne($database->tables["admins"], $query);
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
        $id = (int)$_REQUEST["id"];
        //get payload
        $payload = json_decode(file_get_contents('php://input'), true);
        

        //trim payload
        //$payload = $utilities["dataTrimmer"]($payload);

        //validate payload

        //CHECK IF THIS STUDENT EXISTS
        $admitedStudentQuery = array("id"=> $id);
        $admittedStudent = $database->findOne($database->tables["admitted_students"], $admitedStudentQuery);
        if($admittedStudent){
            if(!$admittedStudent["added"]){
                //set reg number
                $query= array("id"=> 1);
                $regNo = $database->findOne($database->tables["reg_number_count"], $query); 
                $payload["reg_no"] = "".$payload["session"]."/".$regNo["count"]."";

                //update the reg_number_count
                $updateData = array("count"=> $regNo["count"] + 1);
                $querry = array("id"=> $regNo["id"]);
                $database->updateOne($database->tables["reg_number_count"], $updateData, $querry);

                //hash password
                $payload["password"] = password_hash($payload["password"], PASSWORD_DEFAULT);
        
                //add to students table
                $database->insertOne($database->tables["students"], $payload, count($payload));

                //get student id
                $query= array("email"=> $payload["email"]);
                $studentDetail = $database->findOne($database->tables["students"], $query);

                $duration = $payload["duration"];
                $sessions = array();
                $currentSession = 2023;
                $session = "";

                for($i = 0; $i < $duration; $i++ ){
                    $session .= $currentSession;
                    $session .= "/".++$currentSession."";
                    $sessionData = array("user_id"=> $studentDetail["id"], "session"=> $session, "school_fees"=> 0, "course_reg_semester1"=> 0, "course_reg_semester2"=> 0, "semester1_courses"=>"0", "semester2_courses"=>"0");
                    $database->insertOne($database->tables["sessions"], $sessionData, count($sessionData));

                    $session="";
                }

                //UPDATE ADMITTED STUDENT DATA
                $updateQuery = array("id"=>$id);
                $updateAdmittedStudentData = array("added"=>1); 
                $database->updateOne($database->tables["admitted_students"], $updateAdmittedStudentData, $updateQuery);

                //send data
                $responseData = array("status"=> 200, "matric_no"=> $payload["reg_no"]);
                $utilities["sendResponse"](200, "Content-Type: application/json", $responseData, true);
                return;
            }
            else{
                $errorObj = array("status"=> 400, "msg"=> "this student has already been added");
                $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
                return;
            }
        }
        else{
            $errorObj = array("status"=> 400, "msg"=> "student does not exist");
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



$adminDashboard["getStudent"] = function(){
    global $utilities;
    global $database;

    try{
        $reg_no = $_REQUEST["reg_no"];

        if($reg_no){

            //get student details
            $query= array("reg_no"=> $reg_no);
            $studentData = $database->findOne($database->tables["students"], $query);
            unset($studentData["password"]);
            if($studentData){
                //send data
                $responseData = array("status"=> 200, "student_data"=> $studentData);
                $utilities["sendResponse"](200, "Content-Type: application/json", $responseData, true);
                return;
            }
            else{
                $errorObj = array("status"=> 400, "msg"=> "data not found");
                $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
                return;  
            }

            

        }
        else{
            $errorObj = array("status"=> 400, "msg"=> "missing data");
            $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
            return;
        }
        //set reg number
       //$regNo = $database->findOne($database->tables["reg_number_count"], "id", 1); 

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
        $id = (int)$_REQUEST["id"];
        //get payload
        $payload = json_decode(file_get_contents('php://input'), true);
        

        //trim payload
        //$payload = $utilities["dataTrimmer"]($payload);

        //validate payload

        //CHECK IF THIS STUDENT EXISTS
        $admitedStudentQuery = array("id"=> $id);
        $admittedStudent = $database->findOne($database->tables["admitted_students"], $admitedStudentQuery);
        if($admittedStudent){
            if(!$admittedStudent["added"]){
                //set reg number
                $query= array("id"=> 1);
                $regNo = $database->findOne($database->tables["reg_number_count"], $query); 
                $payload["reg_no"] = "".$payload["session"]."/".$regNo["count"]."";

                //update the reg_number_count
                $updateData = array("count"=> $regNo["count"] + 1);
                $querry = array("id"=> $regNo["id"]);
                $database->updateOne($database->tables["reg_number_count"], $updateData, $querry);

                //hash password
                $payload["password"] = password_hash($payload["password"], PASSWORD_DEFAULT);
        
                //add to students table
                $database->insertOne($database->tables["students"], $payload, count($payload));

                //get student id
                $query= array("email"=> $payload["email"]);
                $studentDetail = $database->findOne($database->tables["students"], $query);

                $duration = $payload["duration"];
                $sessions = array();
                $currentSession = 2023;
                $session = "";

                for($i = 0; $i < $duration; $i++ ){
                    $session .= $currentSession;
                    $session .= "/".++$currentSession."";
                    $sessionData = array("user_id"=> $studentDetail["id"], "session"=> $session, "school_fees"=> 0, "course_reg_semester1"=> 0, "course_reg_semester2"=> 0, "semester1_courses"=>"0", "semester2_courses"=>"0");
                    $database->insertOne($database->tables["sessions"], $sessionData, count($sessionData));

                    $session="";
                }

                //UPDATE ADMITTED STUDENT DATA
                $updateQuery = array("id"=>$id);
                $updateAdmittedStudentData = array("added"=>1); 
                $database->updateOne($database->tables["admitted_students"], $updateAdmittedStudentData, $updateQuery);

                //send data
                $responseData = array("status"=> 200, "matric_no"=> $payload["reg_no"]);
                $utilities["sendResponse"](200, "Content-Type: application/json", $responseData, true);
                return;
            }
            else{
                $errorObj = array("status"=> 400, "msg"=> "this student has already been added");
                $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
                return;
            }
        }
        else{
            $errorObj = array("status"=> 400, "msg"=> "student does not exist");
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



$adminDashboard["addLecturer"] = function(){
    global $utilities;
    global $database;

    try{
        $id = (int)$_REQUEST["id"];
        //get payload
        $payload = json_decode(file_get_contents('php://input'), true);
        

        //trim payload
        //$payload = $utilities["dataTrimmer"]($payload);

        //validate payload

        //CHECK IF THIS STUDENT EXISTS
        $admitedStudentQuery = array("id"=> $id);
        $admittedStudent = $database->findOne($database->tables["admitted_students"], $admitedStudentQuery);
        if($admittedStudent){
            if(!$admittedStudent["added"]){
                //set reg number
                $query= array("id"=> 1);
                $regNo = $database->findOne($database->tables["reg_number_count"], $query); 
                $payload["reg_no"] = "".$payload["session"]."/".$regNo["count"]."";

                //update the reg_number_count
                $updateData = array("count"=> $regNo["count"] + 1);
                $querry = array("id"=> $regNo["id"]);
                $database->updateOne($database->tables["reg_number_count"], $updateData, $querry);

                //hash password
                $payload["password"] = password_hash($payload["password"], PASSWORD_DEFAULT);
        
                //add to students table
                $database->insertOne($database->tables["students"], $payload, count($payload));

                //get student id
                $query= array("email"=> $payload["email"]);
                $studentDetail = $database->findOne($database->tables["students"], $query);

                $duration = $payload["duration"];
                $sessions = array();
                $currentSession = 2023;
                $session = "";

                for($i = 0; $i < $duration; $i++ ){
                    $session .= $currentSession;
                    $session .= "/".++$currentSession."";
                    $sessionData = array("user_id"=> $studentDetail["id"], "session"=> $session, "school_fees"=> 0, "course_reg_semester1"=> 0, "course_reg_semester2"=> 0, "semester1_courses"=>"0", "semester2_courses"=>"0");
                    $database->insertOne($database->tables["sessions"], $sessionData, count($sessionData));

                    $session="";
                }

                //UPDATE ADMITTED STUDENT DATA
                $updateQuery = array("id"=>$id);
                $updateAdmittedStudentData = array("added"=>1); 
                $database->updateOne($database->tables["admitted_students"], $updateAdmittedStudentData, $updateQuery);

                //send data
                $responseData = array("status"=> 200, "matric_no"=> $payload["reg_no"]);
                $utilities["sendResponse"](200, "Content-Type: application/json", $responseData, true);
                return;
            }
            else{
                $errorObj = array("status"=> 400, "msg"=> "this student has already been added");
                $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
                return;
            }
        }
        else{
            $errorObj = array("status"=> 400, "msg"=> "student does not exist");
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


        
return $adminDashboard;
?>