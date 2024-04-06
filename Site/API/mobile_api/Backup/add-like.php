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
		
		$checkuser = mysqli_query($conn, "select * from liker WHERE u_id='$uid' AND f_id='$id' AND screen='$screen'");
		$countuser = mysqli_num_rows($checkuser);
		
		if ($countuser == 0) {
			
			$sql = "INSERT INTO liker(u_id,f_id,screen) VALUES ('$uid','$id','$screen')";
			if(mysqli_query($conn, $sql)){
				
				$queryupdate=mysqli_query($conn,"UPDATE ".$table." SET ".$pr."_like=".$pr."_like+1 WHERE ".$pr."_id=$id");
				
				echo json_encode(['success' => true,'action' => 'added','sql' => $sql]);
				
			}
			else{
				echo json_encode(['success' => false,'action' => 'failed']);
			}
			
		}
		else{
			
			$sql = "delete from liker WHERE u_id=$uid AND f_id=$id AND screen='$screen'";
			$result = $conn->query($sql);
			$queryupdate=mysqli_query($conn,"UPDATE ".$table." SET ".$pr."_like=".$pr."_like-1 WHERE ".$pr."_id=$id");
			echo json_encode(['success' => true,'action' => 'deleted','sql' => $sql]);
			
		}
		
		
		}else{
		echo json_encode(['success' => false,'action' => 'fail']);
		
	}
	
	
	
	$conn->close();
?>