<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/poste/db.inc.php');
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
    <link rel="stylesheet" href="../css/Envoie1.css">
</head>
<body>
    
<?php require('../aside.php'); ?>
<?php require('../navbar.php'); ?>

<div class="table_data">
    <div class="price_calculator">
        <form id="priceForm">
            <label for="weightInput">Enter Weight (0-30):</label>
            <input type="number" step="0.001" id="weightInput" name="weightInput" min="0" max="30" placeholder="Enter weight">
            <div class="service_prices">
                <button type="button" id="service1Button">Service 1 Price</button>
                <button type="button" id="service2Button">Service 2 Price</button>
            </div>
        </form>
    </div>
</div>

<div class="ajout_depot">
    <a href="ajout_depot.php"><button>+ Ajouter nouveau depot</button></a>
</div>

<div id="asideContainer"></div>

<button id="showAsideButton">Show Total Amount</button>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const showAsideButton = document.getElementById('showAsideButton');
    const asideContainer = document.getElementById('asideContainer');

    showAsideButton.addEventListener('click', () => {
        // Create aside element
        const aside = document.createElement('aside');
        aside.style.position = 'fixed';
        aside.style.right = '0';
        aside.style.top = '0';
        aside.style.width = '300px';
        aside.style.height = '100%';
        aside.style.backgroundColor = '#f8f8f8';
        aside.style.boxShadow = '-2px 0 5px rgba(0,0,0,0.5)';
        aside.style.padding = '20px';
        aside.style.zIndex = '1000';
        aside.id = 'asideBar';

        // Calculate total amount (dummy value for demonstration)
        const totalAmount = calculateTotalAmount();

        // Create content for aside
        const content = document.createElement('div');
        content.innerHTML = `
            <h2>Total Amount</h2>
            <p>2 MAD</p>
            <button id="closeAsideButton">Close</button>
        `;

        // Append content to aside
        aside.appendChild(content);

        // Append aside to container
        asideContainer.appendChild(aside);

        // Add event listener to close button
        const closeAsideButton = document.getElementById('closeAsideButton');
        closeAsideButton.addEventListener('click', () => {
            asideContainer.removeChild(aside);
        });
    });

    function calculateTotalAmount() {
        // Replace this with your actual calculation logic
        return 100; // Dummy value
    }
});
</script>

</body>
</html>

