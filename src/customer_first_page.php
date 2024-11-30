<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input
    
    $customer_Email = $conn->real_escape_string($_POST['customer_Email']);
    $customer_password = $conn->real_escape_string($_POST['customer_password']);

    // Validate credentials
    $query = "SELECT Customer_ID, First_Name, Last_Name, E_Mail, password FROM Customer WHERE E_Mail = '$customer_Email'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($customer_password, $row['password'])) {
            $_SESSION['customer_id'] = $row['Customer_ID'];
            $_SESSION['customer_name'] = $row['First_Name'] . ' ' . $row['Last_Name'];
            header("Location: customer_first_page.php");
            exit();
        } else {
            $error = "Invalid Customer E-mail or Password. <a href='cust_register.php'>Create a new account</a>";
        }
    } else {
        $error = "Invalid Customer E-mail or Password. <a href='cust_register.php'>Create a new account</a>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Portal - Computer Product Store</title>
    <link rel="stylesheet" href="assets/css/customer_first_page.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
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

        $(document).ready(function() {
            $('.category-link').click(function(e) {
                e.preventDefault();
                var categoryId = $(this).data('category-id');
                $.ajax({
                    url: 'cust_fetch_products.php',
                    type: 'GET',
                    data: { category_id: categoryId },
                    success: function(response) {
                        $('#product-list').html(response);
                        $('#product-details').empty(); // Clear product details when a new category is selected
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error: ' + status + error);
                    }
                });
            });



            $('.owl-carousel').owlCarousel({
                items: 3,
                loop: true,
                margin: 10,
                nav: true,
                autoplay: true,
                autoplayTimeout: 2000,
                autoplayHoverPause: true
            });
        });
    </script>
</head>
<body>
    <?php include 'nav_customer_first.php'; ?>

    <div class="container">
        <div class="category-section">
            <h2>Featured Categories</h2>
            <div class="category-list">
                <?php
                $categories_result = $conn->query("SELECT Category_ID, Category_Name, Category_Image FROM category");

                if ($categories_result->num_rows > 0) {
                    while ($row = $categories_result->fetch_assoc()) {
                        echo "<div class='category-item'>";
                        echo "<a href='cust_categories.php?category_id=" . $row['Category_ID'] . "'>";
                        echo "<img src='" . $row['Category_Image'] . "' alt='" . $row['Category_Name'] . "' style='width:100px; height: auto;;'>";
                        echo "<h3>" . $row['Category_Name'] . "</h3>";
                        echo "</a>";
                        echo "</div>";
                    }
                } else {
                    echo "No categories found.";
                }
                ?>
            </div>
        </div>

        <div class="featured-products-section">
            <h2>Featured Products</h2>
            <div class="owl-carousel owl-theme">
                <?php
                // Fetch products from the database
                $products_result = $conn->query("SELECT product_id, product_name, image_path FROM product");

                if ($products_result->num_rows > 0) {
                    while ($row = $products_result->fetch_assoc()) {
                        echo "<div class='item'>";
                        echo "<img src='" . $row['image_path'] . "' alt='" . $row['product_name'] . "' style='width:100%; height:auto;'>";
                        echo "<h3>" . $row['product_name'] . "</h3>";
                        echo "<button class='add-to-cart-btn' onclick='addToCart(" . htmlspecialchars($row['product_id']) . ", null)'>Add to Cart</button>";
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