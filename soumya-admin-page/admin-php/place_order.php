<?php
$conn = new mysqli("localhost", "root", "", "order_db");

if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$username = $_POST['username'];
$product = $_POST['product'];
$quantity = $_POST['quantity'];

$sql = "INSERT INTO orders (username, product, quantity) VALUES ('$username', '$product', '$quantity')";
if ($conn->query($sql)) {
    echo "Order placed successfully.<br><a href='index.php'>Back to Order Page</a>";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
