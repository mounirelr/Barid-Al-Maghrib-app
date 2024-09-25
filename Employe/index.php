<?php   
require $_SERVER['DOCUMENT_ROOT'] . '/poste/function.php';
check_login();
authorization(5);
require_once($_SERVER['DOCUMENT_ROOT'] . '/poste/db.inc.php');
if(count($_GET) > 0){
    $search=$_GET['search'];


if(isset($search)){
    $data= $conn->query("SELECT * FROM users WHERE (codeProfil != 5) AND etat = 'active' AND (firstName LIKE '%$search%' OR lastName LIKE '%$search%'); ");
}
}

else{
    $data =$conn->query("SELECT * from users where etat ='active' and codeProfil <> 5 ");
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employe</title>
    <link href="https://fonts.googleapis.com/css2?family=Gabarito&display=swap" rel="stylesheet">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../css/aside.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/employe.css">
</head>
<body>
    
<?php require($_SERVER['DOCUMENT_ROOT'] . '/poste/aside.php'); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . '/poste/navbar.php'); ?>

<div class="table_data">
    <div class="addEmploye">
     <a href="ajouterEmploye.php"><button>+Ajouter Employe</button></a>
     <form action="" method="get" class="search-form">
    <input type="search" name="search" id="search" placeholder="Rechercher">
    <button type="submit">Rechercher</button>
</form>

    
        
    </div>
    <div class="table_content">
        <table>
            <thead>
                <tr>
                   
                    <th>Matricule</th>
                    <th>Nom</th>
                    <th>Profil</th>
                    <th>Etat</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php  while($emp=$data->fetch(PDO::FETCH_OBJ)){
                    $prf=$conn->query("SELECT intitule from profil where id=$emp->codeProfil");
                    $prf=$prf->fetch(PDO::FETCH_OBJ);
                    echo "<td class=\"numEmp\">$emp->matricule</td>
                     <td>$emp->firstName"." "."$emp->lastName</td>";
                     echo "<td>$prf->intitule</td>
                     <td>$emp->etat</td>
                     <td>
                        <a href=\"editerEmploye.php?matricule=$emp->matricule\">Editer</a>
                        <a href=\"supprimerEmploye.php?matricule=$emp->matricule\">supprimer</a>
                </td>
                <tr>";

                     
                    }?>
            </tbody>
        </table>
    </div>
</div>



</div>

<script src="/poste/js/main.js"></script>
</body>
</html>