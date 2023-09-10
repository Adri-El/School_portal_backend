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
        $department = $_REQUEST["department"];
        
        
        //check if this lecturer has juridsjiction of this course
        $getStudentsQuery = array("code"=>"'".$course_code."'", "student_dept"=>"'".$department."'", "session"=> "'".$session."'");
        $students = $database->findMany($database->tables["registered_courses"], $getStudentsQuery);

        
        if($students){
            $lecturer = $database->findOne($database->tables["lecturers"], array("id"=>$userID));

            if($lecturer["department"] == $students[0]["course_dept"]){
 
                //send data
                $responseData = array("status"=> 200, "students"=> $students);
                $utilities["sendResponse"](200, "Content-Type: application/json", $responseData, true);
                return;
                
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
            $responseData = array("status"=> 400, "msg"=> "No student from this department has registered this course yet");
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



$lecturerDashboard["uploadResult"] = function(){
    global $utilities;
    global $database;
    
   
    try{
        //get user id from decoded token 
        $userID = $_SERVER["decodedToken"]->userID;
        //GET PAYLOAD
       $payload = json_decode(file_get_contents('php://input'), true);

        
        $lecturer = $database->findOne($database->tables["lecturers"], array("id"=>$userID));
        $course = $database->findOne($database->tables["registered_courses"], array("id"=>$payload[0]["id"]));

        if($lecturer["department"] == $course["course_dept"]){
            //Upload results
        
            foreach ($payload as $student){
                $updateQuery = array("id"=> $student["id"]);
                unset($student["id"]);
                if(!$student["CA"]){
                    unset($student["CA"]);
                }
                if(!$student["exam"]){
                    unset($student["exam"]);
                }
                if(!$student["total"]){
                    unset($student["total"]);
                }
                if(!$student["grade"]){
                    unset($student["grade"]);
                }
                else{
                    $student["grade"] = "'".$student["grade"]."'";
                }
                
                $updatedData = $student;

                $database->updateOne($database->tables["registered_courses"], $updatedData, $updateQuery);

            }
 
            //send data
            $responseData = array("status"=> 200, "msg"=> "Result upload successful");
            $utilities["sendResponse"](200, "Content-Type: application/json", $responseData, true);
            return;
                
        }
        else{
            //send response
            $responseData = array("status"=> 400, "msg"=> "this course is not in your jurisdiction");
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