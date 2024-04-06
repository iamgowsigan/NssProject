<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
	
	$key = $_POST['key'];
	$uid = $_POST['uid'];
	$page = $_POST['page'];
	
	$cid = $_POST['cid'];
	$sid = $_POST['sid'];
	$sort = $_POST['sort'];
	$f1 = $_POST['f1'];
	$f2 = $_POST['f2'];
	$f3 = $_POST['f3'];
	$f4 = $_POST['f4'];
	$f5 = $_POST['f5'];
	$f6 = $_POST['f6'];
	$color = $_POST['color'];
	$lable = $_POST['lable'];
	$min = $_POST['min'];
	$max = $_POST['max'];
	$offer = $_POST['offer'];
	
	
	
	$start = 0; 
	$limit=20;
	$start = ($page - 1) * $limit; 
	
	
	if($sort=="0" || $sort==""){
		$sort_class=" products.p_id DESC";
	}
	if($sort=="1"){
		$sort_class=" products.p_sell+0 DESC";
	}
	if($sort=="2"){
		$sort_class=" products.views DESC";
	}
	if($sort=="3"){
		$sort_class=" products.p_sell+0 ASC";
	}
	if($sort=="4"){
		$sort_class=" products.p_rating DESC";
	}
	
	//Cat
	$catarray=explode(",",$cid);
	if($cid!="" ){
		$cat_class=" AND (";
		for($i=0;$i<sizeof($catarray);$i++ ){
			$cat_class=$cat_class."products.c_id = '".$catarray[$i]. "' OR ";
		}
		
		$cat_class=substr($cat_class, 0, -4)." )";
	}
	
	
	
	//Subcat
	$subarray=explode(",",$sid);
	if($sid!="" ){
		$sub_class=" AND (";
		for($i=0;$i<sizeof($subarray);$i++ ){
			$sub_class=$sub_class."products.sc_id = '".$subarray[$i]. "' OR ";
		}
		
		$sub_class=substr($sub_class, 0, -4)." )";
	}
	
	
	//f1
	$f1array=explode(",",$f1);
	if($f1!="" ){
		$f1_class=" AND (";
		for($i=0;$i<sizeof($f1array);$i++ ){
			$f1_class=$f1_class."products.f_1 = '".$f1array[$i]. "' OR ";
		}
		
		$f1_class=substr($f1_class, 0, -4)." )";
	}
	
	//f2
	$f2array=explode(",",$f2);
	if($f2!="" ){
		$f2_class=" AND (";
		for($i=0;$i<sizeof($f2array);$i++ ){
			$f2_class=$f2_class."products.f_2 = '".$f2array[$i]. "' OR ";
		}
		
		$f2_class=substr($f2_class, 0, -4)." )";
	}
	
	//f3
	$f3array=explode(",",$f3);
	if($f3!="" ){
		$f3_class=" AND (";
		for($i=0;$i<sizeof($f3array);$i++ ){
			$f3_class=$f3_class."products.f_3 = '".$f3array[$i]. "' OR ";
		}
		
		$f3_class=substr($f3_class, 0, -4)." )";
	}
	
	//f4
	$f4array=explode(",",$f4);
	if($f4!="" ){
		$f4_class=" AND (";
		for($i=0;$i<sizeof($f4array);$i++ ){
			$f4_class=$f4_class."products.f_4 = '".$f4array[$i]. "' OR ";
		}
		
		$f4_class=substr($f4_class, 0, -4)." )";
	}
	
	//f5
	$f5array=explode(",",$f5);
	if($f5!="" ){
		$f5_class=" AND (";
		for($i=0;$i<sizeof($f5array);$i++ ){
			$f5_class=$f5_class."products.f_5 = '".$f5array[$i]. "' OR ";
		}
		
		$f5_class=substr($f5_class, 0, -4)." )";
	}
	
	//f6
	$f6array=explode(",",$f6);
	if($f6!="" ){
		$f6_class=" AND (";
		for($i=0;$i<sizeof($f6array);$i++ ){
			$f6_class=$f6_class."products.f_6 = '".$f6array[$i]. "' OR ";
		}
		
		$f6_class=substr($f6_class, 0, -4)." )";
	}
	
	
	//color
	$colorarray=explode(",",$color);
	if($color!="" ){
		$color_class=" AND (";
		for($i=0;$i<sizeof($colorarray);$i++ ){
			$color_class=$color_class."products.p_color = '".$colorarray[$i]. "' OR ";
		}
		
		$color_class=substr($color_class, 0, -4)." )";
	}
	
	
	//lable	
	$lablearray=explode(",",$lable);
	if($lable!="" ){
		$lable_class=" AND (";
		for($i=0;$i<sizeof($lablearray);$i++ ){
			$lable_class=$lable_class."products.l_id = '".$lablearray[$i]. "' OR ";
		}
		
		
		$lable_class=substr($lable_class, 0, -4)." )";
	}
	
	
	
	if($min==""){
		$min="0";
	}
	
	if($max==""){
		$max="100000";
	}
	
	$offer_class='';
	if($offer!=""){
		$offer_class="AND p_mrp>p_sale";
	}
	
	
	if(strcmp($AppKey,$key)==0){
		
		$productlist=array();
		
		
		$sql = "SELECT $productFields,category.*,subcategory.*,lable.*  FROM products 
		JOIN category ON category.c_id=products.c_id 
		JOIN subcategory ON subcategory.sc_id=products.sc_id 
		LEFT JOIN lable ON lable.l_id=products.l_id 
		LEFT JOIN filter1 ON filter1.f1_id=products.f_1 
		LEFT JOIN filter2 ON filter2.f2_id=products.f_2 
		LEFT JOIN filter3 ON filter3.f3_id=products.f_3 
		LEFT JOIN filter4 ON filter4.f4_id=products.f_4 
		LEFT JOIN filter5 ON filter5.f5_id=products.f_5 
		LEFT JOIN filter6 ON filter6.f6_id=products.f_6 
		WHERE $activeStatus AND (products.p_sell BETWEEN $min AND $max) $cat_class $sub_class $offer_class $f1_class $f2_class $f3_class $f4_class $f5_class $f6_class $color_class $lable_class ORDER BY $sort_class limit $start, $limit";
		$result = $conn->query($sql);	
		if ($result->num_rows >0) 
		{
			while($row = $result->fetch_assoc()) 
			{	
				
				$getid=$row['p_id'];
				$queryLike = mysqli_query($conn, "SELECT * from product_like WHERE u_id=$uid AND p_id=$getid");
				$countLike = mysqli_num_rows($queryLike);
				
				if($countLike==null || $countLike==""){
					$countLike =0;
				}
				
				$imagearray=['userlike' => $countLike];
				$joinall=$row+$imagearray;
				
				
				array_push($productlist,$joinall);
				
			}
		} 
		
		
		
		
		
		echo json_encode(['success' => true,'productlist' => $productlist,'sql' => $sql]);
		
		
		
		
		
		
		}else{
		echo json_encode(['success' => false,'productlist' => $productlist,'sql' => $sql ]);
		
		
		
	}
	
	
	
	$conn->close();
?>