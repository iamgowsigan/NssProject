<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
    $key = $_POST['key'];
    $uid = $_POST['uid'];
    $name = $_POST['name'];
    $email = $_POST['email'];
	$expert = $_POST['expert'];
    $gender = $_POST['gender'];

  
    

	
	$response = array();  
	$user = array();  
	
	
	if(strcmp($AppKey,$key)==0){
		$sql = "SELECT * FROM experts WHERE s_email='$email' AND s_id!=$uid";
		$result = $conn->query($sql);
		
		if ($result->num_rows >0) 
		{
			
			echo json_encode(['success' => true, 'email' => false,'sql' => $sql]);

			
			}else{
			
			$sql2 = "UPDATE experts SET 
			s_name='$name',
			s_email='$email',
			s_gender='$gender',
			s_domain= '$expert'
			WHERE s_id='$uid'";
			if(mysqli_query($conn, $sql2)){
			
			
			$sql = "SELECT * FROM experts WHERE s_id=$uid";
				$result1 = $conn->query($sql);	
				if ($result1->num_rows >0) 
				{
					while($row1 = $result1->fetch_assoc()) 
					{	
						$joinall=$row1;
						array_push($user,$joinall);
					}
				} 
				
				
				echo json_encode(['success' => true, 'email' => true,'user' => $user,'sql' => $sql2]);
				
				
			}
			else{
				echo json_encode(['success' => true, 'email' => false,'sql' => $sql2]);
			}
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		}else{
		
		echo json_encode(['success' => false, 'url' => false]);
		
	}
	
	
	
	$conn->close();
?>