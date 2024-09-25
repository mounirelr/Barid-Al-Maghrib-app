<?php
require($_SERVER['DOCUMENT_ROOT'] . '/poste/db.inc.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/poste/function.php';
check_login();
authorization(5);

if(count($_GET) > 0){
    $search=$_GET['search'];


if(isset($search)){
    $data= $conn->query("select * from office where  etat='active' and name LIKE '%$search%' ;");
}
}

else{
$data= $conn->query('select * from office where etat="active";');
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agence</title>
    <link href="https://fonts.googleapis.com/css2?family=Gabarito&display=swap" rel="stylesheet">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../css/aside.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/agence.css">
</head>
<body>
    
<?php require($_SERVER['DOCUMENT_ROOT'] . '/poste/aside.php'); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . '/poste/navbar.php'); ?>

<div class="table_data">
    <div class="addEmploye">
        <a href="ajouterAgence.php"><button>+Ajouter Agence</button></a>
       
        <form action="index.php" method="GET" class="search-form">
            
    <input type="search" name="search" id="search" placeholder="Rechercher">
    <button type="submit">Rechercher</button>
</form>

        
    </div>
    <div class="table_content">
        <table>
            <thead>
                <tr>
                    
                    <th>Numero</th>
                    <th>Nom Agence</th>
                    <th>Ville</th>
                    <th>Adresse</th>
                    <th>Action</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php  while($office= $data->fetch(PDO::FETCH_OBJ)){      
                    echo"<tr>";
                    echo"<td>$office->id</td>
                    <td>$office->name</td>
                    <td>$office->city</td>
                     <td>$office->adress</td>
                     <td>
                     <a href=\"editerAgence.php?id=$office->id\">Editer</a>
                     <a href=\"supprimerAgence.php?id=$office->id\">Supprimer</a>
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
</body>
</html>