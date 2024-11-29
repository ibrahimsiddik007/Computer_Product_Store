<?php
    
session_start();
include 'db.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input
    $customer_id = $conn->real_escape_string($_POST['customer_id']);
    $new_password = $conn->real_escape_string($_POST['new_password']);
    $confirm_password = $conn->real_escape_string($_POST['confirm_password']);

    if ($new_password === $confirm_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        // Update password in the database
        $query = "UPDATE customer SET password = '$hashed_password' WHERE Customer_ID = '$customer_id'";
        if ($conn->query($query) === TRUE) {
            $success = "Password updated successfully. <a href='cust_login.php'>Login</a>";
        } else {
            $error = "Error updating password: " . $conn->error;
        }
    } else {
        $error = "Passwords do not match.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="assets/css/cust_forgot_password.css">
</head>
<body>
    <div class="login-container">
        <h2>Reset Password</h2>
        <form action="cust_forgot_password.php" method="post">
            <div class="form-group">
                <label for="customer_id">Customer ID</label>
                <input type="text" id="customer_id" name="customer_id" required>
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" required>
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit">Reset Password</button>
            <?php if (isset($error)): ?>
                <p><?php echo $error; ?></p>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <p><?php echo $success;  ?> </p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>