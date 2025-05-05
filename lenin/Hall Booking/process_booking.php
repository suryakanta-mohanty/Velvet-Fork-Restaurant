<?php
session_start();

// DB connection
$server ="localhost";
$username = "root";
$password="";
$dbname = "restaurant_project";

$conn = new mysqli("localhost", "root", "", "restaurant_project");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect form data
/*$name = $_POST['name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$identity_type = isset($_POST['identity_type']) ? $_POST['identity_type'] : '';
$location = isset($_POST['location']) ? $_POST['location'] : '';
$booking_date = $_POST['date'];
$from_time = $_POST['from_time'];
$to_time = $_POST['to_time'];
$occasion = $_POST['occasion'];
$hall_name = isset($_POST['hall_name']) ? $_POST['hall_name'] : '';
$number_of_people = isset($_POST['number_of_people']) ? $_POST['number_of_people'] : '';
$photo_video_requirement = $_POST['photo_video'];
$description = $_POST['description'];
$special_request = $_POST['special_request'];
*/
// Retrieve session values
$name = $_SESSION['name'];
$phone = $_SESSION['phone'];
$email = $_SESSION['email'];
$identity_type = $_SESSION['identity_type'];
$location = $_SESSION['location'];
//$booking_date = $_SESSION['booking_date'];
$from_time = $_SESSION['from_time'];
$to_time = $_SESSION['to_time'];
$occasion = $_SESSION['occasion'];
$hall_name = $_SESSION['hall_name'];
$number_of_people = $_SESSION['number_of_people'];
//$photo_video_requirement = $_SESSION['photo_video_requirement'];
$description = $_SESSION['description'];
$special_request = $_SESSION['special_request'];
$booking_date = isset($_SESSION['booking_date']) ? $_SESSION['booking_date'] : '';
$photo_video_requirement = isset($_SESSION['photo_video_requirement']) ? $_SESSION['photo_video_requirement'] : '';



// Add a temporary debugging block right after the above lines:This will print all the session data on the page. You can check whether all values are coming or not.
// echo "<h3>SESSION DATA BEFORE INSERT:</h3>";
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";
// echo "Preparing to insert data into database...<br>";



// Insert query
$sql = "INSERT INTO bookings (name, phone, email, identity_type, location, booking_date, from_time, to_time, occasion, hall_name, number_of_people, photo_video_requirement, description, special_request)
VALUES ('$name', '$phone', '$email', '$identity_type', '$location', '$booking_date', '$from_time', '$to_time', '$occasion', '$hall_name', '$number_of_people', '$photo_video_requirement', '$description', '$special_request')";

if ($conn->query($sql) === TRUE) {
//     session_start();
// $_SESSION['user_email'] = $email;  // (Assuming $email was collected earlier from form)
//     header("Location: mailvarrification.html");
//exit();
    // Optional: redirect to a confirmation page
    // header("Location: confirmation_page.php");
    
        echo "
        <script>
        alert('✅ Your booking was successful!');
        window.location.href = 'bookingpage.html';
    </script>";
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
