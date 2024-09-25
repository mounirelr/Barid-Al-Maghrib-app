<?php   
require_once($_SERVER['DOCUMENT_ROOT'] . '/poste/function.php');
check_login();
authorization(5);
require_once($_SERVER['DOCUMENT_ROOT'] . '/poste/db.inc.php');

try {
    $profil = $conn->query('SELECT * from profil');
    $agency = $conn->query('SELECT * from office');
} catch (PDOException $e) {
    $error = $e->getMessage();
}
$empID=$_GET['matricule'];
if(!empty($empID)){
    $empData=$conn->query("SELECT firstName,lastName,email,phone,adress,city,codeOffice,codeProfil from users where matricule=$empID");
}

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $lastName = htmlspecialchars($_POST["lastName"]);
    $firstName = htmlspecialchars($_POST["firstName"]);
    $email = htmlspecialchars($_POST["email"]);
    $phone = htmlspecialchars($_POST["phone"]);
    $adress = htmlspecialchars($_POST["adress"]);
    $city = htmlspecialchars($_POST["city"]);
    $agency = htmlspecialchars($_POST["agency"]);
    $profil = htmlspecialchars($_POST["profil"]);
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email";
    }
    else {
        try {
            
            if ($conn->exec("UPDATE users set  lastName='$lastName',firstName='$firstName',email='$email',phone='$phone',adress='$adress',codeOffice='$agency',codeProfil=$profil,city='$city' where matricule=$empID")) {
                header("Location:index.php");
                exit();
            }
        } catch (PDOException $e) {
            $error = $e->getMessage();
        }
    }
}





?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editer employe</title>
    <link href="https://fonts.googleapis.com/css2?family=Gabarito&display=swap" rel="stylesheet">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../css/aside.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/editerEmploye.css">
</head>
<body>
<?php require($_SERVER['DOCUMENT_ROOT'] . '/poste/aside.php'); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . '/poste/navbar.php'); ?>
<div class="content">
<span class="error"><?php if(isset($error)) echo $error; ?></span>
    <h2>Editer employe</h2>
<?php $emp=$empData->fetch(PDO::FETCH_OBJ); ?>
    <form action="" method="post">
    <div class="matricule">
        <label for="matricule">matricule:</label>
        <input type="text" name="matricule" value="<?=  $empID ?>" readonly style="background-color: lightgray;">
    </div>

    <div class="lastName">
        <label for="lastName">Nom:</label>
        <input type="text" name="lastName" value="<?= $emp->lastName  ?>" >
    </div>

    <div class="firstName">
        <label for="firstName">Prenom:</label>
        <input type="text" name="firstName" value="<?= $emp->firstName  ?>">
    </div>

    <div class="email">
        <label for="email">Email:</label>
        <input type="email" name="email" value="<?= $emp->email  ?>">
    </div>
    <div class="phone">
        <label for="phone">Telephone:</label>
        <input type="tel" name="phone"  value="<?= $emp->phone  ?>">
    </div>

    <div class="adress">
        <label for="adress">Adresse:</label>
        <input type="text" name="adress"  value="<?= $emp->adress  ?>">
    </div>

    <div class="city">
        <label for="city">Ville:</label>
        <input type="text" name="city"  value="<?= $emp->city  ?>" >
    </div>

    <div class="agency">
        <label for="agency">Agence:</label>
        <select name="agency" >
        <?php while ($data = $agency->fetch(PDO::FETCH_OBJ)) {
            if($data->id ==$emp->codeOffice)
            echo "<option value=\"$data->id\" selected>$data->name</option>";
            else
                echo "<option value=\"$data->id\">$data->name</option>";
            } ?>
        </select>
    </div>

    <div class="profil">
        <label for="profil">Profil:</label>
       
        <select name="profil" >
        <?php while ($data = $profil->fetch(PDO::FETCH_OBJ)) {
            if($data->id==$emp->codeProfil)
            echo "<option value=\"$data->id\" selected>$data->intitule</option>";
        else
                echo "<option value=\"$data->id\">$data->intitule</option>";
            } ?>
        </select>
    </div>

    <button type="submit">Modifier</button>
</form>
</div>
<script src="/poste/js/main.js"></script>
</body>
</html>