<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
	
	
	
	$key = $_POST['key'];
	$uid = $_POST['uid'];
	$word = $_POST['word'];
	
	
	
	
	if(strcmp($AppKey,$key)==0){
		
		$products=array();
		
		
		$sql = "SELECT $productFields,category.*,subcategory.*,lable.* FROM products 
		join category ON category.c_id=products.c_id 
		join subcategory ON subcategory.sc_id=products.sc_id 
		LEFT join lable ON lable.l_id=products.l_id 
		WHERE $activeStatus AND (p_title LIKE '%$word%' OR p_detail LIKE '%$word%' OR c_name LIKE '%$word%' OR sc_name LIKE '%$word%')  ORDER BY p_id DESC ";
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
				
				
				array_push($products,$joinall);
			}
		}
		
		
 
		
		
		echo json_encode(['success' => true,'products' => $products ]);
		
		
		
		
		
		}else{
		
		echo json_encode(['success' => false,'products' => $products]);
		
	}
	
	
	
	$conn->close();
?>