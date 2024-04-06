<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
	
	$key = $_POST['key'];
	$uid = $_POST['uid'];
	$page = $_POST['page'];
	$tab = $_POST['tab'];
	
	
	$start = 0; 
	$limit=20;
	$start = ($page - 1) * $limit; 
	
	
	
	if(strcmp($AppKey,$key)==0){
		
		$post=array();
		
		
		
		if($tab=='1'){
			
			$sql = "SELECT community.*, user.* FROM community JOIN user ON user.u_id=community.u_id WHERE cu_status='A' AND community.u_id='$uid' ORDER BY cu_id DESC limit $start, $limit";
			$result = $conn->query($sql);	
			if ($result->num_rows >0) 
			{
				while($row = $result->fetch_assoc()) 
				{	
					
					
					$tags=array();
					$tagreply=array();
					$product=array();
					$getcid=$row['cu_id'];
					$getpid=$row['p_id'];
					
					$sql2 = "SELECT * FROM community_tag WHERE ct_status='A' AND cu_id=$getcid";
					$result2 = $conn->query($sql2);	
					if ($result2->num_rows >0) 
					{
						while($row3 = $result2->fetch_assoc()) 
						{	
							array_push($tags,$row3);
						}
					} 
					
					
					$sql3 = "SELECT * FROM community_tag_reply WHERE c_id=$getcid";
					$result3 = $conn->query($sql3);	
					if ($result3->num_rows >0) 
					{
						while($row4 = $result3->fetch_assoc()) 
						{	
							array_push($tagreply,$row4);
						}
					} 
					
					
					$sql3 = "SELECT products.*,category.* FROM products JOIN category ON category.c_id=products.c_id WHERE products.p_id=$getpid";
					$result3 = $conn->query($sql3);	
					if ($result3->num_rows >0) 
					{
						while($row4 = $result3->fetch_assoc()) 
						{	
							array_push($product,$row4);
						}
					} 
					
					$queryLike = mysqli_query($conn, "SELECT * from community_like WHERE u_id=$uid AND ci_id=$getcid");
					$countLike = mysqli_num_rows($queryLike);
					
					if($countLike==null || $countLike==""){
						$countLike =0;
					}
					
					
					$queryComment = mysqli_query($conn, "SELECT * from community_reply WHERE cr_status='A' AND cu_id=$getcid");
					$countComment = mysqli_num_rows($queryComment);
					
					if($countComment==null || $countComment==""){
						$countComment =0;
					}
					
					
					
					
					$tagarray=[ 'product' => $product,'userlike' => $countLike, 'usercomment' => $countComment,'tags' => $tags,'tagreply' => $tagreply];
					$joinall=$row+$tagarray;
					array_push($post,$joinall);
					
				}
			} 
			
			
			
			}else{
			
			
			
			$sql = "SELECT community_reply.*, community.*, user.* FROM community_reply JOIN community ON community.cu_id=community_reply.cu_id JOIN user ON user.u_id=community.u_id WHERE cu_status='A' AND cr_status='A' AND community_reply.u_id=$uid ORDER BY community_reply.cu_id DESC limit $start, $limit";
			$result = $conn->query($sql);	
			if ($result->num_rows >0) 
			{
				while($row = $result->fetch_assoc()) 
				{	
					
					
					$tags=array();
					$tagreply=array();
					$getcid=$row['cu_id'];
					$product=array();
					$getpid=$row['p_id'];
					
					$sql2 = "SELECT * FROM community_tag WHERE ct_status='A' AND cu_id=$getcid";
					$result2 = $conn->query($sql2);	
					if ($result2->num_rows >0) 
					{
						while($row3 = $result2->fetch_assoc()) 
						{	
							array_push($tags,$row3);
						}
					} 
					
					
					$sql3 = "SELECT * FROM community_tag_reply WHERE c_id=$getcid";
					$result3 = $conn->query($sql3);	
					if ($result3->num_rows >0) 
					{
						while($row4 = $result3->fetch_assoc()) 
						{	
							array_push($tagreply,$row4);
						}
					} 
					
					$sql3 = "SELECT products.*,category.* FROM products JOIN category ON category.c_id=products.c_id WHERE products.p_id=$getpid";
					$result3 = $conn->query($sql3);	
					if ($result3->num_rows >0) 
					{
						while($row4 = $result3->fetch_assoc()) 
						{	
							array_push($product,$row4);
						}
					} 
					
					$queryLike = mysqli_query($conn, "SELECT * from community_like WHERE u_id=$uid AND ci_id=$getcid");
					$countLike = mysqli_num_rows($queryLike);
					
					if($countLike==null || $countLike==""){
						$countLike =0;
					}
					
					
					$queryComment = mysqli_query($conn, "SELECT * from community_reply WHERE cr_status='A' AND cu_id=$getcid");
					$countComment = mysqli_num_rows($queryComment);
					
					if($countComment==null || $countComment==""){
						$countComment =0;
					}
					
					
					
					
					$tagarray=[ 'product' => $product,'userlike' => $countLike, 'usercomment' => $countComment,'tags' => $tags,'tagreply' => $tagreply];
					$joinall=$row+$tagarray;
					array_push($post,$joinall);
					
				}
			} 
			
			
			
		}
		
		
		
		
		
		echo json_encode(['success' => true,'post' => $post,'sql' => $sql]);
		
		
		
		
		
		
		}else{
		echo json_encode(['success' => false,'post' => $post,'sql' => $sql ]);
		
		
		
	}
	
	
	
	$conn->close();
?>