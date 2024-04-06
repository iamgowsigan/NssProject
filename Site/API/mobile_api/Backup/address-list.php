<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
	$key = $_POST['key'];
	$uid = $_POST['uid'];


	if(strcmp($AppKey,$key)==0){
		

		$address=array();
		

		$sql = "SELECT * FROM address where u_id='$uid' AND a_status='1'";
		
		$result = $conn->query($sql);
		
		if ($result->num_rows >0) 
		{
			while($row[] = $result->fetch_assoc()) 
			{
				$address = $row;
			}
			
		} 
		
		

		
		echo json_encode(['success' => true,'address' => $address ]);
		
		
		
		
		
		}else{

		echo json_encode(['success' => false,'address' => $address]);
		
	}
	
	
	
	$conn->close();
?>