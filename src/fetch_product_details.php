<?php
include 'db.php';

if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $product_result = $conn->query("SELECT * FROM product WHERE product_id = $product_id");

    if ($product_result->num_rows > 0) {
        $product = $product_result->fetch_assoc();
        echo json_encode($product);
    } else {
        echo json_encode([]);
    }
}

$conn->close();
?>