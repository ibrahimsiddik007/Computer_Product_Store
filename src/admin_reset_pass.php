<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="assets/css/admin_reset_pass.css">
</head>
<body>
    <div class="reset-container" id="reset-form">
        <h2>Reset Password</h2>
        <form action="admin_reset_pass.php" method="post">
            <div class="form-group">
                <label for="admin_id_reset">Admin ID</label>
                <input type="text" id="admin_id_reset" name="user" required>
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" required>
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit">Reset Password</button>
            <p class="back-to-login">
                <a href="#" onclick="showLoginForm()">Back to login</a>
            </p>
        </form>
    </div>
</body>
<script>
    function showLoginForm() {
        window.location.href = 'admin.php';
    }
</script>
</html>






<?php
include 'db.php';
session_start();

// Check if the user is already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: admin_dashboard.php"); // Redirect to the dashboard if logged in
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_id = isset($_POST['user']) ? trim($_POST['user']) : '';
    $new_password = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';
    $confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';

    // Check if the new password and confirm password match
    // Validate fields are filled
    if (!empty($admin_id) && !empty($new_password) && !empty($confirm_password)) {
        if ($new_password === $confirm_password) {
            // Sanitize inputs
            $s_admin_id = mysqli_real_escape_string($conn, $admin_id);
            $s_new_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password in the database
            $sql = "UPDATE administrator SET password='$s_new_password' WHERE Admin_ID='$s_admin_id'";
            $result = mysqli_query($conn, $sql);

            if ($result === TRUE) {
                echo "<script>alert('Password updated successfully. Redirecting to login page.');
                      window.location.href = 'admin.php';
                      </script>";
            } else {
                echo "<script>alert('Error updating password: " . $conn->error . "'); 
                      window.history.back();</script>";
            }
        } else {
            echo "<script>alert('Passwords do not match. Please try again.'); 
            window.history.back();</script>";
        }
    } else {
        echo "<script>alert('All fields are required.');</script>";
    }
}
?>