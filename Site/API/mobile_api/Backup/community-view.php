<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
	
	
	
	$key = $_POST['key'];
	$uid = $_POST['uid'];
	$cid = $_POST['cid'];
	
	
	
	
	
	if(strcmp($AppKey,$key)==0){
		
		
		
		$tagReply=array();
		$reviews=array();
		
		
		
		
		
		mysqli_query($conn,"UPDATE community SET cu_view=cu_view+1 WHERE cu_id=$cid");
		
		
		
		$sql = "SELECT community_tag_reply.*,user.* FROM community_tag_reply JOIN user ON user.u_id=community_tag_reply.u_id WHERE c_id='$cid'";
		$result = $conn->query($sql);	
		if ($result->num_rows >0) 
		{
			while($row = $result->fetch_assoc()) 
			{	
				
				$getcid=$row['c_id'];
				$joinall=$row;				
				array_push($tagReply,$joinall);
				
			}
			
		} 
		
		
		$sql = "SELECT community_reply.*,user.* FROM community_reply JOIN user ON user.u_id=community_reply.u_id WHERE community_reply.cr_status='A' ORDER BY cr_id DESC";
		
		$result = $conn->query($sql);	
		if ($result->num_rows >0) 
		{
			while($row = $result->fetch_assoc()) 
			{	
				
			
				$joinall=$row;				
				array_push($reviews,$joinall);
				
			}
			
		} 
		
		
		$queryLike = mysqli_query($conn, "SELECT * from community_like WHERE u_id=$uid AND ci_id=$cid");
		$countLike = mysqli_num_rows($queryLike);
		
		if($countLike==null || $countLike==""){
			$countLike =0;
		}
		
		
 
		
		
		//$queryLike = mysqli_query($conn, "SELECT * from product_like WHERE u_id=$uid AND p_id=$pid LIMIT 1");
		//$countLike = mysqli_num_rows($queryLike);
		
		//if($countLike==null){
		////	$countLike=0;
		//}
		
		
		
		echo json_encode(['success' => true, 'userlike' => $countLike,   'tagReply' => $tagReply,'reviews' => $reviews ]);
		
		
		
		
		
		}else{
		
		echo json_encode(['success' => false, 'userlike' => $countLike,  'tagReply' => $tagReply,'reviews' => $reviews  ]);
		
	}
	
	
	
	$conn->close();
?>