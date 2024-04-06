<?PHP

function sendMessage($title,$message,$image,$topic) {
		
    $url = 'https://fcm.googleapis.com/fcm/send';
	$tooo = '/topics/'.$topic;
	$action = 'FLUTTER_NOTIFICATION_CLICK';
	
	
    $api_key = 'AAAAD7twuf0:APA91bEHqMg21kJ7WwzMdPRlMTz9tB33bRUQL0PR70n7Yoi2Wbdwg_35CtOx3p4Htv1qdsm7m700RagYiwfxZOwiGjO3HFLpF6D9B98C8MPMrCZ9hvD9zYph2Z417cAL8j74k8xDy39x';
                
    $fields = array (
        'to' => $tooo,
        'notification' => array (
				"body"=>$message,
                "click_action" => $action,
				"title" => $title,
				"image" => $image
        ),
		'data' => array (
                "click_action" => $action,
				"title" => $title,
				"body"=>$message,
				"image" => $image,
				"main_picture" => $image
        ),
    );

    //header includes Content type and api key
    $headers = array(
        'Content-Type:application/json',
        'Authorization:key='.$api_key
    );
                
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
    return $result;

}


function sendMessageScreen($title,$message,$image,$topic,$id,$screen) {
		
    $url = 'https://fcm.googleapis.com/fcm/send';
	$tooo = '/topics/'.$topic;
	$action = 'FLUTTER_NOTIFICATION_CLICK';
	
	
    $api_key = 'AAAAD7twuf0:APA91bEHqMg21kJ7WwzMdPRlMTz9tB33bRUQL0PR70n7Yoi2Wbdwg_35CtOx3p4Htv1qdsm7m700RagYiwfxZOwiGjO3HFLpF6D9B98C8MPMrCZ9hvD9zYph2Z417cAL8j74k8xDy39x';
                
    $fields = array (
        'to' => $tooo,
        'notification' => array (
				"body"=>$message,
                "click_action" => $action,
				"title" => $title,
				"screen" =>$screen,
				"id" => $id,
				"image" => $image
        ),
		'data' => array (
                "click_action" => $action,
				"title" => $title,
				"body"=>$message,
				"image" => $image,
				"screen" =>$screen,
				"id" => $id,
				"main_picture" => $image
        ),
    );

    //header includes Content type and api key
    $headers = array(
        'Content-Type:application/json',
        'Authorization:key='.$api_key
    );
                
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
    return $result;

}



?>