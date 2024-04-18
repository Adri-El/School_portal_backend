<?php
$employedLecturerController = array();


$employedLecturerController["getEmployedLecturer"] = function(){
    global $utilities;
    global $database;
    
    try{
        //extract query string from url
        $id_no = $_REQUEST["id_no"];

        //get admitted student data
        $query= array("id_no"=> $id_no);
        $employedLecturer = $database->findOne($database->tables["employed_lecturers"], $query);
        if($employedLecturer){
            //send data 
            $responseData = array("status"=> 200, "employed_lecturer_data"=> $employedLecturer);
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
        
return $employedLecturerController;


?>