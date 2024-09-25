<?php 
function check_login(){
    session_set_cookie_params([
        'secure' => false, /* false just in development environments -- but in production it's better to change it to true 
        to allow the cookies to be sent just in HTTPS only*/
        'httponly' => true, 
    ]);
    session_start();
    if(!isset($_SESSION["user"])){
        $_SESSION["url_back"] = $_SERVER['REQUEST_URI'];
        $loginUrl = '/poste/index.php';
        header("Location: $loginUrl");
        exit();
    }
}

function authorization(...$id){
    $userId=$_SESSION["user"]->codeProfil;
    
    if(!in_array($userId,$id)){
        header("Location:/poste/apercu.php");
    }


}
?>
