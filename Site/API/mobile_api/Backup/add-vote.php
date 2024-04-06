<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
	$json = file_get_contents('php://input');
	$obj = json_decode($json,true);
	
	
	$key = $_POST['key'];
    $uid = $_POST['uid'];
    $tagid = $_POST['tagid'];
    $communityid = $_POST['communityid'];
	
	
	$response = array();  
	
	
	if(strcmp($AppKey,$key)==0){
		
	
		$counttagclick = mysqli_num_rows( mysqli_query($conn, "select * from community_tag_reply WHERE u_id='$uid' AND c_id='$communityid' AND ct_id='$tagid'"));
		
	
		if ($counttagclick == 0) {
			
			
			$checkuser = mysqli_query($conn, "select * from community_tag_reply WHERE u_id='$uid' AND c_id='$communityid'");
			$countuser = mysqli_num_rows($checkuser);
			
			if ($countuser == 0) {
				
				$sql = "INSERT INTO community_tag_reply(ct_id,c_id,u_id) VALUES ('$tagid','$communityid','$uid')";
				if(mysqli_query($conn, $sql)){
					
					//$queryupdate=mysqli_query($conn,"UPDATE product SET p_likes=p_likes+1 WHERE p_id=$pid");
					
					echo json_encode(['success' => true,'action' => 'added']);
					
				}
			}
			else{
				
				$queryupdate=mysqli_query($conn,"UPDATE community_tag_reply SET ct_id='$tagid' WHERE u_id='$uid' AND c_id='$communityid'");
				echo json_encode(['success' => true,'action' => 'updated']);
				
			}
			
			
			
		}
		else{
			
			$sql = "delete from community_tag_reply WHERE u_id='$uid' AND c_id='$communityid' AND ct_id='$tagid'";
			$result = $conn->query($sql);
			echo json_encode(['success' => true,'action' => 'deleted']);
			
			
			
			
		}
		
		
		
		
		
		
		
		}else{
		echo json_encode(['success' => false,'action' => 'fail']);
		
	}
	
	
	
	$conn->close();
?>