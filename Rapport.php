<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/poste/db.inc.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/poste/function.php';
check_login();
authorization(5);

$agence= $conn->query("SELECT id,name from office where etat='active'");

if($_SERVER['REQUEST_METHOD']=="POST"){
    $startDate=$_POST['start_date'];
    $endDate=$_POST['end_date'];
    $agenceid=$_POST['office'];
    if($agenceid==0){
        $dataComp = $conn->query("SELECT sum(price) as total, nom FROM sending s INNER JOIN product p ON p.codeProduct = s.type where s.etat='V' and sendDate between '$startDate' and '$endDate' and etat='V' GROUP BY nom");
        $dataLab = $dataComp->fetchAll(PDO::FETCH_OBJ);
        $dataCity=$conn->query("SELECT count(*) as 'Nombre',city from destinataire d INNER JOIN sending s ON s.idDestinataire=d.id  where sendDate between '$startDate' and '$endDate'  and etat='V' GROUP BY d.city  order by Nombre desc limit 5");
$dataTop=$dataCity->fetchAll(PDO::FETCH_OBJ);
        $dataCount=$conn->query("SELECT count(*) as 'nbrColis' from sending where type=1 and sendDate between '$startDate' and '$endDate' and etat='V'");
$nbrColis=$dataCount->fetch(PDO::FETCH_OBJ);
$dataCountc=$conn->query("SELECT count(*) as 'nbrCourrier' from sending where type=2 and sendDate between '$startDate' and '$endDate' and etat='V'");
$nbrCourrier=$dataCountc->fetch(PDO::FETCH_OBJ);
$nbrSms=$conn->query("SELECT count(*) as 'nbr' from sending where optionSms=1 and sendDate between '$startDate' and '$endDate' and etat='V'");
$sms=$nbrSms=$nbrSms->fetch(PDO::FETCH_OBJ);
$tarifData=$conn->query("SELECT nom,minWeight,maxWeight ,sum(s.price) as 'montant' from sending s inner join product p on s.type=p.codeProduct inner join productstash pr on pr.codeProduct=p.codeProduct inner join  weightstash w on w.id=pr.idStash where  weight BETWEEN minWeight AND maxWeight and sendDate between '$startDate' and '$endDate' and s.etat='V' group by nom,minWeight,maxWeight  order by minWeight,maxWeight asc");
    }
    else{
            $dataComp = $conn->query("SELECT sum(price) as total, nom FROM sending s INNER JOIN product p ON p.codeProduct = s.type inner join  office o  on o.id=s.officeId where o.id=$agenceid and s.etat='V'and sendDate between '$startDate' and '$endDate' and o.id=$agenceid  and s.etat='V' GROUP BY nom");
            $dataLab = $dataComp->fetchAll(PDO::FETCH_OBJ);
            
            $dataCity=$conn->query("SELECT count(*) as 'Nombre',d.city from destinataire d INNER JOIN sending s ON s.idDestinataire=d.id  inner join  office o  on o.id=s.officeId where o.id=$agenceid and sendDate between '$startDate' and '$endDate'  and s.etat='V' GROUP BY d.city  order by Nombre desc limit 5");
$dataTop=$dataCity->fetchAll(PDO::FETCH_OBJ);


$dataCount=$conn->query("SELECT count(*) as 'nbrColis' from sending  s inner join office o on o.id=s.officeId where o.id=$agenceid and type=1 and sendDate between '$startDate' and '$endDate' and s.etat='V'");
$nbrColis=$dataCount->fetch(PDO::FETCH_OBJ);
$dataCountc=$conn->query("SELECT count(*) as 'nbrCourrier' from sending s inner join  office o on o.id=s.officeId where o.id=$agenceid and type=2 and sendDate between '$startDate' and '$endDate' and s.etat='V'");
$nbrCourrier=$dataCountc->fetch(PDO::FETCH_OBJ);
$nbrSms=$conn->query("SELECT count(*) as 'nbr' from sending s inner join  office o on o.id=s.officeId where o.id=$agenceid and optionSms=1 and sendDate between '$startDate' and '$endDate' and s.etat='V'");
$sms=$nbrSms=$nbrSms->fetch(PDO::FETCH_OBJ);

$tarifData=$conn->query("SELECT nom,minWeight,maxWeight ,sum(s.price) as 'montant' from sending s inner join product p on s.type=p.codeProduct inner join productstash pr on pr.codeProduct=p.codeProduct inner join  weightstash w on w.id=pr.idStash  inner join  office o on o.id=s.officeId where o.id=$agenceid and   weight BETWEEN minWeight AND maxWeight and sendDate between '$startDate' and '$endDate' and s.etat='V' group by nom,minWeight,maxWeight order by minWeight,maxWeight asc  ");



        }
       
}else{
    $startDate=date('Y-m-d', strtotime('-30 days'));
    $endDate=date("Y-m-d");
    
    $dataComp = $conn->query("SELECT sum(price) as total, nom FROM sending s INNER JOIN product p ON p.codeProduct = s.type WHERE  s.etat='V' and sendDate BETWEEN '$startDate' AND '$endDate' GROUP BY nom");
    $dataLab = $dataComp->fetchAll(PDO::FETCH_OBJ);
    $dataCount=$conn->query("SELECT count(*) as 'nbrColis' from sending where type=1 and sendDate between '$startDate' and '$endDate' and etat='V'");
$nbrColis=$dataCount->fetch(PDO::FETCH_OBJ);
$dataCountc=$conn->query("SELECT count(*) as 'nbrCourrier' from sending where type=2 and sendDate between '$startDate' and '$endDate' and etat='V'");
$nbrCourrier=$dataCountc->fetch(PDO::FETCH_OBJ);
$nbrSms=$conn->query("SELECT count(*) as 'nbr' from sending where optionSms=1 and sendDate between '$startDate' and '$endDate' and etat='V'");
$sms=$nbrSms=$nbrSms->fetch(PDO::FETCH_OBJ);
$dataCity=$conn->query("SELECT count(*) as 'Nombre',city from destinataire d INNER JOIN sending s ON s.idDestinataire=d.id  where sendDate between '$startDate' and '$endDate'  and s.etat='V' GROUP BY d.city  order by Nombre desc limit 5");
$dataTop=$dataCity->fetchAll(PDO::FETCH_OBJ);
$tarifData=$conn->query("SELECT nom,minWeight,maxWeight ,sum(s.price) as 'montant' from sending s inner join product p on s.type=p.codeProduct inner join productstash pr on pr.codeProduct=p.codeProduct inner join  weightstash w on w.id=pr.idStash where s.etat='V' and sendDate between '$startDate' and '$endDate'  and weight BETWEEN minWeight AND maxWeight group by nom,minWeight,maxWeight order by minWeight,maxWeight asc ");


    
    
}
$total=0;
foreach($dataLab as $v)
$total+=$v->total;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport</title>
    <link href="https://fonts.googleapis.com/css2?family=Gabarito&display=swap" rel="stylesheet">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/aside.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/Rapport.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://unpkg.com/pdf-lib@1.17.1"></script>
</head>
<body>
<?php require($_SERVER['DOCUMENT_ROOT'] . '/poste/aside.php'); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . '/poste/navbar.php'); ?>

<div class="first_part">
    <form id="filterForm" method="post">
        <div class="date-inputs">
            <label for="start_date">Du</label>
            <input type="date" id="start_date" name="start_date" class="date-input" value="<?php  if(isset($startDate)) echo $startDate ; else echo date("Y-m-d");?>">
        
            <label for="end_date">au</label>
            <input type="date" id="end_date" name="end_date" class="date-input" value="<?php  if(isset($endDate)) echo $endDate ; else echo date('Y-m-d', strtotime('-30 days'));?>">
            <select name="office" id="office">
            <option value="0">Tout les agences</option>
                <?php while($office=$agence->fetch(PDO::FETCH_OBJ)){
                    if(isset($agenceid)&& $agenceid==$office->id)
                    echo "<option value=\"$office->id\" selected> $office->name </option>";
                else
                    echo "<option value=\"$office->id\"> $office->name </option>";
                }
                
                ?>
            </select>
        </div>
        
        <button type="submit">Appliquer</button>
    </form>
    <button id="downloadButton">Télécharger Rapport</button>
</div>

<div class="content_page">
        <div class="first_section">
            <div class="data_summary">
                <i class="fa-solid fa-dollar-sign"></i>
                <h3>Total d'envoi</h3>
                <span><?= $total ?>DH</span>
            </div>

            <div class="data_summary">
                <i class="fa-solid fa-box"></i>
                <h3>Nombre de colis</h3>
                <span><?= $nbrColis-> nbrColis?></span>
            </div>

            <div class="data_summary">
                <i class="fa-solid fa-envelope"></i>
                <h3>Nombre de courrier</h3>
                <span><?= $nbrCourrier->nbrCourrier ?></span>
            </div>

            <div class="data_summary">
                <i class="fa-solid fa-paper-plane"></i>
                <h3>Envoi avec option</h3>
                <span><?= $sms->nbr ?></span>
            </div>
        </div>


        
        <div class="second_section">
            <div class="data_top ">
                <h3>Comparaison entre colis et courrier</h3>
                <canvas id="myChart" width="300" height="200"></canvas>
            </div>

            
            <div class="data_recent">
            <h3>Les destinations les plus demandées</h3>
            <canvas id="cityChart" width="300" height="200"></canvas>
            </div>

        </div>
        

<h3 style="text-align: center; margin-top: 25px; margin-bottom: 15px;">Montant réalisé par chaque type:</h3>

<div class="table_content">
        <table>
            <thead>
                <tr>
                    
                    <th>Type</th>
                    <th>Poids min</th>
                    <th>Poids max</th>
                    <th>Montant réalisé</th>
                    
                    
                </tr>

                <?php   while($tarif=$tarifData->fetch(PDO::FETCH_OBJ)){
                    echo "<tr style=\"
    background-color: white;\">";
                    echo "<td>$tarif->nom</td>
                    <td>$tarif->minWeight g</td>
                    <td>$tarif->maxWeight g</td>
                    <td>$tarif->montant DH</td>
                    ";
                    echo "</tr>";
                }  ?>
            </thead>
            <tbody>
                
                    
            </tbody>
        </table>
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
<script src="/poste/js/main.js"></script>
<script src="/poste/js/rapport.js"></script>
</body>
</html>