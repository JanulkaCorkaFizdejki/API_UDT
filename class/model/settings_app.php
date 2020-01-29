<?php

    class TestsList {

        private const TESTS = [
            // (1)
            "podesty_ruchome_przejezdne"    => array(
                "test-name"                 => "Podesty ruchome przejezdne",
                "test-description"          => "Opis tego testu - podestów ruchmych",
                "questions-limit"           => 15,
                "answers-limit"             => 4,
                "part-1"                    => [1, 75, 5],
                "part-2"                    => [76, 178, 5],
                "part-3"                    => [208, 244, 5],
                "threshold"                 => 11,
                "time"                      => 1800
            ),
        ];

        public function getTests () { return $this::TESTS; }
    }


    class DatabaseSettings {
        private const DB_NAME     = "";
        private const DB_USER     = "";
        private const DB_HOST     = "";
        private const DB_PASSWORD = "";
        private const DB_PORT     = "3306";

        public function getDBName     () { return $this::DB_NAME; }
        public function getDBUser     () { return $this::DB_USER; } 
        public function getDBHost     () { return $this::DB_HOST; }
        public function getDBPassword () { return $this::DB_PASSWORD; } 
        public function getDBPort     () { return $this::DB_PORT; } 
    }

    class GlobalSettings {
        private const API_MAIN_URL      = "";
        private const API_KEY_VALUE     = "";
        private const API_KEY_NAME      = "";
        private const API_TEST_NAME     = "";
        private const API_TESTS_LIST    = "";

        public function getApiKeyValue  () { return $this::API_KEY_VALUE; }
        public function getApiKeyName   () { return $this::API_KEY_NAME; }
        public function getApiTestName  () { return $this::API_TEST_NAME; }
        public function getApiTestsList () { return $this::API_TESTS_LIST; }
        public function getApiMainURL   () { return $this::API_MAIN_URL; }  
    }

?>