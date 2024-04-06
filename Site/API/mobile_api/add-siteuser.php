<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
    $key = $_POST['key'];
    $uid = $_POST['uid'];
    $sid = $_POST['sid'];
 

	
	if(strcmp($AppKey,$key)==0){
		
		
		
		$sql = "INSERT INTO site_user(u_id,s_id) VALUES ('$uid','$sid')";
		if(mysqli_query($conn, $sql)){
			

		
		echo json_encode(['success' => true ,'message' => 'User added sucessfully']);
			
		}
		else{
			
				echo json_encode(['success' => true ,'message' => 'User added Faild']);
			
		}
		
		
		
		}else{
		echo json_encode(['success' => false ,'pid' => $last_id]);
		
	}
	
	
	
	$conn->close();
?>