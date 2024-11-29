<?php
 include 'db.php';

// Fetch categories from the database
$categories_result = $conn->query("SELECT category_id, category_name FROM category");

$select_str = "";

// Check if categories were fetched successfully
if ($categories_result && $categories_result->num_rows > 0) {
    while ($row = $categories_result->fetch_assoc()) {
        // Concatenate each category as an option for the dropdown
        $select_str .= "<option value='" . $row['category_id'] . "'>" . $row['category_name'] . "</option>";}
} else {
    echo "No categories found or query failed.";
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_price = $_POST['product_price'];
    $category_id = $_POST['Category_ID'];
    $product_brand = $_POST['product_brand'];
    $in_stock = $_POST['product_in_stock'];
    $admin_id = $_POST['Admin_ID'];
    $date = date('Y-m-d H:i:s', time());
    $imagePath = "";


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
                } else {
                    echo "Please upload an image file.";
                    exit;
                }


    // Prepare and bind

$stmt = $conn->prepare("INSERT INTO product ( category_id, brand, product_name, product_description, price, stock, admin_id, time_of_entry,image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?,?)");
$stmt->bind_param("isssdisss",$category_id, $product_brand, $product_name, $product_description, $product_price, $in_stock, $admin_id, $date, $imagePath);
    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>alert('New product added successfully');</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();

    // Close the connection
    $conn->close();
}
?>








<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <link rel="stylesheet" href="assets/css/add_products.css">
</head>
<body>
    <?php include 'nav.php'; ?>
    <h2>Add a New Product</h2>
    <form method="post" action="" enctype="multipart/form-data">

        <input type="hidden" name="image_path" id="image_path">

        <label for="Category_ID">Category:</label><br>
        <select id="Category_ID" name="Category_ID" required>
            <option value="" disabled selected>Select Category</option>
            <?php echo $select_str; ?>
        </select><br>

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
        <input type="file" id="product_image" name="product_image" accept="image/*" required><br>

        <label for="data_insertion_admin">Data Insertion Admin ID:</label><br>
        <input type="number" id="Admin_ID" name="Admin_ID" required><br><br>

        <input type="submit" value="Submit">
    </form>
</body>
</html>