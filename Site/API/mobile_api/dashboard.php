<?php
    include 'DatabaseConfig.php';
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    
    $key = $_POST['key'];
    $uid = $_POST['uid'];
    
    if(strcmp($AppKey, $key) == 0){
        
        $user = array();
        $activities = array(); // Added this line
        $bloods = array(); // Added this line
        
        $sql = "SELECT * FROM experts WHERE s_id=$uid";
        $result1 = $conn->query($sql);    
        if ($result1->num_rows > 0) {
            while ($row1 = $result1->fetch_assoc()) {    
                mysqli_query($conn, "UPDATE user SET last_login=now() WHERE u_id='$uid'");
                $joinall = $row1;
                array_push($user, $joinall);
            }
        } 
        
        // Fetch banner data
        $sql = "SELECT * FROM activity";
        $result2 = $conn->query($sql);
        if ($result2->num_rows > 0) {
            while ($row2 = $result2->fetch_assoc()) {
                $activity = $row2;
                array_push($activities, $activity);
            }
        }

        $sql = "SELECT * FROM blood";
        $result3 = $conn->query($sql);
        if ($result3->num_rows > 0) {
            while ($row3 = $result3->fetch_assoc()) {
                $blood = $row3;
                array_push($bloods, $blood);
            }
        }
        
        echo json_encode(['success' => true, 'user' => $user, 'activities' => $activities, 'bloods' => $bloods]);
        
    } else {
        echo json_encode(['success' => false, 'user' => $user, 'activities' => $activities, 'bloods' => $bloods]);
    }
    
    $conn->close();
?>
