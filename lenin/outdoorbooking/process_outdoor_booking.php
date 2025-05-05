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
$parts = explode("-", $table_id_raw); // Expected format: tableA-1
// Verify OTP before proceeding
// $entered_otp = isset($_SESSION['entered_otp']) ? $_SESSION['entered_otp'] : '';
// $stored_otp = isset($_SESSION['otp']) ? $_SESSION['otp'] : '';

// if ($entered_otp !== $stored_otp) {
//     die("Error: OTP verification failed. Booking not processed.");
// }

// Retrieve indoor table booking details from session
// $table_id_raw = isset($_POST['table_id']) ? $_POST['table_id'] : '';
// $parts = explode("-", $table_id_raw); // Expected format: tableA-1
// $table_id = $_POST['table_id']; // e.g. table6-1
// $table_type = isset($parts[0]) ? str_replace("table", "", $parts[0]) : '';
// $table_number = isset($parts[1]) ? $parts[1] : '';
// $booking_date = isset($_POST['date']) ? $_POST['date'] : '';
// $from_time = isset($_POST['from_time']) ? $_POST['from_time'] : '';
// $to_time = isset($_POST['to_time']) ? $_POST['to_time'] : '';
// $name = isset($_POST['name']) ? $_POST['name'] : '';
// $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
// $email = isset($_POST['email']) ? $_POST['email'] : '';
// $special_request = isset($_POST['special_request']) ? $_POST['special_request'] : '';
// $booking_id = generateBookingId($conn, $table_id, $booking_date);
// $table_id_raw = isset($_SESSION['table_id']) ? $_SESSION['table_id'] : '';
// $parts = explode("-", $table_id_raw); // Expected format: table6-1

// $table_id = isset($_SESSION['table_id']) ? $_SESSION['table_id'] : '';
// $table_type = isset($parts[0]) ? str_replace("table", "", $parts[0]) : '';
// $table_number = isset($parts[1]) ? $parts[1] : '';
// $booking_date = isset($_SESSION['booking_date']) ? $_SESSION['booking_date'] : '';
// $from_time = isset($_SESSION['from_time']) ? $_SESSION['from_time'] : '';
// $to_time = isset($_SESSION['to_time']) ? $_SESSION['to_time'] : '';
// $name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
// $phone = isset($_SESSION['phone']) ? $_SESSION['phone'] : '';
// $email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
// $special_request = isset($_SESSION['special_request']) ? $_SESSION['special_request'] : '';
// $booking_id = isset($_SESSION['booking_id']) ? $_SESSION['booking_id'] : generateBookingId($conn, $table_id_raw, $booking_date);


// $_SESSION['booking_id'] = $booking_id;
// $_SESSION['table_number'] = $table_number;
// $_SESSION['table_id'] = $table_id_raw;
// $_SESSION['table_type'] = $table_type;
// $_SESSION['booking_date'] = $booking_date;
// $_SESSION['from_time'] = $from_time;
// $_SESSION['to_time'] = $to_time;
// $_SESSION['name'] = $name;
// $_SESSION['phone'] = $phone;
// $_SESSION['email'] = $email;
// $_SESSION['special_request'] = $special_request;
$table_id = $_SESSION['table_id'] ;
$table_type = $_SESSION['table_type'];
$table_number = $_SESSION['table_number'];
$booking_date = $_SESSION['booking_date'];
$from_time = $_SESSION['from_time'];
$to_time = $_SESSION['to_time'];
$name = $_SESSION['name'];
$phone =$_SESSION['phone'];
$email = $_SESSION['email'];
$special_request = $_SESSION['special_request'];

// echo "Debug Info:<br>";
// echo "Table Number: " . $table_number . "<br>";
// echo "Booking Date: " . $booking_date . "<br>";
// echo "From Time: " . $from_time . "<br>";
// echo "To Time: " . $to_time . "<br>";
// echo "Name: " . $name . "<br>";
// echo "Phone: " . $phone . "<br>";
// echo "Email: " . $email . "<br>";


// Validate required fields
if (empty($table_number) || empty($booking_date) || empty($from_time) || empty($to_time) || empty($name) || empty($phone) || empty($email)) {
    die("Error: Missing required booking details.");
}

// Insert query for indoor booking
// $sql = "INSERT INTO indoorbooking (table_number, table_type, booking_date, from_time, to_time, name, phone, email, special_request) 
//         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

// $stmt = $conn->prepare($sql);
// $stmt->bind_param("sssssssss", $table_number, $table_type, $booking_date, $from_time, $to_time, $name, $phone, $email, $special_request);

// if ($stmt->execute()) {
//     // Update table availability status after successful booking
//     $update_sql = "UPDATE indoor_tables SET status = 'reserved' WHERE table_number = ? AND table_type = ?";
//     $update_stmt = $conn->prepare($update_sql);
//     $update_stmt->bind_param("ss", $table_number, $table_type);
//     $update_stmt->execute();
//     $update_stmt->close();
    
//     echo "✅ Indoor table booking successful!";
// } else {
//     echo "Error: " . $stmt->error;
// }

// $stmt->close();


// Extract type and number
preg_match('/table(\d+)-(\d+)/', $table_id, $matches);
$table_type = $matches[1];    // e.g. 6
$table_number = $matches[2];  // e.g. 1

$sql = "INSERT INTO outdoorbooking (booking_id, table_id, table_number, table_type, booking_date, from_time, to_time, name, phone, email, special_request)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssssss",$booking_id, $table_id, $table_number, $table_type, $booking_date, $from_time, $to_time, $name, $phone, $email, $special_request);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);  // Shows the real DB error
}

if ($stmt->execute()) {
    // Update table availability status after successful booking
    $update_sql = "UPDATE outdoor_tables SET status = 'reserved' WHERE table_number = ? AND table_type = ?";
    $update_stmt = $conn->prepare($update_sql);
    
    if (!$update_stmt) {
        die("Update prepare failed: " . $conn->error);
    }

    $update_stmt->bind_param("ss", $table_number, $table_type);
    $update_stmt->execute();
    $update_stmt->close();
    
    echo "<script>
        alert('✅ Your booking was successful!');
        window.location.href = 'outdoor.html';
    </script>";
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();

$conn->close();
?>

