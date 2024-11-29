<?php
session_start();
include 'db.php';

// Set the default timezone
date_default_timezone_set('Asia/Dhaka');

// Check if the user is logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: cust_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_SESSION['customer_id'];
    $order_id = uniqid('order');
    $shipping_date = date('Y-m-d H:i:s', strtotime('+7 days'));
    $order_date = date('Y-m-d H:i:s');
    $total_price = floatval($_POST['total_price']);

    // Insert order into order_table
    $query = "INSERT INTO order_table (Order_ID, customer_id, shipping_date, Order_Date, Total_Price) VALUES ('$order_id', $customer_id, '$shipping_date', '$order_date', $total_price)";
    if ($conn->query($query) === TRUE) {
        // Fetch cart items for the logged-in user
        $query = "SELECT c.Product_ID, c.Quantity, p.product_name, p.price FROM cart c JOIN product p ON c.Product_ID = p.Product_ID WHERE c.Customer_ID = $customer_id";
        $result = $conn->query($query);

        // Insert each cart item into order_details
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $product_id = $row['Product_ID'];
            $quantity = $row['Quantity'];
            $price = $row['price'];
            $product_name = $row['product_name'];

            $query = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES ('$order_id', $product_id, $quantity, $price)";
            if (!$conn->query($query)) {
                echo "Error: " . $conn->error;
                exit();
            }

            $products[] = [
                'product_name' => $product_name,
                'quantity' => $quantity,
                'price' => $price
            ];
        }

        // Clear the cart
        $query = "DELETE FROM cart WHERE Customer_ID = $customer_id";
        $conn->query($query);

        // Fetch customer details and address
        $query = "SELECT c.First_Name, c.Last_Name, a.Apartment_no, a.Street_Name, a.State, a.City, a.Pincode 
                  FROM customer c 
                  JOIN address a ON c.Customer_ID = a.Customer_ID 
                  WHERE c.Customer_ID = $customer_id";
        $customer_result = $conn->query($query);
        $customer = $customer_result->fetch_assoc();

        // Concatenate address components into a single string
        $address = $customer['Apartment_no'] . ', ' . $customer['Street_Name'] . ', ' . $customer['City'] . ', ' . $customer['State'] . ', ' . $customer['Pincode'];

        // Store order details in session for receipt
        $_SESSION['receipt'] = [
            'order_id' => $order_id,
            'customer_name' => $customer['First_Name'] . ' ' . $customer['Last_Name'],
            'address' => $address,
            'products' => $products,
            'total_price' => $total_price,
            'order_date' => $order_date
        ];

        header("Location: cus_receipt.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>