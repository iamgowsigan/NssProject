<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
	$image = $_POST['image'];
    $key = $_POST['key'];
    $uid = $_POST['uid'];
    $defaultimage = $_POST['defaultimage'];
	
	
	$filename="";
	
	if($defaultimage==''){
		
		if($image=="no"){
			$filename="";
			}else{
			
			
			$filename = $_FILES['image']['name'];
			$file_tmp = $_FILES['image']['tmp_name'];
			$temp = explode(".", $_FILES["image"]["name"]);
			$filename = 'user_' . round(microtime(true)) . '.' . end($temp);
			$file_type=$_FILES['image']['type'];
			
			if($production=="1"){
				
				$result = $s3->putObject([
				'Bucket' => $bucketName,
				'Key'    => $filename,
				'ContentType'    => $file_type,
				'SourceFile' => $file_tmp			
				]);
				}else{
				
				move_uploaded_file($file_tmp, $imgloc . $filename);
			}
			
			
			
			
		}
		
		}else{
		
		$filename=$defaultimage;
		
	}
	
	
	
	
	
	$response = array();  
	
	
	if(strcmp($AppKey,$key)==0){
		
		$sql = "UPDATE user SET profile_pic='$filename' WHERE u_id='$uid'";
		
		
		
		if(mysqli_query($conn, $sql)){
			//$last_id = $conn->insert_id;
			
			
			
			echo json_encode(['success' => true,'filename' => "$filename"]);
			
		}
		else{
			echo json_encode(['success' => false,'filename' => "0"]);
		}
		
		}else{
		echo json_encode(['success' => false,'filename' => "0"]);
		
	}
	
	
	
	$conn->close();
?>