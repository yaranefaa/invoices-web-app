<?php 
    
	session_start();
	$db = mysqli_connect('localhost', 'root', '', 'sakr_db');

	// initialize variables
	$sellerName = "";
    $sellerId = "";
    $commissionRate=0;
	$id = 0;
	$update = false;

	if (isset($_POST['save'])) {
		$sellerName = $_POST['seller_name'];
		$sellerName=ucwords($sellerName);
		$sellerId = $_POST['seller_id'];
		$sellerId=strtoupper($sellerId);
        $commissionRate = $_POST['commission_rate'];
		if(strlen($sellerName)>0 && strlen($sellerId)>0 && !($commissionRate<0 || $commissionRate>100)){
			mysqli_query($db, "INSERT INTO sellers (SellerId, SellerName, CommissionRate) VALUES ('$sellerId', '$sellerName','$commissionRate')") or die(mysqli_error($db)); 
			$_SESSION['message'] = "Seller $sellerName Added!"; 
			header('location: seller.php');
		}else{
			$_SESSION['message'] = "Please make sure the name and id are not empty, and the commission rate is between 0 and 100."; 
			header('location: seller.php');
		}
		
		
	}

	if (isset($_POST['update'])) {
		
		$id = $_POST['id'];
		
		
		$sellerName = $_POST['seller_name'];
		$sellerName=ucwords($sellerName);
		$sellerId = $_POST['seller_id'];
		$sellerId=strtoupper($sellerId);
		$commissionRate = $_POST['commission_rate'];
		if(strlen($sellerName)>0 && strlen($sellerId)>0 && !($commissionRate<0 || $commissionRate>100)){
			mysqli_query($db, "UPDATE sellers SET SellerName='$sellerName', SellerId='$sellerId', CommissionRate='$commissionRate' WHERE id=$id") or die(mysqli_error($db));
			$_SESSION['message'] = "Seller $sellerName updated!"; 
			header('location: seller.php');
		}else{
			$_SESSION['message'] = "Please make sure the name and id are not empty, and the commission rate is between 0 and 100."; 
			header('location: seller.php');
		}
	}

	if (isset($_GET['del'])) {
		$data = explode(" ",$_GET['del']);
		
		mysqli_query($db, "DELETE FROM sellers WHERE id=$data[0]") or die(mysqli_error($db));
		$_SESSION['message'] = "Seller $data[1] deleted!"; 
		header('location: seller.php');
	}
?>