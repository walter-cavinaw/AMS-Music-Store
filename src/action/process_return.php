<div>
<?php
    if (!empty($_POST['rid']) && !empty($_POST['upc']) && !empty($_POST['quantity'])){
        //Get the old stock
        $query = $connection->prepare('SELECT stock FROM item WHERE upc = ?');
        $query->bind_param('s', $_POST['upc']);
        $query->execute();
        $result = $query->get_result();
        $resultArray = $result->fetch_assoc();
        $oldStock = $resultArray["stock"];
        
        if($result->num_rows<1){
            echo '<h1>Error adding item</h1>';
            echo '<p>No mathcing UPC found.</p>';
            echo '<p><a href="javascript:history.go(-1)">Go Back</a></p>';
            die();
        }
        
        // Update stocks
        $query = $connection->prepare('UPDATE item SET stock=? WHERE upc=?;');
        $query->bind_param('ss', strval($_POST['quantity']+$oldStock), $_POST['upc']);
        $query->execute();
        
        if(!empty($_POST['price'])){
            $query = $connection->prepare('UPDATE item SET price=? WHERE upc=?;');
            $query->bind_param('ss', $_POST['price'], $_POST['upc']);
            $query->execute();
        }
        echo '<h1>Succesfully updated items.</h1>';
        echo '<p><a href="javascript:history.go(-1)">Go Back</a></p>';
    } else {
        echo '<h1>Error returning item</h1>';
        echo '<p>You must specify a Receipt ID, UPC and quantity.</p>';
        echo '<p><a href="javascript:history.go(-1)">Go Back</a></p>';
    }
    ?>
</div>