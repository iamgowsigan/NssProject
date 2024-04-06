<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	

    $key = $_POST['key'];
    $mid = $_POST['mid'];
		
	
	
	$response = array();  
	
	
	if(strcmp($AppKey,$key)==0){
		

		
		$sql = "delete from media WHERE m_id=$mid ";

		
		
		if(mysqli_query($conn, $sql)){
			
			
			
			echo json_encode(['success' => true, 'message'=> 'Image deleted successfully']);
			
		}
		else{
			echo json_encode(['success' => false,'message'=> 'Faild']);
		}
		
		}else{
		echo json_encode(['success' => false]);
		
	}
	
	
	
	$conn->close();
?>