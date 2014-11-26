<?php
    if (isset($_GET['type'])) {
        $term = "%{$_GET['term']}%";
        if ($_GET['type'] == "title" || $_GET['type'] == "category") {
            $query = $connection->prepare('SELECT upc, title, itemtype, category, releaseyear, price FROM item WHERE '.$_GET['type'].' LIKE ?');
            $query->bind_param('s', $term);
            $search_by_artist = false;
        } elseif ($_GET['type'] == "artist") {
            $query = $connection->prepare('SELECT DISTINCT upc FROM lead_singer WHERE sname LIKE ?');
            $query->bind_param('s', $term);
            $query->execute();
            $result = $query->get_result();
    
            $search_by_artist = true;
        } else {
            require "404.php";
            die();
        }
    } else {
        require "404.php";
        die();
    }

    $query->execute();
    $result = $query->get_result();

    if (isset($_GET['page']))
        $page = $_GET['page'] - 1;
    else
        $page = 0;

    $i = 0;
    while ($row = $result->fetch_assoc()) {
        if ($i >= $page * 6 && $i <= $page * 6 + 5 ) {
            if (!file_exists('img/covers/'.$row['upc'].'.jpg'))
                $cover = "unknown";
            else
                $cover = $row['upc'];
            
            if ($search_by_artist) {
                $query = $connection->prepare('SELECT upc, title, itemtype, category, releaseyear, price FROM item WHERE upc=?');
                $query->bind_param('s', $row['upc']);
                $query->execute();
                $row = $query->get_result()->fetch_assoc();
            }

            echo '<a href="?p=display_item&id='.$row['upc'].'" class="item">';
            echo '<div class="cover"><img src="img/covers/'.$cover.'.jpg" width="64px" height="64px" /></div>';
            echo '<div class="info"><h2>'.$row['title'].'</h2>'.$row['itemtype'].'<br />'.$row['category'].'<br />'.$row['releaseyear'].'</div>';
            echo '<div class="price"><h2>$'.number_format($row['price'], 2).'</h2></div></a>';
        }

        $i++;
    }

    if ($i != 0) {
        echo '<div id="pager">Page: ';
        for ($i = 1; $i <= ceil($result->num_rows / 6); $i++) {
            echo ' <a href="?p=search&type='.$_GET['type'].'&term='.$_GET['term'].'&page='.$i.'">[ '.$i.' ]</a> ';
        }
        echo '</div>';
    } else {
        echo '<div><h1>No items found</h1><br />';
        echo '<p>No items matching your search were found.</p>';
        echo 'Why don\'t you try searching for another item or take a look at our <a href="?p=list_items">virtual catalogue</a>?</p></div>';
    }
?>