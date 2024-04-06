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
		$getcid='';
		$bidList=array();
		
		
		
		$queryupdate=mysqli_query($conn,"UPDATE bid SET views=views+1 WHERE p_id=$pid");
 
		
		
		
		$sql = "SELECT bid.*,category.*,subcategory.*,lable.*, filter1.*,filter2.*,filter3.*,filter4.*,filter5.*,filter6.* FROM bid 
		JOIN category ON category.c_id=bid.c_id 
		JOIN subcategory ON subcategory.sc_id=bid.sc_id 
		LEFT JOIN lable ON lable.l_id=bid.l_id 
		LEFT JOIN filter1 ON filter1.f1_id=bid.f_1 
		LEFT JOIN filter2 ON filter2.f2_id=bid.f_2 
		LEFT JOIN filter3 ON filter3.f3_id=bid.f_3 
		LEFT JOIN filter4 ON filter4.f4_id=bid.f_4 
		LEFT JOIN filter5 ON filter5.f5_id=bid.f_5 
		LEFT JOIN filter6 ON filter6.f6_id=bid.f_6 
		WHERE bid.p_id=$pid";
		
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
		
		
		$sql4 = "SELECT * FROM product_images WHERE p_id=$pid AND is_bid='1'";
		$result4 = $conn->query($sql4);	
		if ($result4->num_rows >0) 
		{
			while($row4 = $result4->fetch_assoc()) {
				
				array_push($images,$row4);
			}
		}
		
		
		
		$sql6 = "SELECT bidding.*,user.* FROM bidding JOIN user ON user.u_id=bidding.u_id WHERE p_id=$pid ORDER BY b_id DESC";
			$result6 = $conn->query($sql6);
			if ($result6->num_rows >0) 
			{
				while($row6[] = $result6->fetch_assoc()) 
				{
					$bidList = $row6;
				}
			}
	
		
		
		
		echo json_encode(['success' => true, 'products' => $products,'images' => $images ,'bidList' => $bidList  ]);
		
		
		
		
		
		}else{
		
		echo json_encode(['success' => false, 'products' => $products,'images' => $images,'bidList' => $bidList   ]);
		
	}
	
	
	
	$conn->close();
?>