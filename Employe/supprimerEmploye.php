<?php 
require($_SERVER['DOCUMENT_ROOT'] . '/poste/db.inc.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/poste/function.php';
check_login();
authorization(5);
$matricule= $_GET["matricule"];

if(isset($matricule)){

    if($conn->exec("UPDATE users set etat='inactive' where matricule=$matricule")){
        header("Location:index.php");
    }
}


?>