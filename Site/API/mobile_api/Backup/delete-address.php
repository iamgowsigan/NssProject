<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
 
	
	
	$key = $_POST['key'];
    $uid = $_POST['uid'];
    $aid = $_POST['aid'];
	$success=false;
	
	
	
	$response = array();  
	
	
	if(strcmp($AppKey,$key)==0){
		
		$sql = "UPDATE address SET 
		a_status='0'
		WHERE a_id='$aid'";
		if(mysqli_query($conn, $sql)){
			
			echo json_encode(['success' => true]);
			
		}
		else{
			echo json_encode(['success' => false]);
		}
		
		
		
	}
	else{
		echo json_encode(['success' => false,'action' => 'failed']);
		
	}
	
	
	
	
	
	
	$conn->close();
?>