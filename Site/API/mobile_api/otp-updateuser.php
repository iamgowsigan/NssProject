<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
    $key = $_POST['key'];
	
    $name = $_POST['name'];
    $phone = $_POST['phone'];
	
    $country = $_POST['country'];
    $email = $_POST['email'];
	$city = $_POST['city'];
	$gender = $_POST['gender'];
	$expert = $_POST['expert'];
	$profile = $_POST['profile'];	

	
	
	
	
	$response = array();  
	
	
	if(strcmp($AppKey,$key)==0){
		
		
		
		$sql2 = "SELECT * FROM user WHERE email='$email' ORDER BY u_id DESC LIMIT 1";
		$result = $conn->query($sql2);
		
		if ($result->num_rows >0) 
		{
			
			echo json_encode(['success' => true, 'email' => false]);
			
			}else{
			
			
			
			
			$sql = "UPDATE user SET 
			name='$name',
			country='$country',
			city='$city',
			gender='$gender',
			expert='$expert',
			profile_pic='$profile',
			email='$email' 
			WHERE phone='$phone'";
			if(mysqli_query($conn, $sql)){
				
				
				
				
				echo json_encode(['success' => true, 'email' => true]);
				
			}
			else{
				echo json_encode(['success' => false, 'email' => true]);
			}
		}
		
		
		
		
		
		}else{
		
		echo json_encode(['success' => false, 'email' => false]);
		
	}
	
	
	
	$conn->close();
?>