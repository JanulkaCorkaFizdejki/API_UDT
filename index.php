
<?php
    require_once("class/controller/Controller.php");
    $controller = new Controller();
    header('Content-type: application/json');
    $controller -> getTest();
    
?>

