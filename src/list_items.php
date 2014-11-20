<?php
    if (isset($_GET['cat'])) {
        $query = $connection->prepare('SELECT upc, title, itemtype, category, releaseyear, price FROM item WHERE itemtype=?');
        $query->bind_param('s', $_GET['cat']);
    } else {
        $query = $connection->prepare('SELECT upc, title, itemtype, category, releaseyear, price FROM item');
    }

    $query->execute();
    $result = $query->get_result();

    while ($row = $result->fetch_assoc()) {
        if (!file_exists('img/covers/'.$row['upc'].'.jpg'))
            $cover = "unknown";
        else
            $cover = $row['upc'];
        
        echo '<a href="?p=display_item&id='.$row['upc'].'" class="item"><div class="cover"><img src="img/covers/'.$cover.'.jpg" width="64px" height="64px" /></div><div class="info"><h2>'.$row['title'].'</h2>'.$row['itemtype'].'<br />'.$row['category'].'<br />'.$row['releaseyear'].'</div><div class="price"><h2>$'.number_format($row['price'], 2).'</h2></div></a>';
    }

    // TODO: Add paging to the table. Possible idea: use JavaScript not to have to reload pages and complete a new query every page.
?>