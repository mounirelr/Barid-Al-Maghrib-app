<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/poste/function.php');
check_login();

require_once($_SERVER['DOCUMENT_ROOT'] . '/poste/db.inc.php');
$user=$_SESSION['user'];


if($_SERVER["REQUEST_METHOD"]=="POST"){
    $lastPassword=$_POST['lastPassword'];
    $newPassword=$_POST['newPassword'];
    $newPassword2=$_POST['newPassword2'];

    try{
     if(password_verify($lastPassword,$user->password)){
        if($newPassword==$newPassword2){
            $password=password_hash($newPassword,PASSWORD_BCRYPT);
            $sql=$conn->prepare("UPDATE users set password=? where matricule=$user->matricule");
            if($sql->execute([$password])){
                $succ="Mot de passe modifier avec succes";
            }
        }else{
            $error="erreur mot de passe ne sont pas conforme";
        }
     }else{
        $error="Mot de passe Incorrect";
     }

    }catch(PDOException $e){

        $error=$e->getMessage();
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>paramètres</title>
    <link href="https://fonts.googleapis.com/css2?family=Gabarito&display=swap" rel="stylesheet">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/aside.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/parametre.css">
</head>
<body>
<?php require($_SERVER['DOCUMENT_ROOT'] . '/poste/aside.php'); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . '/poste/navbar.php'); ?>
<div class="content">
<span class="error"><?php if(isset($error)) echo $error; ?></span>
<span class="succes"><?php if(isset($succ)) echo $succ; ?></span>
<h2>Mettre à jour votre Mot de passe</h2>
<form action="" method="post">

    <div class="email">
        <label for="email">Email:</label>
       
        <input type="email" name="email"  value="<?= $user->email ?>" readonly style="background-color: lightgray;">
    </div>

    <div class="lastPassword">
        <label for="lastPassword">Ancien Mot de passe:</label>
        <input type="password" name="lastPassword" >
    </div>

    <div class="newPassword">
        <label for="newPassword">Nouveau Mot de passe:</label>
        <input type="password" name="newPassword" >
    </div>
    <div class="newPassword2">
        <label for="newPassword2">Confirmer Mot de passe:</label>
        <input type="password" name="newPassword2" >
    </div>
    

    
    

    <button type="submit">Modifier</button>
</form>
</div>
<script src="js/main.js"></script>
</body>
</html>