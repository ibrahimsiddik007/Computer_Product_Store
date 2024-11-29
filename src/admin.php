<?php
include 'db.php';
session_start();

// Check if the user is already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$login_failed = "Login Failed! Check your credentials!";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_id = $_POST['user'];
    $password = $_POST['pass'];

    // Sanitizing input for security
    $s_admin_id = mysqli_real_escape_string($conn, $admin_id);
    $s_password = mysqli_real_escape_string($conn, $password);

    $sql = "SELECT * FROM administrator WHERE Admin_ID='$s_admin_id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($s_password, $row['Password'])) {
            $_SESSION['admin_id'] = $s_admin_id;
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid password.');window.history.back(); </script>";
        }
    } else {
        echo "<script>alert('Invalid admin ID.'); window.history.back();</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <form action="admin.php" method="post">
            <div class="form-group">
                <p id="first_time">For entering first time, change your password from Forgot Password</p>
                <label for="admin_id">Admin ID</label>
                <input type="text" id="admin_id" name="user" required>
                <label for="password">Password</label>
                <input type="password" id="password" name="pass" required>
            </div>
            <button type="submit">Login</button>
            <p><a href="#" onclick="showResetForm()">Forgot Password? Click Here</a></p>
        </form>
    </div>

    <script>
    function showResetForm() {
        window.location.href = 'admin_reset_pass.php';
    }
    </script>
</body>
</html>