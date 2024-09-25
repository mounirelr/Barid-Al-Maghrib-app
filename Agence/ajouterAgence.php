<?php   
require_once($_SERVER['DOCUMENT_ROOT'] . '/poste/db.inc.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/poste/function.php';
check_login();
authorization(5);
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $name=htmlspecialchars($_POST['name']);
    $city=htmlspecialchars($_POST['city']);
    $adress=htmlspecialchars($_POST['adress']);
    $openDate=date("Y-m-d");  
    try{
$sql=$conn->prepare("INSERT INTO office(name,city,adress,openDate) VALUES(?,?,?,?)");
$sql->execute([$name,$city,$adress,$openDate]);
header("Location:index.php");
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
    <h2>Ajouter une agence</h2>
<form action="" method="post">
<div class="name">
    <label for="name">Nom Agence:</label>
<input type="text" name="name" id="name">
</div>

<div class="city">
    <label for="city">Ville:</label>
<input type="text" name="city"id="city">
</div>


<div class="adress">
    <label for="adress">Adresse:</label>
<input type="text" name="adress" id="adress">
</div>

<button type="submit">Ajouter</button>



</form>
</div>
</div>

<script src="/poste/js/main.js"></script>

</body>
</html>