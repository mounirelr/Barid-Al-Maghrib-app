<?php  require_once($_SERVER['DOCUMENT_ROOT'] . '/poste/db.inc.php');
require $_SERVER['DOCUMENT_ROOT'] . '/poste/function.php';
check_login();
authorization(4);

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
    <link rel="stylesheet" href="../css/choixTarif.css">
</head>
<body>
    
<?php require('../aside.php'); ?>
<?php require('../navbar.php'); ?>
<div class="content">

<div class="price_calculator">
        <form id="priceForm">
            
            <input type="number" step="1" id="weightInput" name="weightInput" min="1" max="30000" placeholder="Enter un poids en g" required>
            <div class="service_prices">
                <button type="button" id="getPrice">Obtenir les Tarifs</button>
            </div>
        </form>
        <div id="priceResult">
                    <button type="button"></button>
                    <button type="button"></button>
                </div>
    </div>

   
    </div>




</div>




<script src="/poste/js/main.js"></script>
<script src="/poste/js/choixTarif.js"></script>
</body>
</html>