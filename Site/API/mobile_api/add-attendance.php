<?php
include 'DatabaseConfig.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$key = $_POST['key'];
$date = $_POST['date'];
$attendanceData = json_decode($_POST['attendanceData'], true);

if (strcmp($AppKey, $key) == 0) {
    foreach ($attendanceData as $attendanceRecord) {

        $studentId = $attendanceRecord['u_id'];
        $attendanceStatus = $attendanceRecord['a_status'];

        mysqli_query($conn, sprintf("UPDATE user SET last_login=now() WHERE u_id=$studentId"));

        $existingRecord = mysqli_query($conn, "SELECT * FROM attendance WHERE u_id='$studentId' AND a_date='$date'");

        if (mysqli_num_rows($existingRecord) > 0) {
            updateAttendanceRecord($conn, $studentId, $date, $attendanceStatus);
        } else {
            insertAttendanceRecord($conn, $studentId, $date, $attendanceStatus);
        }
    }

   echo json_encode(['success' => true, 'message' => 'Attendance recorded successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid API key']);
}

$conn->close();

function updateAttendanceRecord($conn, $studentId, $date, $attendanceStatus) {
    mysqli_query($conn, "UPDATE attendance SET a_status='$attendanceStatus' WHERE u_id='$studentId' AND a_date='$date'");
}

function insertAttendanceRecord($conn, $studentId, $date, $attendanceStatus) {
    mysqli_query($conn, "INSERT INTO attendance (u_id, a_date, a_status) VALUES ('$studentId', '$date', '$attendanceStatus')");
}
?>
