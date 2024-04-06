<?php
include 'DatabaseConfig.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$key = $_POST['key'];
$uid = $_POST['uid'];

if (strcmp($AppKey, $key) == 0) {
    $students = array();

    $sql = "SELECT u_id, name, reg_no, email, phone,gender FROM user";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $student = $row;
            array_push($students, $student);
        }
    }

    echo json_encode(['success' => true, 'students' => $students]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid API key']);
}

$conn->close();
?>
