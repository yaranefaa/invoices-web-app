<?php
header('Content-Type: application/json');

include('invoice-db-connection.php');
$customerData = array();
// Get search term 
$searchTerm = $_GET['term'];
$statement = $connect->prepare("
  SELECT * FROM customers where CustomerName LIKE '%" . $searchTerm . "%'
 ");
$statement->execute();
$result = $statement->fetchAll();
foreach ($result as $row) {
  $data['CustomerId'] = $row['CustomerId'];
  $data['value'] = $row['CustomerName'];
  array_push($customerData, $data);
}
// Return results as json encoded array 


echo json_encode($customerData);
