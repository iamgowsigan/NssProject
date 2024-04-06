<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
	
	$key = $_POST['key'];
    $cid = $_POST['cid'];
    $pid = $_POST['pid'];
	
	
	
	$response = array();  
	
	
	if(strcmp($AppKey,$key)==0){
		
 
		$getcount=0;
		$sql = "SELECT * from cart WHERE cart_id=$cid";
		$result3 = $conn->query($sql);	
		if ($result3->num_rows >0) 
		{
			while($row3 = $result3->fetch_assoc()) 
			{	
				$getcount=$row3['quantity'];
			}
		} 
		mysqli_query($conn,"UPDATE products SET p_stock_left=p_stock_left+$getcount WHERE  p_id='$pid'");
		
		
		mysqli_query($conn,"delete from cart WHERE cart_id=$cid");
		echo json_encode(['success' => true,'action' => 'updated']);
		
	}
	else{
		echo json_encode(['success' => false,'action' => 'failed']);
		
	}
	
	
	
	
	
	
	$conn->close();
?>