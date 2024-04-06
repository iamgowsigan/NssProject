<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
	$image = $_POST['image'];
    $key = $_POST['key'];
    $uid = $_POST['uid'];
    $title = $_POST['title'];
    $category = $_POST['category'];
    $image = $_POST['image'];
    $randomnumber = $_POST['randomnumber'];
    $pid = $_POST['pid'];
	
	
	
	
	if(strcmp($AppKey,$key)==0){
		
		
		$sql = "INSERT INTO community( u_id, cu_cat, cu_detail, p_id, cu_image) VALUES ('$uid','$category', '$title', '$pid','$image')";
		
		
		if(mysqli_query($conn, $sql)){
			
			$last_id = $conn->insert_id;
			
			$sql2 = "UPDATE community_tag SET cu_id='$last_id' WHERE cu_id='$randomnumber'";
			if(mysqli_query($conn, $sql2)){
				
				echo json_encode(['success' => true ]);
			}
				
			
		}
		else{
			echo json_encode(['success' => false ]);
		}
		
		}else{
		echo json_encode(['success' => false ]);
		
	}
	
	
	
	$conn->close();
?>