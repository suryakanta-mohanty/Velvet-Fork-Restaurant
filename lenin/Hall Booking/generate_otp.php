<?php 
session_start();



// Store form data in session
$_SESSION['name'] = $_POST['name'];
$_SESSION['phone'] = $_POST['phone'];
$_SESSION['email'] = $_POST['email'];
$_SESSION['identity_type'] = $_POST['identity_type'];
$_SESSION['location'] = $_POST['location'];
$_SESSION['booking_date'] = $_POST['booking_date'];
$_SESSION['from_time'] = $_POST['from_time'];
$_SESSION['to_time'] = $_POST['to_time'];
$_SESSION['occasion'] = $_POST['occasion'];
$_SESSION['hall_name'] = $_POST['hall_name'];
$_SESSION['number_of_people'] = $_POST['number_of_people'];
$_SESSION['photo_video_requirement'] = $_POST['photo_video_requirement'];
$_SESSION['description'] = $_POST['description'];
$_SESSION['special_request'] = $_POST['special_request'];




require 'php-mailer\Exception.php'; 
require 'php-mailer\PHPMailer.php';
require 'php-mailer\SMTP.php';
use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\SMTP; 
use PHPMailer\PHPMailer\Exception;

// Function to generate a random verification code 
function generateVerificationCode($length = 6) {  // Typically OTP is 6 digits
    $characters = '0123456789'; 
    $code = ''; 
    for ($i = 0; $i < $length; $i++) { 
        $code .= $characters[rand(0, strlen($characters) - 1)]; 
    } 
    return $code; 
}

// Function to send a verification email using PHPMailer 
function sendVerificationEmail($userEmail, $verificationCode) { 
    $mail = new PHPMailer(true); 
    try { 
        // Server settings 
        $mail->SMTPDebug = SMTP::DEBUG_OFF;     // Set to DEBUG_SERVER for debugging 
        $mail->isSMTP();                         
        $mail->Host       = 'smtp.gmail.com';    
        $mail->SMTPAuth   = true;                

        // ✅ Admin Email & App Password
        $mail->Username   = 'thevelvetfork.team@gmail.com';    
        $mail->Password   = 'csus cwfp goog htso';      // ⬅️ Replace with your actual Gmail App Password

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('thevelvetfork.team@gmail.com', 'The Velvet Fork');   // Admin sender email
        $mail->addAddress($userEmail);                                   // ✅ Test recipient email

        // Content 
        $mail->isHTML(false); 
        $mail->Subject = 'Your OTP for Email Verification'; 
        $mail->Body    = "Hello,\n\nYour OTP for email verification is: $verificationCode\n\nRegards,\nThe Velvet Fork Team"; 

        $mail->send(); 
        return true; 
    } catch (Exception $e) {
        return false; 
    } 
}


$userEmail = $_SESSION['email']; // Your test email
$otp = generateVerificationCode(); 
$_SESSION['otp'] = $otp;
if (sendVerificationEmail($userEmail, $otp)) { 
// OTP mail sent successfully
$_SESSION['user_email'] = $userEmail; // Store user email for mailvarrification
header("Location: mailvarrification.php");
exit();
} else { 
    echo "Failed to send OTP. Try again later."; 
}
?>
