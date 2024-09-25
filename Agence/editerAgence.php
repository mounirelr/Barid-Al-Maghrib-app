<?php   
require_once($_SERVER['DOCUMENT_ROOT'] . '/poste/db.inc.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/poste/function.php';
check_login();
authorization(5);
$idOffice=$_GET["id"];
if(isset($idOffice)){
$officeData=$conn->query("SELECT * from office where id= $idOffice");
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $name =$_POST['name'];
    $city =$_POST['city'];
    $adress =$_POST['adress'];


if($conn->exec("UPDATE office set name='$name' , city= '$city' ,adress ='$adress' where id=$idOffice"))
header("Location:index.php");
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Agence</title>
    <link href="https://fonts.googleapis.com/css2?family=Gabarito&display=swap" rel="stylesheet">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../css/aside.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/ajouterAgence.css">
</head>
<body>
<?php require($_SERVER['DOCUMENT_ROOT'] . '/poste/aside.php'); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . '/poste/navbar.php'); ?>
<div class="content">
    <span class="error"><?php if(isset($error)) echo $error; ?></span>
    <h2>Editer agence</h2>
    <?php $office=$officeData->fetch(PDO::FETCH_OBJ);?>
<form action="" method="post">
<div class="name">
    <label for="name">Nom Agence:</label>
<input type="text" name="name" id="name" value="<?= $office->name ?>">
</div>

<div class="city">
    <label for="city">Ville:</label>
<input type="text" name="city"id="city" value="<?= $office->city ?>">
</div>


<div class="adress">
    <label for="adress">Adresse:</label>
<input type="text" name="adress" id="adress" value="<?= $office->adress ?>">
</div>

<button type="submit">Modifier</button>



</form>
</div>
</div>

<script src="/poste/js/main.js"></script>

</body>
</html>