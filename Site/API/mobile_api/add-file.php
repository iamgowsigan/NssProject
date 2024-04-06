<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
	$image = $_POST['image'];
    $key = $_POST['key'];
    $uid = $_POST['uid'];
    $screen = $_POST['screen'];
    $fid = $_POST['fid'];
    $sid = $_POST['sid'];
	$defaultimage = $_POST['defaultimage'];
	
	
	$filename="";
	
	
	if($defaultimage==''){
		$filename = $_FILES['image']['name'];
		$file_tmp = $_FILES['image']['tmp_name'];
		$temp = explode(".", $_FILES["image"]["name"]);
		$filename = 'uploads_' . round(microtime(true)) . '.' . end($temp);
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
		}else{
		
		$filename=$defaultimage;
		
	}
	
	
	
	$response = array();  
	$images=array();
	
	if(strcmp($AppKey,$key)==0){
		
		
		$sql = "INSERT INTO media(f_id,screen,m_name,s_id) VALUES ('$fid','$screen','$filename','$sid')";
		
		
		if(mysqli_query($conn, $sql)){
			
			
			
			echo json_encode(['success' => true, 'filename' => $filename]);
			
		}
		else{
			echo json_encode(['success' => false, 'filename' => $filename]);
			}
		
		}else{
		echo json_encode(['success' => false, 'sql' => $sql]);
		
	}
	
	
	
	$conn->close();
?>