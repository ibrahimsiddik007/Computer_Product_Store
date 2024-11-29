<?php
include 'db.php';

if (isset($_POST['category_id'])) {
    $category_id = $_POST['category_id'];
    $product_result = $conn->query("SELECT product_id, product_name FROM product WHERE category_id = $category_id");

    if ($product_result->num_rows > 0) {
        while ($row = $product_result->fetch_assoc()) {
            echo "<option value='" . $row['product_id'] . "'>" . $row['product_name'] . "</option>";
        }
    } else {
        echo "<option value=''>No products found</option>";
    }
}

$conn->close();
?>
