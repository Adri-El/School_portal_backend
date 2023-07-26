<?php
$database = require "lib/database.php";
$utilities = require "lib/utilities.php";
require_once realpath(__DIR__ . "/vendor/autoload.php");
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

try{
    
   $connectDatabase = $database->connect();
    
    include "routes/router.php";

}
catch(Exception $ex){
    $errorObj = array("status"=> 500, "msg"=> "something went wrong with server (database connection)");
    $utilities["sendResponse"](500, "Content-Type: application/json", $errorObj, true);
    return;
}



?>
