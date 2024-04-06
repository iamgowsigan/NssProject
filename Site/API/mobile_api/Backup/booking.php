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
	$trans_id = $_POST['trans_id'];
	$delivery_time = $_POST['delivery_time'];
	$code = substr(uniqid('', false), -10);
	
	
	$response = array();  
	$bookid='';
	
	if(strcmp($AppKey,$key)==0){
		
		
		if($method=='wallet'){
			
			
			$sql2 = "SELECT wallet FROM user WHERE u_id=$uid LIMIT 1";
			$result2 = $conn->query($sql2);
			while($row2[] = $result2->fetch_assoc()) 
			{
				$balance = $row2[0]["wallet"];
				
			} 
			
			if($balance>$purchase_price){
				
				mysqli_query($conn,"INSERT INTO  wallet(u_id,w_amount,w_detail,w_type) VALUES ('$uid','$purchase_price','Money debited for purchase','0')");
				mysqli_query($conn,"UPDATE user SET wallet=wallet-$purchase_price WHERE u_id=$uid");
				
			}
			
		}
		
		if($method=='pay'){
			
			
			mysqli_query($conn,"INSERT INTO  wallet(u_id,w_amount,w_detail,w_type) VALUES ('$uid','$purchase_price','Money credit from payment gateway','1')");
			mysqli_query($conn,"INSERT INTO  wallet(u_id,w_amount,w_detail,w_type) VALUES ('$uid','$purchase_price','Money debited for purchase','0')");
			
		}
		
		
		
		
		
		
		$sql = "INSERT INTO orders( u_id, method, quantity ,product_price, offer_price, purchase_price,delivery_cost, coupon_code, coupon_id, coupon_offer, coupon_product, address_id, tax_percentage,tax_cost,order_note,delivery_time,code,trans_id,o_dated  ) VALUES ('$uid','$method','$quantity','$product_price','$offer_price','$purchase_price','$delivery_cost','$coupon_code','$coupon_id','$coupon_offer','$coupon_product','$address_id','$tax_percentage','$tax_cost','$order_note','$delivery_time','$code','$trans_id',now())";
		if(mysqli_query($conn, $sql)){
			$bookid = $conn->insert_id;
			
			
			if($coupon_offer!='' && $coupon_offer!='0' ){
				
				mysqli_query($conn,  "INSERT INTO coupon_used(u_id,book_id,coupon_id,coupon_code,coupon_price) VALUES ('$uid','$bookid','$coupon_id','$coupon_code','$coupon_offer')");
				
				
				mysqli_query($conn,  "UPDATE coupons SET coupon_use=coupon_use+1 WHERE coupon_id='$coupon_id'");
				
			}
			
			
			
			$sql1 = "SELECT cart.*,cart.p_id as product_id,products.*,product_variant.* FROM cart 
			JOIN products ON products.p_id=cart.p_id 
			LEFT JOIN product_variant ON product_variant.pv_id=cart.variant WHERE cart.u_id='$uid' AND cart.book_id='0'";
			$result = $conn->query($sql1);
			
			if ($result->num_rows >0) 
			{
				while($row = $result->fetch_assoc()) 
				{
					
					
					if($row['pv_sell']==null){
						$getsell=$row['p_sell'];
						}else{
						$getsell=$row['pv_sell'];
					}
					$pid=$row['product_id'];
					$couponPrice=0;
					
					if($pid==$coupon_product){
						$couponPrice=$coupon_offer;
					}
					
					$getQuantity=$row['quantity'];
					
					
					
					//deliveryMethodCost=deliveryMethodCost+InterOne+(InterTwo*(itemQuantity-1));	
					
					$deliveryMethodCost=$oneDelivery+($twoDelivery*($getQuantity-1));
					$getcid=$row['cart_id'];
					$getversion=$row['p_version'];
					
					mysqli_query($conn,"UPDATE cart SET 
					purchase_price='$getsell',
					coupon_price='$couponPrice',
					book_id='$bookid'
					WHERE cart_id=$getcid");
					
					
				}
				echo json_encode(['success' => true, 'bookid' => $bookid ,   'sql2' => $sql]);
				} else{
				echo json_encode(['success' => false, 'bookid' => $bookid ,   'sql2' => $sql]);
			}
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			}else{
			echo json_encode(['success' => false, 'bookid' => $bookid ,   'sql2' => $sql]);
		}
		
		
		
		
		
		}else{
		
		echo json_encode(['success' => false]);
		
	}
	
	
	
	$conn->close();
?>