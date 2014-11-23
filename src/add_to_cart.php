<div id="add_to_cart">
    <?php
        if ($loggedin) {
            if (isset($_GET['id'])) {
                $query = $connection->prepare('SELECT * FROM item WHERE upc=?');
                $query->bind_param('i', $_GET['id']);
                $query->execute();
                $result = $query->get_result();

                $result = $result->fetch_assoc();
                
                if ($result['stock'] > 0) {
                    echo '<form action="?act=add_to_cart&id='.$_GET['id'].'" method="post">';
                    echo '<h1>Add item to cart</h1><br />';
                    echo '<p>Item: '.$result['title'].'</p>';
                    echo '<p>Quantity: <input name="quantity" type="number" min="1" value="1" max="'.$result['stock'].'" /></p><br />';
                    echo '<p><input type="submit" value="Add to cart" /></p>';
                    echo '</form>';
                } else {
                    echo '<h1>Error adding item to cart</h1>';
                    echo '<p>We don\'t have this item in stock. Please, try again later.<p>';
                }
            } else
                require "404.php";
        } else {
            echo '<h1>Error adding item to cart</h1>';
            echo '<p>You must be logged in to add an item to your cart.<p>';
            echo '<p>If you already have an account, please log in through the top right corner of the page;</p>';
            echo '<p>If you don\'t have an account, you can <a href="?p=register">register here</a>, it only takes a few seconds!</a>';
        }
    ?>
</div>