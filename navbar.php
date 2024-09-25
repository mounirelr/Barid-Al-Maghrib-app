<?php 

if(isset($_SESSION["user"])){
    try{
        $user=$_SESSION["user"];
    require_once($_SERVER['DOCUMENT_ROOT'] . '/poste/db.inc.php');
    $office=$conn->query("SELECT name from office where id=$user->codeOffice");
    $office=$office->fetch(PDO::FETCH_OBJ);
    $profile=$conn->query("SELECT intitule from profil where id=$user->codeProfil");
    $profile=$profile->fetch(PDO::FETCH_OBJ);
    }catch(PDOException $e){
        echo $e->getMessage();
    }
}

?>

<div class="header_nav">
    <div class="header_title">
        <?php    if($user->codeProfil!=5)
        echo "<span>$office->name</span>";
    
        ?>
        
    </div>
    <div class="user_info">
        <span><?= $profile->intitule ?>:</span>
            <span><?= $user->firstName ." ". $user->lastName ?></span>
    </div>
</div>