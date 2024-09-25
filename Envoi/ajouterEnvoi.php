<?php  require_once($_SERVER['DOCUMENT_ROOT'] . '/poste/db.inc.php');
require $_SERVER['DOCUMENT_ROOT'] . '/poste/function.php';
check_login();
authorization(4);
if(count($_GET)>0){
    $type = $_GET['type'];
    $weight = $_GET['weight'];
    $tarif = $_GET['tarif'];
}
function formatToNineDigits($number) {
    $numberStr = (string) $number;
    return str_pad($numberStr, 9, '0', STR_PAD_LEFT);
}
$num=$conn->query("SELECT sequence ,prefixe from product where nom='$type'");
$nums=$num->fetch(PDO::FETCH_OBJ);
$numseq=formatToNineDigits($nums->sequence);
$numseq=$nums->prefixe.$numseq.'MA';
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $price=$_POST["price"];
    //client info
 
    $cinClient=$_POST["cin"];
    $lastNameClient=$_POST["lastName"];
    $firstNameClient=$_POST["firstName"];
    $cityClient=ucfirst(strtolower($_POST["city"]));
    $adressClient=$_POST["adress"];
    $phoneClient=$_POST["phone"];

    //destinataire info

    $lastNameDes=$_POST["lastNameDes"];
    $firstNameDes=$_POST["firstNameDes"];
    $cityDes=ucfirst(strtolower($_POST["cityDes"]));
    
    $adressDes=$_POST["adressDes"];
    $phoneDes=$_POST["phoneDes"];
  
   if(isset($_POST['optionSms']))
        $optionSms=1;
    else
    $optionSms=0;

    $dataSeq=$conn->query("Select codeProduct,sequence,prefixe from product where nom='$type';");
    $sequence=$dataSeq->fetch(PDO::FETCH_OBJ);
    
    $sequenceNumber =formatToNineDigits($sequence->sequence);
    $codeEnvoie=$sequence->prefixe.$sequenceNumber.'MA';

    try{
        $conn->beginTransaction();
        $checkExist =$conn->query("SELECT * from client where cin='$cinClient'");
        $flagchange=false;
        if($checkExist->rowCount()==1){
            $checkClient=$checkExist->fetch(PDO::FETCH_OBJ);
            if($lastNameClient!=$checkClient->lastName || $firstNameClient!=$checkClient->firstName || $checkClient->city!=$cityClient || $checkClient->adress!=$adressClient||$checkClient->phone!=$phoneClient)
            $flagchange=true;
    
    if($flagchange){
        $inClient = $conn->prepare("UPDATE client set firstName=? ,lastName=?,city=?,adress=?,phone=? where cin=?");
        $inClient->execute([$firstNameClient,$lastNameClient,$cityClient,$adressClient,$phoneClient,$cinClient]);
    }
}
else{
$inClient = $conn->prepare("INSERT INTO client(cin,firstName,lastName,city,adress,phone) VALUES(?,?,?,?,?,?)");
$inClient->execute([$cinClient,$firstNameClient,$lastNameClient,$cityClient,$adressClient,$phoneClient]);
}
        $inDestinataire=$conn->prepare("INSERT INTO destinataire(firstName,lastName,city,adress,phone) VALUES(?,?,?,?,?)");
        $inDestinataire->execute([$firstNameDes,$lastNameDes,$cityDes,$adressDes,$phoneDes]);
        $idDes=intval($conn->lastInsertId());
        $idAgent=$_SESSION["user"]->matricule;
        $officeId=$_SESSION["user"]->codeOffice;
        $sendDate=date("Y-m-d");
        $sending=$conn->prepare("INSERT INTO sending(numEnvoie,`weight`,`type`,price,optionSms,idClient,idDestinataire,idAgent,sendDate,officeId) VALUES(?,?,?,?,?,?,?,?,?,?)");
        $sending->execute([$codeEnvoie,$weight,$sequence->codeProduct,$price,$optionSms,$cinClient,$idDes,$idAgent,$sendDate,$officeId]);
        $conn->exec("UPDATE product set sequence=sequence+1 where nom='$type'");
        $conn->commit();
        header("Location:index.php");

    }catch(PDOException $e){
        $conn->rollBack();
        $error=$e->getMessage();
    }


}
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
    <link rel="stylesheet" href="../css/ajouterEnvoie.css">
    
</head>
<body>
    
<?php require('../aside.php'); ?>
<?php require('../navbar.php'); ?>
<div class="content">

<div id="asideBarLeft">
    <h3 style="text-align: center;" id="numEnvoie"><?=  $numseq ?></h3>
    <h2></h2>
    <span></span>
    <br>
    <div id="TotalValue">
        <div id="line"></div>
        <span>Total:</span>
       <span> <span id="total"></span>DH</span>
    </div>
</div>

<span class="error"><?php if(isset($error)) echo $error; ?></span>
                                <form action="" method="post" id="formInfo" onsubmit="saveInfo()">
                                 <input type="hidden" name="type" id="type" value="<?= $type  ?>">
                                 <input type="hidden" name="weight"  id="weight" value="<?= $weight  ?>">
                                 <input type="hidden" name="price" id="tarif"  value="<?= $tarif  ?>">
                                 <input type="hidden" name="sendDate" id="sendDate" value="<?= date("Y-m-d")  ?>">
                                    <h2 class="titleH">Entrer les informations de l'expéditeur:</h2>
                        
                                    <div class="cin">
                                        <label for="cin">cin:</label>
                                        <input type="text" name="cin"  id="cin" required>
                                        <button id="checkUser">Verifier</button>
                                        <span id="MessageExist" style="display: none; color:red; margin-left: 220px;">Client n'existe pas</span>
                                    </div>
                                    <div class="lastName">
                                        <label for="lastName">Nom:</label>
                                        <input type="text" name="lastName"  id="lastName" required>
                                    </div>
                                    <div class="firstName">
                                        <label for="firstName">Prenom:</label>
                                        <input type="text" name="firstName" id="firstName" required>
                                    </div>
                                    <div class="city">
                                        <label for="city">Ville:</label>
                                        <input type="text" name="city" id="city" required>
                                    </div>
                                    <div class="adress">
                                        <label for="adress">Adresse:</label>
                                        <input type="text" name="adress" id="adress" required>
                                    </div>
                                    <div class="phone">
                                        <label for="phone">Telephone:</label>
                                        <input type="tel" name="phone" id="phone" required>
                                    </div>
                                    <h2 class="titleH">Entrer les informations du destinataire:</h2>
                                    <div class="lastNameDes">
                                        <label for="lastNameDes">Nom:</label>
                                        <input type="text" name="lastNameDes" id="lastNameDes" required> 
                                    </div>
                                    <div class="firstNameDes">
                                        <label for="firstNameDes">Prenom:</label>
                                        <input type="text" name="firstNameDes" id="firstNameDes" required>
                                    </div>
                                    <div class="cityDes">
                                        <label for="cityDes">Ville:</label>
                                        <input type="text" name="cityDes" id="cityDes" required>
                                    </div>
                                    <div class="adressDes">
                                        <label for="adressDes">Adresse:</label>
                                        <input type="text" name="adressDes" id="adressDes" required>
                                    </div>
                                    <div class="phoneDes">
                                        <label for="phoneDes">Telephone:</label>
                                        <input type="tel" name="phoneDes" id="phoneDes" required>
                                    </div>
                                    <div class="optionSms">
                                        <label for="optionSms">Option sms:</label>
                                        <input type="checkbox" name="optionSms" id="optionSms" value="0">
                                    </div>
                                    <div class="subButton">
                                        <button class="submitOption" type="submit">Valider</button>
                                        
                                    </div>
                                </form>


</div>

</div>

<script>
    function saveInfo(){
         let sendDetails ={};
         sendDetails['numEnvoie']=document.getElementById('numEnvoie').innerHTML;
         sendDetails['type']=document.getElementById('type').value;
         sendDetails['weight']=document.getElementById('weight').value;
         sendDetails['tarif']=document.getElementById('tarif').value;
         sendDetails['optionSms']=document.getElementById('optionSms').value;
         sendDetails['sendDate']=document.getElementById('sendDate').value;
//client Info
         sendDetails['cin']=document.getElementById('cin').value;
         sendDetails['lastNameClient']=document.getElementById('lastName').value;
         sendDetails['firstNameClient']=document.getElementById('firstName').value;
         sendDetails['cityClient']=document.getElementById('city').value;
         sendDetails['adressClient']=document.getElementById('adress').value;
         sendDetails['phoneClient']=document.getElementById('phone').value;

         //destinataire Info

         
         sendDetails['lastNameDes']=document.getElementById('lastNameDes').value;
         sendDetails['firstNameDes']=document.getElementById('firstNameDes').value;
         sendDetails['cityDes']=document.getElementById('cityDes').value;
         sendDetails['adressDes']=document.getElementById('adressDes').value;
         sendDetails['phoneDes']=document.getElementById('phoneDes').value;



        localStorage.setItem("sendDetails", JSON.stringify(sendDetails));
    }
</script>
<script src="/poste/js/main.js"></script>
<script src="/poste/js/envoie.js"></script>
<script src="/poste/js/checkUser.js"></script>
</body>
</html>