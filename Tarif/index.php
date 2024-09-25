<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/poste/db.inc.php');
require $_SERVER['DOCUMENT_ROOT'] . '/poste/function.php';
check_login();
authorization(5);
if(count($_GET) > 0){
    $search=$_GET['search'];


if(isset($search)){

    $data= $conn->query(" Select ws.id,minWeight,maxWeight,nom,price from weightstash ws inner join productstash p on p.idStash=ws.id inner join product pr on pr.codeProduct=p.codeProduct where etat='active' and $search  between minWeight and maxWeight");
    
}
}

else{
    $data= $conn->query('Select ws.id,minWeight,maxWeight,nom,price from weightstash ws inner join productstash p on p.idStash=ws.id inner join product pr on pr.codeProduct=p.codeProduct where etat="active";');

}


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarifs</title>
    <link href="https://fonts.googleapis.com/css2?family=Gabarito&display=swap" rel="stylesheet">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../css/aside.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/tarif.css">
</head>
<body>
    
<?php require($_SERVER['DOCUMENT_ROOT'] . '/poste/aside.php'); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . '/poste/navbar.php'); ?>

<div class="table_data">
<div class="addEmploye">
     <a href="ajouterTarif.php"><button>+Ajouter Tarif</button></a>
     <form action="" method="get" class="search-form">
    <input type="search" name="search" id="search" placeholder="Rechercher">
    <button type="submit">Rechercher</button>
</form>

    
        
    </div>
    <div class="table_content">
        <table>
            <thead>
                <tr>
                    
                    <th>Numero</th>
                    <th>nom</th>
                    <th>poidsmin</th>
                    <th>poidsmax</th>
                    <th>prix</th>
                    <th>Action</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php  while($weight_stash= $data->fetch(PDO::FETCH_OBJ)){      
                    echo"<tr>";
                    echo"<td>$weight_stash->id</td>
                    <td>$weight_stash->nom</td>
                    <td>$weight_stash->minWeight g</td>
                     <td>$weight_stash->maxWeight g</td>
                     <td>$weight_stash->price DH</td>
                    <td>
                        <a href=\"editerTarif.php?id=$weight_stash->id\">Editer</a>
                        <a href=\"supprimerTarif.php?id=$weight_stash->id\">supprimer</a>
                </td>
                    </tr>";
                    }   
                    ?>
            </tbody>
        </table>
    </div>
</div>



</div>
<script src="../js/main.js"></script>
</body>
</html>