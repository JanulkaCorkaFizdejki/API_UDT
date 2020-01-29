<?php 
require_once("class/model/settings_app.php");

class DatabaseConnectManager {
    private $db_connect         = NULL;
    private $app_settings       = NULL;
    private $db_error_message   = NULL;
    private $json_error         = ["status" => 0, "type" => "error"];

    function __construct() {
        $this -> db_settings = new DatabaseSettings();
        
        try {
            $this -> db_connect = 
            new PDO('mysql:host='.$this -> db_settings -> getDBHost().
            ';dbname='.$this -> db_settings -> getDBName().';port:'.$this -> db_settings -> getDBPort(), 
            $this -> db_settings    -> getDBUser(), 
            $this -> db_settings    -> getDBPassword());
            $this -> db_connect     -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this -> db_connect     -> exec('SET NAMES utf8');
        } catch (PDOException $e) {
            $this -> db_error_message = "Connection failed: " . $e->getMessage();
            $this -> json_error["type"] = $this -> db_error_message;
            echo json_encode($this -> json_error);
        }
    }

    public function getTestPool($query, $answersLimit) {

        $db_data = $this -> db_connect -> query($query);

        $question   = NULL;
        $imageb64   = NULL;
        $answers    = [];
        $values     = [];
        $outputData = [];

        $limiter = 1;
        
        while ($row = $db_data -> fetch()) {
            $limiter = ($limiter > $answersLimit) ? $limiter = 1 : $limiter = $limiter;

            array_push($answers, $row["answers"]);
            array_push($values, $row["values"]);
            
            if ($limiter == 1) {
                $question = $row["question"];
                $imageb64 = $row["imageb64"];
                
            } else if ($limiter == $answersLimit) {
                
                $outputData[] = [
                    "question"  => $question,
                    "answers"   => $answers,
                    "values"    => $values,
                    "imageb64"  => $imageb64
                ];
                $answers    = [];
                $values     = [];
            }

            $limiter++;
        }
        $db_data -> closeCursor();
        return $outputData;
    }

    function __destruct() { $this -> db_connect = NULL; }
}
?>