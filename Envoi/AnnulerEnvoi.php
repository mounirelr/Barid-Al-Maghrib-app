<?php
require($_SERVER['DOCUMENT_ROOT'] . '/poste/db.inc.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/poste/function.php';
check_login();
authorization(4);
$numEnvoi= $_GET["id"];

if(isset($numEnvoi)){
 
    $h=date("H:i",strtotime("+1 hours"));
    if($conn->exec("UPDATE sending set etat='A' , UpdateDate='$h' where numEnvoie='$numEnvoi' "))
    header("Location:index.php");
    
}


?>