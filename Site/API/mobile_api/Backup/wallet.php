<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
	
	$key = $_POST['key'];
	$uid = $_POST['uid'];
	
	
	
	if(strcmp($AppKey,$key)==0){
		
		

		$user=array();
		$wallet=array();
 

		
		$sql = "SELECT *  FROM user WHERE u_id=$uid";
		$result1 = $conn->query($sql);	
		if ($result1->num_rows >0) 
		{
			while($row1 = $result1->fetch_assoc()) 
			{	
				$joinall=$row1;
				array_push($user,$joinall);
			}
		} 
		
		$sql = "SELECT *  FROM wallet WHERE u_id=$uid ORDER BY w_id DESC";
		$result2 = $conn->query($sql);	
		if ($result2->num_rows >0) 
		{
			while($row2 = $result2->fetch_assoc()) 
			{	
				$joinall=$row2;
				array_push($wallet,$joinall);
			}
		} 
		
	
				
		
		echo json_encode(['success' => true,'wallet' => $wallet,'user' => $user ]);
		
		
		
		
		
		}else{
		
		echo json_encode(['success' => false,'wallet' => $wallet,'user' => $user ]);
		
	}
	
	
	
	$conn->close();
?>