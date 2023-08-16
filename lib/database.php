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
        "reg_number_count"=> "reg_number_count",
        "sessions"=> "sessions",
        "courses"=> "courses",
        "year_one_fees"=> "year_one_fees",
        "year_two_fees"=> "year_two_fees",
        "year_three_fees"=> "year_three_fees",
        "year_four_fees"=> "year_four_fees",
        "year_five_fees"=> "year_five_fees",
        "year_six_fees"=> "year_six_fees"
    );

    public function connect(){
       $this->db = mysqli_connect($this->dbServerName, $this->dbUsername, $this->dbPassword, $this->dbName) ; 
       return $this->db;
    }
    public function getDB(){
        
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

    //public function updateOne($table, $data, $attribute, $value, $querry){
    public function updateOne($table, $data, $querry){
        $columns = array_keys($data);
        $values = array_values($data);
        $stringData = "";
        $count="count";
        $querryKeys = array_keys($querry); 
        $querryValues = array_values($querry);
        $querryString= "";

        for($i = 0; $i < count($columns); $i++){
    
            $stringData .= $columns[$i];
            $stringData .= "=";
            $stringData .= $values[$i];
            if($i == count($columns) -1){
                continue;
            }
            else{
                $stringData .= ", ";
            }
        }


        for($i = 0; $i < count($querryKeys); $i++){
    
            $querryString .= $querryKeys[$i];
            $querryString .= "=";
            $querryString .= $querryValues[$i];
            if($i == count($querryKeys) -1){
                continue;
            }
            else{
                $querryString .= " AND ";
            }
        }
        
       
        $sql = "UPDATE $table SET $stringData WHERE $querryString";
        
        $statement = mysqli_stmt_init($this->db); 

        if(mysqli_stmt_prepare($statement, $sql)){
           // mysqli_stmt_bind_param($statement, "".implode("", $variables), ...$values);
            mysqli_stmt_execute($statement);

            return true;
        }
        else{
            return null;
        }
    }

}

$database = new Database();
return $database;
?>


    