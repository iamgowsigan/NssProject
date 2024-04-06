<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
	
	
	
	$key = $_POST['key'];
	$uid = $_POST['uid'];
	$id = $_POST['id'];
	$lang = $_POST['lang'];
	
	$screen = 'RESEARCH';
	
	
	
	
	if(strcmp($AppKey,$key)==0){
		
		
		$post=array();
		$media=array();;
		$reviews=array();
		$getcid='';
		
		
		
		
		mysqli_query($conn,"UPDATE research SET r_view=r_view+1 WHERE r_id=$id");
		
		if($uid!=0 && $uid!=''){
			mysqli_query($conn, "INSERT INTO viewer(u_id,f_id,screen) VALUES ('$uid','$id','$screen')");
		}
		
		
		
		$sql = "SELECT * FROM research WHERE r_id=$id";
		$result = $conn->query($sql);	
		if ($result->num_rows >0) 
		{
			while($row = $result->fetch_assoc()) 
			{	
				
				$getcid=$row['c_id'];
				$joinall=$row;				
				array_push($post,$joinall);
				
			}
			
		} 
		
		
		$sql4 = "SELECT * FROM media WHERE f_id=$id  AND screen='$screen' ORDER BY m_id ASC";
		$result4 = $conn->query($sql4);	
		if ($result4->num_rows >0) 
		{
			while($row4 = $result4->fetch_assoc()) {
				
				array_push($media,$row4);
			}
		}
		
		
		
		
		//$sql = "SELECT reviews.*,user.* FROM reviews JOIN user ON user.u_id=reviews.u_id WHERE f_id='$id' AND screen='$screen' ORDER BY re_id DESC";
		$sql = "SELECT reviews.*,user.* FROM reviews JOIN user ON user.u_id=reviews.u_id ORDER BY re_id DESC";
		$result3 = $conn->query($sql);	
		if ($result3->num_rows >0) 
		{
			while($row3 = $result3->fetch_assoc()) 
			{	
				
				$getid=$row3['re_id'];
				$queryLike = mysqli_query($conn, "SELECT * from liker WHERE screen='REVIEW' AND u_id=$uid AND f_id='$getid'");
				$countLike = mysqli_num_rows($queryLike);
				
				if($countLike==null || $countLike==""){
					$countLike =0;
				}
				$imagearray=['userlike' => $countLike];
				$joinall=$row3+$imagearray;
				
				
				array_push($reviews,$joinall);
			}
		} 
		
		
		
		$queryLike = mysqli_query($conn, "SELECT * from liker WHERE u_id=$uid AND f_id=$id AND screen='$screen'");
		$countLike = mysqli_num_rows($queryLike);
		
		if($countLike==null){
			$countLike=0;
		}
		
		$queryLibrary = mysqli_query($conn, "SELECT * from library WHERE u_id=$uid AND f_id=$id AND screen='$screen'");
		$countLibrary = mysqli_num_rows($queryLibrary);
		
		if($countLibrary==null){
			$countLibrary=0;
		}
		
		
		
		echo json_encode(['success' => true, 'post' => $post,'media' => $media,'reviews'=>$reviews,'likes'=>$countLike,'library'=>$countLibrary ]);
		
		
		
		
		
		}else{
		
		echo json_encode(['success' => false, 'post' => $post,'media' => $media, 'reviews'=>$reviews,'likes'=>$countLike,'library'=>$countLibrary ]);
		
	}
	
	
	
	$conn->close();
?>