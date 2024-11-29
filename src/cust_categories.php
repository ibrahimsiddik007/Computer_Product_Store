<?php
include 'db.php';
session_start();

// Fetch categories from the database
$categories_result = $conn->query("SELECT Category_ID, Category_Name FROM category");
$selected_category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories - Computer Product Store</title>
    <link rel="stylesheet" href="assets/css/cust_categories.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php include 'nav_customer_first.php'; ?>

    <div class="container">
        <div class="categories">
            <h2>Categories</h2>
            <ul>
                <?php
                if ($categories_result->num_rows > 0) {
                    while ($row = $categories_result->fetch_assoc()) {
                        echo "<li><a href='#' class='category-link' data-category-id='" . $row['Category_ID'] . "'>" . $row['Category_Name'] . "</a></li>";
                    }
                } else {
                    echo "<li>No categories found.</li>";
                }
                ?>
            </ul>
        </div>
        <div class="products">
            <h2>Products</h2>
            <div id="product-list" class="product-list">
                <!-- Products will be loaded here based on the selected category -->
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function loadProducts(categoryId) {
                $.ajax({
                    url: 'cust_fetch_products.php',
                    type: 'GET',
                    data: { category_id: categoryId },
                    success: function(response) {
                        $('#product-list').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error: ' + status + error);
                    }
                });
            }

            $('.category-link').click(function(e) {
                e.preventDefault();
                var categoryId = $(this).data('category-id');
                loadProducts(categoryId);
            });

            // Load products if a category ID is provided in the URL
            var selectedCategoryId = <?php echo json_encode($selected_category_id); ?>;
            if (selectedCategoryId) {
                loadProducts(selectedCategoryId);
            }
        });
    </script>
</body>
</html>
<?php
$conn->close();
?>