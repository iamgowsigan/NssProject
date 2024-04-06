<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
	
	
	$key = $_POST['key'];
    $uid = $_POST['uid'];
    $id = $_POST['id'];
    $screen = $_POST['screen'];
	
	
	if(strcmp($AppKey,$key)==0){
		
		
		
		
		if($screen=='community'){
			$sql = "UPDATE community SET cu_status='D'  WHERE cu_id='$id'";
		}
		
		if($screen=='comments'){
			$sql = "UPDATE community_reply SET cr_status='D'  WHERE cr_id ='$id'";
		}
		
		if($screen=='tag'){
			$sql = "UPDATE community_tag SET ct_status='D'  WHERE ct_id ='$id'";
		}
		
		if(mysqli_query($conn, $sql)){
			
			echo json_encode(['success' => true,'sql' => $sql]);
			
		}
		else{
			echo json_encode(['success' => false]);
		}
		
		
		
	}
	else{
		echo json_encode(['success' => false]);
		
	}
	
	
	
	
	
	
	$conn->close();
?>