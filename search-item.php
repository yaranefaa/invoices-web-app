<?php
header('Content-Type: application/json');

include('invoice-db-connection.php');
$itemData = array();
// Get search term 
$searchTerm = $_GET['term'];
$statement = $connect->prepare("
  SELECT * FROM items where ItemDesc LIKE '%" . $searchTerm . "%'
 ");
$statement->execute();
$result = $statement->fetchAll();
foreach ($result as $row) {
    $data['ItemId'] = $row['ItemId'];
    $data['value'] = $row['ItemDesc'];
    array_push($itemData, $data);
}
// Return results as json encoded array 


echo json_encode($itemData);
