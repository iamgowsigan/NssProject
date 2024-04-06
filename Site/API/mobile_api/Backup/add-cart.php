<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
	$json = file_get_contents('php://input');
	$obj = json_decode($json,true);
	
	
	$key = $_POST['key'];
    $uid = $_POST['uid'];
    $pid = $_POST['pid'];
    $variant = $_POST['variant']; 
    $extra_delivery = $_POST['extra_delivery'];
	
	
	$response = array();  
	
	
	if(strcmp($AppKey,$key)==0){

 
		
		//// 
		$countproduct = mysqli_num_rows(mysqli_query($conn, "select * from cart WHERE u_id='$uid' AND p_id='$pid' AND variant='$variant' AND book_id='0'"));
		
		if ($countproduct == 0) {
			
			$sql = "INSERT INTO cart(u_id,p_id,variant,quantity) VALUES ('$uid','$pid','$variant' ,'1')";
			if(mysqli_query($conn, $sql)){
				
				
				 mysqli_query($conn,"UPDATE products SET p_stock_left=p_stock_left-1 WHERE  p_id='$pid'");
				echo json_encode(['success' => true,'action' => 'added']);
				
			}
			else{
				echo json_encode(['success' => false,'action' => 'failed']);
			}
			
		}
		else{
			$queryupdate=mysqli_query($conn,"UPDATE cart SET quantity=quantity+1 WHERE u_id='$uid' AND p_id='$pid' AND variant='$variant'");
			$queryupdate=mysqli_query($conn,"UPDATE products SET p_stock_left=p_stock_left-1 WHERE  p_id='$pid'");
			echo json_encode(['success' => true,'action' => 'Updated']);
			
		}
		
		
		
		
		
		}else{
		echo json_encode(['success' => false,'action' => 'fail']);
		
	}
	
	
	
	$conn->close();
?>