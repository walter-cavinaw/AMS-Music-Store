<div>
<?php
    if (!empty($_POST['date'])){
        //Get info for the items that were purchased on that day
        $query = $connection->prepare('SELECT * FROM item WHERE upc IN (SELECT upc FROM purchase_item WHERE receiptId IN (SELECT receiptID FROM purchase WHERE pdate = ?)) ORDER BY category');
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
        echo '<table width=100%>';
        echo '   <tr>';
        echo '      <th width=15%>UPC</th>';
        echo '      <th width=30%>Category</th>';
        echo '      <th width=15%>Price/unit</th>';
        echo '      <th width=15%>Units Sold</th>';
        echo '      <th width=15%>Total Sales</th>';
        echo '   </tr>';
        
    $totalSales = '';
        $totalValue = '';
        
        $prevRowCategory = '';
        $prevRowRunningSales = '';
        $prevRowRunningValue = '';
        while ($row = $itemsBoughtThatDay->fetch_assoc()) {
            //If this isnt the same category as the last one we need to print out the totals from the last category
            if($prevRowCategory!=$row['category'] && $prevRowCategory!=''){
                echo '<tr>';
                echo '   <td align="right"> </td>';
                echo '   <td align="right"> </td>';
                echo '   <td align="right">Total:</td>';
                echo '   <td align="right">' . $prevRowRunningSales . '</td>';
                echo '   <td align="right">$' . $prevRowRunningValue . '</td>';
                echo '</tr>';
                
                //Reset the variables
                $prevRowCategory = '';
                $prevRowRunningSales = '';
                $prevRowRunningValue = '';
            }
            
            //Get the quantites sold for this upc on that day
            $query = $connection->prepare('SELECT SUM(quantity) from purchase_item WHERE receiptID IN (SELECT receiptID FROM purchase WHERE pdate = ?) AND upc = ?'); //There must be a way to pass the receiptIDSFromThatDay result set in here.
            $query->bind_param('ss', $_POST['date'], $row['upc']);
            $query->execute();
            $quantitiesSold = $query->get_result()->fetch_row();

            //Display the data for this row
                echo '<tr>';
                echo '   <td align="right">' . $row['upc'] . '</td>';
                echo '   <td align="right">' . $row['category'] . '</td>';
                echo '   <td align="right">$' . $row['price'] . '</td>';
                echo '   <td align="right">' . $quantitiesSold[0] . '</td>';
                echo '   <td align="right">$' . $quantitiesSold[0]*$row['price'] . '</td>';
                echo '</tr>';
            
            //Add to the total daily sales
            $totalSales += $quantitiesSold[0];
            $totalValue += $quantitiesSold[0]*$row['price'];
            
            //Set this rows values for the next row to check
            $prevRowCategory = $row['category'];
            $prevRowRunningSales += $quantitiesSold[0];
            $prevRowRunningValue += $quantitiesSold[0]*$row['price'];
        }
        //Print the last column of sales
        echo '<tr>';
        echo '   <td align="right"> </td>';
        echo '   <td align="right"></td>';
        echo '   <td align="right">Total:</td>';
        echo '   <td align="right">' . $prevRowRunningSales . '</td>';
        echo '   <td align="right">$' . $prevRowRunningValue . '</td>';
        echo '</tr>';
        //Blank line
        echo '<tr>';
        echo '   <td align="right"> </td>';
        echo '   <td align="right"> </td>';
        echo '   <td align="right"> </td>';
        echo '   <td align="right"> </td>';
        echo '   <td align="right">--------</td>';
        echo '</tr>';
        //Total for the entire day
        echo '<tr>';
        echo '   <td align="right"> </td>';
        echo '   <td align="right">Total Daily Sales: </td>';
        echo '   <td align="right"> </td>';
        echo '   <td align="right">' . $totalSales . '</td>';
        echo '   <td align="right">$' . $totalValue . '</td>';
        echo '</tr>';
        echo '</table>';
        echo '<p><a href="javascript:history.go(-1)">Go Back</a></p>';
    } else {
        echo '<h1>Error generating sales report</h1>';
        echo '<p>You must specify a date.</p>';
        echo '<p><a href="javascript:history.go(-1)">Go Back</a></p>';
    }
    ?>
</div>