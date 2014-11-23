<?php
    if (isset($_GET['cat'])) {
        $query = $connection->prepare('SELECT upc, title, itemtype, category, releaseyear, price FROM item WHERE itemtype=?');
        $query->bind_param('s', $_GET['cat']);
    } else {
        $query = $connection->prepare('SELECT upc, title, itemtype, category, releaseyear, price FROM item');
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

            echo '<a href="?p=display_item&id='.$row['upc'].'" class="item">';
            echo '<div class="cover"><img src="img/covers/'.$cover.'.jpg" width="64px" height="64px" /></div>';
            echo '<div class="info"><h2>'.$row['title'].'</h2>'.$row['itemtype'].'<br />'.$row['category'].'<br />'.$row['releaseyear'].'</div>';
            echo '<div class="price"><h2>$'.number_format($row['price'], 2).'</h2></div></a>';
        }

        $i++;
    }

    echo '<div id="pager">Page: ';
    for ($i = 1; $i <= ceil($result->num_rows / 6); $i++) {
        echo ' <a href="?';
        if (isset($_GET['cat']))
            echo 'cat='.$_GET['cat'].'&';
        echo 'page='.$i.'">[ '.$i.' ]</a> ';
    }
echo '</div>';
?>