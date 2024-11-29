<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin.php");
    exit();
}

include 'db.php';

// Fetch order information
$query = "SELECT o.Order_ID, o.Order_Date, c.First_Name, c.Last_Name, p.product_name, od.quantity, od.price 
          FROM order_table o
          JOIN customer c ON o.customer_id = c.Customer_ID
          JOIN order_details od ON o.Order_ID = od.order_id
          JOIN product p ON od.product_id = p.product_id";
$result = $conn->query($query);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders - Admin</title>
    <link rel="stylesheet" href="assets/css/view_orders.css">
</head>
<body>
    <?php include 'nav.php'; ?>
    <div class="container">
        <h1>Customer Orders</h1>
        <table class="order-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Order Date</th>
                    <th>Customer Name</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['Order_ID']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Order_Date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['First_Name'] . ' ' . $row['Last_Name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['price']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No orders found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>