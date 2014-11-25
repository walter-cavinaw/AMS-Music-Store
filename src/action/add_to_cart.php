<div>
    <?php
        if(!$loggedin){ //Make sure the user is logged in
            echo '<h1>Error Adding to Cart</h1>';
            echo '<p>You must be logged in to use the cart.<p>';
            echo '<p>If you already have an account, please log in through the top right corner of the page; </p';
            echo '<p>If you don\'t have an account, you can <a href="?p=register">register here</a>, it only takes a few seconds!</a>';
            die();
            
        }
        if (isset($_GET['id'])) {
            $query = $connection->prepare('SELECT * FROM item WHERE upc=?');
            $query->bind_param('i', $_GET['id']);
            $query->execute();
            $result = $query->get_result();

            if ($result->num_rows > 0) {
                $result = $result->fetch_assoc();

                if ($result['stock'] >= $_POST['quantity']) {
                    if (isset($_SESSION['cart'][$_GET['id']]))
                        $_SESSION['cart'][$_GET['id']] += $_POST['quantity'];
                    else
                        $_SESSION['cart'][$_GET['id']] = $_POST['quantity'];

                    header("Location: ?p=cart");
                } else {
                    echo '<h1>Error adding item to cart</h1>';
                    echo '<p>We don\'t have this item in stock at this quantity. Please, try again later.<p>';
                }
            } else {
                echo '<h1>Error adding item to cart</h1>';
                echo '<p>We don\'t have this item in stock. Please, try again later.<p>';
            }
        }
    ?>
</div>