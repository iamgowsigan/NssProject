<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	

	
	$key = $_POST['key'];
    $uid = $_POST['uid'];
    $cid = $_POST['cid'];
    $pid = $_POST['pid'];
	

	
	
	
	$response = array();  
	
	
	if(strcmp($AppKey,$key)==0){
		
		$queryupdate=mysqli_query($conn,"UPDATE cart SET quantity=quantity+1 WHERE cart_id=$cid");
		mysqli_query($conn,"UPDATE products SET p_stock_left=p_stock_left-1 WHERE  p_id='$pid'");
		echo json_encode(['success' => true,'action' => 'updated']);
		
		}
	else{
		echo json_encode(['success' => false,'action' => 'failed']);
		
	}
	
	
	
	
	
	
	$conn->close();
?>