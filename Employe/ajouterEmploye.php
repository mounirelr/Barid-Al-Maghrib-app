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

function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); 
    $alphaLength = strlen($alphabet) - 1; 
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricule = htmlspecialchars($_POST["matricule"]);
    $lastName = htmlspecialchars($_POST["lastName"]);
    $firstName = htmlspecialchars($_POST["firstName"]);
    $email = htmlspecialchars($_POST["email"]);
    $phone = htmlspecialchars($_POST["phone"]);
    $adress = htmlspecialchars($_POST["adress"]);
    $city = htmlspecialchars($_POST["city"]);
    $agency = htmlspecialchars($_POST["agency"]);
    $profil = htmlspecialchars($_POST["profil"]);
    $dateHiring = date("Y-m-d");
    $passwordSend = randomPassword();
    $password = password_hash($passwordSend, PASSWORD_BCRYPT);
    $etat = "active";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email";
    } else {
        try {
            $sql = $conn->prepare("INSERT INTO users(matricule, firstName, lastName, email, password, etat, adress, codeProfil, phone, codeOffice, dateHiring) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
            if ($sql->execute([$matricule, $firstName, $lastName, $email, $password, $etat, $adress, $profil, $phone, $agency, $dateHiring])) {
                $email_subject = "Bienvenue chez Barid Al Maghrib";
                $email_body = "Chère $firstName $lastName<br>Nous avons le plaisir de vous accueillir au sein de Barid Al Maghrib<br>Afin de vous aider à démarrer, voici vos informations de connexion pour accéder à nos systèmes internes :<br>
                email: $email<br>Mot de passe: $passwordSend";
                require('../send.php');
                header("Location: index.php");
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
    <title>ajouter Employe</title>
    <link href="https://fonts.googleapis.com/css2?family=Gabarito&display=swap" rel="stylesheet">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../css/aside.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/ajouterEmploye.css">
</head>
<body>
<?php require($_SERVER['DOCUMENT_ROOT'] . '/poste/aside.php'); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . '/poste/navbar.php'); ?>
<div class="content">
<span class="error"><?php if(isset($error)) echo $error; ?></span>
    <h2>Ajouter un employe</h2>

<form action="" method="post">
    <div class="matricule">
        <label for="matricule">matricule:</label>
        <input type="text" name="matricule">
    </div>

    <div class="lastName">
        <label for="lastName">Nom:</label>
        <input type="text" name="lastName">
    </div>

    <div class="firstName">
        <label for="firstName">Prenom:</label>
        <input type="text" name="firstName">
    </div>

    <div class="email">
        <label for="email">Email:</label>
        <input type="email" name="email" >
    </div>
    <div class="phone">
        <label for="phone">Telephone:</label>
        <input type="tel" name="phone" >
    </div>

    <div class="adress">
        <label for="adress">Adresse:</label>
        <input type="text" name="adress" >
    </div>

    <div class="city">
        <label for="city">Ville:</label>
        <input type="text" name="city" >
    </div>

    <div class="agency">
        <label for="agency">Agence:</label>
        <select name="agency" >
        <?php while ($data = $agency->fetch(PDO::FETCH_OBJ)) {
                echo "<option value=\"$data->id\">$data->name</option>";
            } ?>
        </select>
    </div>

    <div class="profil">
        <label for="profil">Profil:</label>
        <select name="profil" >
        <?php while ($data = $profil->fetch(PDO::FETCH_OBJ)) {
                echo "<option value=\"$data->id\">$data->intitule</option>";
            } ?>
        </select>
    </div>

    <button type="submit">Ajouter</button>
</form>
</div>
<script src="/poste/js/main.js"></script>
</body>
</html>
