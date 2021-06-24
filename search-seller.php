<?php
header('Content-Type: application/json');

include('invoice-db-connection.php');
$sellerData = array();
// Get search term 
$searchTerm = $_GET['term'];
$statement = $connect->prepare("
  SELECT * FROM sellers where SellerId LIKE '%" . $searchTerm . "%'
 ");
$statement->execute();
$result = $statement->fetchAll();
foreach ($result as $row) {
    $data['SellerName'] = $row['SellerName'];
    $data['value'] = $row['SellerId'];
    array_push($sellerData, $data);
}
// Return results as json encoded array 


echo json_encode($sellerData);
