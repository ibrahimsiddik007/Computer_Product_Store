<?php
session_start();
include 'db.php';


// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin.php");
    exit();
}

// Handle delete operation
if (isset($_POST['delete_product_id'])) {
    $delete_product_id = intval($_POST['delete_product_id']);

    // Delete related records from order_details
    $query = "DELETE FROM order_details WHERE product_id = $delete_product_id";
    if (!$conn->query($query)) {
        echo "Error deleting related order details: " . $conn->error;
        exit();
    }

    // Delete related records from cart
    $query = "DELETE FROM cart WHERE Product_ID = $delete_product_id";
    if (!$conn->query($query)) {
        echo "Error deleting related cart items: " . $conn->error;
        exit();
    }

    // Delete the product
    $query = "DELETE FROM product WHERE product_id = $delete_product_id";
    if ($conn->query($query) === TRUE) {
        echo "Product deleted successfully.";
    } else {
        echo "Error deleting product: " . $conn->error;
    }
    exit();
}

// Handle update operation
if (isset($_POST['update_product_id'])) {
    $product_id = intval($_POST['update_product_id']);
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_price = $_POST['product_price'];
    $product_brand = $_POST['product_brand'];
    $product_in_stock = $_POST['product_in_stock'];
    $category_id = $_POST['category_id'];
    $admin_id = $_POST['admin_id'];
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
            exit();
        }
    }

    // Prepare and bind
    $stmt = $conn->prepare("UPDATE product SET category_id = ?, brand = ?, product_name = ?, product_description = ?, price = ?, stock = ?, admin_id = ?, time_of_entry = ?, image_path = ? WHERE product_id = ?");
    $stmt->bind_param("isssdisssi", $category_id, $product_brand, $product_name, $product_description, $product_price, $product_in_stock, $admin_id, $date, $imagePath, $product_id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Product updated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
    $conn->close();
    exit();
}

// Handle search operation
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = $conn->real_escape_string($_GET['search']);
}

$query = "SELECT p.product_id, p.product_name, p.product_description, p.price, p.stock, p.image_path, p.brand AS product_brand, c.Category_Name, a.Admin_Name, p.time_of_entry 
          FROM product p 
          JOIN category c ON p.category_id = c.category_id 
          JOIN administrator a ON p.admin_id = a.admin_id";
if (!empty($search_query)) {
    $query .= " WHERE p.product_name LIKE '%$search_query%'";
}
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/pro_information.css">
    <title>Product Table</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php include 'nav.php'; ?>
    <div class="container">
        <h1 id="details">Product Details</h1>
        <p class="stock-info">Stock:<br> 1-Available<br>0-Not Available</p>
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Search by Product Name" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit">Search</button>
        </form>

        <table class="product-table">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Admin</th>
                    <th>Time of Entry</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['product_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Category_Name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['product_brand']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['product_description']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['price']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['stock']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Admin_Name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['time_of_entry']) . "</td>";
                        echo "<td><img src='" . htmlspecialchars($row['image_path']) . "' alt='Product Image' width='100' height='100'></td>";
                        echo "<td class='action-buttons'><button onclick='deleteProduct(" . htmlspecialchars($row['product_id']) . ")'>Delete</button> <button onclick='showUpdateForm(" . htmlspecialchars($row['product_id']) . ")'>Update</button></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>No products found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Update Product Modal -->
    <div id="updateProductModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeUpdateForm()">&times;</span>
            <h2>Update Product</h2>
            <form id="updateProductForm" method="post" enctype="multipart/form-data">
                <input type="hidden" name="update_product_id" id="update_product_id">
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
                <label for="category_id">Category ID:</label><br>
                <input type="number" id="category_id" name="category_id" required><br>
                <label for="admin_id">Admin ID:</label><br>
                <input type="number" id="admin_id" name="admin_id" required><br><br>
                <input type="submit" value="Update Product">
            </form>
        </div>
    </div>

    <script>
        function deleteProduct(productId) {
            if (confirm("Are you sure you want to delete this product?")) {
                $.post('product_info.php', { delete_product_id: productId }, function(response) {
                    alert(response);
                    location.reload();
                });
            }
        }

        function showUpdateForm(productId) {
            $.post('fetch_product_details.php', { product_id: productId }, function(response) {
                var product = JSON.parse(response);
                $('#update_product_id').val(product.product_id);
                $('#product_name').val(product.product_name);
                $('#product_description').val(product.product_description);
                $('#product_price').val(product.price);
                $('#product_brand').val(product.brand);
                $('input[name="product_in_stock"][value="' + product.in_stock + '"]').prop('checked', true);
                $('#existing_image_path').val(product.image_path);
                $('#category_id').val(product.category_id);
                $('#admin_id').val(product.admin_id);
                $('#updateProductModal').show();
            });
        }

        function closeUpdateForm() {
            $('#updateProductModal').hide();
        }

        $(document).ready(function() {
            $('#updateProductForm').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: 'product_info.php',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        alert(response);
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error: ' + status + error);
                    }
                });
            });
        });
    </script>
</body>
</html>
<?php
$conn->close();
?>