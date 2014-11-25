<div>
    <?php
        if (!empty($_SESSION['cart'])) {
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
            }

            if ($no_stock) {
                echo '<h1>Checkout failed</h1>';
                echo '<p>One of more of your items are not in stock at the moment.</p>';
                echo '<p>Please, try buying a lower ammount or come back later!</p>';
            } else {
                //Get the number of pending orders
                $query = $connection->prepare('SELECT * FROM purchase WHERE deliveredDate IS NULL AND expectedDate IS NOT NULL'); //Find outstanding orders
                $query->execute();
                $numOutstandingOrders = $query->get_result()->num_rows;
                var_dump($numOutstandingOrders);
                $expectedDelay= strval(2 + round($numOutstandingOrders/6)); //Say standard is two days and we can deliver 6 per day
                $query->close(); //Need to close it so we can execute the next query
                $query = $connection->prepare('INSERT INTO purchase (pdate, cid, cardNumber, expiryDate, expectedDate) VALUES (CURDATE(), ?, ?, ?, DATE_ADD(CURDATE(), INTERVAL ?  DAY))');
                if(!$query){
                    echo ' : ' . $connection->error;
                }
                $query->bind_param('ssss', $_SESSION['username'], $_POST['card_number'], $_POST['expiry_date'],$expectedDelay);
                $query->execute();

                if ($query->affected_rows >= 1) {
                    $receipt_id = $query->insert_id;

                    foreach ($_SESSION['cart'] as $id => $quantity) {
                        // Add item to purchase_item
                        $query = $connection->prepare('INSERT INTO purchase_item (receiptId, upc, quantity) VALUES (?, ?, ?)');
                        $query->bind_param('iii', $receipt_id, $id, $quantity);
                        $query->execute();

                        // Update stock
                        $query = $connection->prepare('UPDATE item SET stock=stock-'.$quantity.' WHERE upc=?');
                        $query->bind_param('i', $id);
                        $query->execute();
                    }
                    
                    echo '<h1>Checkout successful</h1><br />';
                    echo '<p>Your payment has been processed successfully.</p><p>You can now wait for the items at your address in ' . $expectedDelay .' days.</p><br />';
                    echo '<p>Your transaction number is <strong>'.$receipt_id.'</strong>.</p><p>Write it down in case you need to contact us about your order.</p><br />';
                    echo '<p>Have a great day and consider taking a look at our <a href="index.php">other offers</a>.</p>';
                    
                    unset($_SESSION['cart']);
                }
            }
        }
    ?>
</div>