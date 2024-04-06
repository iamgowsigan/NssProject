<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
	
	$key = $_POST['key'];
	$uid = $_POST['uid'];
	$page = $_POST['page'];
	$cid = $_POST['cid'];
	
	
	$start = 0; 
	$limit=20;
	$start = ($page - 1) * $limit; 
	


	//Cat
	$catarray=explode(",",$cid);
	if($cid!="" ){
		$cat_class=" AND (";
		for($i=0;$i<sizeof($catarray);$i++ ){
			$cat_class=$cat_class."bid.c_id = '".$catarray[$i]. "' OR ";
		}
		
		$cat_class=substr($cat_class, 0, -4)." )";
	}
	
	
	
	if(strcmp($AppKey,$key)==0){
		
		$productlist=array();
		
		
		$sql = "SELECT $bidFields,category.*,subcategory.*,lable.*  FROM bid 
		JOIN category ON category.c_id=bid.c_id 
		JOIN subcategory ON subcategory.sc_id=bid.sc_id 
		LEFT JOIN lable ON lable.l_id=bid.l_id 
		LEFT JOIN filter1 ON filter1.f1_id=bid.f_1 
		LEFT JOIN filter2 ON filter2.f2_id=bid.f_2 
		LEFT JOIN filter3 ON filter3.f3_id=bid.f_3 
		LEFT JOIN filter4 ON filter4.f4_id=bid.f_4 
		LEFT JOIN filter5 ON filter5.f5_id=bid.f_5 
		LEFT JOIN filter6 ON filter6.f6_id=bid.f_6 
		WHERE bid.p_status!='E' && bid.p_status!='H'  && category.c_active!='0' $cat_class limit $start, $limit";
		$result = $conn->query($sql);	
		if ($result->num_rows >0) 
		{
			while($row = $result->fetch_assoc()) 
			{	
				
 
 
				array_push($productlist,$row);
				
			}
		} 
		
		
		
		
		
		echo json_encode(['success' => true,'productlist' => $productlist,'sql' => $sql]);
		
		
		
		
		
		
		}else{
		echo json_encode(['success' => false,'productlist' => $productlist,'sql' => $sql ]);
		
		
		
	}
	
	
	
	$conn->close();
?>