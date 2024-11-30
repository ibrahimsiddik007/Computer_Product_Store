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
    $quantity = intval($_POST['quantity']);

    // Check if the product is already in the cart
    $query = "SELECT * FROM cart WHERE Customer_ID = $customer_id AND Product_ID = $product_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Update the quantity if the product is already in the cart
        $query = "UPDATE cart SET Quantity = Quantity + $quantity WHERE Customer_ID = $customer_id AND Product_ID = $product_id";
    } else {
        // Insert the product into the cart
        $query = "INSERT INTO cart (Customer_ID, Product_ID, Quantity) VALUES ($customer_id, $product_id, $quantity)";
    }

    if ($conn->query($query) === TRUE) {
        echo "<script>alert('Product added to cart successfully!'); window.location.href='customer_first_page.php?category_id=" . $_POST['category_id'] . "';</script>";
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>