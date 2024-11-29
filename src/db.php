<?php

$servername = "localhost";
$db_user = "root";
$password = "";
$db_name = "computer_product_store";

$conn = new mysqli($servername, $db_user, $password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>