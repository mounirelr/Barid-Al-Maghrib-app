<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/poste/db.inc.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/poste/function.php';
check_login();


$userMatricule=$_SESSION['user']->matricule;
$todayDate=date("Y-m-d");
if($_SESSION['user']->codeProfil!=5){
    if($_SESSION['user']->codeProfil==4){
$dataComp = $conn->query("SELECT sum(price) as total, nom FROM sending s INNER JOIN product p ON p.codeProduct = s.type where idAgent=$userMatricule and s.etat='V' and sendDate = '$todayDate'  GROUP BY nom");
$dataLab = $dataComp->fetchAll(PDO::FETCH_OBJ);
$total=0;
foreach($dataLab as $v)
$total+=$v->total;
$dataCount=$conn->query("SELECT count(*) as 'nbrColis' from sending where type=1 and idAgent=$userMatricule and etat='V' and sendDate = '$todayDate'");
$nbrColis=$dataCount->fetch(PDO::FETCH_OBJ);
$dataCountc=$conn->query("SELECT count(*) as 'nbrCourrier' from sending where type=2 and idAgent=$userMatricule and etat='V' and sendDate = '$todayDate'");
$nbrCourrier=$dataCountc->fetch(PDO::FETCH_OBJ);
$nbrSms=$conn->query("SELECT count(*) as 'nbr' from sending where optionSms=1 and idAgent=$userMatricule and etat='V' and sendDate = '$todayDate'");
$sms=$nbrSms=$nbrSms->fetch(PDO::FETCH_OBJ);
$dataCity=$conn->query("SELECT count(*) as 'Nombre',d.city from destinataire d INNER JOIN sending s ON s.idDestinataire=d.id where idAgent=$userMatricule and etat='V'  and sendDate = '$todayDate' GROUP BY d.city  order by Nombre desc limit 5");
$dataTop=$dataCity->fetchAll(PDO::FETCH_OBJ);
    }
    if($_SESSION['user']->codeProfil==2){
        $userOffice=$_SESSION['user']->codeOffice;
        $dataComp = $conn->query("SELECT sum(price) as total, nom FROM sending s INNER JOIN product p ON p.codeProduct = s.type where officeId=$userOffice and s.etat='V' and sendDate = '$todayDate'  GROUP BY nom");
        $dataLab = $dataComp->fetchAll(PDO::FETCH_OBJ);
        $total=0;
        foreach($dataLab as $v)
        $total+=$v->total;
        $dataCount=$conn->query("SELECT count(*) as 'nbrColis' from sending where type=1 and officeId=$userOffice and etat='V' and sendDate = '$todayDate'");
$nbrColis=$dataCount->fetch(PDO::FETCH_OBJ);

$dataCountc=$conn->query("SELECT count(*) as 'nbrCourrier' from sending where type=2 and officeId=$userOffice and etat='V' and sendDate = '$todayDate'");
$nbrCourrier=$dataCountc->fetch(PDO::FETCH_OBJ);


        $nbrSms=$conn->query("SELECT count(*) as 'nbr' from sending where optionSms=1 and officeId=$userOffice and etat='V' and sendDate = '$todayDate'");
        $sms=$nbrSms=$nbrSms->fetch(PDO::FETCH_OBJ);

        $dataCity=$conn->query("SELECT count(*) as 'Nombre',d.city from destinataire d INNER JOIN sending s ON s.idDestinataire=d.id where officeId=$userOffice and etat='V'  and sendDate = '$todayDate' GROUP BY d.city  order by Nombre desc limit 5");
$dataTop=$dataCity->fetchAll(PDO::FETCH_OBJ);







    }
}
else{
$totalEnvoi=$conn->query("SELECT sum(price) as 'total' from sending where sendDate = '$todayDate'");
$envoi=$totalEnvoi->fetch(PDO::FETCH_OBJ);
$nbrOffice=$conn->query("SELECT count(id)  as'nbr'  from office");
$nbrOffice=$nbrOffice->fetch(PDO::FETCH_OBJ);
$nbrAgent=$conn->query("SELECT count(*) as 'nbr' from users where codeProfil=4");
$agent=$nbrAgent->fetch(PDO::FETCH_OBJ);
$cancelEnvoi=$conn->query("SELECT count(*) as 'nbr' from sending where etat='A' and sendDate = '$todayDate'");
$cancelEnvoi=$cancelEnvoi->fetch(PDO::FETCH_OBJ);

$clientData=$conn->query("SELECT concat(firstName,' ',lastName) as 'nom' ,phone,cin,count(*) as 'nbr' from client c inner join sending s on c.cin=s.idClient  where etat='V' group by nom,phone,cin");


}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aperçu</title>
    <link href="https://fonts.googleapis.com/css2?family=Gabarito&display=swap" rel="stylesheet">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/aside.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/apercu.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php  require('aside.php')  ?>
    <?php  require('navbar.php')  ?>
    <div class="content_page">
        <div class="first_section">
            <?php  if($_SESSION['user']->codeProfil!=5){
                echo "<div class=\"data_summary\">
                <i class=\"fa-solid fa-dollar-sign\"></i>
                <h3>Total d'envoi</h3>
                <span> $total DH</span>
            </div>

            <div class=\"data_summary\">
                <i class=\"fa-solid fa-box\"></i>
                <h3>Nombre de colis</h3>
                <span>$nbrColis->nbrColis</span>
            </div>

            <div class=\"data_summary\">
                <i class=\"fa-solid fa-envelope\"></i>
                <h3>Nombre de courrier</h3>
                <span>$nbrCourrier->nbrCourrier</span>
            </div>

            <div class=\"data_summary\">
                <i class=\"fa-solid fa-paper-plane\"></i>
                <h3>Envoi avec option</h3>
                <span>$sms->nbr</span>
            </div>";
            } 
            else{
                echo "<div class=\"data_summary\">
                <i class=\"fa-solid fa-dollar-sign\"></i>
                <h3>Total d'envoi</h3>
                <span>" .($envoi->total?$envoi->total:0)."DH</span>
            </div>

            <div class=\"data_summary\">
                <i class=\"fa-solid fa-building\"></i>
                <h3>Nombre d'agence</h3>
                <span>$nbrOffice->nbr</span>
            </div>

            <div class=\"data_summary\">
                <i class=\"fa-solid fa-users\"></i>
                <h3>Agents de guichet</h3>
                <span>$agent->nbr</span>
            </div>

            <div class=\"data_summary\">
                <i class=\"fa-regular fa-circle-xmark\"></i>
                <h3>Envois annulés</h3>
                <span>$cancelEnvoi->nbr</span>
            </div>";

            }?>
        </div>
       
        
            <?php  
            if($_SESSION['user']->codeProfil!=5){
                echo "<div class=\"second_section\">";
            echo "<div class=\"data_top \">
                <h3>Comparaison entre colis et courrier</h3>
                <canvas id=\"myChart\" width=\"300\" height=\"200\"></canvas>
            </div>

            
            <div class=\"data_recent\">
            <h3>Les destinations les plus demandées</h3>
            <canvas id=\"cityChart\" width=\"300\" height=\"200\"></canvas>
            </div> </div>";
            }
            else{
                echo "<h2 style=\"text-align:center; margin-top:20px\">Clients fidèles</h2>";
                echo "<div class=\"table_content\">
                <table>
                    <thead>
                        <tr>
                            
                            <th>Cin</th>
                            <th>Nom </th>
                            <th>Telephone</th>
                            <th>Nombre d'envoi</th>
                        </tr>
                    </thead>
                    <tbody>";
                         while($client=$clientData->fetch(PDO::FETCH_OBJ)){      
                            echo"<tr>";
                            echo"<td>$client->cin</td>
                            <td>$client->nom</td>
                            <td>$client->phone</td>
                             <td>$client->nbr</td>
                            </tr>";
                            }   
                            
                    echo"</tbody>
                </table>
            </div>";
            }
            
            ?>

        </div>
        </div>
        <script>
                var ctx = document.getElementById('myChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [
                            <?php foreach($dataLab as $data) {
                                echo '"' . $data->nom . '",'; 
                            } ?>
                        ],
                        datasets: [{
                            label: 'Montant',
                            data: [
                                <?php foreach($dataLab as $data) {
                                    echo $data->total . ','; 
                                } ?>
                            ],
                            backgroundColor: 'rgb(38, 120, 212)', 
                            borderColor: 'rgb(38, 120, 212)', 
                            borderWidth: 1
                        }]
                    },
                    options: {
                        maintainAspectRatio: true, 
                        responsive: true, 
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });



                var ctx = document.getElementById('cityChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [
                            <?php foreach($dataTop as $data) {
                                echo '"' . $data->city . '",'; 
                            } ?>
                        ],
                        datasets: [{
                            label: 'Montant',
                            data: [
                                <?php foreach($dataTop as $data) {
                                    echo '"' . $data->Nombre . '",'; 
                                } ?>
                            ],
                            backgroundColor: 'rgb(38, 120, 212)', 
                            borderColor: 'rgb(38, 120, 212)', 
                            borderWidth: 1
                        }]
                    },
                    options: {
                        maintainAspectRatio: true, 
                        responsive: true, 
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });


                
            </script>
        <script src="js/main.js"></script>

    </body>
</html>
