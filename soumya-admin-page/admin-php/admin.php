<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "order_db");
$result = $conn->query("SELECT * FROM orders ORDER BY order_date DESC");
?>

<h2>Admin Panel - Orders</h2>
<table border="1">
    <tr>
        <th>ID</th><th>Customer</th><th>Product</th><th>Qty</th><th>Date</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['username'] ?></td>
        <td><?= $row['product'] ?></td>
        <td><?= $row['quantity'] ?></td>
        <td><?= $row['order_date'] ?></td>
    </tr>
    <?php endwhile; ?>
</table>

<br><a href="index.php">Back to Order Page</a>
