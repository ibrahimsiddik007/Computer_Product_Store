<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: cust_login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

// Fetch cart items for the logged-in user
$query = "SELECT p.product_id, p.product_name, p.price, c.Quantity, p.image_path FROM cart c JOIN product p ON c.Product_ID = p.product_id WHERE c.Customer_ID = $customer_id";
$result = $conn->query($query);
$total_price = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Summary - Computer Product Store</title>
    <link rel="stylesheet" href="assets/css/cart_summary.css">
    <script>
        function confirmPurchase() {
            if (confirm("Are you sure you want to complete the purchase?")) {
                document.getElementById('confirm_purchase_form').submit();
            }
        }
    </script>
</head>
<body>
    <?php include 'nav_customer_first.php'; ?>

    <div class="summary-container">
        <h2>Cart Summary</h2>
        <div class="cart-list">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $total_price += $row['price'] * $row['Quantity'];
                    echo "<div class='cart-item'>";
                    echo "<img src='" . htmlspecialchars($row['image_path']) . "' alt='" . htmlspecialchars($row['product_name']) . "'>";
                    echo "<div class='product-details'>";
                    echo "<h3>" . htmlspecialchars($row['product_name']) . "</h3>";
                    echo "<p>Price: $" . htmlspecialchars($row['price']) . "</p>";
                    echo "<p>Quantity: " . htmlspecialchars($row['Quantity']) . "</p>";
                    echo "</div>";
                    echo "</div>";
                }
                echo "<div class='total-price'>Total Price: $" . htmlspecialchars($total_price) . "</div>";
                echo "<button class='buy-now-btn' onclick='confirmPurchase()'>Buy Now</button>";
            } else {
                echo "<p class='empty-cart'>Your cart is empty.</p>";
            }
            ?>
        </div>
    </div>

    <form id="confirm_purchase_form" method="post" action="place_order.php" style="display:none;">
        <input type="hidden" id="customer_id" name="customer_id" value="<?= $customer_id; ?>">
        <input type="hidden" id="total_price" name="total_price" value="<?= $total_price; ?>">
    </form>

    <footer>
        <div class="footer-container">
            <p>&copy; <?= date("Y"); ?> Computer Product Store. All rights reserved.</p>
            <p>For any information please contact:</p>
            <p>Email: ibrahimsiddik007@gmail.com | Phone: 01601750278</p>
        </div>
    </footer>
</body>
</html>
<?php
$conn->close();
?>