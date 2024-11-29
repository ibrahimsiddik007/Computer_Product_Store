<?php
    include 'db.php';
session_start();


$search_query = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$query = "SELECT product_id, product_name, product_description, price, image_path FROM product WHERE product_name LIKE '%$search_query%'";
$result = $conn->query($query);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Computer Product Store</title>
    <link rel="stylesheet" href="assets/css/cus_search_result.css">
    <script>
        function addToCart(productId) {
            var quantity = prompt("Enter the quantity:", "1");
            if (quantity != null && quantity > 0) {
                document.getElementById('product_id').value = productId;
                document.getElementById('quantity').value = quantity;
                document.getElementById('add_to_cart_form').submit();
            }
        }
    </script>
</head>
<body>
    <?php include 'nav_customer_first.php' ?>

    <div class="container">
        <h2>Search Results for "<?php echo htmlspecialchars($search_query); ?>"</h2>
        <div class="product-list">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='product-item'>";
                    echo "<img src='" . htmlspecialchars($row['image_path']) . "' alt='" . htmlspecialchars($row['product_name']) . "'>";
                    echo "<h3>" . htmlspecialchars($row['product_name']) . "</h3>";
                    echo "<p>" . htmlspecialchars($row['product_description']) . "</p>";
                    echo "<p>Price: $" . htmlspecialchars($row['price']) . "</p>";
                    echo "<button onclick='addToCart(" . htmlspecialchars($row['product_id']) . ")'>Add to Cart</button>";
                    echo "</div>";
                }
            } else {
                echo "No products found.";
            }
            ?>
        </div>
    </div>

    <form id="add_to_cart_form" method="post" action="add_to_cart.php" style="display:none;">
        <input type="hidden" id="product_id" name="product_id">
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