<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
	
	
	
	$key = $_POST['key'];
	$uid = $_POST['uid'];
	$pid = $_POST['pid'];
	
	
	
	
	
	if(strcmp($AppKey,$key)==0){
		
		
		$products=array();
		$images=array();
		$variants=array();
		$related=array();
		$reviews=array();
		$getcid='';
		
		
		
		
		$queryupdate=mysqli_query($conn,"UPDATE products SET views=views+1 WHERE p_id=$pid");
		if($uid!=0 && $uid!=''){
			mysqli_query($conn, "INSERT INTO product_views(u_id,p_id) VALUES ('$uid','$pid')");
		}
		
		
		
		$sql = "SELECT products.*,category.*,subcategory.*,lable.*, filter1.*,filter2.*,filter3.*,filter4.*,filter5.*,filter6.* FROM products 
		JOIN category ON category.c_id=products.c_id 
		JOIN subcategory ON subcategory.sc_id=products.sc_id 
		LEFT JOIN lable ON lable.l_id=products.l_id 
		LEFT JOIN filter1 ON filter1.f1_id=products.f_1 
		LEFT JOIN filter2 ON filter2.f2_id=products.f_2 
		LEFT JOIN filter3 ON filter3.f3_id=products.f_3 
		LEFT JOIN filter4 ON filter4.f4_id=products.f_4 
		LEFT JOIN filter5 ON filter5.f5_id=products.f_5 
		LEFT JOIN filter6 ON filter6.f6_id=products.f_6 
		WHERE products.p_id=$pid";
		
		$result = $conn->query($sql);	
		if ($result->num_rows >0) 
		{
			while($row = $result->fetch_assoc()) 
			{	
				
				$getcid=$row['c_id'];
				$joinall=$row;				
				array_push($products,$joinall);
				
			}
			
		} 
		
		
		$sql4 = "SELECT * FROM product_images WHERE p_id=$pid  AND is_bid='0'";
		$result4 = $conn->query($sql4);	
		if ($result4->num_rows >0) 
		{
			while($row4 = $result4->fetch_assoc()) {
				
				array_push($images,$row4);
			}
		}
		
		
		
		
		$sql4 = "SELECT * FROM product_variant WHERE p_id=$pid";
		$result4 = $conn->query($sql4);	
		if ($result4->num_rows >0) 
		{
			while($row4 = $result4->fetch_assoc()) {
				
				array_push($variants,$row4);
			}
		}
		
		
		$sql = "SELECT $productFields,category.*,subcategory.*,lable.* FROM products 
		join category ON category.c_id=products.c_id 
		join subcategory ON subcategory.sc_id=products.sc_id 
		LEFT join lable ON lable.l_id=products.l_id 
		WHERE $activeStatus AND products.c_id=$getcid ORDER BY p_id DESC LIMIT 5";
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
				
				
				array_push($related,$joinall);
			}
		} 
		
		
		//$sql = "SELECT product_rating.*,user.* FROM product_rating JOIN user ON user.u_id=product_rating.u_id WHERE product_rating.p_id=$pid ORDER BY pr_id DESC";
		$sql = "SELECT product_rating.*,user.* FROM product_rating JOIN user ON user.u_id=product_rating.u_id ORDER BY pr_id DESC";
		$result3 = $conn->query($sql);	
		if ($result3->num_rows >0) 
		{
			while($row3 = $result3->fetch_assoc()) 
			{	
				$joinall=$row3;
				array_push($reviews,$joinall);
			}
		} 
		
		
		$queryLike = mysqli_query($conn, "SELECT * from product_like WHERE u_id=$uid AND p_id=$pid LIMIT 1");
		$countLike = mysqli_num_rows($queryLike);
		
		if($countLike==null){
			$countLike=0;
		}
		
		
		
		echo json_encode(['success' => true, 'products' => $products,'images' => $images,'variants' => $variants, 'related'=>$related,'reviews'=>$reviews,'likes'=>$countLike ]);
		
		
		
		
		
		}else{
		
		echo json_encode(['success' => false, 'products' => $products,'images' => $images,'variants' => $variants, 'related'=>$related,'reviews'=>$reviews,'likes'=>$countLike ]);
		
	}
	
	
	
	$conn->close();
?>