<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: cust_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = intval($_POST['product_id']);
    $customer_id = $_SESSION['customer_id'];

    // Remove the product from the cart
    $query = "DELETE FROM cart WHERE Customer_ID = $customer_id AND Product_ID = $product_id";
    if ($conn->query($query) === TRUE) {
        echo "<script>alert('Product removed from cart successfully!'); window.location.href='view_cart.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>