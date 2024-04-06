<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
    $key = $_POST['key'];
    $id = $_POST['id'];
    $sid = $_POST['sid'];
    $value = $_POST['value'];
	
	
	
	
	if(strcmp($AppKey,$key)==0){
		
		$services=array();
		$mediaList=array();
		
		$sql4 = "SELECT * FROM media WHERE s_id=$sid AND f_id='$id'";
		$result4 = $conn->query($sql4);	
		if ($result4->num_rows >0) 
		{
			while($row4 = $result4->fetch_assoc()) 
			{	
				
				$joinal4=$row4;
				array_push($mediaList,$joinal4);
			}
		}
		
 
		
		if($value=="inspection"){
			$sql = "SELECT * FROM inspection WHERE i_id=$id";	
		}
		
		if($value=="replacement"){
	        $sql = "SELECT * FROM replacement WHERE r_id=$id";	
		}
		
		if($value=="compliant"){
	        $sql = "SELECT * FROM compliant WHERE c_id=$id";		
		}
		$result1 = $conn->query($sql);	
		if ($result1->num_rows >0) 
		{
			while($row1 = $result1->fetch_assoc()) 
			{	
				$joinall=$row1;				
				array_push($services,$joinall);
			}
			
		} 
		
		
		
		
		echo json_encode(['success' => true,'services' => $services,'mediaList'=>$mediaList]);
		
		}else{
		
		echo json_encode(['success' => false,'services' => $services,'mediaList'=>$mediaList]);
		
	}
	
	
	
	$conn->close();
?>