<div>
<?php
    if (!empty($_POST['date']) && !empty($_POST['number'])){
        //Get upcs of items bought on that day
        $query = $connection->prepare('SELECT DISTINCT upc FROM purchase_item WHERE receiptID in (SELECT receiptID FROM purchase WHERE pdate = ?)');
        $query->bind_param('s', $_POST['date']);
        $query->execute();
        $upcs = $query->get_result();
        $query->close();
        
        //Set up the column headers
        echo '<h1>Total sales for ' . $_POST['date'] . '</h1>';
        echo '<table width=100%>';
        echo '   <tr>';
        echo '      <th width=25% align="center">Title</th>';
        echo '      <th width=25% align="center">Company</th>';
        echo '      <th width=25% align="center">Current Stock</th>';
        echo '      <th width=25% align="center">Units Sold That Day</th>';
        echo '   </tr>';
        
        $upcQuantityArray;
        //Step through each upc, getting the details for it, and summing the number of sold that day
        while ($row = $upcs->fetch_assoc()) {
            //Get the number sold that day
            $query = $connection->prepare('SELECT SUM(quantity) FROM purchase_item WHERE upc = ? and receiptID in (SELECT receiptID FROM purchase WHERE pdate = ?)');
            $query->bind_param('ss', $row['upc'], $_POST['date']);
            $query->execute();
            $quantity = $query->get_result()->fetch_row();
            $query->close();
            
            //Store the upc and quanity in the array
            $upcQuantityArray[$row['upc']] = $quantity[0];
        }
        
        //Sort the array according to value, highest value first
        arsort($upcQuantityArray);
        $keys = array_keys($upcQuantityArray); //Need to access the array with an index. Each element of keys is the key value of upcQuantityArray
        
        $i = 0;
        while($i<$_POST['number']){ //GO through the first n elements as supplied by the user
            //Get the upc
            $upc = $keys[$i];
            
            //Get the upc info
            $query = $connection->prepare('SELECT title, company, stock FROM item where upc = ?');
            $query->bind_param('s', $upc);
            $query->execute();
            $upcInfo = $query->get_result()->fetch_row();
            $query->close();
            
            //Print the info
            echo '   <tr>';
            echo '      <td align="right">' . $upcInfo[0] .'</td>';
            echo '      <td align="right">' . $upcInfo[1] .'</td>';
            echo '      <td align="right">' . $upcInfo[2] .'</td>';
            echo '      <td align="right">' . $upcQuantityArray[$upc] .'</td>';
            echo '   </tr>';
            $i++;
        }
        echo '</table>';
    } else {
        echo '<h1>Error getting top sales</h1>';
        echo '<p>You must specify a date and number of top sellers.</p>';
        echo '<p><a href="javascript:history.go(-1)">Go Back</a></p>';
    }
    ?>
</div>