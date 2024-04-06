<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
	
	
	
	$key = $_POST['key'];
	$uid = $_POST['uid'];
	$oid = $_POST['oid'];
	
	
	
	
	
	if(strcmp($AppKey,$key)==0){
		
		$orders=array();
		$products=array();
		$address=array();
		
		$sql3 = "SELECT orders.* FROM orders WHERE o_id=$oid ORDER by o_id DESC";
		$result3 = $conn->query($sql3);	
		if ($result3->num_rows >0) 
		{
			while($row3 = $result3->fetch_assoc()) 
			{	
				$getAddress=$row3['address_id'];
				$getotype=$row3['o_type'];
				$joinall=$row3;			
				array_push($orders,$joinall);
				
			}
			
		} 
		
		
		
		if($getotype=='REGULAR'){
			$sql4 = "SELECT cart.*,products.*,product_variant.* FROM cart 
			JOIN products ON products.p_id=cart.p_id 
			LEFT JOIN product_variant ON product_variant.pv_id=cart.variant
			WHERE cart.book_id=$oid ORDER by cart_id DESC";
			}else{
			
			$sql4 = "SELECT cart.*,bid.* FROM cart 
			JOIN bid ON bid.p_id=cart.p_id 
			WHERE cart.book_id=$oid ORDER by cart_id DESC";
		}
		
		
		
		
		
		
		$result4 = $conn->query($sql4);	
		if ($result4->num_rows >0) 
		{
			while($row3 = $result4->fetch_assoc()) 
			{	
				
				$joinall=$row3;			
				array_push($products,$joinall);
				
			}
			
		} 
		
		
		$sql4 = "SELECT address.*,user.* FROM address JOIN user ON user.u_id=address.u_id WHERE a_id=$getAddress";
		$result4 = $conn->query($sql4);	
		if ($result4->num_rows >0) 
		{
			while($row3 = $result4->fetch_assoc()) 
			{	
				
				$joinall=$row3;			
				array_push($address,$joinall);
				
			}
			
		} 
		
		
		echo json_encode(['success' => true,'orders' => $orders ,'products' => $products,'address' => $address ]);
		
		
		
		
		
		}else{
		
		echo json_encode(['success' => false,'orders' => $orders,'products' => $products,'address' => $address ]);
		
	}
	
	
	
	$conn->close();
?>