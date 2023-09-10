<?php

$adminAuth = require ("controllers/adminController/adminAuth.php");
$adminDashboard = require("controllers/adminController/adminDashboard.php");
$admittedStudentsController = require("controllers/admittedStudentsController/admittedStudentsController.php");
$studentAuth = require("controllers/studentController/studentAuth.php");
$studentDashboard = require("controllers/studentController/studentDashboard.php");
$employedLecturerController = require("controllers/employedLecturersController/employedLecturersController.php");
$lecturerAuth = require("controllers/lecturerController/lecturerAuth.php");
$lecturerDashboard = require("controllers/lecturerController/lecturerDasboard.php");
$schoolController = require("controllers/schoolController/schoolController.php");
$middlewear = require("lib/middleware.php");


$uri = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$path = explode('?', $_SERVER["REQUEST_URI"])[0];
$method = $_SERVER['REQUEST_METHOD'];


header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

switch($path . $method){
    // SCHOOL
    case($path == "/school/create-student-account" and $method == "POST"):
            
        $schoolController["createStudentAccount"](); 
    break; 


    //ADMIN ROUTES
    case($path == "/admin/login" and $method == "PUT"):
        $adminAuth["login"]();
    break;


    case($path == "/admin/get-dashboard" and $method == "GET"):
        if($middlewear["isTokenValid"]()){
        
            if($middlewear["isAdmin"]()){
                $adminDashboard["getDashboard"]();
            }
            else{
                $errorObj = array("status"=> 400, "msg"=> "This account is not authorized to access this route");
                $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
            }    
        }
        else{ 
            $errorObj = array("status"=> 400, "msg"=> "Unauthorized");
            $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
        } 
    break;


    case($path == "/admin/add-student" and $method == "POST"):
        if($middlewear["isTokenValid"]()){
        
            if($middlewear["isAdmin"]()){
                $adminDashboard["addStudent"]();
            }
            else{
                $errorObj = array("status"=> 400, "msg"=> "This account is not authorized to access this route");
                $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
            }    
        }
        else{ 
            $errorObj = array("status"=> 400, "msg"=> "Unauthorized");
            $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
        } 
    break;


    case($path == "/admin/add-lecturer" and $method == "POST"):
        if($middlewear["isTokenValid"]()){
        
            if($middlewear["isAdmin"]()){
                $adminDashboard["addLecturer"]();
            }
            else{
                $errorObj = array("status"=> 400, "msg"=> "This account is not authorized to access this route");
                $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
            }    
        }
        else{ 
            $errorObj = array("status"=> 400, "msg"=> "Unauthorized");
            $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
        } 
    break;


    case($path == "/admin/get-student" and $method == "GET"):
        if($middlewear["isTokenValid"]()){
        
            if($middlewear["isAdmin"]()){
                $adminDashboard["getStudent"]();
            }
            else{
                $errorObj = array("status"=> 400, "msg"=> "This account is not authorized to access this route");
                $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
            }    
        }
        else{ 
            $errorObj = array("status"=> 400, "msg"=> "Unauthorized");
            $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
        } 
    break;

    //ADMITTED STUDENTS ROUTES
    case($path == "/admitted-student/get-admitted-student" and $method == "GET"):       
        $admittedStudentsController["getAdmittedStudent"]();       
    break;

    //STUDENTS ROUTES

    case($path == "/student/login" and $method == "PUT"):
        $studentAuth["login"]();
    break;


    case($path == "/student/get-dashboard" and $method == "GET"):
        if($middlewear["isTokenValid"]()){
        
            if($middlewear["isStudent"]()){
                $studentDashboard["getDashboard"]();
            }
            else{
                $errorObj = array("status"=> 400, "msg"=> "This account is not authorized to access this route");
                $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
            }    
        }
        else{ 
            $errorObj = array("status"=> 400, "msg"=> "Unauthorized");
            $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
        } 
    break;


    case($path == "/student/pay-school-fees" and $method == "PUT"):
        if($middlewear["isTokenValid"]()){
        
            if($middlewear["isStudent"]()){
                $studentDashboard["schoolFeesPayment"]();
            }
            else{
                $errorObj = array("status"=> 400, "msg"=> "This account is not authorized to access this route");
                $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
            }    
        }
        else{ 
            $errorObj = array("status"=> 400, "msg"=> "Unauthorized");
            $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
        } 
    break;


    case($path == "/student/get-school-fees" and $method == "GET"):
        if($middlewear["isTokenValid"]()){
        
            if($middlewear["isStudent"]()){
                $studentDashboard["getSchoolFees"]();
            }
            else{
                $errorObj = array("status"=> 400, "msg"=> "This account is not authorized to access this route");
                $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
            }    
        }
        else{ 
            $errorObj = array("status"=> 400, "msg"=> "Unauthorized");
            $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
        } 
    break;


    case($path == "/student/get-registration-courses" and $method == "GET"):
        if($middlewear["isTokenValid"]()){
        
            if($middlewear["isStudent"]()){
                $studentDashboard["getRegistrationCourses"]();
            }
            else{
                $errorObj = array("status"=> 400, "msg"=> "This account is not authorized to access this route");
                $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
            }    
        }
        else{ 
            $errorObj = array("status"=> 400, "msg"=> "Unauthorized");
            $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
        } 
    break;


    case($path == "/student/get-more-registration-courses" and $method == "GET"):
        if($middlewear["isTokenValid"]()){
        
            if($middlewear["isStudent"]()){
                $studentDashboard["getMoreRegistrationCourses"]();
            }
            else{
                $errorObj = array("status"=> 400, "msg"=> "This account is not authorized to access this route");
                $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
            }    
        }
        else{ 
            $errorObj = array("status"=> 400, "msg"=> "Unauthorized");
            $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
        } 
    break;


    case($path == "/student/register-courses" and $method == "POST"):
        if($middlewear["isTokenValid"]()){
        
            if($middlewear["isStudent"]()){
                $studentDashboard["registerCourses"]();
            }
            else{
                $errorObj = array("status"=> 400, "msg"=> "This account is not authorized to access this route");
                $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
            }    
        }
        else{ 
            $errorObj = array("status"=> 400, "msg"=> "Unauthorized");
            $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
        } 
    break;


    case($path == "/student/get-profile-update-data" and $method == "GET"):
        if($middlewear["isTokenValid"]()){
        
            if($middlewear["isStudent"]()){
                $studentDashboard["getUpdateData"]();
            }
            else{
                $errorObj = array("status"=> 400, "msg"=> "This account is not authorized to access this route");
                $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
            }    
        }
        else{ 
            $errorObj = array("status"=> 400, "msg"=> "Unauthorized");
            $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
        } 
    break;

    case($path == "/student/update-profile" and $method == "PUT"):
        if($middlewear["isTokenValid"]()){
        
            if($middlewear["isStudent"]()){
                $studentDashboard["updateProfile"]();
            }
            else{
                $errorObj = array("status"=> 400, "msg"=> "This account is not authorized to access this route");
                $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
            }    
        }
        else{ 
            $errorObj = array("status"=> 400, "msg"=> "Unauthorized");
            $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
        } 
    break;


    case($path == "/student/update-email" and $method == "PUT"):
        if($middlewear["isTokenValid"]()){
        
            if($middlewear["isStudent"]()){
                $studentAuth["updateEmail"]();
            }
            else{
                $errorObj = array("status"=> 400, "msg"=> "This account is not authorized to access this route");
                $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
            }    
        }
        else{ 
            $errorObj = array("status"=> 400, "msg"=> "Unauthorized");
            $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
        } 
    break;


    case($path == "/student/update-password" and $method == "PUT"):
        if($middlewear["isTokenValid"]()){
        
            if($middlewear["isStudent"]()){
                $studentAuth["updatePassword"]();
            }
            else{
                $errorObj = array("status"=> 400, "msg"=> "This account is not authorized to access this route");
                $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
            }    
        }
        else{ 
            $errorObj = array("status"=> 400, "msg"=> "Unauthorized");
            $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
        } 
    break;


    case($path == "/student/get-result" and $method == "GET"):
        if($middlewear["isTokenValid"]()){
        
            if($middlewear["isStudent"]()){
                $studentDashboard["getResult"]();
            }
            else{
                $errorObj = array("status"=> 400, "msg"=> "This account is not authorized to access this route");
                $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
            }    
        }
        else{ 
            $errorObj = array("status"=> 400, "msg"=> "Unauthorized");
            $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
        } 
    break;


    //EMPLOYED LECTURERS ROUTES
    case($path == "/employed-lecturer/get-employed-lecturer" and $method == "GET"):       
        $employedLecturerController["getEmployedLecturer"]();       
    break;

    //LECTURERS ROUTES
    case($path == "/lecturer/login" and $method == "PUT"):
        $lecturerAuth["login"]();
    break;


    case($path == "/lecturer/get-dashboard" and $method == "GET"):
        if($middlewear["isTokenValid"]()){
        
            if($middlewear["isLecturer"]()){
                $lecturerDashboard["getDashboard"]();
            }
            else{
                $errorObj = array("status"=> 400, "msg"=> "This account is not authorized to access this route");
                $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
            }    
        }
        else{ 
            $errorObj = array("status"=> 400, "msg"=> "Unauthorized");
            $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
        } 
    break;


    case($path == "/lecturer/get-registered-course-students" and $method == "GET"):
        if($middlewear["isTokenValid"]()){
        
            if($middlewear["isLecturer"]()){
                $lecturerDashboard["getRegisteredCourseStudents"]();
            }
            else{
                $errorObj = array("status"=> 400, "msg"=> "This account is not authorized to access this route");
                $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
            }    
        }
        else{ 
            $errorObj = array("status"=> 400, "msg"=> "Unauthorized");
            $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
        } 
    break;



    case($path == "/lecturer/upload-results" and $method == "PUT"):
        if($middlewear["isTokenValid"]()){
        
            if($middlewear["isLecturer"]()){
                $lecturerDashboard["uploadResult"]();
            }
            else{
                $errorObj = array("status"=> 400, "msg"=> "This account is not authorized to access this route");
                $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
            }    
        }
        else{ 
            $errorObj = array("status"=> 400, "msg"=> "Unauthorized");
            $utilities["sendResponse"](400, "Content-Type: application/json", $errorObj, true);
        } 
    break;


    
    
    default:
    echo "404 no page found";

}






// if($path == "/admin/login" && $method == "PUT"){
//     $adminAuth["login"]();
    
// }

// else{
//     echo "404 no page found";
// }l
?>