<?php
class Database{
    private $dbServerName = "localhost";
    private $dbUsername = "root";
    private $dbPassword = "";
    private $dbName = "internship_university";
    private $db;
    public $tables = array(
        "admins"=> "admins",
        "admitted_students"=> "admitted_students"
    );

    public function connect(){
       $this->db = mysqli_connect($this->dbServerName, $this->dbUsername, $this->dbPassword, $this->dbName) ; 
       return $this->db;
    }

    public function findOne($table, $attribute, $value){
        $sql = "SELECT * FROM $table WHERE $attribute=?";

        //Create a prepared statement
        $statement = mysqli_stmt_init($this->db);
        if(mysqli_stmt_prepare($statement, $sql)){
            mysqli_stmt_bind_param($statement, "s", $value);
            mysqli_stmt_execute($statement);
            $result = mysqli_stmt_get_result($statement);
            $row = mysqli_fetch_assoc($result);
            return $row;
        }
        else{
            return null;
        }
        

    }

}

$database = new Database();
return $database;
?>