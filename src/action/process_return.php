<div>
<?php
    if (!empty($_POST['rid']) && !empty($_POST['upc']) && !empty($_POST['quantity'])){
        //First make sure the receipt ID is within the last 15 days
        $query = $connection->prepare('SELECT receiptid FROM purchase where ABS(DATEDIFF(pdate, CURDATE())) <= 15 && receiptid = ?');
        $query->bind_param('s', $_POST['rid']);
        $query->execute();
        $result = $query->get_result();
        $query->close();
        
        if($result->num_rows!=1){
            echo '<h1>Error returning item</h1>';
            echo '<p>No matching receipt ID found.</p>';
            echo '<p>Make sure this purchase was within the last 15 days, and you entered the receipt ID correctly.</p>';
            echo '<p><a href="javascript:history.go(-1)">Go Back</a></p>';
            die();
        }
        
        //Next make sure that the UPC is included in the receipt
        $query = $connection->prepare('SELECT quantity from purchase_item where receiptId = ? and upc = ?');
        $query->bind_param('ss', $_POST['rid'], $_POST['upc']);
        $query->execute();
        $result = $query->get_result();
        $query->close();
        
        if($result->num_rows!=1){
            echo '<h1>Error returning item</h1>';
            echo '<p>No matching UPC found in the given receipt ID.</p>';
            echo '<p>Make sure this purchase included the returned item.</p>';
            echo '<p><a href="javascript:history.go(-1)">Go Back</a></p>';
            die();
        }
        $quantityBought = $result->fetch_row()[0];

    
        //Make sure the quantity being returned is less than or equal to the quantity purchased
        if($quantityBought<$_POST['quantity']){
            echo '<h1>Error returning item</h1>';
            echo '<p>Too many items being returned.</p>';
            echo '<p>Make sure the quantity being returned is less than or equal to the quantity purchased.</p>';
            echo '<p><a href="javascript:history.go(-1)">Go Back</a></p>';
            die();
        }
        
        //Get the highest return ID so far
        $query = $connection->prepare('SELECT MAX(retid) FROM return_item;');
        $query->execute();
        $max = $query->get_result()->fetch_row()[0];
        $query->close();
        
        $retid = $max + 1;
        //All checks out, make return id
        //First purchase_return
        $query = $connection->prepare('INSERT INTO purchase_return VALUES (?, CURDATE(), ?)');
        $query->bind_param('ss', $retid, $_POST['rid']);
        $query->execute();
        $result = $query->get_result();
        $query->close();
        //Next return_item
        $query = $connection->prepare('INSERT INTO return_item VALUES (?, ?, ?)');
        $query->bind_param('sss', $retid, $_POST['upc'], $_POST['quantity']);
        $query->execute();
        $result = $query->get_result();
        $query->close();
        
        echo '<h1>Succesfully returned item(s).</h1>';
        echo '<p>Return ID is: ' . $retid . '</p>';
        echo '<p><a href="javascript:history.go(-1)">Go Back</a></p>';
        
    } else {
        echo '<h1>Error returning item</h1>';
        echo '<p>You must specify a Receipt ID, UPC and quantity.</p>';
        echo '<p><a href="javascript:history.go(-1)">Go Back</a></p>';
    }
    ?>
</div>