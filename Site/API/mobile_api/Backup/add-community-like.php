<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
	$json = file_get_contents('php://input');
	$obj = json_decode($json,true);
	
	
	$key = $_POST['key'];
    $uid = $_POST['uid'];
    $cid = $_POST['cid'];
	
	
	$response = array();  
	
	
	if(strcmp($AppKey,$key)==0){
		
		$checkuser = mysqli_query($conn, "select * from community_like WHERE u_id='$uid' AND cu_id='$cid'");
		$countuser = mysqli_num_rows($checkuser);
		
		if ($countuser == 0) {
			
			$sql = "INSERT INTO community_like(u_id,cu_id) VALUES ('$uid','$cid')";
			if(mysqli_query($conn, $sql)){
				
				$queryupdate=mysqli_query($conn,"UPDATE community SET cu_likes=cu_likes+1 WHERE cu_id =$cid");
				
				echo json_encode(['success' => true,'action' => 'added']);
				
			}
			else{
				echo json_encode(['success' => false,'action' => 'failed']);
			}
			
		}
		else{
			
			$sql = "delete from community_like WHERE u_id=$uid AND cu_id=$cid";
			$result = $conn->query($sql);
			$queryupdate=mysqli_query($conn,"UPDATE community SET cu_likes=cu_likes-1 WHERE cu_id =$cid");
			echo json_encode(['success' => true,'action' => 'deleted']);
			
		}
		
		
		}else{
		echo json_encode(['success' => false,'action' => 'fail']);
		
	}
	
	
	
	$conn->close();
?>