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
        $courses = $database->findMany($database->tables[$student["dept_code"]], $query);

        //send data
        $responseData = array("status"=> 200, "courses"=> $courses);
        $utilities["sendResponse"](200, "Content-Type: application/json", $responseData, true);
        return;

    }
    catch(Exception $ex){
        $errorObj = array("status"=> 500, "msg"=> "server error");
        $utilities["sendResponse"](500, "Content-Type: application/json", $errorObj, true);
        return; 
    }

};


$studentDashboard["getMoreRegistrationCourses"] = function(){
    global $utilities;
    global $database;

    try{
        $semester = (int)$_REQUEST["semester"];
        
        //get user id from decoded token 
        $userID = $_SERVER["decodedToken"]->userID;
        $query = array("id"=> $userID);
        $student = $database->findOne($database->tables["students"], $query);
        
        $query = array("department"=> "'".$student["department"]."'", "semester"=>$semester);
        $courses = $database->findMany($database->tables[$student["dept_code"]], $query);

        //send data
        $responseData = array("status"=> 200, "courses"=> $courses);
        $utilities["sendResponse"](200, "Content-Type: application/json", $responseData, true);
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

        //check whether student has gotten matric number
        $studentQuery = array("id"=> $userID);
        $studentData = $database->findOne($database->tables["students"], $studentQuery);

        if($studentData["matric_no"]){
            //check whether the student has paid school fees for the session
            $query = array("user_id"=> $userID, "session"=> $session);
        
            $sessionCheck = $database->findOne($database->tables["sessions"], $query);
            if($sessionCheck["school_fees"]){

                //check if the unit load is within range
                $maxUnitload= 0;
                for($i=0; $i<count($payload); $i++){
                    $course = $database->findOne($database->tables[$studentData["dept_code"]], array("id"=>(int)$payload[$i]));

                    $maxUnitload += $course["unit"];
                }

                if($maxUnitload <= 25){
                    if($semester == 1){
                        //check if student has already registered for the semester
                        if(!$sessionCheck["course_reg_semester1"]){
                            //rgister the courses
                            $updateQuery = array("user_id"=> $userID, "session"=> "'".$session."'");
                            $data = array("course_reg_semester1"=> 1, "semester1_courses"=> "'".implode(',', $payload)."'"); 
                            $database->updateOne($database->tables["sessions"], $data, $updateQuery);
    
                            //add courses to rergistered course list
                            //$student = $database->findOne($database->tables["students"], array("id"=>$userID));
                        
                            foreach ($payload as $courseID) {
                                $queryCourse = array("id"=> (int)$courseID);
                                $course = $database->findOne($database->tables[$studentData["dept_code"]], $queryCourse);
                                $insertQuery = array("student_id"=> $userID, "student_dept"=> $studentData["department"], "matric_no"=>$studentData["matric_no"], "session"=>$session, "semester"=>$course["semester"], "course_id"=> $course["id"], "course_dept"=>$course["department"], "title"=>$course["title"], "code"=> $course["code"], "unit"=> $course["unit"]);
                            
                                $database->insertOne($database->tables["registered_courses"], $insertQuery, count($insertQuery));
                    
                            }
    
                            if($studentData["login_id"]!= $studentData["matric_no"]){
                                $updateData = array("login_id"=> "'".$studentData["matric_no"]."'");
                                $updateQuery = array("id"=> $userID);
                                $database->updateOne($database->tables["students"], $updateData,  $updateQuery);
                            }
                        
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

                            foreach ($payload as $courseID) {
                                $queryCourse = array("id"=> (int)$courseID);
                                $course = $database->findOne($database->tables[$studentData["dept_code"]], $queryCourse);
                                $insertQuery = array("student_id"=> $userID, "student_dept"=> $studentData["department"], "matric_no"=>$studentData["matric_no"], "session"=>$session, "semester"=>$course["semester"], "course_id"=> $course["id"], "course_dept"=>$course["department"], "title"=>$course["title"], "code"=> $course["code"], "unit"=> $course["unit"]);
                            
                                $database->insertOne($database->tables["registered_courses"], $insertQuery, count($insertQuery));
                    
                            }
    
                            if($studentData["login_id"]!= $studentData["matric_no"]){
                                $updateData = array("login_id"=> "'".$studentData["matric_no"]."'");
                                $updateQuery = array("id"=> $userID);
                                $database->updateOne($database->tables["students"], $updateData,  $updateQuery);
                            }
    
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
                    $responseData = array("status"=> 400, "msg"=> "maximum unit load is 25");
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
        else{
            //send response
            $responseData = array("status"=> 400, "msg"=> "no matric number");
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


$studentDashboard["getPrevCourseReg"] = function(){
    global $utilities;
    global $database;

    try{
        $session = $_REQUEST["session"];
        $semester = (int) $_REQUEST["semester"];
        //get user id from decoded token 
        $userID = $_SERVER["decodedToken"]->userID;
        
        //GET courses
        $query = array("student_id"=>$userID, "semester"=> $semester, "session"=> "'".$session."'");
        $courses = $database->findMany($database->tables["registered_courses"], $query);
        
        if($courses){
            //refine data
            $refinedCourses = array();

            foreach($courses as $course){
                $refinedCourse = array();
                $refinedCourse["id"] = $course["id"];
                $refinedCourse["session"] = $course["session"];
                $refinedCourse["code"] = $course["code"];
                $refinedCourse["title"] = $course["title"];
                $refinedCourse["code"] = $course["code"];
                $refinedCourse["semester"] = $course["semester"];
                $refinedCourse["unit"] = $course["unit"];

                array_push($refinedCourses, $refinedCourse);

            }
            //send response
            $responseData = array("status"=> 200, "courses"=> $refinedCourses);
            $utilities["sendResponse"](200, "Content-Type: application/json", $responseData, true);
            return;  
        }
        else{
            $errorObj = array("status"=> 400, "msg"=> "no courses were registerd for this semester");
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







$studentDashboard["getUpdateData"] = function(){
    global $utilities;
    global $database;

    try{
        //get user id from decoded token 
        $userID = $_SERVER["decodedToken"]->userID;
        
        //GET STUDENT DATA
        $query = array("id"=> $userID);
        $studentData = $database->findOne($database->tables["students"], $query);
        $data = array("state_of_origin"=>$studentData["state_of_origin"], "phone_number"=>$studentData["phone_number"], "guardian_name"=>$studentData["guardian_name"], "guardian_number"=>$studentData["guardian_number"], "email"=>$studentData["email"]);

        //send response
        $responseData = array("status"=> 200, "data"=> $data);
        $utilities["sendResponse"](200, "Content-Type: application/json", $responseData, true);
        return;  
    }
    catch(Exception $ex){
        $errorObj = array("status"=> 500, "msg"=> "server error");
        $utilities["sendResponse"](500, "Content-Type: application/json", $errorObj, true);
        return; 
    }

};



$studentDashboard["updateProfile"] = function(){
    global $utilities;
    global $database;

    try{
        
        $payload = json_decode(file_get_contents('php://input'), true); 
        
        //get user id from decoded token 
        $userID = $_SERVER["decodedToken"]->userID;
        
        //verify payload
        if($utilities["formValidator"]($payload, array("state_of_origin", "phone_number", "guardian_name", "guardian_number"))["isValid"]){
            $query = array("id"=> $userID);
            $updateData = array("state_of_origin"=>"'".$payload["state_of_origin"]."'", "phone_number"=>"'".$payload["phone_number"]."'", "guardian_name"=>"'".$payload["guardian_name"]."'", "guardian_number"=>"'".$payload["guardian_number"]."'");
            $database->updateOne($database->tables["students"], $updateData, $query);

            //GET STUDENT DATA
            $studentData = $database->findOne($database->tables["students"], $query);
            $data = array("state_of_origin"=>$studentData["state_of_origin"], "phone_number"=>$studentData["phone_number"], "guardian_name"=>$studentData["guardian_name"], "guardian_number"=>$studentData["guardian_number"]);
            //send response
            $responseData = array("status"=> 200, "data"=> $data);
            $utilities["sendResponse"](200, "Content-Type: application/json", $responseData, true);
            return;
        }
        else{
           //send response
            $errorObj = array("status"=> 400, "msg"=> $utilities["formValidator"]($payload, array("state_of_origin", "phone_number", "guardian_name", "guardian_number"))["msg"]);
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




$studentDashboard["getResult"] = function(){
    global $utilities;
    global $database;

    try{
       
        $session = $_REQUEST["session"];
        $semester = (int) $_REQUEST["semester"];
        //get user id from decoded token 
        $userID = $_SERVER["decodedToken"]->userID;
        
        //GET STUDENT DATA
        $query = array("student_id"=> $userID, "session"=>"'".$session."'", "semester"=>$semester);
        
        $result = $database->findMany($database->tables["registered_courses"], $query);
        if($result){
            //send response
            $responseData = array("status"=> 200, "result"=> $result);
            $utilities["sendResponse"](200, "Content-Type: application/json", $responseData, true);
            return; 
        }
        else{
            //send response
            $errorObj = array("status"=> 400, "msg"=> "result for this session or semester is not available");
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
        
return $studentDashboard;
?>