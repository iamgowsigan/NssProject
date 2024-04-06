<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
	
	$key = $_POST['key'];
	$uid = $_POST['uid']; 
	
	
	
	if(strcmp($AppKey,$key)==0){
		
		
		$cart=array();
		$coupon=array();
		
		
		
		
		$sql = "SELECT cart.*,cart.p_id as productid,$productFields,category.*,subcategory.*,product_variant.* FROM cart 
		JOIN products on products.p_id=cart.p_id 
		JOIN category ON category.c_id=products.c_id 
		JOIN subcategory ON subcategory.sc_id=products.sc_id 
		LEFT JOIN product_variant ON product_variant.pv_id=cart.variant 
		WHERE cart.u_id=$uid AND book_id='0'";
		
		$result2 = $conn->query($sql);	
		if ($result2->num_rows >0) 
		{
			while($row = $result2->fetch_assoc()) 
			{	
				
				
				$getid=$row['p_id'];
				$queryLike = mysqli_query($conn, "SELECT * from product_like WHERE u_id=$uid AND p_id=$getid");
				$countLike = mysqli_num_rows($queryLike);
				
				if($countLike==null || $countLike==""){
					$countLike =0;
				}
				
				$imagearray=['userlike' => $countLike];
				$joinall=$row+$imagearray;
				
				
				array_push($cart,$joinall);
				
				
				
			}
		} 
		
		
		
		//coupon
		
		$sql = "SELECT * FROM coupons WHERE coupon_active='A'";
		
		$result2 = $conn->query($sql);	
		if ($result2->num_rows >0) 
		{
			while($row = $result2->fetch_assoc()) 
			{	
				
				
				$getid=$row['coupon_id']; 
				$countUse = mysqli_num_rows(mysqli_query($conn, "SELECT * from coupon_used WHERE u_id=$uid AND coupon_id=$getid"));
				
				if($countUse==null || $countUse==""){
					$countUse =0;
				}
				
				$imagearray=['countUse' => $countUse];
				$joinall=$row+$imagearray;
				
				
				array_push($coupon,$joinall);
				
				
				
			}
		} 
		
		
		
		
		
		
		
		
		echo json_encode(['success' => true,'cart' => $cart,'coupon' => $coupon ]);
		
		
		
		
		
		}else{
		
		echo json_encode(['success' => false,'cart' => $cart,'coupon' => $coupon  ]);
		
	}
	
	
	
	$conn->close();
?>