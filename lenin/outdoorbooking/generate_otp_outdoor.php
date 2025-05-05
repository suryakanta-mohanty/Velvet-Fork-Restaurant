<?php 
session_start();


function generateBookingId($conn, $table_id, $booking_date) {
    // Step 1: Count existing bookings for the given date
    $query = "SELECT COUNT(*) AS count FROM outdoorbooking WHERE booking_date = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $booking_date);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $n = $row['count']; // Starting from 0

    // Step 2: Format the components
    $padded_n = str_pad($n, 2, '0', STR_PAD_LEFT); // '00', '01', ...
    $formatted_date = date("Y-m-d"); // Ensure correct format---, strtotime($booking_date)

    // Step 3: Construct the booking ID
    $booking_id = "VF-OTB-" . $formatted_date . "-" . $table_id . "-" . $padded_n;

    return $booking_id;
}

// Store indoor table booking form data in session
// $_SESSION['name'] = $_POST['name'];
// $_SESSION['phone'] = $_POST['phone'];
// $_SESSION['email'] = $_POST['email'];
// $_SESSION['booking_date'] = $_POST['booking_date'];
// $_SESSION['from_time'] = $_POST['from_time'];
// $_SESSION['to_time'] = $_POST['to_time'];
// $_SESSION['table_type'] = $_POST['table_type'];
// $_SESSION['table_number'] = $_POST['table_number'];
// $_SESSION['special_request'] = $_POST['special_request'];
// $_SESSION['booking_id'] = $booking_id;
$table_id_raw = isset($_POST['table_id']) ? $_POST['table_id'] : '';
$parts = explode("-", $table_id_raw); // Expected format: otable4-2 or similar

$table_id = $table_id_raw;
$table_type = isset($parts[0]) ? str_replace("otable", "", $parts[0]) : '';
$table_number = isset($parts[1]) ? $parts[1] : '';
$booking_date = isset($_POST['date']) ? $_POST['date'] : '';
$from_time = isset($_POST['from_time']) ? $_POST['from_time'] : '';
$to_time = isset($_POST['to_time']) ? $_POST['to_time'] : '';
$name = isset($_POST['name']) ? $_POST['name'] : '';
$phone = isset($_POST['phone']) ? $_POST['phone'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$special_request = isset($_POST['special_request']) ? $_POST['special_request'] : '';

// Store all values in session
$_SESSION['table_id'] = $table_id;
$_SESSION['table_type'] = $table_type;
$_SESSION['table_number'] = $table_number;
$_SESSION['booking_date'] = $booking_date;
$_SESSION['from_time'] = $from_time;
$_SESSION['to_time'] = $to_time;
$_SESSION['name'] = $name;
$_SESSION['phone'] = $phone;
$_SESSION['email'] = $email;
$_SESSION['special_request'] = $special_request;

// ✅ Step 3: DB Connection (now everything is ready for booking ID)
$server = "localhost";
$username = "root";
$password = "";
$dbname = "restaurant_project";

$conn = new mysqli($server, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ Step 4: Now safely generate booking ID
$booking_id = generateBookingId($conn, $table_id, $booking_date);
$_SESSION['booking_id'] = $booking_id;


require 'php-mailer\Exception.php'; 
require 'php-mailer\PHPMailer.php';
require 'php-mailer\SMTP.php';
use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\SMTP; 
use PHPMailer\PHPMailer\Exception;

// Function to generate a random OTP
function generateVerificationCode($length = 6) {
    $characters = '0123456789'; 
    $code = ''; 
    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)]; 
    } 
    return $code; 
}

// Function to send OTP via email
function sendVerificationEmail($userEmail, $verificationCode) { 
    $mail = new PHPMailer(true); 
    try { 
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        
        // Admin Email & App Password
        $mail->Username   = 'thevelvetfork.team@gmail.com';
        $mail->Password   = 'csus cwfp goog htso'; // Use your actual Gmail App Password
        
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('thevelvetfork.team@gmail.com', 'The Velvet Fork');
        $mail->addAddress($userEmail);

        // Email content
        $mail->isHTML(false);
        $mail->Subject = 'Your OTP for Table Booking Verification';
        $mail->Body    = "Hello,\n\nYour OTP for verifying your table booking is: $verificationCode\n\nRegards,\nThe Velvet Fork Team";

        $mail->send();
        return true; 
    } catch (Exception $e) {
        return false; 
    } 
}

$userEmail = $_SESSION['email'];
$otp = generateVerificationCode();
$_SESSION['otp'] = $otp;

if (sendVerificationEmail($userEmail, $otp)) { 
    $_SESSION['user_email'] = $userEmail;
    header("Location: mailvarrification_outdoor.php");
    exit();
} else { 
    echo "Failed to send OTP. Try again later."; 
}
?>
