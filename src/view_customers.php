<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin.php");
    exit();
}

include 'db.php';

// Fetch customer information
$query = "SELECT c.Customer_ID, c.First_Name, c.Last_Name, c.Phone_Number, c.Date_of_Birth, c.E_Mail, a.Apartment_no, a.Street_Name, a.City, a.State, a.Pincode 
          FROM customer c 
          LEFT JOIN address a ON c.Customer_ID = a.Customer_ID";
$result = $conn->query($query);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Customers - Admin</title>
    <link rel="stylesheet" href="assets/css/view_customers.css">
</head>
<body>
    <?php include 'nav.php'; ?>
    <div class="container">
        <h1>Customer Information</h1>
        <table class="customer-table">
            <thead>
                <tr>
                    <th>Customer ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Phone Number</th>
                    <th>Date of Birth</th>
                    <th>Email</th>
                    <th>Address</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $address = $row['Apartment_no'] . ', ' . $row['Street_Name'] . ', ' . $row['City'] . ', ' . $row['State'] . ', ' . $row['Pincode'];
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['Customer_ID']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['First_Name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Last_Name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Phone_Number']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Date_of_Birth']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['E_Mail']) . "</td>";
                        echo "<td>" . htmlspecialchars($address) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No customers found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>