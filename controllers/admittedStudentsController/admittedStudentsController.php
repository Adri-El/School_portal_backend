<?php

$admittedStudentsController = array();


$admittedStudentsController["getAdmittedStudent"] = function(){
    global $utilities;
    global $database;
    
    try{
        //extract query string from url
        $reg_no = $_REQUEST["reg_no"];

        echo $reg_no;
        // //get admitted student data
        // $admittedStudentData = $database->findOne($database->tables["admitted_students"], "reg_no", );
        // //remove the password
        // unset($adminData["password"]);

        // //send data
        // $responseData = array("status"=> 200, "admin_data"=> $adminData);
        // $utilities["sendResponse"](200, "Content-Type: application/json", $responseData, true);
        // return;

    }
    catch(Exception $ex){
        $errorObj = array("status"=> 500, "msg"=> "server error");
        $utilities["sendResponse"](500, "Content-Type: application/json", $errorObj, true);
        return; 
    }

};
        
return $admittedStudentsController;
?>