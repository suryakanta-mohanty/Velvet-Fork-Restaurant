<?php
session_start();
var_dump($_SESSION['booking_id']); // ← Check this
if (!isset($_SESSION['booking_id'])) {
    $booking_id = generateBookingId($conn, $_SESSION['table_id'], $_SESSION['booking_date']);
    $_SESSION['booking_id'] = $booking_id;
} else {
    $booking_id = $_SESSION['booking_id'];
}

// Database connection
$server = "localhost";
$username = "root";
$password = "";
$dbname = "restaurant_project";

$conn = new mysqli($server, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$table_id_raw = isset($_POST['table_id']) ? $_POST['table_id'] : '';
$parts = explode("-", $table_id_raw); // Expected format: otable4-2 or similar

$table_id = $_SESSION['table_id'] ;
$table_type = $_SESSION['table_type'];
$table_number = $_SESSION['table_number'];
$booking_date = $_SESSION['booking_date'];
$from_time = $_SESSION['from_time'];
$to_time = $_SESSION['from_time'];
$name = $_SESSION['name'];
$phone =$_SESSION['phone'];
$email = $_SESSION['email'];
$special_request = $_SESSION['special_request'];

// Validate required fields
if (empty($table_number) || empty($booking_date) || empty($from_time) || empty($to_time) || empty($name) || empty($phone) || empty($email)) {
    die("Error: Missing required booking details.");
}



// Extract type and number
preg_match('/table(\d+)-(\d+)/', $table_id, $matches);
$table_type = $matches[1];    // e.g. 6
$table_number = $matches[2];  // e.g. 1

$sql = "INSERT INTO indoorbooking (booking_id, table_id, table_number, table_type, booking_date, from_time, to_time, name, phone, email, special_request)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssssss",$booking_id, $table_id, $table_number, $table_type, $booking_date, $from_time, $to_time, $name, $phone, $email, $special_request);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);  // Shows the real DB error
}


if ($stmt->execute()) {
    // Update table availability status after successful booking
    $update_sql = "UPDATE indoor_tables SET status = 'reserved' WHERE table_number = ? AND table_type = ?";
    $update_stmt = $conn->prepare($update_sql);
    
    if (!$update_stmt) {
        die("Update prepare failed: " . $conn->error);
    }

    $update_stmt->bind_param("ss", $table_number, $table_type);
    $update_stmt->execute();
    $update_stmt->close();

    // ✅ Show popup message and then redirect
    echo "
    <script>
        alert('✅ Your booking was successful!');
        window.location.href = 'indoor.html';
    </script>";
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();

$conn->close();

?>