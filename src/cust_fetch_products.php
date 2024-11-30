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
            echo "<p>Price: BDT" . htmlspecialchars($row['price']) . "</p>";
            echo "<button onclick='addToCart(" . htmlspecialchars($row['product_id']) . ", " . htmlspecialchars($category_id) . ")'>Add to Cart</button>";
            echo "</div>";
        }
    } else {
        echo "No products found for this category.";
    }
}

$conn->close();
?>

<script>
    function addToCart(productId, categoryId) {
        var quantity = prompt("Enter the quantity:", "1");
        if (quantity != null && quantity > 0) {
            document.getElementById('product_id').value = productId;
            document.getElementById('category_id').value = categoryId;
            document.getElementById('quantity').value = quantity;
            document.getElementById('add_to_cart_form').submit();
        }
    }
</script>

<form id="add_to_cart_form" method="post" action="add_to_cart.php" style="display:none;">
    <input type="hidden" id="product_id" name="product_id">
    <input type="hidden" id="category_id" name="category_id">
    <input type="hidden" id="quantity" name="quantity">
</form>