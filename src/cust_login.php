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
            $error = "<script>alert('Invalid password.');window.history.back(); </script>";
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
    <title>Customer Login</title>
    <link rel="stylesheet" href="assets/css/cust_login.css">
</head>
<body>
    <div class="login-container">
        <h2>Customer Login</h2>
        <form action="cust_login.php" method="post">
            <div class="form-group">
                <label for="customer_Email">Email</label>
                <input type="text" id="customer_Email" name="customer_Email" required>
                <label for="customer_password">Password</label>
                <input type="password" id="customer_password" name="customer_password" required>
            </div>
            <button type="submit">Login</button>
            <?php if (isset($error)): ?>
                <p><?php echo $error; ?></p>
            <?php endif; ?>
        </form>
        <p><a href="cust_forgot_password.php">Forgot Password?</a></p>
    </div>
</body>
</html>

<?php
$conn->close();