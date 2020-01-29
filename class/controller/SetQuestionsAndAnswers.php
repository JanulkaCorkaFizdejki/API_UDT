<?php
require_once("class/model/settings_app.php");
require_once("class/model/DatabaseConnectManager.php");
require_once("class/model/GeneratorTestsPool.php");

class SetQuestionsAndAnswers {

    private $setWorkspace = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16];
    private $questionLimit = 10;

    public function setIndexList () {
        // $db_connect_manager = new DatabaseConnectManager();
        // $db_connect_manager -> getTestPool("testy_udt");
        $pool = GeneratorTestsPool::generate(5, 1, 22);
        sort($pool);
        $select = "SELECT q_podesty_ruchome_przejezdne.question, a_podesty_ruchome_przejezdne.answers, a_podesty_ruchome_przejezdne.`values` 
        FROM q_podesty_ruchome_przejezdne INNER JOIN a_podesty_ruchome_przejezdne 
        ON q_podesty_ruchome_przejezdne.id = a_podesty_ruchome_przejezdne.id_questions WHERE q_podesty_ruchome_przejezdne.id IN (".implode(', ', $pool).")";
        echo $select;

    }
}

?>