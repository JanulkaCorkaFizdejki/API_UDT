<?php
require_once("class/model/settings_app.php");
require_once("class/model/GeneratorTestsPool.php");
require_once("class/model/DatabaseConnectManager.php");

class Controller {
    private $testsListObj           = NULL;
    private $globalSettingsObj      = NULL;
    private $databaseConnectManager = NULL;
    private const JSON_ERROR        = ["status" => 0, "type" => "error"];

    function __construct () {
        $this -> testsListObj           = new TestsList();
        $this -> globalSettingsObj      = new GlobalSettings();
        $this -> databaseConnectManager = new DatabaseConnectManager();
    }

    /** VOID */
    public function getTest() {
        if (isset($_GET[$this -> globalSettingsObj -> getApiKeyName()])) {
            if ($_GET[$this -> globalSettingsObj -> getApiKeyName()] == $this -> globalSettingsObj -> getApiKeyValue ()) {
                if (!isset($_GET[$this -> globalSettingsObj -> getApiTestsList()]) && isset($_GET[$this -> globalSettingsObj -> getApiTestName()])) {  
                    if ($this -> testExists ($_GET[$this -> globalSettingsObj -> getApiTestName()])) {
                        
                        $test =  $this -> testsListObj -> getTests()[$_GET[$this -> globalSettingsObj -> getApiTestName()]];
                        $query = $this -> createQuerySelectForTest($_GET[$this -> globalSettingsObj -> getApiTestName()], $this -> generateTestIndexes($test["part-1"], $test["part-2"], $test["part-3"]));
                        
                       $json = $this -> databaseConnectManager -> getTestPool($query, $test["answers-limit"]);
                        echo json_encode($json,  JSON_PRETTY_PRINT);
                    }
                    else { $this -> httpError(); return; }
                }
                else if (isset($_GET[$this -> globalSettingsObj -> getApiTestsList()])) {
                    $this -> getTestsList (); 
                }
                else {
                    $this -> httpError();
                    return;
                }
            } else {
                $this -> httpError();
                return;
            }
        } else {
            $this -> httpError();
            return;
        }
    }

    /** VOID */
    public function getTestsList () {
        $outputData     = [];
        $testsList      = $this -> testsListObj -> getTests();
        $master_keys    = array_keys($testsList);
        $iterator       = 0;
        
        foreach ($this -> testsListObj -> getTests() as $item) {
            $array = [];
            $array = array (
                "url"           => $this -> globalSettingsObj -> getApiMainURL()."?".$this -> globalSettingsObj -> getApiKeyName ()
                                    ."=".$this -> globalSettingsObj -> getApiKeyValue()."&".$this -> globalSettingsObj -> getApiTestName()
                                    ."=".$master_keys[$iterator],
                "name"          => $item["test-name"],
                "description"   => $item["test-description"],
                "question-pool" => $item["questions-limit"],
                "answers-limit" => $item["answers-limit"],
                "threshold"     => $item["threshold"],
                "time"          => $item["time"]
            );
            array_push($outputData, $array);
            $iterator ++;
        }
        echo json_encode($outputData, JSON_PRETTY_PRINT);
    }

    /** VOID */
    private function httpError () {
        echo json_encode($this::JSON_ERROR, JSON_PRETTY_PRINT);
    }

    /** BOOL */
    private function testExists ($testName) {
        return array_key_exists($testName, $this -> testsListObj -> getTests());
    }

    /**
     * Zmienne tablicowe part: 1 argument górna granica przedziału, 2 dolna granica przedziału, 3 liczba losowanych elementów
     */
    private function generateTestIndexes ($part1 = [0, 7, 4], $part2 = [8, 12, 4], $prt3 = [13, 22, 4]) {
        $outputArray = [];
        foreach(range(1, func_num_args()) as $index) {
            $arg = func_get_arg($index - 1);
            foreach (GeneratorTestsPool::generate($arg[2], $arg[0], $arg[1]) as $element) {
                    array_push($outputArray, $element);
                }   
            }
        sort($outputArray);
        return $outputArray;
    }

    /** STRING */
    private function createQuerySelectForTest ($testShotName = "test_name", $pool = [1, 2]) {
        $table_questions    = "q_".$testShotName;
        $table_answers      = "a_".$testShotName;

        $select = "SELECT ".$table_questions.".question, ".$table_answers.".answers, ".$table_answers.".`values`, ".$table_questions.".imageb64 
        FROM ".$table_questions." INNER JOIN ".$table_answers." 
        ON ".$table_questions.".id = ".$table_answers.".id_questions WHERE ".$table_questions.".id IN (".implode(', ', $pool).")";
        return $select;
    }

    function __destruct() {
        $this -> testsListObj           = NULL;
        $this -> globalSettingsObj      = NULL;
        $this -> databaseConnectManager = NULL;
    }

}

?>