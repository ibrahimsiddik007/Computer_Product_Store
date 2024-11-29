<?php
// Database configuration
include 'db.php';
// Fetch categories from the database
$categories_result = $conn->query("SELECT category_id, category_name FROM category");

$select_str = "";

// Check if categories were fetched successfully
if ($categories_result && $categories_result->num_rows > 0) {
    while ($row = $categories_result->fetch_assoc()) {
        // Concatenate each category as an option for the dropdown
        $select_str .= "<option value='" . $row['category_id'] . "'>" . $row['category_name'] . "</option>";
    }
} else {
    echo "No categories found or query failed.";
}

$select_pro = ""; // Initialize the product options string

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['Product_Name']; // Assuming Product_Name holds the product_id
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_price = $_POST['product_price'];
    $category_id = $_POST['Category_ID'];
    $product_brand = $_POST['product_brand'];
    $in_stock = $_POST['product_in_stock'];
    $admin_id = $_POST['Admin_ID'];
    $date = date('Y-m-d H:i:s', time());
    $imagePath = $_POST['existing_image_path']; // Use the existing image path

    // Check if a new image is uploaded
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        // Define the directory to store images
        $targetDir = "assets/uploads/";
        // Get the original filename
        $imageFileName = basename($_FILES["product_image"]["name"]);
        // Set the path where the image will be saved
        $targetFilePath = $targetDir . $imageFileName;

        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $targetFilePath)) {
            // File upload successful, proceed to save other data
            $imagePath = $targetFilePath;
        } else {
            echo "Error uploading the image.";
            exit;
        }
    }

    // Prepare and bind
    $stmt = $conn->prepare("UPDATE product SET category_id = ?, brand = ?, product_name = ?, product_description = ?, price = ?, stock = ?, admin_id = ?, time_of_entry = ?, image_path = ? WHERE product_id = ?");
    $stmt->bind_param("isssdisssi", $category_id, $product_brand, $product_name, $product_description, $product_price, $in_stock, $admin_id, $date, $imagePath, $product_id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>alert('Product updated successfully');</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <link rel="stylesheet" href="assets/css/update_products.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php include 'nav.php'; ?>
    <h1 class="Header">Update Product based on the category and name</h1>
    <div class="update-product-container">
        <div class="category-selection">
            <h2>Select product that you want to update (choose from category and product name)</h2>
            <form method="post" action="" enctype="multipart/form-data">
                <label for="Category_ID">Category:</label><br>
                <select id="Category_ID" name="Category_ID" required>
                    <option value="" disabled selected>Select Category</option>
                    <?php echo $select_str; ?>
                </select><br>

                <label for="Product_Name">Product Name:</label><br>
                <select id="Product_Name" name="Product_Name" required>
                    <option value="" disabled selected>Select Product</option>
                    <?php echo $select_pro; ?>
                </select><br>

                <div class="info-box">
                    <h3>Updated Product Information</h3>

                    <input type="hidden" name="existing_image_path" id="existing_image_path">

                    <label for="product_name">Product Name:</label><br>
                    <input type="text" id="product_name" name="product_name" required><br>

                    <label for="product_description">Product Description:</label><br>
                    <textarea id="product_description" name="product_description" required></textarea><br>

                    <label for="product_price">Product Price:</label><br>
                    <input type="number" id="product_price" name="product_price" required><br>

                    <label for="product_brand">Product Brand:</label><br>
                    <textarea id="product_brand" name="product_brand" required></textarea><br>

                    <label for="product_in_stock">Product In Stock:</label><br>
                    <input type="radio" id="in_stock_yes" name="product_in_stock" value="1" required>
                    <label for="in_stock_yes">Yes</label>
                    <input type="radio" id="in_stock_no" name="product_in_stock" value="0" required>
                    <label for="in_stock_no">No</label><br>

                    <label for="product_image">Product Image:</label><br>
                    <input type="file" id="product_image" name="product_image" accept="image/*"><br>

                    <label for="Admin_ID">Admin ID:</label><br>
                    <input type="number" id="Admin_ID" name="Admin_ID" required><br><br>

                    <input type="submit" value="Update Product">
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function fetchData(url, data, successCallback) {
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: data,
                    success: successCallback,
                    error: function(xhr, status, error) {
                        console.error('AJAX Error: ' + status + error);
                    }
                });
            }

            $('#Category_ID').change(function() {
                var categoryId = $(this).val();
                
                if (categoryId) {
                    fetchData('fetch_product_update.php', {category_id: categoryId}, function(response) {
                        $('#Product_Name').html(response);
                    });
                } else {
                    $('#Product_Name').html('<option value="">Select Product</option>');
                }
            });

            $('#Product_Name').change(function() {
                var productId = $(this).val();
                
                if (productId) {
                    fetchData('fetch_product_details.php', {product_id: productId}, function(response) {
                        var product = JSON.parse(response);
                        $('#product_name').val(product.product_name);
                        $('#product_description').val(product.product_description);
                        $('#product_price').val(product.price);
                        $('#product_brand').val(product.brand);
                        $('input[name="product_in_stock"][value="' + product.in_stock + '"]').prop('checked', true);
                        $('#existing_image_path').val(product.image_path);
                    });
                }
            });
        });
    </script>
</body>
</html>