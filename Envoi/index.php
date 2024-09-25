<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/poste/db.inc.php');
require $_SERVER['DOCUMENT_ROOT'] . '/poste/function.php';
check_login();
authorization(2,4,5);
$matriculeUserLogin=$_SESSION["user"]->matricule;
$userInfo=$conn->query("SELECT codeProfil from users where matricule=$matriculeUserLogin");
$userProfil=$userInfo->fetch(PDO::FETCH_OBJ);
if($userProfil->codeProfil==5){
    if(count($_GET)>0){
        $search=$_GET['search'];
        if(isset($search)){

            $data=$conn->query("SELECT sendDate,numEnvoie,weight,nom,price from sending s inner join product p on s.type=p.codeProduct  where numEnvoie like '%$search%' order by sendDate desc  ");
        }
    }
    else
    $data=$conn->query("SELECT sendDate,numEnvoie,weight,nom,price from sending s inner join product p where s.type=p.codeProduct  order by sendDate desc ");
}
else if($userProfil->codeProfil==2){
    $officeUser=$_SESSION["user"]->codeOffice;
    if(count($_GET)>0){
        $search=$_GET['search'];
        if(isset($search)){

            $data=$conn->query("SELECT sendDate,numEnvoie,weight,nom,price from sending s inner join product p on s.type=p.codeProduct  where numEnvoie like '%$search%' and s.officeId=$officeUser order by sendDate desc ");
        }
    }
    else
    $data=$conn->query("SELECT sendDate,numEnvoie,weight,nom,price from sending s inner join product p where s.type=p.codeProduct  and s.officeId=$officeUser order by sendDate desc ");
}
else{
    if(count($_GET)>0){
        $search=$_GET['search'];
        if(isset($search)){

            $data=$conn->query("SELECT sendDate,numEnvoie,weight,nom,price from sending s inner join product p on s.type=p.codeProduct  where numEnvoie like '%$search%' and s.idAgent=$matriculeUserLogin order by sendDate desc ");
        }
    }
    else
    $data=$conn->query("SELECT sendDate,numEnvoie,weight,nom,price from sending s inner join product p where s.type=p.codeProduct  and s.idAgent=$matriculeUserLogin order by sendDate desc ");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Envoi</title>
    <link href="https://fonts.googleapis.com/css2?family=Gabarito&display=swap" rel="stylesheet">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../css/aside.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/envoie.css">
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
    <script src="https://unpkg.com/pdf-lib@1.17.1"></script>
</head>
<body>
<?php require($_SERVER['DOCUMENT_ROOT'] . '/poste/aside.php'); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . '/poste/navbar.php'); ?>
<div class="table_data">
    
        <?php  if($user->codeProfil==4){
        echo "<div class=\"addEmploye \">
        <a href=\"choixTarif.php\"><button>+Nouveau envoi</button></a>";
    echo "<form action=\"index.php\" method=\"GET\" class=\"search-form\">
            
    <input type=\"search\" name=\"search\" id=\"search\" placeholder=\"Rechercher\">
    <button type=\"submit\">Rechercher</button>
</form>
</div>";
        }
        else{
            echo "<div class=\"addEmploye  exAdmin\">
            <form id=\"formUnique\" action=\"index.php\" method=\"GET\" class=\"search-form\">
            
    <input type=\"search\" name=\"search\" id=\"search\" placeholder=\"Rechercher\">
    <button type=\"submit\">Rechercher</button>
</form>
</div>";
        }
        ?>
        
       
        

        
    
    <div class="table_content">
        <table>
            <thead>
                <tr>
                    <th>Date d'envoi</th>
                    <th>Numero d'envoi</th>
                    <th>Type</th>
                    <th>Poids</th>
                    <th>prix</th>
                    <th>Action</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php  while($envoie= $data->fetch(PDO::FETCH_OBJ)){      
                    echo"<tr>";
                    echo"<td>$envoie->sendDate</td>
                    <td>$envoie->numEnvoie</td>
                    <td>$envoie->nom</td>
                    <td>$envoie->weight G</td>
                     <td>$envoie->price DH</td>
                     <td>
                     <a href=\"vueEnvoi.php?id=$envoie->numEnvoie\">Voir</a>
                     </td>
                    </tr>";
                    }   
                    ?>
            </tbody>
        </table>
    </div>
</div>



</div>
<script src="/poste/js/main.js"></script>
<script src="/poste/js/envoie1.js"></script>
</body>
</html>