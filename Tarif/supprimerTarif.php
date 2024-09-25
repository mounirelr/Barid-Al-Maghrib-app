<?php 
require($_SERVER['DOCUMENT_ROOT'] . '/poste/db.inc.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/poste/function.php';
check_login();
authorization(5);
$id= $_GET["id"];

if(isset($id)){

    if($conn->exec("UPDATE productstash set etat='inactive' where idStash=$id")){
        header("Location:index.php");
    }
}


?>