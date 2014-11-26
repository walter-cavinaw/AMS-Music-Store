<div>
<?php
    if (!empty($_POST['date']) && !empty($_POST['number'])){
        //Get info for the items that were purchased on that day
        $query = $connection->prepare('SELECT * FROM item WHERE upc IN (SELECT upc FROM purchase_item WHERE receiptId IN (SELECT receiptID FROM purchase WHERE pdate = ?)) ORDER BY category');
        $query->bind_param('s', $_POST['date']);
        $query->execute();
        $itemsBoughtThatDay = $query->get_result();

        $query->close();
    } else {
        echo '<h1>Error getting top sales</h1>';
        echo '<p>You must specify a date and number of top sellers.</p>';
        echo '<p><a href="javascript:history.go(-1)">Go Back</a></p>';
    }
    ?>
</div>