<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
	$json = file_get_contents('php://input');
	$obj = json_decode($json,true);
	
	
	$key = $_POST['key'];
    $uid = $_POST['uid'];
    $aid = $_POST['aid'];
	
    $phone = $_POST['phone'];
    $name = $_POST['name'];
    $address = $_POST['address'];
	$map = $_POST['map'];
    $city = $_POST['city'];
    $country = $_POST['country'];

    
	$success=false;
	
	$response = array();  
	
	
	if(strcmp($AppKey,$key)==0){
		
		if ($aid=='' || $aid=='0') 
		{
			$sql = "INSERT INTO address(u_id,a_name,a_phone,a_address,a_city,a_country,a_map ) VALUES ('$uid','$name','$phone','$address','$city','$country','$map')";
			if(mysqli_query($conn, $sql)){
				
				echo json_encode(['success' => true,'action' => 'added']);
				
			}
			else{
				echo json_encode(['success' => false,'action' => 'failed']);
			}
			
			
		} 
		else 
		{
			
			
			$sql = "UPDATE address SET 
			u_id='$uid',
			a_name='$name',
			a_phone='$phone',
			a_address='$address',
			a_city='$city',
			a_country='$country',
			a_map='$map'
			WHERE a_id='$aid'";
			if(mysqli_query($conn, $sql)){

				echo json_encode(['success' => true]);
				
			}
			else{
				echo json_encode(['success' => false]);
			}
		}
		
	}
	else{
		echo json_encode(['success' => false,'action' => 'failed']);
		
	}
	

	$conn->close();
?>