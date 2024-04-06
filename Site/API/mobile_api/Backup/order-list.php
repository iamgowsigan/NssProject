<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
	
	
	
	$key = $_POST['key'];
	$uid = $_POST['uid'];
	$order = $_POST['order'];
	
	
	
	
	
	if(strcmp($AppKey,$key)==0){
		
		$orders=array();
		
		$sql3 = "SELECT orders.*,address.* FROM orders JOIN address ON address.a_id=orders.address_id WHERE orders.u_id=$uid AND orders.o_type='$order' ORDER by o_id DESC";
		
		
		$result3 = $conn->query($sql3);	
		if ($result3->num_rows >0) 
		{
			while($row3 = $result3->fetch_assoc()) 
			{	
				$images=array();
				$getoid=$row3['o_id'];
				$getotype=$row3['o_type'];
				
				if($getotype=='REGULAR'){
					$sql4 = "SELECT cart.p_id,products.p_id,products.p_image FROM cart JOIN products ON products.p_id=cart.p_id WHERE cart.book_id=$getoid ORDER BY cart.cart_id DESC";
					}else{
					
					$sql4 = "SELECT cart.p_id,bid.p_id,bid.p_image FROM cart JOIN bid ON bid.p_id=cart.p_id WHERE cart.book_id=$getoid ORDER BY cart.cart_id DESC";
				}
				
				
				
				$result4 = $conn->query($sql4);	
				if ($result4->num_rows >0) 
				{
					while($row4 = $result4->fetch_assoc()) {
						
						array_push($images,$row4);
					}
				}
				
				
				$imagesarray=['images' => $images];
				$joinall=$row3+$imagesarray;			
				array_push($orders,$joinall);
				
			}
			
		} 
		
		
		echo json_encode(['success' => true,'orders' => $orders ,'sql3' => $sql3]);
		
			
			
			
			
			}else{
			
			echo json_encode(['success' => false,'orders' => $orders,'sql3' => $sql3]);
			
	}
	
	
	
	$conn->close();
?>