<?php 
    
	session_start();
	$db = mysqli_connect('localhost', 'root', '', 'sakr_db');

	// initialize variables
	$itemDesc = "";
	$id = 0;
	$update = false;

	if (isset($_POST['save'])) {
		$itemDesc = $_POST['item_desc'];
		$itemDesc=ucwords($itemDesc);
		
		if(strlen($itemDesc)>0 ){
			mysqli_query($db, "INSERT INTO items (itemDesc) VALUES ('$itemDesc')") or die(mysqli_error($db)); 
			$_SESSION['message'] = "Item $itemDesc Added!"; 
			header('location: item.php');
		}else{
			$_SESSION['message'] = "Please make sure the description is not empty."; 
			header('location: item.php');
		}
		
		
	}

	if (isset($_POST['update'])) {
		
		$id = $_POST['id'];
		
		
		$itemDesc = $_POST['item_desc'];
		$itemDesc=ucwords($itemDesc);
	    if(strlen($itemDesc)>0 ){
			mysqli_query($db, "UPDATE items SET itemDesc='$itemDesc' WHERE itemId=$id") or die(mysqli_error($db));
			$_SESSION['message'] = "Item $itemDesc updated!"; 
			header('location: item.php');
		}else{
			$_SESSION['message'] = "Please make sure the item description is not empty."; 
			header('location: item.php');
		}
	}

	if (isset($_GET['del'])) {
		$data = explode(" ",$_GET['del']);
		
		mysqli_query($db, "DELETE FROM items WHERE itemId=$data[0]") or die(mysqli_error($db));
		$_SESSION['message'] = "Item $data[1] deleted!"; 
		header('location: item.php');
	}
?>