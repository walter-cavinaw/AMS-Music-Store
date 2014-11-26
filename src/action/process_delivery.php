<div>
<?php
    if (!empty($_POST['rid']) && !empty($_POST['date'])){
        //Get the old stock
        $query = $connection->prepare('SELECT deliveredDate FROM purchase WHERE receiptID = ?');
        $query->bind_param('s', $_POST['rid']);
        $query->execute();
        $result = $query->get_result();
        
        if($result->num_rows<1){
            echo '<h1>Error processing delivery</h1>';
            echo '<p>No matching receiptID found.</p>';
            echo '<p><a href="javascript:history.go(-1)">Go Back</a></p>';
            die();
        }
        
        // Update data
        $query = $connection->prepare('UPDATE purchase SET deliveredDate=? WHERE receiptID=?;');
        $query->bind_param('ss', $_POST['date'], $_POST['rid']);
        $query->execute();

        echo '<h1>Succesfully updated delivery.</h1>';
        echo '<p><a href="javascript:history.go(-1)">Go Back</a></p>';
    } else {
        echo '<h1>Error processing delivery</h1>';
        echo '<p>You must specify a UPC and quantity.</p>';
        echo '<p><a href="javascript:history.go(-1)">Go Back</a></p>';
    }
    ?>
</div>