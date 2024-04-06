<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
	$key = $_POST['key'];
    $uid = $_POST['uid'];
    $price = $_POST['price'];
    $pid = $_POST['pid'];
    $timeexpand = $_POST['timeexpand'];
    $oldtime = $_POST['oldtime'];
	
	$newbittime='';
	if($timeexpand=='0' || $timeexpand=='' ){
		$newbittime=$oldtime;
		
		}else{
		
		
		$oldtimecut=substr($oldtime,11,5);
		$datecut=substr($oldtime,0,10);
		$newtime=date('H:i', strtotime("+$timeexpand minutes", strtotime($oldtimecut)) );
		$newbittime=$datecut.'T'.$newtime.'+0400';
		
		
	}
	
	
	
	
	
	
	$response = array();  
	
	
	if(strcmp($AppKey,$key)==0){
		
		
		
		
		$sql6 = "SELECT * FROM bidding WHERE p_id='$pid' AND price>$price";
		$result6 = $conn->query($sql6);
		
		if ($result6->num_rows >0) 
		{
			echo json_encode(['success' => false,'bookid' => 0,'sql6' => $sql6]);
			
		} 
		else 
		{
			
			
			$sql3 = "INSERT INTO bidding (u_id,p_id,price) VALUES ('$uid','$pid','$price')";
			mysqli_query($conn, $sql3);
			
			mysqli_query($conn,"UPDATE bid SET p_price_latest=$price,end_time='$newbittime' WHERE p_id=$pid");
			echo json_encode(['success' => true,'bookid' => $last_id,'sql2' => 'sss']);
			
			
			
			
		}
		
		
		
		
		
		
		
		
	}
	else{
		echo json_encode(['success' => false,'bookid' => '0']);
		
	}
	
	
	
	
	
	
	$conn->close();
?>	