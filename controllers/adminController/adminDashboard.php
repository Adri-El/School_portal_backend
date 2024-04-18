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








$adminDashboard["getStudent"] = function(){
    global $utilities;
    global $database;

    try{
        $matric_no = $_REQUEST["matric_no"];

        if($matric_no){

            //get student details
            $query= array("matric_no"=> $matric_no);
            $studentData = $database->findOne($database->tables["students"], $query);
            
            if($studentData){
                unset($studentData["password"]);
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



$adminDashboard["giveMatricNumber"] = function(){
    global $utilities;
    global $database;

    try{
       
        //get payload
        $payload = json_decode(file_get_contents('php://input'), true);
        

        //CHECK IF THIS STUDENT EXISTS
        $studentQuery = array("jamb_reg_no"=> $payload["jamb_reg_no"]);
        $student = $database->findOne($database->tables["students"], $studentQuery);
        if($student){
            if(!$student["matric_no"]){
                //set reg number
                $query= array("id"=> 1);
                $regNo = $database->findOne($database->tables["reg_number_count"], $query); 
                $matric_no = "".$student["year"]."/".$regNo["count"]."";
                
                //update the reg_number_count
                $updateData = array("count"=> $regNo["count"] + 1);
                $querry = array("id"=> $regNo["id"]);
                $database->updateOne($database->tables["reg_number_count"], $updateData, $querry);

        
                //update student matric number
                $database->updateOne($database->tables["students"], array("matric_no"=> "'".$matric_no."'"), array("jamb_reg_no"=> "'".$payload["jamb_reg_no"]."'"));
                
                //send data
                $responseData = array("status"=> 200, "matric_no"=> $matric_no);
                $utilities["sendResponse"](200, "Content-Type: application/json", $responseData, true);
                return;
            }
            else{
                $errorObj = array("status"=> 400, "msg"=> "this student already has a matric number");
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
        $id_no = $_REQUEST["id_no"];
        //get payload
        $payload = json_decode(file_get_contents('php://input'), true);
        

        //trim payload
        //$payload = $utilities["dataTrimmer"]($payload);

        //validate payload

        //CHECK IF THIS LECTURER EXISTS
        
        $employedLecturerQuery = array("id_no"=> $id_no);
        $employedLecturer = $database->findOne($database->tables["employed_lecturers"],  $employedLecturerQuery);
        
        if($employedLecturer){
            if(!$employedLecturer["added"]){
                
                //hash password 
                $employedLecturer["password"]= password_hash($payload["password"], PASSWORD_DEFAULT);
                unset($employedLecturer["id"]);
                unset($employedLecturer["added"]);
                
                //add to lectures table
                $database->insertOne($database->tables["lecturers"], $employedLecturer, count($employedLecturer));
                 
                // //get lecturer id
                // $query= array("email"=> $employedLecturer["email"]);
                // $lecturerDetail = $database->findOne($database->tables["lecturers"], $query);


                //UPDATE employed lecturer DATA
                $updateQuery = array("id_no"=>"'".$id_no."'");
                $updateEmployedLecturerData = array("added"=>1); 
                $database->updateOne($database->tables["employed_lecturers"], $updateEmployedLecturerData, $updateQuery);

                //send data
                $responseData = array("status"=> 200, "msg"=> "success");
                $utilities["sendResponse"](200, "Content-Type: application/json", $responseData, true);
                return;
            }
            else{
                $errorObj = array("status"=> 400, "msg"=> "this employee has already been added");
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



$adminDashboard["getStudentByJambRegNo"] = function(){
    global $utilities;
    global $database;

    try{
        $jamb_reg_no = $_REQUEST["jamb_reg_no"];

        if($jamb_reg_no){

            //get student details
            $query= array("jamb_reg_no"=> $jamb_reg_no);
            $studentData = $database->findOne($database->tables["students"], $query);
            
            if($studentData){
                unset($studentData["password"]);
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


        
return $adminDashboard;
?>