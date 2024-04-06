<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
    $key = $_POST['key'];
	
    $uid = $_POST['uid'];
    $cid = $_POST['cid'];
    $reply = $_POST['reply'];
    $image = $_POST['image'];
 
	
	$response = array();  
	
	
	if(strcmp($AppKey,$key)==0){
		

			mysqli_query($conn, "INSERT INTO community_reply(u_id,cu_id,cr_reply,cr_reply_image) VALUES ('$uid','$cid','$reply','$image')");
	
	
		
		
		echo json_encode(['success' => true]);
		
		
		
		
		
		
		}else{
		
		echo json_encode(['success' => false]);
		
	}
	
	
	
	$conn->close();
?>