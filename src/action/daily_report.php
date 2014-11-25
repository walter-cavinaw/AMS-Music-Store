<div>
<?php
    if (!empty($_POST['date'])){
        //Get info for the items that were purchased on that day
        $query = $connection->prepare('SELECT * FROM item WHERE upc IN (SELECT upc FROM purchase_item WHERE receiptId IN (SELECT receiptID FROM purchase WHERE pdate = ?))');
        $query->bind_param('s', $_POST['date']);
        $query->execute();
        $itemsBoughtThatDay = $query->get_result();

        $query->close();

        //Get the purchases from that day
        $query = $connection->prepare('SELECT receiptID FROM purchase WHERE pdate = ?');
        $query->bind_param('s', $_POST['date']);
        $query->execute();
        $receiptIDsFromThatDay = $query->get_result();
        
        if($receiptIDsFromThatDay->num_rows<1){
            echo '<h1>No sales found on that day</h1>';
            die();
        }
        
        //Set up the column headers
        echo '<h1>Total sales for ' . $_POST['date'] . '</h1>';
        echo '<table>';
        echo '   <tr>';
        echo '      <td>UPC</id>';
        echo '      <td>Category</id>';
        echo '      <td>Price/unit</id>';
        echo '      <td>Units Sold</id>';
        echo '      <td>Total Sales</id>';
        echo '   </tr>';
        
        while ($row = $itemsBoughtThatDay->fetch_assoc()) {
            //Get the quantites sold for this upc on that day
            $query = $connection->prepare('SELECT SUM(quantity) from purchase_item WHERE receiptID IN (SELECT receiptID FROM purchase WHERE pdate = ?) AND upc = ?'); //There must be a way to pass the receiptIDSFromThatDay result set in here.
            $query->bind_param('ss', $_POST['date'], $row['upc']);
            $query->execute();
            $quantitiesSold = $query->get_result()->fetch_row();

            //Display the data for each upc
                echo '<tr>';
                echo '   <td>' . $row['upc'] . '</td>';
                echo '   <td>' . $row['category'] . '</td>';
                echo '   <td>' . $row['price'] . '</td>';
                echo '   <td>' . $quantitiesSold[0] . '</td>';
                echo '   <td>' . $quantitiesSold[0]*$row['price'] . '</td>';
                echo '</tr>';
        }
        echo '</table>';
        echo '<p><a href="javascript:history.go(-1)">Go Back</a></p>';
    } else {
        echo '<h1>Error generating sales report</h1>';
        echo '<p>You must specify a date.</p>';
        echo '<p><a href="javascript:history.go(-1)">Go Back</a></p>';
    }
    ?>
</div>