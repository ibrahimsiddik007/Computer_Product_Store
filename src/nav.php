<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/nav.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <title>Admin Navigation</title>
</head>
<body>
<div class="container">
    <header>
        <h1>Computer Product Store</h1>
    </header>
    <nav>
        <ul>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="add_products.php">Add Products</a></li>
            <li><a href="product_info.php">Product Management</a></li>
            <li><a href="view_orders.php">View Orders</a></li>
            <li><a href="view_customers.php">View Customers</a></li>
            <li><a href="admin_logout.php">Logout</a></li>
        </ul>
    </nav>
</div>
</body>
</html>
