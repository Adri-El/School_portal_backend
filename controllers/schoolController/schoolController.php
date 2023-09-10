<?php

$schoolController = array();

$schoolController["createStudentAccount"] = function(){
    global $utilities;
    global $database;

    try{
        //get payload
        $payload = json_decode(file_get_contents('php://input'), true);
        

        //trim payload
        //$payload = $utilities["dataTrimmer"]($payload);

        //validate payload

        //CHECK IF THIS STUDENT EXISTS
        $admitedStudentQuery = array("jamb_reg_no"=> $payload["jamb_reg_no"]);
        $admittedStudent = $database->findOne($database->tables["admitted_students"], $admitedStudentQuery);
        
        if($admittedStudent){
            $studentQuery = array("jamb_reg_no"=> $payload["jamb_reg_no"]);
            $studentDetail = $database->findOne($database->tables["students"], $studentQuery);
            if(!$studentDetail){
                //remove admitted student id
                unset($admittedStudent["id"]);

                //hash password
                $admittedStudent["password"] = password_hash($payload["password"], PASSWORD_DEFAULT);
                $admittedStudent["login_id"] = $payload["jamb_reg_no"];
        
                //add to students table
                $database->insertOne($database->tables["students"], $admittedStudent, count($admittedStudent));

                //get student id
                $query= array("jamb_reg_no"=> $payload["jamb_reg_no"]);
                $studentDetail = $database->findOne($database->tables["students"], $query);
                
                $duration = $studentDetail["duration"];
                $currentSession = $studentDetail["year"];
                $session = "";

                  
                for($i = 0; $i < $duration; $i++ ){
                    $session .= $currentSession;
                    $session .= "/".++$currentSession."";
                    $sessionData = array("user_id"=> $studentDetail["id"], "session"=> $session, "school_fees"=> 0, "course_reg_semester1"=> 0, "course_reg_semester2"=> 0, "semester1_courses"=>"0", "semester2_courses"=>"0");
                    $database->insertOne($database->tables["sessions"], $sessionData, count($sessionData));
                    $session ="";
                }
                
                //send data
                $responseData = array("status"=> 200, "msg"=> "account created succesfully");
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

return $schoolController;
?>