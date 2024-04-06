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
	
	
	$response = array();  
	
	
	if(strcmp($AppKey,$key)==0){
		
		$checkuser = mysqli_query($conn, "select * from notify WHERE u_id='$uid' AND p_id='$pid'");
		$countuser = mysqli_num_rows($checkuser);
		
		if ($countuser == 0) {
			
			$sql = "INSERT INTO notify(u_id,p_id) VALUES ('$uid','$pid')";
			if(mysqli_query($conn, $sql)){
				
				
				echo json_encode(['success' => true,'action' => 'added']);
				
			}
			else{
				echo json_encode(['success' => false,'action' => 'failed']);
			}
			
		}else{

			echo json_encode(['success' => true,'action' => 'already added']);
		}

	
		
		}else{
		echo json_encode(['success' => false,'action' => 'fail']);
		
	}
	
	
	
	$conn->close();
?>