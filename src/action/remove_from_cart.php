<?php
    if (isset($_GET['id'])) {
        unset($_SESSION['cart'][$_GET['id']]);
        header("Location: ?p=cart");
    } else
        require "404.php";
?>