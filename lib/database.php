<?php
class Database{
    private $dbServerName = "localhost";
    private $dbUsername = "root";
    private $dbPassword = "";
    private $dbName = "internship_university";
    private $db;
    public $tables = array(
        "admins"=> "admins",
        "admitted_students"=> "admitted_students",
        "students"=> "students",
        "reg_number_count"=> "reg_number_count"
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

    public function insertOne($table, $data, $variable_num){
        $columns = array_keys($data);
        $values = array_values($data);
        $placeholder = array_fill(0, $variable_num, '?');
        $variables = array_fill(0, $variable_num, 's');
        $sql = "INSERT INTO $table (".implode(',', $columns).") VALUES (". implode(",", $placeholder).")";
        $statement = mysqli_stmt_init($this->db); 

        if(mysqli_stmt_prepare($statement, $sql)){
            mysqli_stmt_bind_param($statement, "".implode("", $variables), ...$values);
            mysqli_stmt_execute($statement);

            return true;
        }
        else{
            return null;
        }
    }

    // public function updateOne($table, $data, $variable_num){
    //     $stringData = "";
    //     $sql = "UPDATE $table SET "
    // }

}

$database = new Database();
return $database;
?>


    