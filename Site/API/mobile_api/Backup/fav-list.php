<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
	
	
	
	$key = $_POST['key'];
	$uid = $_POST['uid'];
	
	
	
	
	
	
	if(strcmp($AppKey,$key)==0){
		
		$favouritelist=array();
		
		
		$sql = "SELECT product_like.*, $productFields,category.*,subcategory.*,lable.* FROM product_like 
		join products ON products.p_id=product_like.p_id 
		join category ON category.c_id=products.c_id 
		join subcategory ON subcategory.sc_id=products.sc_id 
		LEFT join lable ON lable.l_id=products.l_id 
		WHERE $activeStatus AND product_like.u_id='$uid' ORDER BY product_like.p_id DESC";
		$result1 = $conn->query($sql);	
		if ($result1->num_rows >0) 
		{
			while($row1 = $result1->fetch_assoc()) 
			{	
				
				$getid=$row1['p_id'];
				$queryLike = mysqli_query($conn, "SELECT * from product_like WHERE u_id=$uid AND p_id=$getid");
				$countLike = mysqli_num_rows($queryLike);
				
				if($countLike==null || $countLike==""){
					$countLike =0;
				}
				
				$imagearray=['userlike' => $countLike];
				$joinall=$row1+$imagearray;
				
				
				array_push($favouritelist,$joinall);
			}
		}
		
		
		

		
		
		echo json_encode(['success' => true,'favouritelist' => $favouritelist ]);
		
		
		
		
		
		}else{
		
		echo json_encode(['success' => false,'savedjoblist' => $savedjoblist]);
		
	}
	
	
	
	$conn->close();
?>