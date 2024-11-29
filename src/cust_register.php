<?php
session_start();
include 'db.php';

$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);
    $date_of_birth = $conn->real_escape_string($_POST['date_of_birth']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $apartment_no = $conn->real_escape_string($_POST['apartment_no']);
    $street_name = $conn->real_escape_string($_POST['street_name']);
    $state = $conn->real_escape_string($_POST['state']);
    $city = $conn->real_escape_string($_POST['city']);
    $pincode = $conn->real_escape_string($_POST['pincode']);

    // Insert new customer into the database
    $query = "INSERT INTO customer (First_Name, Last_Name, Phone_Number, Date_of_Birth, E_Mail, password) VALUES ('$first_name', '$last_name', '$phone_number', '$date_of_birth', '$email', '$hashed_password')";
    if ($conn->query($query) === TRUE) {
        $customer_id = $conn->insert_id; // Get the inserted customer ID

        // Insert address into the address table
        $query = "INSERT INTO address (Apartment_no, Street_Name, State, City, Pincode, Customer_ID) VALUES ('$apartment_no', '$street_name', '$state', '$city', '$pincode', $customer_id)";
        if ($conn->query($query) === TRUE) {
            $success = true;
        } else {
            $error = "Error creating address: " . $conn->error;
        }
    } else {
        $error = "Error creating account: " . $conn->error;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Registration</title>
    <link rel="stylesheet" href="assets/css/cust_register.css">
</head>
<body>
    <div class="register-container">
        <h2>Create a New Account</h2>
        <form action="cust_register.php" method="post">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" required>
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" required>
                <label for="phone_number">Phone Number</label>
                <input type="text" id="phone_number" name="phone_number" required>
                <label for="date_of_birth">Date of Birth</label>
                <input type="date" id="date_of_birth" name="date_of_birth" required>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <label for="apartment_no">Apartment No</label>
                <input type="text" id="apartment_no" name="apartment_no" required>
                <label for="street_name">Street Name</label>
                <input type="text" id="street_name" name="street_name" required>
                <label for="state">State</label>
                <input type="text" id="state" name="state" required>
                <label for="city">City</label>
                <input type="text" id="city" name="city" required>
                <label for="pincode">Pincode</label>
                <input type="text" id="pincode" name="pincode" required>
            </div>
            <button type="submit">Register</button>
            <?php if (isset($error)): ?>
                <p class="error-message"><?php echo $error; ?></p>
            <?php endif; ?>
        </form>
    </div>
    <script>
        <?php if ($success): ?>
            alert("Account created successfully.");
            window.location.href = 'cust_login.php';
        <?php endif; ?>
    </script>
</body>
</html>