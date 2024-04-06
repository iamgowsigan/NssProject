<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
 
	
	
	$key = $_POST['key'];
    $uid = $_POST['uid'];
    $ct_id = $_POST['ct_id'];
	$success=false;
	
	
	
	$response = array();  
	
	
	if(strcmp($AppKey,$key)==0){
		
		$sql = "DELETE FROM community_tag WHERE ct_id='$ct_id'";
		if(mysqli_query($conn, $sql)){
			
			echo json_encode(['success' => true,'sql' => $sql]);
			
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