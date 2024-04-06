<?php
	include 'DatabaseConfig.php';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
    $key = $_POST['key'];
    $bid = $_POST['bid'];
    $gid = $_POST['gid'];
    $sname = $_POST['sname'];
    $scode = $_POST['scode'];
    $scontact_person = $_POST['s_contact_person'];
    $sphone = $_POST['sphone'];
    $spurchase_date = $_POST['spurchase_date'];
    $slat = $_POST['slat'];
    $slng = $_POST['slng'];
    $saddress = $_POST['saddress'];
    $scity = $_POST['scity'];
    $semail = $_POST['semail'];

	


	if(strcmp($AppKey,$key)==0){
		
		$sql = "INSERT INTO sites(b_id,g_id,s_name,s_code,s_contact_person,s_phone,s_email,s_purchase_date,s_lat,s_lng,s_address,s_city) VALUES ('$bid','$gid','$sname','$scode','$scontact_person','$sphone','$semail','$spurchase_date','$slat','$slng','$saddress','$scity')";
		if(mysqli_query($conn, $sql)){
			

		
		echo json_encode(['success' => true ,'message' => 'Site Added']);
			
		}
		else{
			
				echo json_encode(['success' => true ,'message' => 'Added faild']);
			
		}
		
		
		
		}else{
		echo json_encode(['success' => false ,'pid' => $last_id]);
		
	}
	
	
	
	$conn->close();
?>