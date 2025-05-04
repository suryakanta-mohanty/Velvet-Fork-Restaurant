<?php
session_start();

// Simple hardcoded password
$admin_password = "admin123";

if ($_POST['password'] === $admin_password) {
    $_SESSION['admin_logged_in'] = true;
    header("Location: admin.php");
} else {
    echo "Incorrect password. <a href='admin_login.php'>Try again</a>";
}
?>
