<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
	$key = $_POST['key'];
    $phone = $_POST['phone'];
    //$fbtoken = $_POST['fbtoken'];
	
	
	$user = array();  
	$appsettings = array();
	
	
	
	
	if(strcmp($AppKey,$key)==0){
		
		
		$sql = "SELECT * FROM app_setting ORDER BY s_id DESC LIMIT 1";
		$result = $conn->query($sql);	
		if ($result->num_rows >0) 
		{
			while($row = $result->fetch_assoc()) 
			{	
				
				array_push($appsettings,$row);
				
			}
			
		} 
		
		
		$sql = "SELECT * FROM experts WHERE s_phone='$phone' ORDER BY s_id DESC LIMIT 1";
		$result = $conn->query($sql);	
		if ($result->num_rows >0) 
		{
			while($row = $result->fetch_assoc()) 
			{	
				
				//$queryupdate=mysqli_query($conn,"UPDATE user SET fbtoken='$fbtoken' WHERE s_phone=$phone");
				array_push($user,$row);
				
			}
			
		} 
		
		

		
		
		
		
		
		echo json_encode(['success' => true,'user' => $user, 'appsettings' => $appsettings ]);
		
		
		}else{
		
		echo json_encode(['success' => false,'user' => $user, 'appsettings' => $appsettings ]);
		
	}
	
	
	
	$conn->close();
?>