<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/poste/db.inc.php');
$matriculeUserLogin=$_SESSION["user"]->matricule;
$userInfo=$conn->query("SELECT codeProfil from users where matricule=$matriculeUserLogin");
$userProfil=$userInfo->fetch(PDO::FETCH_OBJ);
?>
<div class="sidebar">
    <div class="logo">
    <img src="/poste/images/logo.png" alt="Logo">;
       
    </div>
        <ul>
     <li class="active">
     <a href="/poste/apercu.php">
     <i class="fas fa-tachometer-alt"></i>
    <span >Aperçu</span>
    </a></li>
  
    <li>
    <a href="/poste/Envoi/index.php">
    <i class="fa-solid fa-paper-plane"></i>
     <span>Envoi</span>
     </a></li>

    <?php  if($userProfil->codeProfil==5){


echo "<li>
    <a href=\"/poste/Agence/index.php\">
    <i class=\"fa-solid fa-building\"></i>
     <span>Agence</span>
     </a></li>";
     
    
echo "<li>
     <a href=\"/poste/Employe/index.php\">
            <i class=\"fa-solid fa-user\"></i>
         <span>Employe</span>
         </a></li>";

echo "<li>
            <a href=\"/poste/Tarif/index.php\">
            <i class=\"fa-solid fa-tag\"></i>
             <span>Tarif</span>
             </a></li>";

echo "<li>
                <a href=\"/poste/Rapport.php\">
                    <i class=\"fa-solid fa-chart-simple\"></i>
                <span>Rapport</span>
                </a></li>";
    }   ?>


        <li>
     <a href="/poste/parametre.php">
        <i class="fa-solid fa-gear"></i>
    <span>paramètres</span>
     </a></li>
       
        <li class="logout">
        <a href="/poste/logout.php">

     <i class="fa-solid fa-right-from-bracket"></i>
    <span>Deconnecter</span>
    </a></li>
        </ul>
    </div>
    
    <div class="main_content">