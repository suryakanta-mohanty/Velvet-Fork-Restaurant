<?php
$servername = "localhost";
$username = "root";         // replace if different
$password = "";             // replace if you have one
$database = "restaurant_project"; // update with your actual DB name

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$hall_name = $_POST['hall_name']; // e.g., "Galleria 1"
$booking_date = $_POST['booking_date'];
$from_time = $_POST['from_time'];
$to_time = $_POST['to_time'];

// 1. Count bookings for this hall on this date
$query1 = "SELECT COUNT(*) as total FROM bookings 
           WHERE hall_name = ? AND booking_date = ?";
$stmt1 = $conn->prepare($query1);
$stmt1->bind_param("ss", $hall_name, $booking_date);
$stmt1->execute();
$count_result = $stmt1->get_result()->fetch_assoc();

if ($count_result['total'] >= 3) {
    echo json_encode([
        "status" => "error",
        "message" => "This galleria is fully booked for the selected date."
    ]);
    exit;
}

// 2. Check for time conflicts (overlapping time)
$query2 = "SELECT from_time, to_time FROM bookings 
           WHERE hall_name = ? AND booking_date = ? 
           AND (
               (from_time < ? AND to_time > ?) OR 
               (from_time >= ? AND from_time < ?)
           )";
$stmt2 = $conn->prepare($query2);
$stmt2->bind_param("ssssss", $hall_name, $booking_date, $to_time, $from_time, $from_time, $to_time);
$stmt2->execute();
$result2 = $stmt2->get_result();

if ($result2->num_rows > 0) {
    $conflicts = [];
    while ($row = $result2->fetch_assoc()) {
        $conflicts[] = "{$row['from_time']} - {$row['to_time']}";
    }

    echo json_encode([
        "status" => "error",
        "message" => "This request is not available for the entered time period. It has already been booked on $booking_date for time period(s): " . implode(", ", $conflicts)
    ]);
    exit;
}

// ✅ All good
echo json_encode([
    "status" => "success",
    "message" => "The galleria is available for booking."
]);

$conn->close();
?>
