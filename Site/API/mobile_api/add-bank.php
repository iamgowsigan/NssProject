<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
    $key = $_POST['key'];
    $bname = $_POST['bname'];
    $blogo = $_POST['blogo'];

	

	
	if(strcmp($AppKey,$key)==0){
		
		
		
		$sql = "INSERT INTO bank(b_name,b_logo) VALUES ('$bname','$blogo')";
		if(mysqli_query($conn, $sql)){
			

		
		echo json_encode(['success' => true ,'message' => 'sucess']);
			
		}
		else{
			
				echo json_encode(['success' => true ,'message' => 'faild']);
			
		}
		
		
		
		}else{
		echo json_encode(['success' => false ,'pid' => $last_id]);
		
	}
	
	
	
	$conn->close();
?>