<?php
session_start();
include 'db.php';

$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

// Fetch category name
$category_query = "SELECT Category_Name FROM category WHERE Category_ID = $category_id";
$category_result = $conn->query($category_query);
$category_name = $category_result->num_rows > 0 ? $category_result->fetch_assoc()['Category_Name'] : '';

// Fetch products in the selected category
$product_query = "SELECT product_id, product_name, product_description, price, image_path FROM product WHERE category_id = $category_id";
$product_result = $conn->query($product_query);
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($category_name); ?> - Computer Product Store</title>
    <link rel="stylesheet" href="assets/css/selected_category_item.css">
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
</head>
<body>
    <?php include 'nav_customer_first.php' ?>

    <div class="container">
        <h2><?php echo htmlspecialchars($category_name); ?></h2>
        <div class="product-list">
            <?php
            if ($product_result->num_rows > 0) {
                while ($row = $product_result->fetch_assoc()) {
                    echo "<div class='product-item'>";
                    echo "<img src='" . htmlspecialchars($row['image_path']) . "' alt='" . htmlspecialchars($row['product_name']) . "'>";
                    echo "<h3>" . htmlspecialchars($row['product_name']) . "</h3>";
                    echo "<p>" . htmlspecialchars($row['product_description']) . "</p>";
                    echo "<p>Price: $" . htmlspecialchars($row['price']) . "</p>";
                    echo "<button onclick='addToCart(" . htmlspecialchars($row['product_id']) . ", " . htmlspecialchars($category_id) . ")'>Add to Cart</button>";
                    echo "</div>";
                }
            } else {
                echo "No products found in this category.";
            }
            ?>
        </div>
    </div>

    <form id="add_to_cart_form" method="post" action="add_to_cart.php" style="display:none;">
        <input type="hidden" id="product_id" name="product_id">
        <input type="hidden" id="category_id" name="category_id">
        <input type="hidden" id="quantity" name="quantity">
    </form>

    <footer>
        <div class="footer-container">
            <p>&copy; <?php echo date("Y"); ?> Computer Product Store. All rights reserved.</p>
            <p>For any information please contact : </p>
            <p>Email : ibrahimsiddik007@gmail.com | Phone: 01601750278</p>
        </div>
    </footer>
</body>
</html>
<?php
$conn->close();
?>