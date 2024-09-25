<?php

session_set_cookie_params([
    'secure' => false,  /* false just in development environments -- but in production it's better to change it to true 
    to allow the cookies to be sent just in HTTPS only*/
    'httponly' => true, 
    
]);

session_start();
if(isset($_SESSION['user']))
header("Location:apercu.php");
require($_SERVER['DOCUMENT_ROOT'] . '/poste/db.inc.php'); 
if($_SERVER["REQUEST_METHOD"]=="POST"){
$email = htmlspecialchars($_POST["email"]);
$password = htmlspecialchars($_POST["password"]);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
        $error="Invalid email";
else{
    try{
    $data = $conn->query("SELECT * from users where email = '$email'");
    if($data->rowCount()==1){
        $user=$data->fetch(PDO::FETCH_OBJ);
        if(password_verify($password,$user->password)){
            session_start();
            $_SESSION["user"]=$user;
            if(isset($_SESSION["url_back"])){
                $urlBack=$_SESSION["url_back"];
                header("Location:$urlBack");
                unset($_SESSION["url_back"]);
                exit();
            }
            header("Location:apercu.php");
            exit();
        }
        
    }
    $error="Invalide email ou mot de passe";
    
}catch(PDOException $e){
    $error=$e->getMessage();
}
}
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barid</title>
    <link href="https://fonts.googleapis.com/css2?family=Gabarito&display=swap" rel="stylesheet">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
<div class="background"></div>
    <div class="card">
        
        <img src="images/logo.png" alt="Logo">
        
        <form class="form" method="post">
            
            <input type="email" name="email" placeholder="Email">
            <input type="password" name="password" placeholder="Mot de passe">
            <button type="submit">Login</button>
            <span class="error"><?php if(isset($error)) echo $error; ?></span>
        </form>
    </div>
</body>
</html>