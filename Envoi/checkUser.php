<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/poste/db.inc.php');

if(isset($_POST['cin'])){
    
    $cinClient=strval($_POST['cin']);
    
$data=$conn->query("SELECT firstName,lastName,city,adress,phone from client where cin='$cinClient'");
if($data->rowCount()==1){
   
    $userData=$data->fetch(PDO::FETCH_OBJ);
    $arrReponse=array('firstName'=>$userData->firstName,'lastName'=>$userData->lastName,'city'=>$userData->city,'adress'=>$userData->adress,'phone' => $userData->phone);
     // Send JSON response
     header('Content-Type: application/json');
     echo json_encode($arrReponse);
}
else {
   
    echo json_encode(array('error' => 'No client exist with this cin'));
}
}
else {
   
    echo json_encode(array('error' => 'cin not provided'));
}

?>