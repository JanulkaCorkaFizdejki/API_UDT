<?php

class GeneratorTestsPool {

    public static function generate($pool = 10, $rangeStart = 1, $rangeStop = 10) {
        $arrayOpt = [];
        $array = range($rangeStart, $rangeStop);
        shuffle($array);

        for ($pool; $pool; $pool--) {
            $randomKey = array_rand($array);
            array_push($arrayOpt, $array[$randomKey]);
            array_splice($array, $randomKey, 1);
        }

        return $arrayOpt;
    }
}
?>