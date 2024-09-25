<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/poste/db.inc.php');


if(isset($_POST['weight'])){
    $weight = intval($_POST['weight']);
    
    
    $query = "SELECT price FROM productstash p INNER JOIN weightstash w ON p.idStash = w.id WHERE minWeight <= $weight AND maxWeight >= $weight ORDER BY price DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    
    
    if ($stmt->rowCount() > 0) {
        $response = array();
        
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $response[] = $row['price'];
        }
        
        // Send JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        
        echo json_encode(array('error' => 'No prices found for the provided weight'));
    }
} else {
   
    echo json_encode(array('error' => 'Weight not provided'));
}
?>
