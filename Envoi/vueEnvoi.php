<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/poste/db.inc.php');
require $_SERVER['DOCUMENT_ROOT'] . '/poste/function.php';
check_login();
authorization(2,4,5);
if(isset($_GET['id']))
$numEnvoie=$_GET['id'];
$data=$conn->query("SELECT sendDate,optionSms,updateDate,name,weight,price,nom,cin,CONCAT(c.firstName,' ',c.lastName) as 'nomClient',c.phone as'phoneClient',c.city as 'cityClient',CONCAT(d.firstName,' ',d.lastName) as 'nomdes',d.phone as 'phonedes',d.city as 'citydes' ,s.etat from sending s inner join product p on s.type=p.codeProduct inner join client c on c.cin=s.idClient inner join destinataire d on s.idDestinataire=d.id  inner join office o on o.id=s.officeId where numEnvoie='$numEnvoie'");
$dataEnvoie=$data->fetch(PDO::FETCH_OBJ);
if($dataEnvoie->etat=='A'){
    $dataCancel=$conn->query("select CONCAT(firstName,' ',lastName) as 'nomChef' from users where codeProfil=2 and codeOffice=(select codeOffice from users u inner join sending s on u.matricule=s.idAgent and numEnvoie='$numEnvoie')");
    $cancelData=$dataCancel->fetch(PDO::FETCH_OBJ);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Envoie</title>
    <link href="https://fonts.googleapis.com/css2?family=Gabarito&display=swap" rel="stylesheet">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../css/aside.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/vueEnvoie.css">
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
    <script src="https://unpkg.com/pdf-lib@1.17.1"></script>
</head>
<body>
<?php require($_SERVER['DOCUMENT_ROOT'] . '/poste/aside.php'); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . '/poste/navbar.php'); ?>


<div class="content_page">

<div class="container">
        <table>
            <tbody>
                <tr>
                    <th>Numero d'envoi: </th>
                    <td><?= $numEnvoie?></td>
                </tr>
                <tr>
                    <th>Date d'envoi:</th>
                    <td><?= $dataEnvoie->sendDate?></td>
                </tr>
                <tr>
                    <th>Agence:</th>
                    <td><?= $dataEnvoie->name?></td>
                </tr>

                <tr>
                    <th>Poids:</th>
                    <td><?= $dataEnvoie->weight?>g</td>
                </tr>

                <tr>
                    <th>Type: </th>
                    <td><?= $dataEnvoie->nom?></td>
                </tr>

  

                <tr>
                    <th>Prix:</th>
                    <td><?= $dataEnvoie->price?>DH</td>
                </tr>

                <tr>
                    <th>Destination</th>
                    <td>de <?= $dataEnvoie->cityClient?> a  <?= $dataEnvoie->citydes?> </td>
                </tr>


                <tr>
                    <th>cin client:</th>
                    <td><?= $dataEnvoie->cin?></td>
                </tr>

            
                <tr>
                    <th>Nom client:</th>
                    <td><?= $dataEnvoie->nomClient?></td>
                </tr>

                <tr>
                    <th>Telephone client: </th>
                    <td><?= $dataEnvoie->phoneClient?></td>
                </tr>
                <tr>
                    <th>Nom destinataire: </th>
                    <td><?= $dataEnvoie->nomdes?></td>
                </tr>

                <tr>
                    <th>Telephone destinataire: </th>
                    <td><?= $dataEnvoie->phonedes?></td>
                </tr>
                <?php
            if($dataEnvoie->etat=='A'){
                echo "<tr>
                    <th>Etat</th>
                    <td>Annuler</td>
                </tr>";
                echo "<tr>
                <th>Annuler par:</th>
                <td>$cancelData->nomChef</td>
            </tr>";
            echo "<tr>
                <th>Heure d'annulation:</th>
                <td>$dataEnvoie->updateDate</td>
            </tr>";
            }

?>

                
                


            </tbody>
        </table>
        
        <br>
       
        <div class="btn_action">
            <button id="recipe">Télécharger recu</button>
            <?php  if($dataEnvoie->sendDate==date("Y-m-d") && $dataEnvoie->etat=='V'&& $user->codeProfil==2 )
           echo "<button id=\"Annuler\"><a href=\"AnnulerEnvoi.php?id=$numEnvoie\">Annuler</a></button>";
            ?>
            
            
           
            
            
        </div>
        
    
</div>



</div>
<script>
    document.getElementById('recipe').addEventListener('click', async function(event) {
    event.preventDefault();
    await downloadReport();
});

async function fetchLogo() {
    const response = await fetch('../images/logo.png'); 
    return await response.arrayBuffer();
}

async function downloadReport() {
    const { PDFDocument, rgb } = PDFLib;

    const logoBytes = await fetchLogo();
    const pdfDoc = await PDFDocument.create();
    const page = pdfDoc.addPage([420, 595]);

    const logoImage = await pdfDoc.embedPng(logoBytes);
    const barcodeCanvas = document.createElement('canvas');
    JsBarcode(barcodeCanvas, "<?= $numEnvoie ?>", { format: 'CODE128' });

    const barcodeDataUrl = barcodeCanvas.toDataURL('image/png');
    const barcodeImage = await pdfDoc.embedPng(barcodeDataUrl);
    const logoDims = logoImage.scale(0.5);
    
    page.drawImage(logoImage, {
        x: 10,
        y: page.getHeight() - 70 - 10,
        width: 70,
        height: 70,
    });

    const barcodeDims = barcodeImage.scale(0.5);

    page.drawImage(barcodeImage, {
        x: (page.getWidth() - barcodeDims.width) / 2,
        y: page.getHeight() - barcodeDims.height - 10,
        width: barcodeDims.width,
        height: barcodeDims.height,
    });

    page.drawText("Information de l'envoi:", {
        x: (page.getWidth() - 150) / 2,
        y: page.getHeight() - barcodeDims.height - 100,
        size: 14,
        color: rgb(0, 0, 0),
    });

    const textYStart = page.getHeight() - barcodeDims.height - 140;
    let currentY = textYStart;

    const addText = (text, size = 12) => {
        page.drawText(text, {
            x: 50,
            y: currentY,
            size: size,
            color: rgb(0, 0, 0),
        });
        currentY -= size + 10;
    };

    addText(`Date d'envoi: <?= $dataEnvoie->sendDate?>`);
    addText(`Type: <?= $dataEnvoie->nom ?>`);
    addText(`Poids: <?= $dataEnvoie->weight ?> g`);
    addText(`Prix: <?= $dataEnvoie->price ?> DH`);
    addText(`Option sms: <?= $dataEnvoie->optionSms == 1 ? 'oui' : 'non' ?>`);

    addText(`----------------Information Client-------------------------`);
    addText(`Cin: <?= $dataEnvoie->cin ?>`);
    addText(`Nom: <?= $dataEnvoie->nomClient ?>`);
    addText(`Ville: <?= $dataEnvoie->cityClient ?>`);
    addText(`Téléphone: <?= $dataEnvoie->phoneClient ?>`);

    addText(`----------------Information Destinataire------------------`);
    addText(`Nom: <?= $dataEnvoie->nomdes ?>`);
    addText(`Ville: <?= $dataEnvoie->citydes ?>`);
    addText(`Téléphone: <?= $dataEnvoie->phonedes ?>`);

    const pdfBytes = await pdfDoc.save();
    const blob = new Blob([pdfBytes], { type: 'application/pdf' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = '<?= $numEnvoie ?>.pdf';
    link.click();
}
</script>
<script src="/poste/js/main.js"></script>
</body>
</html>