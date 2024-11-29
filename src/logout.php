<?php
session_start();
session_unset();
session_destroy();
header("Location: customer_first_page.php");
exit();
?>