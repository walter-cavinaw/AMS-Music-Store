<div id="cart">
    <h1>Cart</h1>
    <?php
        if (!empty($_SESSION['cart'])) {
            echo '<table width="100%"><tr><td width="40%"><h3>Item</h3></td><td width="15%"><h3>Quantity</h3></td><td width="15%"><h3>Stock</h3></td><td width="20%"><h3>Total Price</h3></td><td></td></tr>';

            $total = 0;
            foreach ($_SESSION['cart'] as $id => $quantity) {
                $query = $connection->prepare('SELECT * FROM item WHERE upc=?');
                $query->bind_param('i', $id);
                $query->execute();
                $result = $query->get_result()->fetch_assoc();

                echo '<tr><td>'.$result['title'].'</td><td>'.$quantity.'</td><td>'.$result['stock'].'</td><td>$'.number_format($result['price']*$quantity, 2).'</td><td><a href="?act=remove_from_cart&id='.$id.'">Remove</a></td></tr>';
                $total += number_format($result['price']*$quantity, 2);
            }

            echo '<tr><td></td><td></td><td><h3 style="color: #fff;">Total</h3></td><td style="color: #fff;">$'.$total.'</td><td></td></tr>';
            echo '<tr><td></td><td></td><td></td><td><a href="?p=checkout" style="font-size: 18px">Checkout ></a></td><td></td></tr>';
            echo '</table>';
        } else {
            echo '<br /><p>You have no items to display!</p>';
            echo '<p>Start buying now: Take a look at our <a href="?p=list_items">virtual catalogue</a>!';
        }
    ?>
</div>