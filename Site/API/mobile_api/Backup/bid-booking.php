<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
    $key = $_POST['key'];
	
	
    $uid = $_POST['uid']; 
	$pid = $_POST['pid'];
	$price = $_POST['price']; 
	$delivery = $_POST['delivery'];
	$totalprice = $_POST['totalprice'];
	$address_id = $_POST['address_id'];
	$tax_percentage = $_POST['tax_percentage'];
	$tax_cost = $_POST['tax_cost'];
	$trans_id = $_POST['trans_id'];
	$code = substr(uniqid('', false), -10);
	
	
	$response = array();  
	$bookid='';
	
	if(strcmp($AppKey,$key)==0){
		
		
		mysqli_query($conn,"UPDATE bid SET win_user=$uid,trans='$trans_id' WHERE p_id=$pid");
		
		
		$sql = "INSERT INTO orders( u_id,o_type ,method, quantity ,product_price, offer_price, purchase_price,delivery_cost, coupon_code, coupon_id, coupon_offer, coupon_product, address_id, tax_percentage,tax_cost,order_note,delivery_time,code,trans_id,o_dated  ) VALUES ('$uid','BIDDING','pay','1','$price','0','$totalprice','$delivery','','','','','$address_id','$tax_percentage','$tax_cost','','','$code','$trans_id',now())";
		if(mysqli_query($conn, $sql)){
			$bookid = $conn->insert_id;
			
			
			
			
			
			mysqli_query($conn,"INSERT INTO  cart(u_id,product_type,p_id,book_id,variant,quantity,purchase_price) VALUES ('$uid','BIDDING','$pid','$bookid','0','1','$price')");
			
			echo json_encode(['success' => true ]);
			
			
			
			
			
		}
		
		
		
		
		
		
		
		
		
		
		
		}else{
		echo json_encode(['success' => false ]);
	}
	
	
	
	
	
	
	$conn->close();
?>