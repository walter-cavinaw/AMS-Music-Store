<div id="cart">
    <?php
        if (!$loggedin) {
            echo '<h1>Checkout failed</h1><br />';
            echo '<p>You must be logged into the system to checkout.</p>';
            echo '<p>If you already have an account, please log in through the top right corner of the page;</p>';
            echo '<p>If you don\'t have an account, you can <a href="?p=register">register here</a>, it only takes a few seconds!</a>';
        } elseif (!empty($_SESSION['cart'])) {
            $total = 0;
            $no_stock = false;
            foreach ($_SESSION['cart'] as $id => $quantity) {
                $query = $connection->prepare('SELECT * FROM item WHERE upc=?');
                $query->bind_param('i', $id);
                $query->execute();
                $result = $query->get_result()->fetch_assoc();
                if ($quantity > $result['stock']) {
                    $no_stock = true;
                    break;
                }
                $total += number_format($result['price']*$quantity, 2);
            }

            if ($no_stock) {
                echo '<h1>Checkout failed</h1><br />';
                echo '<p>One of more of your items are not in stock at the moment.</p>';
                echo '<p>Please, try buying a lower ammount or come back later!</p>';
            } else {
                echo '<h1>Checkout</h1><br />';
                echo '<p>The total ammount of the order is <strong>$'.$total.'</strong></p><br />';
                echo '<form action="?act=pay" method="post">';
                echo '<table><tr><td>Card Number</td><td><input type="text" name="card_number" maxlength="16" size="40" /></td></tr>';
                echo '<tr><td>Expiry Date</td><td><input type="text" name="expiry_date" maxlength="4" size="4" /> (MMYY)</td></tr>';
                echo '<tr><td></td><td><input type="submit" value="Make payment" /></td></tr></table></form>';
            }
        } else {
            echo '<h1>Checkout failed</h1><br />';
            echo '<p>You have no items in your cart!</p>';
            echo '<p>Start buying now: Take a look at our <a href="?p=list_items">virtual catalogue</a>!';
        }
    ?>
</div>