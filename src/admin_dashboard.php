<?php
    include 'db.php';

session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin.php");
    exit();
}

// Fetch total customers
$total_customers_query = "SELECT COUNT(*) AS total_customers FROM customer";
$total_customers_result = $conn->query($total_customers_query);
$total_customers = $total_customers_result->fetch_assoc()['total_customers'];

// Fetch total products
$total_products_query = "SELECT COUNT(*) AS total_products FROM product";
$total_products_result = $conn->query($total_products_query);
$total_products = $total_products_result->fetch_assoc()['total_products'];

// Fetch total categories
$total_categories_query = "SELECT COUNT(*) AS total_categories FROM category";
$total_categories_result = $conn->query($total_categories_query);
$total_categories = $total_categories_result->fetch_assoc()['total_categories'];

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Interface - Computer Product Store</title>
    <link rel="stylesheet" href="assets/css/admin_dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'nav.php'; ?>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <div class="at-a-glance">
            <div class="glance-item">
                <h2>Total Customers</h2>
                <p><?php echo $total_customers; ?></p>
            </div>
            <div class="glance-item">
                <h2>Total Products</h2>
                <p><?php echo $total_products; ?></p>
            </div>
            <div class="glance-item">
                <h2>Total Categories</h2>
                <p><?php echo $total_categories; ?></p>
            </div>
        </div>
    </div>
</body>
</html>
