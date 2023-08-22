<?php

$admittedStudentsController = array();


$admittedStudentsController["getAdmittedStudent"] = function(){
    global $utilities;
    global $database;
    
    try{
        //extract query string from url
        $reg_no = $_REQUEST["reg_no"];

        //get admitted student data
        $query= array("reg_no"=> $reg_no);
        $admittedStudentData = $database->findOne($database->tables["admitted_students"], $query);
        if($admittedStudentData){
            //send data 
            $responseData = array("status"=> 200, "admitted_student_data"=> $admittedStudentData);
            $utilities["sendResponse"](200, "Content-Type: application/json", $responseData, true);
            return;

        }
        else{
            $errorObj = array("status"=> 404, "msg"=> "student not found");
            $utilities["sendResponse"](404, "Content-Type: application/json", $errorObj, true);
            return;
        }
        

    }
    catch(Exception $ex){
        $errorObj = array("status"=> 500, "msg"=> "server error");
        $utilities["sendResponse"](500, "Content-Type: application/json", $errorObj, true);
        return; 
    }

};
        
return $admittedStudentsController;
?>