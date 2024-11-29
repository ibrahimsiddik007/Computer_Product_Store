<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/nav_customer_first.css">
    <title>Document</title>
</head>
<body>
<div class="container">
        <header>
            <h1>Computer Product Store</h1>
        </header>
        <nav>
            <ul>
            <li><a href="customer_first_page.php">Home</a></li>
                <li><a href=cust_categories.php>All Categories</a></li>
                <li>
                    <form action="cus_search_result.php" method="get" class="search-form">
                        <input type="text" name="search" placeholder="Search by Product Name" required>
                        <button type="submit">Search</button>
                    </form>
                </li>
                <?php
            if (isset($_SESSION['customer_id'])) {
                echo '<li class = "welcome">Welcome, ' . htmlspecialchars($_SESSION['customer_name']) . '</li>';
                echo '<li><a href="view_cart.php">Cart</a></li>';
                echo '<li><a href="logout.php">Logout</a></li>';
            } else {
                echo '<li><a href="cust_login.php">Login</a></li>';
                echo '<li><a href="cust_register.php">Register</a></li>';
            }
            ?>
            </ul>
        </nav>
</div>
</body>
</html>