<?php   
require_once($_SERVER['DOCUMENT_ROOT'] . '/poste/function.php');
check_login();
session_unset();
session_destroy();
header("Location:index.php");
exit();
?>