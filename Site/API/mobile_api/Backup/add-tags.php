<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
	$image = $_POST['image'];
    $key = $_POST['key'];
    $uid = $_POST['uid'];
    $cu_id = $_POST['cu_id'];
    $ct_title = $_POST['ct_title'];
    $ct_image = $_POST['ct_image'];
	
	
	$tags = array();  
	
	
	if(strcmp($AppKey,$key)==0){
		
		
		$sql = "INSERT INTO community_tag(u_id,cu_id,ct_pid,ct_title,ct_image) VALUES ('$uid','$cu_id','0','$ct_title','$ct_image')";
		
		
		if(mysqli_query($conn, $sql)){
			
			
			$sql2 = "SELECT * FROM community_tag WHERE cu_id='$cu_id' ORDER BY ct_id  ASC";
			$result1 = $conn->query($sql2);	
			if ($result1->num_rows >0) 
			{
				while($row1 = $result1->fetch_assoc()) 
				{	
					$joinall=$row1;
					array_push($tags,$joinall);
				}
			} 
			
			
			echo json_encode(['success' => true,'tags' => $tags]);
			
		}
		else{
			echo json_encode(['success' => false,'tags' => $tags]);
		}
		
		}else{
		echo json_encode(['success' => false,'tags' => $tags]);
		
	}
	
	
	
	$conn->close();
?>