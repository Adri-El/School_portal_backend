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


$lecturerDashboard["getRegisteredCourseStudents"] = function(){
    global $utilities;
    global $database;
    
   
    try{
        //get user id from decoded token 
        $userID = $_SERVER["decodedToken"]->userID;
        //GET PAYLOAD
        $session = $_REQUEST["session"];
        $course_code = $_REQUEST["course_code"];
        
        
        //check if this lecturer has juridsjiction of this course
        $course = $database->findOne($database->tables["courses"], array("code"=>$course_code));
        
        if($course){
            $lecturer = $database->findOne($database->tables["lecturers"], array("id"=>$userID));

            if($lecturer["department"] == $course["department"]){

                //get all students that registered the course in that session
                $query = array("course_id"=>$course["id"], "session"=>"'".$session."'");
                $students = $database->findMany($database->tables["registered_courses"], $query);

                if($students){
                    //send data
                    $responseData = array("status"=> 200, "students"=> $students);
                    $utilities["sendResponse"](200, "Content-Type: application/json", $responseData, true);
                    return;
                }
                else{
                    //send response
                    $responseData = array("status"=> 400, "msg"=> "no one has registered this course for this session");
                    $utilities["sendResponse"](400, "Content-Type: application/json", $responseData, true);
                    return;
                }
                
            }
            else{
                //send response
                $responseData = array("status"=> 400, "msg"=> "this course is not in your jurisdiction");
                $utilities["sendResponse"](400, "Content-Type: application/json", $responseData, true);
                return;
            }
            

        }
        else{
            //send response
            $responseData = array("status"=> 400, "msg"=> "this course code does not exist");
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

return $lecturerDashboard;
?>