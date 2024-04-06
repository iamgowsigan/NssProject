<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
	$json = file_get_contents('php://input');
	$obj = json_decode($json,true);
	
	
	$key = $_POST['key'];
    $uid = $_POST['uid'];
    $id = $_POST['id'];
    $screen = $_POST['screen'];
    $table = $_POST['table'];
    $pr = $_POST['pr'];
	
	
	$response = array();  
	
	
	if(strcmp($AppKey,$key)==0){
		
		$checkuser = mysqli_query($conn, "select * from buyer WHERE u_id='$uid' AND f_id='$id' AND screen='$screen'");
		$countuser = mysqli_num_rows($checkuser);
		
		if ($countuser == 0) {
			
			$sql = "INSERT INTO buyer(u_id,f_id,screen) VALUES ('$uid','$id','$screen')";
			if(mysqli_query($conn, $sql)){
				
				$queryupdate=mysqli_query($conn,"INSERT INTO library(u_id,f_id,screen) VALUES ('$uid','$id','$screen')");
				
				echo json_encode(['success' => true,'action' => 'added']);
				
			}
			else{
				echo json_encode(['success' => false,'action' => 'failed']);
			}
			
		}
		else{
			
			$sql = "delete from buyer WHERE u_id=$uid AND f_id=$id AND screen='$screen'";
			$result = $conn->query($sql);
			$queryupdate=mysqli_query($conn,"delete from library WHERE u_id=$uid AND f_id=$id AND screen='$screen'");
			echo json_encode(['success' => true,'action' => 'deleted']);
			
		}
		
		
		}else{
		echo json_encode(['success' => false,'action' => 'fail']);
		
	}
	
	
	
	$conn->close();
?>