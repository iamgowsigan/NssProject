<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
    $key = $_POST['key'];
	
    $uid = $_POST['uid'];
    $id = $_POST['id'];
    $screen = $_POST['screen'];
    $star = $_POST['star'];
    $attachment = $_POST['attachment'];
    $review = $_POST['review'];
    $table = $_POST['table'];
    $prefix = $_POST['prefix'];
	
	$response = array();  
	
	
	if(strcmp($AppKey,$key)==0){
		

			 
			mysqli_query($conn, "INSERT INTO reviews(u_id,f_id,screen,star,comment,r_image) VALUES ('$uid','$id','$screen','$star','$review','$attachment')");
			
			$sql="UPDATE ".$table." SET ".$prefix."_star=".$prefix."_star+$star, ".$prefix."_count=".$prefix."_count+1 WHERE ".$prefix."_id=$id";
			mysqli_query($conn,$sql );
			
	
		
		
		echo json_encode(['success' => true,'sql' => $sql ]);
		
		
		
		
		
		
		}else{
		
		echo json_encode(['success' => false]);
		
	}
	
	
	
	$conn->close();
?>