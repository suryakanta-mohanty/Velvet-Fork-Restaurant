<?php
// DB connection
$conn = new mysqli("localhost", "root", "", "restaurant_project");
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['bookingDate'];
    $start_time = $_POST['fromTime'];
    $end_time = $_POST['toTime'];

    if (empty($date) || empty($start_time) || empty($end_time)) {
        die(json_encode(["error" => "Missing input data"]));
    }

    $query = "SELECT table_id FROM outdoorbooking
              WHERE booking_date = ? 
              AND (
                  (SUBTIME(from_time, '00:15:00') < ? AND ADDTIME(to_time, '00:15:00') > ?) OR 
              (SUBTIME(from_time, '00:15:00') >= ? AND SUBTIME(from_time, '00:15:00') < ?)
          )";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $date, $end_time, $start_time, $start_time, $end_time);
    $stmt->execute();
    $result = $stmt->get_result();

    $reservedTables = [];
    while ($row = $result->fetch_assoc()) {
        $reservedTables[] = $row['table_id'];
    }

    echo json_encode(["reserved" => $reservedTables]);

    $stmt->close();
    $conn->close();
}
?>
