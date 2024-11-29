<?php
session_start();
require '../vendor/autoload.php';

use Dompdf\Dompdf;

if (!isset($_SESSION['receipt'])) {
    header("Location: customer_first_page.php");
    exit();
}

$receipt = $_SESSION['receipt'];

// Create an instance of Dompdf
$dompdf = new Dompdf();

// Generate HTML for the receipt
$html = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - Computer Product Store</title>
    <style>
        body {
            font-family: "Roboto", sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        p {
            font-size: 16px;
            margin: 10px 0;
        }
        h3 {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tfoot {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Receipt</h2>
        <p><strong>Order ID:</strong> ' . htmlspecialchars($receipt['order_id']) . '</p>
        <p><strong>Customer Name:</strong> ' . htmlspecialchars($receipt['customer_name']) . '</p>
        <p><strong>Address:</strong> ' . htmlspecialchars($receipt['address']) . '</p>
        <p><strong>Order Date:</strong> ' . htmlspecialchars($receipt['order_date']) . '</p>
        <h3>Products</h3>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>';

foreach ($receipt['products'] as $product) {
    $html .= '
                <tr>
                    <td>' . htmlspecialchars($product['product_name']) . '</td>
                    <td>' . htmlspecialchars($product['quantity']) . '</td>
                    <td>$' . htmlspecialchars($product['price']) . '</td>
                </tr>';
}

$html .= '
            </tbody>
        </table>
        <p><strong>Total Price:</strong> $' . htmlspecialchars($receipt['total_price']) . '</p>
    </div>
</body>
</html>';

// Load HTML into Dompdf
$dompdf->loadHtml($html);

// (Optional) Set up the paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream("receipt.pdf", ["Attachment" => 1]);

// Clear the receipt session data
unset($_SESSION['receipt']);
?>