<?php
include 'db.php';

if (isset($_GET['category_id'])) {
    $category_id = intval($_GET['category_id']);
    $query = "SELECT product_id, product_name, price, image_path FROM product WHERE category_id = $category_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='product-item'>";
            echo "<img src='" . htmlspecialchars($row['image_path']) . "' alt='" . htmlspecialchars($row['product_name']) . "'>";
            echo "<h3>" . htmlspecialchars($row['product_name']) . "</h3>";
            echo "<p>Price: $" . htmlspecialchars($row['price']) . "</p>";
            echo "<button onclick='addToCart(" . htmlspecialchars($row['product_id']) . ")'>Add to Cart</button>";
            echo "</div>";
        }
    } else {
        echo "No products found for this category.";
    }
}

$conn->close();
?>