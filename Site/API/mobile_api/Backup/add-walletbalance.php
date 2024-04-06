<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	$key = $_POST['key'];
	$uid = $_POST['uid'];
	$money = $_POST['money'];
	$detail = $_POST['detail'];
	$trans = $_POST['trans'];
	
	$addedamount=array();
	if(strcmp($AppKey,$key)==0){
		
		
		
		$sql = "INSERT INTO  wallet(u_id,w_amount,w_detail,w_type,trans) VALUES ('$uid','$money','$detail','1','$trans')";
 
		if(mysqli_query($conn, $sql)){
		
		
			$queryupdate=mysqli_query($conn,"UPDATE user SET wallet=wallet+$money WHERE u_id=$uid");
			
			
			echo json_encode(['success' => true,'message' => true]);
			
		}
		else{
			echo json_encode(['success' => false,'message' => false]);
		}
		
		
		
		
		
		
		
		
		}else{
		
		echo json_encode(['success' => false,'message' => false]);
		
	}
	
	$conn->close();
?>