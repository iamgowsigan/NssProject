<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
    $key = $_POST['key'];
	
	
    $uid = $_POST['uid']; 
	$method = $_POST['method'];
	$quantity = $_POST['quantity'];
	$product_price = $_POST['product_price'];
	$offer_price = $_POST['offer_price'];
	$purchase_price = $_POST['purchase_price'];
	$delivery_cost = $_POST['delivery_cost'];
	$coupon_code = $_POST['coupon_code'];
	$coupon_id = $_POST['coupon_id'];
	$coupon_offer = $_POST['coupon_offer'];
	$coupon_product = $_POST['coupon_product'];
	$address_id = $_POST['address_id'];
	$tax_percentage = $_POST['tax_percentage'];
	$tax_cost = $_POST['tax_cost'];
	$order_note = $_POST['order_note'];
	$code = 'aaaaaa';
	$trans_id = $_POST['trans_id'];
	
	
	
	$response = array();  
	
	
	if(strcmp($AppKey,$key)==0){
		
		
		
		$sql = "INSERT INTO pre_orders( u_id, method, quantity ,product_price, offer_price, purchase_price,delivery_cost, coupon_code, coupon_id, coupon_offer, coupon_product, address_id, tax_percentage,tax_cost,order_note,code,trans_id,o_dated  ) VALUES ('$uid','$method','$quantity','$product_price','$offer_price','$purchase_price','$delivery_cost','$coupon_code','$coupon_id','$coupon_offer','$coupon_product','$address_id','$tax_percentage','$tax_cost','$order_note','$code','$trans_id',now())";
		if(mysqli_query($conn, $sql)){
			$last_id = $conn->insert_id;
			
			echo json_encode(['success' => true, 'last_id' => $last_id ,   'sql2' => $sql]);
			}else{
			echo json_encode(['success' => false, 'last_id' => $last_id ,   'sql2' => $sql]);
		}
		
		
		
		
		
		}else{
		
		echo json_encode(['success' => false]);
		
	}
	
	
	
	$conn->close();
?>