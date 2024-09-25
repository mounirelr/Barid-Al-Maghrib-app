<?php 

require_once($_SERVER['DOCUMENT_ROOT'] . '/poste/function.php');
check_login();
authorization(5);
require_once($_SERVER['DOCUMENT_ROOT'] . '/poste/db.inc.php');
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $type=$_POST["type"];
    $minWeight=$_POST["minWeight"];
    $maxWeight=$_POST["maxWeight"];
    $price=$_POST["price"];
    if($minWeight<0 || $maxWeight>30000)
    $error="Invalide gamme de poids";
else if($type=="courrier" && $maxWeight>5000)
$error="Courrier est entre 0 et 5kg";
else if($type=="colis" && $minWeight>30000)
$error="Courrier est entre 0 et 30kg";
else{
    try{
        //check existence range with same product name
        $testData=$conn->query("SELECT minWeight,maxWeight ,nom from weightstash w inner join productstash p on w.id=p.idStash inner join product pr on pr.codeProduct=p.codeProduct where  minWeight <= $minWeight AND maxWeight >= $maxWeight and nom='$type'");
        if($testData->rowCount()==1){
        $error='Tranche existe deja';
        }
        else{
            $conn->beginTransaction();
    $stah=$conn->prepare("INSERT INTO weightstash(minWeight,maxWeight) VALUES(?,?)");
    if($stah->execute([$minWeight,$maxWeight])){
        $lastId=intval($conn->lastInsertId());
        $productCode=$conn->query("SELECT codeProduct from product where nom='$type'");
        $productCode=$productCode->fetch(PDO::FETCH_OBJ);
    $productStah=$conn->prepare("INSERT INTO productstash(idStash,codeProduct,price,etat) VALUES(?,?,?,?)");
    if($productStah->execute([$lastId,$productCode->codeProduct,$price,'active'])){
        $conn->commit();
        header("Location:index.php");
    }
    }
}
}catch(PDOException $e){
    $conn->roolBack();
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
    <title>ajouter Tarif</title>
    <link href="https://fonts.googleapis.com/css2?family=Gabarito&display=swap" rel="stylesheet">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../css/aside.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/ajouterTarif.css">
</head>
<body>
<?php require($_SERVER['DOCUMENT_ROOT'] . '/poste/aside.php'); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . '/poste/navbar.php'); ?>
<div class="content">
<span class="error"><?php if(isset($error)) echo $error; ?></span>
    <h2>Ajouter Tarif</h2>

<form action="" method="post">
<div class="type">
        <label for="type">Chosir option:</label>
        <select name="type" >
       <option value="Colis">Colis</option>
       <option value="Courrier">Courrier</option>
        </select>
    </div>

    <div class="minWeight">
        <label for="minWeight">Poids min:</label>
        <input type="number" name="minWeight">
    </div>

    <div class="maxWeight">
        <label for="maxWeight">Poids max:</label>
        <input type="number" name="maxWeight">
    </div>

    <div class="price">
        <label for="price">Prix:</label>
        <input type="number" name="price" >
    </div>
    

    <button type="submit">Ajouter</button>
</form>
</div>
<script src="/poste/js/main.js"></script>
</body>
</html>
