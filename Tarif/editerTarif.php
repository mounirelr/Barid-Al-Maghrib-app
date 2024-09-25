<?php 

require_once($_SERVER['DOCUMENT_ROOT'] . '/poste/function.php');
check_login();
authorization(5);
require_once($_SERVER['DOCUMENT_ROOT'] . '/poste/db.inc.php');
$idTarif =$_GET["id"];
if(isset($idTarif)){
    $data=$conn->query("SELECT nom,minWeight,maxWeight,price from product p inner join productstash ps on p.codeProduct=ps.codeProduct inner join weightstash w on w.id=ps.idStash where ps.idStash=$idTarif");
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $price =$_POST["price"];
        if($db=$conn->exec("UPDATE productstash set price =$price where idStash= $idTarif "))
        header("Location:index.php");
    }
    
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editer Tarif</title>
    <link href="https://fonts.googleapis.com/css2?family=Gabarito&display=swap" rel="stylesheet">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../css/aside.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/editerTarif.css">
</head>
<body>
<?php require($_SERVER['DOCUMENT_ROOT'] . '/poste/aside.php'); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . '/poste/navbar.php'); ?>
<div class="content">
<span class="error"><?php if(isset($error)) echo $error; ?></span>
    <h2>Editer Tarif</h2>
<?php   $tarifInfo =$data->fetch(PDO::FETCH_OBJ); ?>
<form action="" method="post">
<div class="type">
        <label for="type">Chosir option:</label>
        <select name="type" >
            <?php  if($tarifInfo->nom =="Colis")
            echo "<option value=\"Colis\" selected>Colis</option>";
        else
        echo "<option value=\"Colis\" >Colis</option>";
            ?>
           
           <?php  if($tarifInfo->nom =="Courrier")
            echo "<option value=\"Courrier\" selected>Courrier</option>";
        else
        echo "<option value=\"Courrier\" >Courrier</option>";
            ?>
        </select>
    </div>

    <div class="minWeight">
        <label for="minWeight">Poids min:</label>
        <input type="number" name="minWeight" value="<?= $tarifInfo->minWeight ?>" readonly>
    </div>

    <div class="maxWeight">
        <label for="maxWeight">Poids max:</label>
        <input type="number" name="maxWeight" value="<?= $tarifInfo->maxWeight ?>" readonly>
    </div>

    <div class="price">
        <label for="price">Prix:</label>
        <input type="number" name="price" value="<?= $tarifInfo->price ?>" >
    </div>
    

    <button type="submit">Modifier</button>
</form>
</div>
<script src="/poste/js/main.js"></script>
</body>
</html>
