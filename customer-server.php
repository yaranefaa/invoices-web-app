<?php 
    
	session_start();
	$db = mysqli_connect('localhost', 'root', '', 'sakr_db');

	// initialize variables
	$customerName = "";
	$id = 0;
	$update = false;

	if (isset($_POST['save'])) {
		$customerName = $_POST['customer_name'];
		$customerName=ucwords($customerName);
		
		if(strlen($customerName)>0 ){
			mysqli_query($db, "INSERT INTO customers (customerName) VALUES ('$customerName')") or die(mysqli_error($db)); 
			$_SESSION['message'] = "Customer $customerName Added!"; 
			header('location: customer.php');
		}else{
			$_SESSION['message'] = "Please make sure the name is not empty."; 
			header('location: customer.php');
		}
		
		
	}

	if (isset($_POST['update'])) {
		
		$id = $_POST['id'];
		
		
		$customerName = $_POST['customer_name'];
		$customerName=ucwords($customerName);
	    if(strlen($customerName)>0 ){
			mysqli_query($db, "UPDATE customers SET customerName='$customerName' WHERE customerId=$id") or die(mysqli_error($db));
			$_SESSION['message'] = "Customer $customerName updated!"; 
			header('location: customer.php');
		}else{
			$_SESSION['message'] = "Please make sure the name is not empty."; 
			header('location: customer.php');
		}
	}

	if (isset($_GET['del'])) {
		$data = explode(" ",$_GET['del']);
		
		mysqli_query($db, "DELETE FROM customers WHERE customerId=$data[0]") or die(mysqli_error($db));
		$_SESSION['message'] = "Customer $data[1] deleted!"; 
		header('location: customer.php');
	}
?>